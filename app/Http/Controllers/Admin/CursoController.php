<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;

class CursoController extends Controller
{
    public function index()
    {
        $courses = Course::with('students')->get();
        $students = User::where('role', 'estudiante')->get();
        return view('admin.cursos.index', compact('courses', 'students'));
    }

    public function create()
    {
        return view('admin.cursos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade' => 'required|string|max:20',
            'parallel' => 'required|string|max:1',
            'school_year' => 'required|string|max:9',
        ]);

        Course::create($request->all());
        return redirect()->route('admin.cursos.index')->with('success', 'Curso creado.');
    }

    public function edit(Course $course)
    {
        return view('admin.cursos.edit', compact('course'));
    }
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'grade' => 'required|string|max:20',
            'parallel' => 'required|string|max:1',
            'school_year' => 'required|string|max:9',
        ]);

        $course->update($request->all());
        return redirect()->route('admin.cursos.index')->with('success', 'Curso actualizado.');
    }

    public function show(Course $course)
    {
        $course->load('students');
        return view('admin.cursos.show', compact('course'));
    }


    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso eliminado');
    }

    // Asignar estudiante a curso
    public function assignStudent(Request $request, $id)
    {
        $request->validate(['student_id' => 'required|exists:users,id']);
        $course = Course::findOrFail($id);
        $course->students()->syncWithoutDetaching([$request->student_id]);
        return redirect()->back()->with('success', 'Estudiante asignado.');
    }

    // Remover estudiante de curso
    public function removeStudent(Course $course, User $student)
    {
        $course->students()->detach($student->id);

        return redirect()
            ->back()
            ->with('success', 'Estudiante removido del curso');
    }



}
