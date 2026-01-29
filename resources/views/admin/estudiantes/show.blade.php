@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Estudiante</h1>
    </div>

    <div class="detail-card">
        <div class="detail-grid">

            <div class="detail-item">
                <span>Nombre completo</span>
                <strong>{{ $estudiante->first_name }} {{ $estudiante->last_name }}</strong>
            </div>

            <div class="detail-item">
                <span>Email</span>
                <strong>{{ $estudiante->user->email ?? '—' }}</strong>
            </div>

            <div class="detail-item">
                <span>Teléfono</span>
                <strong>{{ $estudiante->phone ?? '—' }}</strong>
            </div>

            <div class="detail-item">
                <span>Teléfono del representante</span>
                <strong>{{ $estudiante->representative_phone ?? '—' }}</strong>
            </div>

            <div class="detail-item">
                <span>Fecha de nacimiento</span>
                <strong>{{ $estudiante->birth_date ?? '—' }}</strong>
            </div>

            <div class="detail-item">
                <span>Estado</span>
                <strong class="{{ $estudiante->status === 'Activo' ? 'status-active' : 'status-inactive' }}">
                    {{ $estudiante->status }}
                </strong>
            </div>

            <div class="detail-item">
                <span>Rol</span>
                <strong>{{ ucfirst($estudiante->user->role) }}</strong>
            </div>

        </div>
    </div>
    <br>
    <div class="page-actions">
        <a href="{{ route('admin.estudiantes.edit', $estudiante) }}" class="btn btn-edit">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </a>

        <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

@endsection