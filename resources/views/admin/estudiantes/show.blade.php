@extends('layouts.app')

@section('content')
    <h1>Detalles del Estudiante</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $estudiante->first_name }} {{ $estudiante->last_name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $estudiante->user->email }}</p>
            <p class="card-text"><strong>Teléfono:</strong> {{ $estudiante->phone ?: 'No especificado' }}</p>
            <p class="card-text"><strong>Fecha de Nacimiento:</strong> {{ $estudiante->birth_date ?: 'No especificada' }}
            </p>
            <p class="card-text"><strong>Teléfono del Representante:</strong>
                {{ $estudiante->representative_phone ?: 'No especificado' }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $estudiante->status }}</p>
            <p class="card-text"><strong>Rol:</strong> {{ $estudiante->user->role }}</p>
        </div>
    </div>
    <a href="{{ route('admin.estudiantes.edit', $estudiante) }}" class="btn btn-warning mt-3">Editar</a>
    <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary mt-3">Volver a la Lista</a>
@endsection