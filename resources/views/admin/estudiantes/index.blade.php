@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1>Gestionar Estudiantes</h1>
        <div class="page-actions">
            <form method="GET" class="filter-bar">

                <input type="text" name="search" placeholder="Buscar estudiante..." value="{{ request('search') }}">

                <select name="course_id">
                    <option value="">Todos los cursos</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->grade }} {{ $course->parallel }}
                            ({{ $course->school_year }})
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn-primary">
                    Filtrar
                </button>

            </form>

            <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-primary">
                Crear Nuevo Estudiante +
            </a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombres y Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Tel. Representante</th>
                    <th>Curso Asignado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="estudiantesTable">
                @foreach($estudiantes as $estudiante)
                    <tr>
                        <td class="estudiante-nombre">{{ $estudiante->first_name }} {{ $estudiante->last_name }}</td>
                        <td>{{ $estudiante->user->email }}</td>
                        <td>{{ $estudiante->phone }}</td>
                        <td>{{ $estudiante->representative_phone }}</td>
                        <td>
                            @if($estudiante->user->courses->count())
                                @foreach($estudiante->user->courses as $course)
                                    <span class="course-badge">
                                        {{ $course->grade }} {{ $course->parallel }}
                                        ({{ $course->school_year }})
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">Sin curso</span>
                            @endif
                        </td>
                        <td><span class="status active">{{ $estudiante->status }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.estudiantes.show', $estudiante) }}"><i class="fa-solid fa-user"></i></a>
                            <a href="{{ route('admin.estudiantes.edit', $estudiante) }}"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.estudiantes.destroy', $estudiante) }}" method="POST"
                                style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este estudiante?')"> <i
                                        class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <script>
            document.getElementById('searchEstudiante').addEventListener('keyup', function () {
                let filtro = this.value.toLowerCase();
                let filas = document.querySelectorAll('#estudiantesTable tr');

                filas.forEach(function (fila) {
                    let nombre = fila.querySelector('.estudiante-nombre').textContent.toLowerCase();
                    fila.style.display = nombre.includes(filtro) ? '' : 'none';
                });
            });
        </script>
@endsection