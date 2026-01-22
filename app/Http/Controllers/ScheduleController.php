<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    // Constructor para proteger con middleware (solo admin)
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']); // Asume que tienes un middleware 'role' para admin
    }

    // Método para mostrar la vista de creación de horarios
    public function create()
    {
        // Obtener cursos disponibles para la lista desplegable
        $courses = Course::all(); // Asume que Course::all() trae los cursos registrados

        return view('admin.schedules.create', compact('courses'));
    }

    // Método AJAX para obtener materias disponibles para un curso seleccionado
    public function getSubjectsForCourse(Request $request)
    {
        $courseId = $request->course_id;
        // Asume una relación many-to-many: subjects()->wherePivot('course_id', $courseId)
        $subjects = Subject::whereHas('courses', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();

        return response()->json($subjects);
    }

    // Método AJAX para obtener horarios disponibles (considerando disponibilidad de profesores)
    public function getAvailableSlots(Request $request)
    {
        $courseId = $request->course_id;
        $subjectId = $request->subject_id;
        $day = $request->day; // Día seleccionado (ej. 'Lunes')

        // Obtener el curso para determinar el rango horario
        $course = Course::find($courseId);
        $isBachillerato = str_contains($course->name, 'bachillerato'); // Asume que el nombre indica el nivel
        $startHour = 7; // 7am
        $endHour = $isBachillerato ? 13 : 12; // 1pm para bachillerato, 12pm para otros

        // Generar slots de 1 hora (ej. 07:00-08:00, 08:00-09:00, etc.)
        $slots = [];
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $startTime = sprintf('%02d:00', $hour);
            $endTime = sprintf('%02d:00', $hour + 1);
            $slots[] = ['start' => $startTime, 'end' => $endTime];
        }

        // Filtrar slots ocupados por profesores asignados a esta materia
        $teacherIds = DB::table('subject_teacher')->where('subject_id', $subjectId)->pluck('teacher_id');
        $occupiedSlots = Schedule::whereIn('teacher_id', $teacherIds)
            ->where('day', $day)
            ->where('course_id', '!=', $courseId) // No solapar en el mismo curso, pero sí en otros
            ->pluck('start_time')
            ->toArray();

        // Remover slots ocupados
        $availableSlots = array_filter($slots, function ($slot) use ($occupiedSlots) {
            return !in_array($slot['start'], $occupiedSlots);
        });

        return response()->json(array_values($availableSlots));
    }

    // Método para guardar el horario (llamado vía AJAX o POST)
    public function store(Request $request)
    {
        $courseId = $request->course_id;
        $assignments = $request->assignments; // Array de asignaciones: [{'subject_id':1, 'day':'Lunes', 'start_time':'07:00', 'teacher_id':2}, ...]

        // Verificar si el curso ya tiene horario completo
        $existingSchedules = Schedule::where('course_id', $courseId)->count();
        if ($existingSchedules > 0) {
            return response()->json(['error' => 'Este curso ya tiene un horario asignado. Edítalo en lugar de crear uno nuevo.']);
        }

        // Guardar asignaciones
        foreach ($assignments as $assignment) {
            Schedule::create([
                'course_id' => $courseId,
                'subject_id' => $assignment['subject_id'],
                'teacher_id' => $assignment['teacher_id'],
                'day' => $assignment['day'],
                'start_time' => $assignment['start_time'],
                'end_time' => date('H:i', strtotime($assignment['start_time']) + 3600), // +1 hora
            ]);
        }

        // Verificar si hay huecos (slots sin asignar)
        $course = Course::find($courseId);
        $isBachillerato = str_contains($course->name, 'bachillerato');
        $totalSlots = $isBachillerato ? 35 : 30; // 5 días x 7 slots o 6 slots
        $assignedSlots = Schedule::where('course_id', $courseId)->count();
        $status = ($assignedSlots < $totalSlots) ? 'pending' : 'completed';

        // Actualizar status en schedules (o en una tabla separada si prefieres)
        Schedule::where('course_id', $courseId)->update(['status' => $status]);

        return response()->json(['success' => 'Horario guardado. Status: ' . $status]);
    }

    // Método para mostrar la tabla de horario seleccionado (llamado vía AJAX)
    public function getSelectedSchedule(Request $request)
    {
        $courseId = $request->course_id;
        $schedules = Schedule::where('course_id', $courseId)
            ->with(['subject', 'teacher']) // Asume relaciones en modelo
            ->get()
            ->groupBy('day'); // Agrupar por día

        return response()->json($schedules);
    }
}