<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class MateriaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $subjects = Subject::where('name', 'LIKE', "%{$query}%")->get();
        } else {
            $subjects = Subject::all();
        }
        return view('admin.materias.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.materias.create');
    }

    public function store(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'name' => 'required|string|max:100|unique:subjects,name',
        ]);

        // Crear la nueva materia
        Subject::create(['name' => $request->name]);

        // Redirige con mensaje de éxito
        return redirect()->route('admin.materias.index')->with('success', 'Materia creada.');
    }

    public function edit($id)
    {
        // Busca la materia
        $subject = Subject::findOrFail($id);
        return view('admin.materias.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        // Validar la entrada
        $request->validate([
            'name' => 'required|string|max:100|unique:subjects,name,' . $id,
        ]);

        // Busca y actualiza la materia
        $subject = Subject::findOrFail($id);
        $subject->update(['name' => $request->name]);

        // Redirige con mensaje de éxito
        return redirect()->route('admin.materias.index')->with('success', 'Materia actualizada.');
    }

    public function destroy($id)
    {
        // Busca la materia
        $subject = Subject::findOrFail($id);

        // Verifica si hay horarios asociados
        if ($subject->schedules()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar la materia porque tiene horarios asignados. Elimina los horarios primero.');
        }

        // Si no hay dependencias, borra la materia
        $subject->delete();

        return redirect()->route('admin.materias.index')->with('success', 'Materia eliminada.');
    }

    public function show($id)
    {
        $subject = Subject::with(['teachers', 'courses'])->findOrFail($id);
        return view('admin.materias.show', compact('subject'));
    }


}
