@extends('layouts.admin')

@section('content')
    <div class="form-page">

        <div class="page-header">
            <h1>Asignar estudiantes</h1>
            <p class="subtitle">
                Curso: <strong>{{ $course->name }}</strong>
            </p>
        </div>

        <div class="form-card">
            <form method="POST" action="{{ route('admin.cursos.storeEstudiantes', $course->id) }}">
                @csrf

                <div class="teacher-list">
                    @foreach($students as $student)
                        <label class="teacher-item" for="student_{{ $student->id }}">
                            <input type="checkbox" name="students[]" value="{{ $student->id }}" id="student_{{ $student->id }}"
                                @checked($course->students->contains($student->id))>

                            <span>{{ $student->name }}</span>
                        </label>
                    @endforeach
                </div>


                <div class="form-actions">
                    <button class="btn-primary">
                        Guardar cambios
                    </button>

                    <a href="{{ route('admin.cursos.index') }}" class="btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection