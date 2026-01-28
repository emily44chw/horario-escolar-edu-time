@extends('layouts.admin')

@section('content')

    <div class="form-page">

        <h1>Editar curso</h1>

        @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.cursos.update', $course) }}" method="POST" class="form-card">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Grado</label>
                    <input type="text" name="grade" value="{{ old('grade', $course->grade) }}" required>
                </div>

                <div class="form-group">
                    <label>Paralelo</label>
                    <input type="text" name="parallel" value="{{ old('parallel', $course->parallel) }}" maxlength="1"
                        required>
                </div>

                <div class="form-group">
                    <label>Año lectivo</label>
                    <input type="text" name="school_year" value="{{ old('school_year', $course->school_year) }}" required>
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('admin.cursos.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Actualizar curso</button>
            </div>

        </form>

    </div>

@endsection