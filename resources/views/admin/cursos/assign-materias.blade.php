@extends('layouts.admin')

@section('content')
<div class="form-page">

    <div class="page-header">
        <h1>Asignar materias</h1>
        <p class="subtitle">Curso: <strong>{{ $course->name }}</strong></p>
    </div>

    <div class="form-card">

        <form method="POST"
              action="{{ route('admin.cursos.storeMaterias', $course->id) }}">
            @csrf

            <div class="teacher-list">
                @foreach($subjects as $subject)
                    <label class="teacher-item">
                        <input type="checkbox"
                               name="subjects[]"
                               value="{{ $subject->id }}"
                               {{ $course->subjects->contains($subject->id) ? 'checked' : '' }}>
                        <span>{{ $subject->name }}</span>
                    </label>
                @endforeach
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    Guardar cambios
                </button>

                <a href="{{ route('admin.cursos.index') }}"
                   class="btn-secondary">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
</div>
@endsection

@section('scripts')
    @if(session('success'))
        <script>alert('{{ session('success') }}');</script>
    @endif
    @if(session('error'))
        <script>alert('{{ session('error') }}');</script>
    @endif
@endsection
