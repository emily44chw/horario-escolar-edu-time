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

    public function getSubjectsForCourse(Request $request)
    {
        $courseId = $request->course_id;
        $subjects = Subject::whereHas('courses', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with('teachers')->get();

        return response()->json($subjects);
    }

    public function getAvailableSlots(Request $request)
    {
        $courseId = $request->course_id;
        $subjectId = $request->subject_id;
        $day = $request->day;

        \Log::info('getAvailableSlots called', compact('courseId', 'subjectId', 'day'));

        $course = Course::find($courseId);
        if (!$course) {
            \Log::error('Curso no encontrado', compact('courseId'));
            return response()->json(['error' => 'Curso no encontrado'], 404);
        }

        $isBachillerato = str_contains($course->name, 'bachillerato');
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
            Schedule::create([
                'course_id' => $courseId,
                'subject_id' => $assignment['subject_id'],
                'teacher_id' => $assignment['teacher_id'],
                'day' => $assignment['day'],
                'start_time' => $assignment['start_time'],
                'end_time' => date('H:i', strtotime($assignment['start_time']) + 3600),
            ]);
        }

        $course = Course::find($courseId);
        $isBachillerato = str_contains($course->name, 'bachillerato');
        $totalSlots = $isBachillerato ? 35 : 30;
        $assignedSlots = Schedule::where('course_id', $courseId)->count();
        $status = ($assignedSlots < $totalSlots) ? 'pending' : 'completed';

        Schedule::where('course_id', $courseId)->update(['status' => $status]);

        return response()->json(['success' => 'Horario guardado. Status: ' . $status]);
    }

    public function getSelectedSchedule(Request $request)
    {
        $courseId = $request->course_id;
        $schedules = Schedule::where('course_id', $courseId)
            ->with(['subject', 'teacher'])
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