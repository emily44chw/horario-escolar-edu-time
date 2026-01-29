@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Docente</h1>
    </div>

    <div class="detail-card">
        <div class="detail-grid">

            <div class="detail-item">
                <span>Nombre completo</span>
                <strong>{{ $docente->first_name }} {{ $docente->last_name }}</strong>
            </div>

            <div class="detail-item">
                <span>Email</span>
                <strong>{{ $docente->user->email ?? '—' }}</strong>
            </div>

            <div class="detail-item">
                <span>Teléfono</span>
                <strong>{{ $docente->phone ?? '—' }}</strong>
            </div>

            <div class="detail-item">
                <span>Estado</span>
                <strong class="{{ $docente->status === 'Activo' ? 'status-active' : 'status-inactive' }}">
                    {{ $docente->status }}
                </strong>
            </div>

            <div class="detail-item">
                <span>Rol</span>
                <strong>{{ ucfirst($docente->user->role) }}</strong>
            </div>

        </div>

    </div>
    <br>
    <div class="page-actions">
        <a href="{{ route('admin.docentes.edit', $docente) }}" class="btn btn-edit">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </a>
        <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

@endsection