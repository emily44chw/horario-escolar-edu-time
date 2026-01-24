<?php
namespace App\Http\Controllers;

class EstudianteController extends Controller
{
    public function index()
    {
        return view('student.home');
    }
}