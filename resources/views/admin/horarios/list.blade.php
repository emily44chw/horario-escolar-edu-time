@extends('layouts.admin')

@section('content')
    <h1>Horarios Creados</h1>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary">
            ← Volver a Gestión de Horarios
        </a>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Curso</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>{{ $course->name }}</td>
                    <td>
                        <span class="badge {{ $course->status == 'completo' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($course->status) }}
                        </span>
                    </td>
                    <td>
                        <!-- Ver -->
                        <a href="{{ route('admin.horarios.show', $course->id) }}" class="btn btn-sm btn-light" title="Ver">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <!-- Editar -->
                        <a href="{{ route('admin.horarios.edit', $course->id) }}" class="btn btn-sm btn-light" title="Editar">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <!-- Eliminar -->
                        <form action="{{ route('admin.horarios.destroy', $course->id) }}" method="POST"
                            style="display:inline-block;"
                            onsubmit="return confirm('¿Seguro que deseas eliminar este horario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light" title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection