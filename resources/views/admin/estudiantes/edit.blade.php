@extends('layouts.app')

@section('content')
    <h1>Editar Estudiante</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.estudiantes.update', $estudiante) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Nombre de Usuario:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $estudiante->user->name) }}"
                required>
        </div>
        <div class="form-group mb-3">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" pattern="[a-zA-Z0-9._%+-]+@vr\.edu\.ec"
                title="El email debe terminar en @vr.edu.ec" placeholder="ejemplo@vr.edu.ec"
                value="{{ old('email', $estudiante->user->email) }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="first_name">Nombre:</label>
            <input type="text" name="first_name" id="first_name" class="form-control"
                value="{{ old('first_name', $estudiante->first_name) }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="last_name">Apellido:</label>
            <input type="text" name="last_name" id="last_name" class="form-control"
                value="{{ old('last_name', $estudiante->last_name) }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="phone">Teléfono:</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $estudiante->phone) }}">
        </div>
        <div class="form-group mb-3">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Activo" {{ old('status', $estudiante->status) == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('status', $estudiante->status) == 'Inactivo' ? 'selected' : '' }}>Inactivo
                </option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Estudiante</button>
        <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection