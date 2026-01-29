@extends('layouts.admin')

@section('content')
    <div class="form-page">

        <h1>Crear nueva materia</h1>
        <form action="{{ route('admin.materias.store') }}" method="POST" autocomplete="off" class="form-card">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Nombre de materia</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('admin.materias.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Materia</button>
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