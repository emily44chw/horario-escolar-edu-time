<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\Admin\DocenteController as AdminDocente;
use App\Http\Controllers\Admin\EstudianteController as AdminEstudiante;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Admin\HorariosController;
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

// Rutas de schedules
// Las rutas especificas SIEMPRE van antes del Route::resource
Route::get('schedules/subjects/{course_id}', [ScheduleController::class, 'getSubjectsForCourse'])->middleware(['role:admin']);
Route::get('schedules/slots', [ScheduleController::class, 'getAvailableSlots'])->middleware(['role:admin']);
Route::post('schedules/store', [ScheduleController::class, 'store'])->middleware(['role:admin']);
Route::get('schedules/selected/{course_id}', [ScheduleController::class, 'getSelectedSchedule'])->middleware(['role:admin']);

// Resource al final
Route::resource('schedules', ScheduleController::class)->middleware(['role:admin']);

// Rutas de admin/horarios
Route::prefix('admin')->middleware(['role:admin'])->group(function () {
    Route::get('horarios', [HorariosController::class, 'index'])->name('admin.horarios.index');
    Route::get('horarios/crear', [HorariosController::class, 'create'])->name('admin.horarios.create');
    Route::get('horarios/creaciones', [HorariosController::class, 'list'])->name('admin.horarios.list');
});
