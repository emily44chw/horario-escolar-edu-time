@extends('layouts.app')

@section('content')
    <h1>Gestionar docentes</h1>
    <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">Crear Nuevo Docente + </a>
    <table class="table">
        <thead>
            <br>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($docentes as $docente)
                <tr>
                    <td>{{ $docente->id }}</td>
                    <td>{{ $docente->first_name }} {{ $docente->last_name }}</td>
                    <td>{{ $docente->user->email }}</td>
                    <td>{{ $docente->phone }}</td>
                    <td>{{ $docente->status }}</td>
                    <td>
                        <a href="{{ route('admin.docentes.show', $docente) }}">Ver</a>
                        <a href="{{ route('admin.docentes.edit', $docente) }}">Editar</a>
                        <form action="{{ route('admin.docentes.destroy', $docente) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('¿Estás seguro de eliminar este docente?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.home') }}" class="btn btn-secondary">Volver</a>

@endsection