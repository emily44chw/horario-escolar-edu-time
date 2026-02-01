<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DocenteController as AdminDocente;
use App\Http\Controllers\Admin\EstudianteController as AdminEstudiante;
use App\Http\Controllers\Admin\CursoController as AdminCurso;
use App\Http\Controllers\Admin\HorariosController;
use App\Http\Controllers\Admin\MateriaController as AdminMateria;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\EstudianteController;

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

    // Para estudiantes
    Route::post('admin/estudiantes/{id}/assign-course', [AdminEstudiante::class, 'assignCourse'])->name('admin.estudiantes.assignCourse');
    Route::delete('admin/estudiantes/{studentId}/remove-course/{courseId}', [AdminEstudiante::class, 'removeCourse'])->name('admin.estudiantes.removeCourse');

    // Para docentes
    Route::post('admin/docentes/{id}/assign-subject', [AdminDocente::class, 'assignSubject'])->name('admin.docentes.assignSubject');
    Route::delete('admin/docentes/{teacherId}/remove-subject/{subjectId}', [AdminDocente::class, 'removeSubject'])->name('admin.docentes.removeSubject');

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

    // CRUD Cursos
    Route::resource('admin/cursos', AdminCurso::class)
        ->parameters(['cursos' => 'course'])
        ->names([
            'index' => 'admin.cursos.index',
            'create' => 'admin.cursos.create',
            'store' => 'admin.cursos.store',
            'show' => 'admin.cursos.show',
            'edit' => 'admin.cursos.edit',
            'update' => 'admin.cursos.update',
            'destroy' => 'admin.cursos.destroy',
        ]);

    // Asignar estudiantes a curso
    Route::get(
        'admin/cursos/{course}/assign-estudiantes',
        [AdminCurso::class, 'assignEstudiantes']
    )->name('admin.cursos.assignEstudiantes');

    Route::post(
        'admin/cursos/{course}/store-estudiantes',
        [AdminCurso::class, 'storeEstudiantes']
    )->name('admin.cursos.storeEstudiantes');
    // Remover estudiante de curso
    Route::delete(
        'admin/cursos/{course}/remove-estudiante/{student}',
        [AdminCurso::class, 'removeEstudiante']
    )->name('admin.cursos.removeEstudiante');

    // Asignar docentes a materias
    Route::get(
        'admin/materias/{subject}/assign-docentes',
        [AdminMateria::class, 'assignDocentes']
    )->name('admin.materias.assignDocentes');

    Route::post(
        'admin/materias/{subject}/assign-docentes',
        [AdminMateria::class, 'storeDocentes']
    )->name('admin.materias.storeDocentes');

    // Asignar materias a cursos
    Route::get(
        'admin/cursos/{course}/assign-materias',
        [AdminCurso::class, 'assignMaterias']
    )->name('admin.cursos.assignMaterias');

    Route::post(
        'admin/cursos/{course}/assign-materias',
        [AdminCurso::class, 'storeMaterias']
    )->name('admin.cursos.storeMaterias');

    // CRUD Materias
    Route::resource('admin/materias', AdminMateria::class)->names([
        'index' => 'admin.materias.index',
        'create' => 'admin.materias.create',
        'store' => 'admin.materias.store',
        'show' => 'admin.materias.show',
        'edit' => 'admin.materias.edit',
        'update' => 'admin.materias.update',
        'destroy' => 'admin.materias.destroy',
    ]);

});

//Rutas para el rol de estudiante

Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->group(function () {

    Route::get('/home', [EstudianteController::class, 'home'])
        ->name('estudiante.home');

    Route::get('/horarios', [EstudianteController::class, 'horarios'])
        ->name('estudiante.horarios');

    Route::get('/cursos', [EstudianteController::class, 'cursos'])
        ->name('estudiante.cursos');

    Route::get('/perfil', [EstudianteController::class, 'profile'])
        ->name('estudiante.profile');

    Route::put('/perfil', [EstudianteController::class, 'updatePassword'])
        ->name('estudiante.profile.update');
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
    Route::get('horarios/{course}/ver', [HorariosController::class, 'show'])->name('admin.horarios.show');
    Route::get('horarios/{course}/editar', [HorariosController::class, 'edit'])->name('admin.horarios.edit');
    Route::delete('horarios/{course}', [HorariosController::class, 'destroy'])->name('admin.horarios.destroy');

});