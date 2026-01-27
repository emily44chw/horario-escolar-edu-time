<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = User::where('role', 'teacher')->with('subjects')->get();
        $subjects = Subject::all();
        return view('admin.docentes.index', compact('docentes', 'subjects'));
    }

    public function create()
    {
        return view('admin.docentes.create');
    }

    public function store(Request $request) // Crear nuevo docente
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@vr\.edu\.ec$/',
                'unique:users,email'
            ], //asegura que el email sea único
            'password' => 'required|string|min:8',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:15',
        ]);

        // Crear usuario asociado
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role' => 'docente',
            'status' => 'activo',
        ]);
        // Crear docente asociado
        Teacher::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'status' => 'activo',
        ]);

        return redirect()->route('admin.docentes.index')->with('success', 'Docente creado exitosamente.');

    }

    // CRUD Metodos

    public function show(Teacher $docente)
    {
        return view('admin.docentes.show', compact('docente'));
    }

    public function edit(Teacher $docente)
    {
        return view('admin.docentes.edit', compact('docente'));
    }

    public function update(Request $request, Teacher $docente)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@vr\.edu\.ec$/',
                'unique:users,email,' . $docente->user->id
            ],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:15',
            'status' => 'required|in:Activo,Inactivo',

        ]);
        // Actualizar usuario asociado
        $docente->user->update([
            'name' => $request->name,
            'email' => strtolower($request->email),
        ]);

        // Actualizar docente
        $docente->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.docentes.index')->with('success', 'Docente actualizado.');
    }

    public function destroy(Teacher $docente)
    {
        $docente->user->delete();
        return redirect()->route('admin.docentes.index')->with('success', 'Docente eliminado.');
    }



    // Asignar materia a profesor
    public function assignSubject(Request $request, $id)
    {
        $request->validate(['subject_id' => 'required|exists:subjects,id']);
        $teacher = User::findOrFail($id);
        $teacher->subjects()->syncWithoutDetaching([$request->subject_id]);
        return redirect()->back()->with('success', 'Materia asignada.');
    }

    // Remover materia de profesor
    public function removeSubject($teacherId, $subjectId)
    {
        $teacher = User::findOrFail($teacherId);
        $teacher->subjects()->detach($subjectId);
        return redirect()->back()->with('success', 'Materia removida.');
    }
}