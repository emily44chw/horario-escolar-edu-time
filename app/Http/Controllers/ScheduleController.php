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

        // Verifica si el usuario esta autenticado
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

            // Para recreo - 9 a 10 (no se genera)
            if ($hour == 9) {
                continue;
            }

            $startTime = sprintf('%02d:00', $hour);
            $endTime = sprintf('%02d:00', $hour + 1);
            $slots[] = ['start' => $startTime, 'end' => $endTime];
        }


        \Log::info('Slots generados', $slots);
        return response()->json(array_values($slots));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'assignments' => 'required|array',
            'assignments.*.subject_id' => 'required|integer|exists:subjects,id',
            'assignments.*.teacher_id' => 'nullable|integer|exists:teachers,id',
            'assignments.*.day' => 'required|string',
            'assignments.*.start_time' => 'required|string',
            'assignments.*.end_time' => 'required|string',
            'assignments.*.classroom_id' => 'nullable|integer|exists:classrooms,id', // opcional
        ]);

        $savedSchedules = [];

        // Guardar cada asignación
        foreach ($data['assignments'] as $a) {
            $schedule = Schedule::create([
                'course_id' => $data['course_id'],
                'subject_id' => $a['subject_id'],
                'teacher_id' => $a['teacher_id'] ?? null,
                'classroom_id' => $a['classroom_id'] ?? null,
                'day' => $a['day'],
                'start_time' => $a['start_time'],
                'end_time' => $a['end_time'],
                'status' => 'incompleto', // inicial
            ]);

            $savedSchedules[] = $schedule;
        }

        $courseSchedules = Schedule::where('course_id', $data['course_id'])->get();
        $totalHoras = $courseSchedules->count();
        $expectedHoras = 5 * $courseSchedules->pluck('start_time')->unique()->count(); // 5 días * horas únicas

        $status = $totalHoras >= $expectedHoras ? 'completo' : 'incompleto';
        Schedule::where('course_id', $data['course_id'])->update(['status' => $status]);
        // ----------------------------------------------------

        return response()->json([
            'success' => 'Se guardaron ' . count($savedSchedules) . ' asignaciones.',
            'data' => $savedSchedules
        ]);
    }

    public function update(Request $request, $courseId)
    {
        // Validar que llegue assignments
        $request->validate([
            'assignments' => 'required|array',
        ]);

        $savedSchedules = [];

        // Primero borramos horarios anteriores del curso
        Schedule::where('course_id', $courseId)->delete();

        // Recorrer todas las asignaciones
        foreach ($request->assignments as $day => $hours) {
            foreach ($hours as $hour => $hourAssignmentJson) {

                // Saltar si está vacío
                if (empty($hourAssignmentJson)) {
                    continue;
                }

                // Decodificar el JSON del <option>
                $hourAssignment = json_decode($hourAssignmentJson, true);

                // Crear nuevo registro
                $schedule = Schedule::create([
                    'course_id' => $courseId,
                    'subject_id' => $hourAssignment['subject_id'],
                    'teacher_id' => $hourAssignment['teacher_id'] ?? null,
                    'classroom_id' => $hourAssignment['classroom_id'] ?? null,
                    'day' => $hourAssignment['day'],
                    'start_time' => $hourAssignment['start_time'],
                    'end_time' => $hourAssignment['end_time'],
                    'status' => 'incompleto', // luego se actualizará
                ]);

                $savedSchedules[] = $schedule;
            }
        }

        // --- Actualizar status del curso ---
        $courseSchedules = Schedule::where('course_id', $courseId)->get();
        $totalHoras = $courseSchedules->count();
        $expectedHoras = 5 * $courseSchedules->pluck('start_time')->unique()->count(); // 5 días * horas únicas

        $status = $totalHoras >= $expectedHoras ? 'completo' : 'incompleto';
        Schedule::where('course_id', $courseId)->update(['status' => $status]);

        return redirect()->route('admin.horarios.show', $courseId)
            ->with('success', 'Horario actualizado correctamente.');
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