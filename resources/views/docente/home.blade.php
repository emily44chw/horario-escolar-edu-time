@extends('layouts.app')

@section('content')
    <h1>Bienvenido Docente {{ auth()->user()->name }}</h1>
    <p>Aquí puedes gestionar tus clases y horarios.</p>
    <ul>
        <li><a href="#">Ver Horarios Asignados</a></li>
        <li><a href="#">Ver Asignaturas</a></li>
    </ul>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
    </form>
@endsection