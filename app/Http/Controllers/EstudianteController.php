<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Students;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class EstudianteController extends Controller
{


    public function home()
    {
        $user = Auth::user();

        if (!$user->hasRole('estudiante')) {
            abort(403);
        }

        $student = Students::where('user_id', auth()->id())->first();


        $courseIds = $student->courses()->pluck('id');

        $schedules = Schedule::with(['subject', 'teacher'])
            ->whereIn('course_id', $courseIds)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('estudiante.home', compact('student', 'schedules'));
    }


    public function horarios()
    {
        $userId = auth()->id();

        $courseIds = DB::table('student_courses')
            ->where('student_id', $userId)
            ->pluck('course_id');

        $schedules = Schedule::with(['subject', 'teacher'])
            ->whereIn('course_id', $courseIds)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('estudiante.horarios', compact('schedules'));
    }


    public function cursos()
    {
        $courses = Auth::user()->student->courses;
        return view('estudiante.cursos', compact('courses'));
    }

    public function profile()
    {
        return view('estudiante.profile');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Contraseña actualizada correctamente');
    }
}
