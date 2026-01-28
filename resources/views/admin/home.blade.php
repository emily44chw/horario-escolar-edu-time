@extends('layouts.admin')

@section('content')
    <h1 class="title">Bienvenido {{ Auth::user()->name }}</h1>

    <div class="dashboard-grid">

        <a href="{{ route('admin.docentes.index') }}" class="dashboard-card">
            <img src="{{ asset('img/docentes.png') }}" alt="Docentes">
            <span>Docentes</span>
        </a>

        <a href="{{ route('admin.estudiantes.index') }}" class="dashboard-card">
            <img src="{{ asset('img/estudiantes.png') }}" alt="Estudiantes">
            <span>Estudiantes</span>
        </a>

        <a href="{{ route('admin.cursos.index') }}" class="dashboard-card">
            <img src="{{ asset('img/cursos.png') }}" alt="Cursos">
            <span>Cursos</span>
        </a>

        <a href="{{ route('admin.materias.index') }}" class="dashboard-card">
            <img src="{{ asset('img/materias.png') }}" alt="Materias">
            <span>Materias</span>
        </a>

        <a href="{{ route('admin.horarios.index') }}" class="dashboard-card">
            <img src="{{ asset('img/horarios.png') }}" alt="Horarios">
            <span>Horarios</span>
        </a>

    </div>
@endsection