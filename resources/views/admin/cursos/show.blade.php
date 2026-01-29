@extends('layouts.admin')

@section('content')

    <div class="form-page">

        <h1>Detalle del curso</h1>

        <div class="form-card">

            <div class="form-grid">

                <div class="form-group">
                    <label>Grado</label>
                    <input type="text" value="{{ $course->grade }}" disabled>
                </div>

                <div class="form-group">
                    <label>Paralelo</label>
                    <input type="text" value="{{ $course->parallel }}" disabled>
                </div>

                <div class="form-group">
                    <label>Año lectivo</label>
                    <input type="text" value="{{ $course->school_year }}" disabled>
                </div>

            </div>
            <br>
            <hr>

            <h2>Estudiantes del curso</h2>

            @if($course->students->count())

                <table class="pretty-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($course->students as $i => $student)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <form action="{{ route('admin.cursos.students.remove', [$course->id, $student->id]) }}"
                                        method="POST" onsubmit="return confirm('¿Quitar estudiante del curso?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else
                <p class="text-muted">Este curso no tiene estudiantes asignados.</p>
            @endif

            <div class="form-actions">
                <a href="{{ route('admin.cursos.index') }}" class="btn-secondary">
                    Volver
                </a>

                <a href="{{ route('admin.cursos.edit', $course) }}" class="btn-primary">
                    Editar curso
                </a>
            </div>

        </div>

    </div>

@endsection