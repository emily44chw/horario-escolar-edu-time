<?php

namespace App\Http\Controllers\Admin;

//controller base
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HorariosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // Página principal de horarios
    public function index()
    {
        return view('admin.horarios.index');
    }

    // Vista de creación: redirige a schedules.create para reutilizar lógica
    public function create()
    {
        return redirect('/schedules/create'); // O usa redirect()->route('schedules.create') si tienes nombre de ruta
    }

    // Listar creaciones (horarios existentes)
    public function list()
    {
        $schedules = Schedule::with(['course', 'subject', 'teacher'])->get()->groupBy('course_id');
        return view('admin.horarios.list', compact('schedules'));
    }
}