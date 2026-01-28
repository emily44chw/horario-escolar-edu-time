@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Gestionar Cursos</h1>

        <div class="page-actions">
            <form method="GET" action="{{ route('admin.cursos.index') }}">
                <input type="text" id="searchDocente" placeholder="Buscar por el nombre del curso" class="search-input">
            </form>
            <a href="{{ route('admin.cursos.create') }}" class="btn btn-primary">
                Crear Curso +
            </a>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Asignar estudiante</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>
                        <strong>{{ $course->name }}</strong>
                    </td>

                    <td>
                        @if($course->students->count())
                            <ul class="student-list">
                                @foreach($course->students as $student)
                                    <li class="student-item">
                                        {{ $student->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">Sin estudiantes</span>
                        @endif

                    </td>

                    <td>
                        <form action="{{ route('admin.cursos.assignStudent', $course->id) }}" method="POST" class="assign-form">
                            @csrf

                            <div class="assign-box">
                                <select name="student_id" required>
                                    <option value="">Asignar estudiante</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="assign-btn">
                                    ➕
                                </button>
                            </div>
                        </form>

                    </td>

                    <td class="actions">
                        <a href="{{ route('admin.cursos.show', $course) }}" class="icon-btn view">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="{{ route('admin.cursos.edit', $course) }}" class="icon-btn edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('admin.cursos.destroy', $course) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="icon-btn delete" onclick="return confirm('¿Eliminar este curso?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection