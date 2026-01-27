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
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.schedules.create', compact('courses'));
    }

    public function getSubjectsForCourse($courseId)
    {
        $subjects = Subject::whereHas('courses', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with('teachers.user')->get();

        return response()->json($subjects);
    }

    public function getAvailableSlots(Request $request)
    {
        $courseId = $request->course_id;
        $subjectId = $request->subject_id;
        $day = $request->day;

        \Log::info('getAvailableSlots called', compact('courseId', 'subjectId', 'day'));

        // Verifica si el usuario está autenticado
        if (!auth()->check()) {
            \Log::warning('Usuario no autenticado');
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Verifica el rol del usuario
        if (auth()->user()->role !== 'admin') {
            \Log::warning('Usuario sin rol admin', ['role' => auth()->user()->role]);
            return response()->json(['error' => 'Acceso denegado, rol requerido: admin'], 403);
        }

        // Verifica si el curso existe
        $course = Course::find($courseId);
        if (!$course) {
            \Log::error('Curso no encontrado', compact('courseId'));
            return response()->json(['error' => 'Curso no encontrado'], 404);
        }

        // Genera slots según el curso
        $isBachillerato = str_contains(strtolower($course->grade), 'bachillerato');
        $startHour = 7;
        $endHour = $isBachillerato ? 13 : 12;

        $slots = [];
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $startTime = sprintf('%02d:00', $hour);
            $endTime = sprintf('%02d:00', $hour + 1);
            $slots[] = ['start' => $startTime, 'end' => $endTime];
        }

        \Log::info('Slots generados', $slots);
        return response()->json(array_values($slots));
    }



    public function store(Request $request)
    {
        $courseId = $request->course_id;
        $assignments = $request->assignments;

        $existingSchedules = Schedule::where('course_id', $courseId)->count();
        if ($existingSchedules > 0) {
            return response()->json(['error' => 'Este curso ya tiene un horario asignado. Edítalo en lugar de crear uno nuevo.']);
        }

        foreach ($assignments as $assignment) {

            // Buscar el TEACHER real usando el user_id que viene del frontend
            $teacher = \App\Models\Teacher::where('user_id', $assignment['teacher_id'])->first();

            if (!$teacher) {
                return response()->json([
                    'error' => 'No se encontró el profesor asociado al usuario ID ' . $assignment['teacher_id']
                ], 400);
            }

            Schedule::create([
                'course_id' => $courseId,
                'subject_id' => $assignment['subject_id'],
                'teacher_id' => $teacher->id, // ✅ ID REAL DE LA TABLA TEACHERS
                'day' => $assignment['day'],
                'start_time' => $assignment['start_time'],
                'end_time' => date('H:i', strtotime($assignment['start_time']) + 3600),
            ]);
        }

        $course = Course::find($courseId);
        $isBachillerato = str_contains(strtolower($course->grade), 'bachillerato');
        $totalSlots = $isBachillerato ? 35 : 30;
        $assignedSlots = Schedule::where('course_id', $courseId)->count();
        $status = ($assignedSlots < $totalSlots) ? 'pending' : 'completed';

        Schedule::where('course_id', $courseId)->update(['status' => $status]);

        return response()->json(['success' => 'Horario guardado correctamente. Estado: ' . $status]);
    }

    public function getSelectedSchedule(Request $request)
    {
        $courseId = $request->course_id;
        $schedules = Schedule::where('course_id', $courseId)
            ->with(['subject', 'teacher.user'])
            ->get()
            ->groupBy('day');

        return response()->json($schedules);
    }

    // Método para mostrar un horario específico (requerido por Route::resource)
    public function show($id)
    {
        $schedule = Schedule::with(['course', 'subject', 'teacher'])->findOrFail($id);
        // Opcional: crea una vista 'admin.schedules.show' si quieres mostrar detalles
        // return view('admin.schedules.show', compact('schedule'));

        // Por ahora, devuelve JSON para evitar crear vista
        return response()->json($schedule);
    }
}