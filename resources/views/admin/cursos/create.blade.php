@extends('layouts.admin')

@section('content')

    <div class="form-page">

        <h1>Crear nuevo curso</h1>



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

@section('scripts')
    @if(session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
    @if(session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif
@endsection