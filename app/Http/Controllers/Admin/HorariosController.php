<?php

namespace App\Http\Controllers\Admin;

//controller base
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;

class HorariosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // Página principal de horarios
    public function index()
    {
        return view('admin.horarios.index');
    }

    // Vista de creación: redirige a schedules.create para reutilizar lógica
    public function create()
    {
        return redirect('/schedules/create'); // O usa redirect()->route('schedules.create') si tienes nombre de ruta
    }

    // Listar creaciones (horarios existentes)
    public function list()
    {
        $courses = Course::whereHas('schedules')
            ->with('schedules')
            ->get();

        // Calcular status para cada curso
        $courses->each(function ($course) {
            $totalHoras = $course->schedules->count(); // cantidad de horarios asignados
            $expectedHoras = 5 * 6; // 5 días * 6 horas por día (ajusta según tu caso)

            $course->status = ($totalHoras >= $expectedHoras) ? 'completo' : 'incompleto';
        });

        return view('admin.horarios.list', compact('courses')); // <--- Faltaba esto
    }


    public function destroy($courseId)
    {
        \App\Models\Schedule::where('course_id', $courseId)->delete();

        return redirect()->route('admin.horarios.list')
            ->with('success', 'Horario eliminado correctamente');
    }

    // Mostrar un horario completo
    public function show($courseId)
    {
        $course = Course::findOrFail($courseId);

        $schedules = Schedule::where('course_id', $courseId)
            ->with(['subject', 'teacher'])
            ->get();

        return view('admin.horarios.show', compact('course', 'schedules'));
    }


    // Editar un horario
    public function edit($courseId)
    {
        $course = Course::with(['subjects.teachers'])->findOrFail($courseId);

        $schedules = Schedule::where('course_id', $courseId)
            ->with(['course', 'subject', 'teacher'])
            ->get();

        return view('admin.horarios.edit', compact('course', 'schedules'));
    }



    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}