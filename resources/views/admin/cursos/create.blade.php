@extends('layouts.admin')

@section('content')

    <div class="form-page">

        <h1>Crear nuevo curso</h1>

        @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.cursos.store') }}" method="POST" class="form-card" autocomplete="off">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Grado</label>
                    <input type="text" name="grade" value="{{ old('grade') }}" placeholder="Ej: Primero, Segundo" required>
                </div>

                <div class="form-group">
                    <label>Paralelo</label>
                    <input type="text" name="parallel" value="{{ old('parallel') }}" placeholder="Ej: A" maxlength="1"
                        required>
                </div>

                <div class="form-group">
                    <label>Año lectivo</label>
                    <input type="text" name="school_year" value="{{ old('school_year') }}" placeholder="Ej: 2024-2025"
                        required>
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('admin.cursos.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Crear curso</button>
            </div>

        </form>

    </div>

@endsection