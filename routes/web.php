<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\Admin\DocenteController as AdminDocente;
use App\Http\Controllers\Admin\EstudianteController as AdminEstudiante;
use App\Http\Controllers\ScheduleController;
/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
*/

// Ruta raíz

Route::get('/', function () {
    return redirect('/login');
});

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // Home por rol
    Route::get('/admin/home', [AdminController::class, 'index'])->name('admin.home');
    Route::get('/docente/home', [DocenteController::class, 'index'])->name('docente.home');
    Route::get('/estudiante/home', [EstudianteController::class, 'index'])->name('estudiante.home');

    // CRUD Docentes
    Route::resource('admin/docentes', AdminDocente::class)->names([
        'index' => 'admin.docentes.index',
        'create' => 'admin.docentes.create',
        'store' => 'admin.docentes.store',
        'show' => 'admin.docentes.show',
        'edit' => 'admin.docentes.edit',
        'update' => 'admin.docentes.update',
        'destroy' => 'admin.docentes.destroy',
    ]);

    // CRUD Estudiantes
    Route::resource('admin/estudiantes', AdminEstudiante::class)->names([
        'index' => 'admin.estudiantes.index',
        'create' => 'admin.estudiantes.create',
        'store' => 'admin.estudiantes.store',
        'show' => 'admin.estudiantes.show',
        'edit' => 'admin.estudiantes.edit',
        'update' => 'admin.estudiantes.update',
        'destroy' => 'admin.estudiantes.destroy',
    ]);
});


//Ruta - horario
Route::resource('schedules', ScheduleController::class)->middleware(['auth', 'rol:admin']);
Route::get('schedules/subjects/{course_id}', [ScheduleController::class, 'getSubjectsForCourse']);
Route::get('schedules/slots', [ScheduleController::class, 'getAvailableSlots']);
Route::post('schedules/store', [ScheduleController::class, 'store']);
Route::get('schedules/selected/{course_id}', [ScheduleController::class, 'getSelectedSchedule']);
