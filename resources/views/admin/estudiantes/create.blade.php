@extends('layouts.admin')

@section('content')
    <div class="form-page">
        <h2>Crear Nuevo Estudiante</h2>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.estudiantes.store') }}" method="POST" autocomplete="off">
            <br>
            @csrf
            <div class="form-grid">
                <div class="form-group mb-3">
                    <label for="name">Nombre de Usuario:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                        pattern="[a-zA-Z0-9._%+-]+@vr\.edu\.ec" title="El email debe terminar en @vr.edu.ec"
                        placeholder="ejemplo@vr.edu.ec" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password_confirmation">Confirmar Contraseña:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        required>
                </div>
                <div class="form-group mb-3">
                    <label for="first_name">Nombre:</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                        value="{{ old('first_name') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="last_name">Apellido:</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}"
                        required>
                </div>
                <div class="form-group mb-3">
                    <label for="phone">Teléfono:</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="birth_date">Fecha de Nacimiento:</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control"
                        value="{{ old('birth_date') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="representative">Representante:</label>
                    <input type="text" name="representative" id="representative" class="form-control"
                        value="{{ old('representative') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="representative_phone">Teléfono del Representante:</label>
                    <input type="text" name="representative_phone" id="representative_phone" class="form-control"
                        value="{{ old('representative_phone') }}">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Crear Estudiante</button>
                <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection