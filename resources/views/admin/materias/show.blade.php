@extends('layouts.admin')

@section('content')
    <div class="page-headershow">
        <h1>Detalles de la Materia: <span>{{ $subject->name }}</span></h1>
        <a href="{{ route('admin.materias.index') }}" class="btn-secondary">← Volver</a>
    </div>

    <div class="card">
        <h2>Docentes y Cursos</h2>

        @if($subject->teachers->isNotEmpty())
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Email</th>
                        <th>Cursos donde imparte</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subject->teachers as $teacher)
                        <tr>
                            <td>
                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                            </td>
                            <td>
                                {{ $teacher->user->email ?? 'N/A' }}
                            </td>
                            <td>
                                @if($subject->courses->isNotEmpty())
                                    <ul class="course-list">
                                        @foreach($subject->courses as $course)
                                            <li>{{ $course->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="empty">Sin cursos</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty">No hay docentes asignados a esta materia.</p>
        @endif
    </div>
@endsection