@extends('layouts.app')
@section('content')
    <h1>Bienvenido {{ Auth::user()->name }}</h1>
    <li><a href="{{ route('admin.docentes.index') }}">Gestionar Docentes</a></li>
    <br>
    <li><a href="{{ route('admin.estudiantes.index') }}">Gestionar Estudiantes</a></li>
    <br>

    <form action="{{ route('logout') }}" method="POST">
        <br>
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form>
@endsection