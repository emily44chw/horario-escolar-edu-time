<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Students;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class EstudianteController extends Controller
{


    public function home()
    {
        $user = Auth::user();

        if (!$user->hasRole('estudiante')) {
            abort(403);
        }

        $student = Students::where('user_id', auth()->id())->first();
        $userId = auth()->id();

        $courseIds = DB::table('student_courses')
            ->where('student_id', $userId)
            ->pluck('course_id');

        $today = Carbon::now()->locale('es')->translatedFormat('l');
        $today = ucfirst($today); // Lunes, Martes...

        $schedulesToday = Schedule::with(['subject', 'teacher'])
            ->whereIn('course_id', $courseIds)
            ->where('day', $today)
            ->orderBy('start_time')
            ->get();

        return view('estudiante.home', compact(
            'student',
            'schedulesToday',
            'today'
        ));
    }


    public function horarios()
    {
        $userId = auth()->id();

        $courseIds = DB::table('student_courses')
            ->where('student_id', $userId)
            ->pluck('course_id');

        $schedules = Schedule::with(['subject', 'teacher.user'])
            ->whereIn('course_id', $courseIds)
            ->get();

        $schedulesByDay = $schedules->groupBy('day');


        return view('estudiante.horarios', compact('schedules', 'schedulesByDay'));
    }

    public function cursos()
    {
        $user = auth()->user();

        $student = Students::where('user_id', $user->id)->first();

        $courseIds = DB::table('student_courses')
            ->where('student_id', $user->id)
            ->pluck('course_id');

        $courses = Course::whereIn('id', $courseIds)
            ->with('subjects')
            ->get();

        return view('estudiante.cursos', compact('student', 'courses'));
    }



    public function profile()
    {
        return view('estudiante.profile');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }
}
