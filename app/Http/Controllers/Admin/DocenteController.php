<?php
namespace App\Http\Controllers;

class DocenteController extends Controller
{
    public function index()
    {
        return view('teacher.home');
    }
}