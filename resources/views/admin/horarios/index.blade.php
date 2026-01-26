@extends('layouts.app')

@section('content')
    <h1>Gestión de Horarios</h1>
    <ul>
        <li><a href="{{ route('admin.horarios.create') }}">Crear Nuevo Horario</a></li>
        <li><a href="{{ route('admin.horarios.list') }}">Ver Creaciones</a></li>
    </ul>
@endsection