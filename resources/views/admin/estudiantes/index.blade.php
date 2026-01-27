@extends('layouts.app')

@section('content')
    <h1>Gestionar estudiantes</h1>
    <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-primary">Crear Nuevo Estudiante + </a>
    <br><br>
    <table class="table">
        <thead>
            <br>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Tel. Representante</th>
                <th>Curso</th>
                <th>Asignar Curso</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estudiantes as $estudiante)
                <tr>
                    <td>{{ $estudiante->id }}</td>
                    <td>{{ $estudiante->first_name }} {{ $estudiante->last_name }}</td>
                    <td>{{ $estudiante->user->email }}</td>
                    <td>{{ $estudiante->phone }}</td>
                    <td>{{ $estudiante->representative_phone }}</td>
                    <td>{{ $estudiante->status }}</td>
                    <td>
                        <a href="{{ route('admin.estudiantes.show', $estudiante) }}">Ver</a>
                        <a href="{{ route('admin.estudiantes.edit', $estudiante) }}">Editar</a>
                        <form action="{{ route('admin.estudiantes.destroy', $estudiante) }}" method="POST"
                            style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('¿Estás seguro de eliminar este estudiante?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.home') }}" class="btn btn-secondary">Volver</a>

@endsection