@extends('layouts.admin')

@section('content')
    <div class="form-page">
        <h1>Editar Docente</h1>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.docentes.update', $docente) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-grid">
                <div class="form-group mb-3">
                    <label for="name">Nombre de Usuario:</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', $docente->user->name) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" pattern="[a-zA-Z0-9._%+-]+@vr\.edu\.ec"
                        title="El email debe terminar en @vr.edu.ec" placeholder="ejemplo@vr.edu.ec"
                        value="{{ old('email', $docente->user->email) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="first_name">Nombre:</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                        value="{{ old('first_name', $docente->first_name) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="last_name">Apellido:</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                        value="{{ old('last_name', $docente->last_name) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="phone">Teléfono:</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                        value="{{ old('phone', $docente->phone) }}">
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" required>
                        <option value="Activo" {{ old('status', $docente->status ?? 'Activo') == 'Activo' ? 'selected' : '' }}>
                            Activo
                        </option>
                        <option value="Inactivo" {{ old('status', $docente->status ?? 'Activo') == 'Inactivo' ? 'selected' : '' }}>
                            Inactivo
                        </option>
                    </select>
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Docente</button>
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>
@endsection