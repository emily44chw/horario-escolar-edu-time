@extends('layouts.admin')

@section('content')
<div class="form-page">

    <div class="page-header">
        <h1>Asignar docentes</h1>
        <p class="subtitle">Materia: <strong>{{ $subject->name }}</strong></p>
    </div>

    <div class="form-card">

        <form method="POST"
              action="{{ route('admin.materias.storeDocentes', $subject->id) }}">
            @csrf

            <div class="teacher-list">
                @foreach($teachers as $teacher)
                    <label class="teacher-item">
                        <input type="checkbox"
                               name="teachers[]"
                               value="{{ $teacher->id }}"
                               {{ $subject->teachers->contains($teacher->id) ? 'checked' : '' }}>
                        <span>{{ $teacher->user->name }}</span>
                    </label>
                @endforeach
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    Guardar cambios
                </button>

                <a href="{{ route('admin.materias.index') }}"
                   class="btn-secondary">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
