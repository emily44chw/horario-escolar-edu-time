<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ScheduleController;

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
    require public_path('login.php');
});


//Procesar login

Route::post('/login-procesar', function (Request $request) { //request -> para obtener los datos del formulario

    $email = $_POST['email'] ?? null; // Obtener email del formulario
    $password = $_POST['password'] ?? null; // Obtener contraseña del formulario


    $user = DB::table('users')
        ->where('email', $email)
        ->first();

    if (!$user) {
        return redirect('/login?error=email');
    }

    if (!password_verify($password, $user->password)) {
        return redirect('/login?error=password');
    }


    // Crear sesión
    Session::put('user_id', $user->id); //almacenar id del usuario en la sesion
    Session::put('user_rol', $user->rol); //almacenar rol del usuario en la sesion
    Session::put('user_name', $user->name); //almacenar nombre del usuario en la sesion

    if ($user && password_verify($password, $user->password)) {

        // Redirigir según rol
        return redirect('/' . $user->rol . '/home');
    }

    return redirect('/login');
});

Route::get('/admin', function () {

    if (!session()->has('user_id')) {
        return 'No has iniciado sesión';
    }

    if (session('rol') !== 'admin') {
        return 'Acceso solo para administrador';
    }

    ;
});

Route::get('/logout', function () {
    session()->flush(); // borra toda la sesión
    echo "<script>
        alert('Sesión cerrada correctamente');
        window.location.href = '/login';
      </script>";
    exit;

});

//Rutas por cada rol

Route::get('/admin/home', function () {
    require public_path('admin/home.php');
});

Route::get('/docente/home', function () {
    require public_path('docente/home.php');
});

Route::get('/estudiante/home', function () {
    require public_path('estudiante/home.php');
});


//CRUD 

//Rutas para listar docentes

Route::get('admin/docentes', function () {
    require public_path('admin/docentes/index.php');
});


//Ruta - horario
Route::resource('schedules', ScheduleController::class)->middleware(['auth', 'rol:admin']);
Route::get('schedules/subjects/{course_id}', [ScheduleController::class, 'getSubjectsForCourse']);
Route::get('schedules/slots', [ScheduleController::class, 'getAvailableSlots']);
Route::post('schedules/store', [ScheduleController::class, 'store']);
Route::get('schedules/selected/{course_id}', [ScheduleController::class, 'getSelectedSchedule']);