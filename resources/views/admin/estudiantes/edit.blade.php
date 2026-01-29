@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Editar Estudiante</h1>

        <div class="page-actions">
            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.estudiantes.update', $estudiante) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $estudiante->user->name) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Email institucional</label>
                    <input type="email" name="email" class="form-control" pattern="[a-zA-Z0-9._%+-]+@vr\.edu\.ec"
                        title="Debe terminar en @vr.edu.ec" value="{{ old('email', $estudiante->user->email) }}" required>
                </div>

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="first_name" class="form-control"
                        value="{{ old('first_name', $estudiante->first_name) }}" required>
                </div>

                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="last_name" class="form-control"
                        value="{{ old('last_name', $estudiante->last_name) }}" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $estudiante->phone) }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="Activo" {{ old('status', $estudiante->status) === 'Activo' ? 'selected' : '' }}>
                            Activo
                        </option>
                        <option value="Inactivo" {{ old('status', $estudiante->status) === 'Inactivo' ? 'selected' : '' }}>
                            Inactivo
                        </option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
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