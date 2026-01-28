@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Editar Docente</h1>

        <div class="page-actions">
            <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('admin.docentes.update', $docente) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $docente->user->name) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Email institucional</label>
                    <input type="email" name="email" class="form-control" pattern="[a-zA-Z0-9._%+-]+@vr\.edu\.ec"
                        title="Debe terminar en @vr.edu.ec" value="{{ old('email', $docente->user->email) }}" required>
                </div>

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="first_name" class="form-control"
                        value="{{ old('first_name', $docente->first_name) }}" required>
                </div>

                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="last_name" class="form-control"
                        value="{{ old('last_name', $docente->last_name) }}" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $docente->phone) }}">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="Activo" {{ old('status', $docente->status) === 'Activo' ? 'selected' : '' }}>
                            Activo
                        </option>
                        <option value="Inactivo" {{ old('status', $docente->status) === 'Inactivo' ? 'selected' : '' }}>
                            Inactivo
                        </option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection