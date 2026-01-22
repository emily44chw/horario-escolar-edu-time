<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| RUTAS DEL SISTEMA DE HORARIO ESCOLAR
|--------------------------------------------------------------------------
| Aquí se definen las direcciones que el usuario puede visitar
| y qué acción se ejecuta en cada una.
*/

Route::get('/', function () {
    return view('welcome');
});

//Formulario de login

Route::get('/login', function () {

    return '
        <h2>Login</h2>

        <form method="POST" action="/login-procesar">  <!-- El formulario tendra el metodo Post para enviar los datos ocultos y como accion el de procesar la informacion almacenada --> 
            <input type="hidden" name="_token" value="' . csrf_token() . '"> <!-- _token ->seguridad -->

            <input type="email" name="email" placeholder="ejemplo@vr.edu.ec"><br><br>
            <input type="password" name="password" placeholder="Contraseña"><br><br>

            <button type="submit">Ingresar</button>
        </form>
    ';
});

//Procesar login

Route::post('/login-procesar', function (Request $request) { //request -> para obtener los datos del formulario

    // Obtener datos del formulario
    $email = $request->email; //obtener email
    $password = $request->password; //obtener contraseña

    // Buscar usuario por email
    $user = DB::table('users') //Consulta a la tabla users
        ->where('email', $email)
        ->first();

    // Verificar si existe el usuario
    if (!$user) {
        return back()->with('error', 'Usuario no encontrado'); //redirecciona a la misma pagina con un mensaje de error
    }

    // Verificar contraseña
    if (!password_verify($password, $user->password)) {
        return back()->with('error', 'Contraseña incorrecta'); //redirecciona a la misma pagina con un mensaje de error
    }

    // Crear sesión
    Session::put('user_id', $user->id);
    Session::put('user_rol', $user->rol);
    Session::put('user_name', $user->name);

    // Redirigir según rol
    if ($user->rol == 'admin') {
        return redirect('/admin/home');
    } elseif ($user->rol == 'docente') {
        return redirect('/docente/home');
    } else {
        return redirect('/estudiante/home');
    }
});

Route::get('/inicio', function () {

    // Verificar si hay sesión
    if (!session()->has('user_id')) { // Si no hay sesión redirige a login
        return redirect('/login');
    }

    return '
        <h1>Bienvenido ' . session('name') . '</h1>
        <p>Rol: ' . session('rol') . '</p>

        <a href="/admin">Panel Admin</a><br><br>
        <a href="/logout">Cerrar sesión</a>
    ';
});

Route::get('/admin', function () {

    if (!session()->has('user_id')) {
        return 'No has iniciado sesión';
    }

    if (session('rol') !== 'admin') {
        return 'Acceso solo para administrador';
    }

    return '
        <h2>Panel de Administrador</h2>
        <ul>
            <li><a href="/docentes">Gestionar Docentes</a></li>
            <li><a href="/estudiantes">Gestionar Estudiantes</a></li>
        </ul>
    ';
});

Route::get('/logout', function () {
    session()->flush(); // borra toda la sesión
    return redirect('/login');
});

//Rutas por cada rol

Route::get('/admin/home', function () {
    return view('admin.home');
});

Route::get('/docente/home', function () {
    return view('docente.home');
});

Route::get('/estudiante/home', function () {
    return view('estudiante.home');
});

//Ruta - horario
Route::resource('schedules', ScheduleController::class)->middleware(['auth', 'role:admin']);
Route::get('schedules/subjects/{course_id}', [ScheduleController::class, 'getSubjectsForCourse']);
Route::get('schedules/slots', [ScheduleController::class, 'getAvailableSlots']);
Route::post('schedules/store', [ScheduleController::class, 'store']);
Route::get('schedules/selected/{course_id}', [ScheduleController::class, 'getSelectedSchedule']);