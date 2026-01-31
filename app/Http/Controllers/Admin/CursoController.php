<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Subject;
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

    public function assignEstudiantes(Course $course)
    {
        $students = User::where('role', 'estudiante')->get();

        $course->load('students');

        // Obtener IDs de estudiantes ya asignados
        $assignedStudentIds = $course->students->pluck('id')->toArray();
        // Marcar estudiantes como asignados
        foreach ($students as $student) {
            $student->is_assigned = in_array($student->id, $assignedStudentIds);
        }

        return view(
            'admin.cursos.assign-estudiantes',
            compact('course', 'students')
        );
    }

    public function removeEstudiante(Course $course, User $student)
    {
        $course->students()->detach($student->id);

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Estudiante removido del curso correctamente');
    }

    public function storeEstudiantes(Request $request, Course $course)
    {
        $request->validate([
            'students' => 'array'
        ]);

        // sync = limpia y vuelve a asignar
        $course->students()->sync($request->students ?? []);

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Estudiantes asignados correctamente');
    }


    public function assignMaterias(Course $course)
    {
        $subjects = Subject::all();

        return view(
            'admin.cursos.assign-materias',
            compact('course', 'subjects')
        );
    }

    public function storeMaterias(Request $request, Course $course)
    {
        $request->validate([
            'subjects' => 'array'
        ]);

        $course->subjects()->sync($request->subjects ?? []);

        return redirect()
            ->route('admin.cursos.index', $course->id)
            ->with('success', 'Materias asignadas correctamente');
    }

}
