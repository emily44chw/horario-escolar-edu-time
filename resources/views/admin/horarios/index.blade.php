@extends('layouts.admin')

@section('content')
    <div style="margin-bottom: 20px; text-align: right;">
        <a href="{{ route('admin.home') }}" class="btn btn-secondary">
            ← Volver al menú principal
        </a>
    </div>
    <h1 class="title">Gestión de Horarios</h1>

    <div class="dashboard-grid">

        <a href="{{ route('admin.horarios.create') }}" class="dashboard-card">
            <img src="{{ asset('img/crearHorario.png') }}" alt="Crear Horario">
            <span>Crear Horario</span>
        </a>

        <a href="{{ route('admin.horarios.list') }}" class="dashboard-card">
            <img src="{{ asset('img/verCreaciones.png') }}" alt="Ver Creaciones">
            <span>Ver Creaciones</span>
        </a>

    </div>

@endsection