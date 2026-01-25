<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EstudianteController extends Controller
{
    public function index()
    {
        $estudiantes = Students::with('user')->get(); // Cargar relación con User
        return view('admin.estudiantes.index', compact('estudiantes'));
    }

    public function create()
    {
        return view('admin.estudiantes.create');
    }

    public function store(Request $request) // Crear nuevo estudiante
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@vr\.edu\.ec$/',
                'unique:users,email'
            ],
            'password' => 'required|string|min:8',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:15',
            'birth_date' => 'nullable|date',
            'representative' => 'nullable|string|max:150',
            'representative_phone' => 'nullable|string|max:15',
        ]);

        // Crear usuario asociado
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role' => 'estudiante',
            'status' => 'activo',
        ]);

        // Crear estudiante asociado
        Students::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'representative' => $request->representative,
            'representative_phone' => $request->representative_phone,
            'status' => 'activo',
        ]);

        return redirect()->route('admin.estudiantes.index')->with('success', 'Estudiante creado exitosamente.');
    }

    // CRUD Metodos

    public function show(Students $estudiante)
    {
        return view('admin.estudiantes.show', compact('estudiante'));
    }

    public function edit(Students $estudiante)
    {
        return view('admin.estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, Students $estudiante)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@vr\.edu\.ec$/',
                'unique:users,email,' . $estudiante->user->id
            ],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:15',
            'status' => 'required|in:Activo,Inactivo',

        ]);
        // Actualizar usuario asociado
        $estudiante->user->update([
            'name' => $request->name,
            'email' => strtolower($request->email),
        ]);

        // Actualizar estudiante
        $estudiante->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.estudiantes.index')->with('success', 'Estudianteactualizado.');
    }

    public function destroy(Students $estudiante)
    {
        $estudiante->user->delete();
        return redirect()->route('admin.estudiantes.index')->with('success', 'Estudiante eliminado.');
    }

}