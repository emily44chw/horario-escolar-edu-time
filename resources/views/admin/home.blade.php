@extends('layouts.admin')

@section('content')
    <h1>Bienvenido {{ Auth::user()->name }}</h1>

    <div class="cards">

        <a href="{{ route('admin.docentes.index') }}" class="card">
            Gestionar Docentes
        </a>

        <a href="{{ route('admin.estudiantes.index') }}" class="card">
            Gestionar Estudiantes
        </a>

        <a href="{{ route('admin.horarios.index') }}" class="card">
            Horarios
        </a>

    </div>
@endsection