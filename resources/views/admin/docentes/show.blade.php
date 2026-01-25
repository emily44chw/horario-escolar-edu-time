@extends('layouts.app')

@section('content')
    <h1>Detalles del Docente</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $docente->first_name }} {{ $docente->last_name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $docente->user->email }}</p>
            <p class="card-text"><strong>Teléfono:</strong> {{ $docente->phone ?: 'No especificado' }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $docente->status }}</p>
            <p class="card-text"><strong>Rol:</strong> {{ $docente->user->role }}</p>
        </div>
    </div>
    <a href="{{ route('admin.docentes.edit', $docente) }}" class="btn btn-warning mt-3">Editar</a>
    <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary mt-3">Volver a la Lista</a>
@endsection