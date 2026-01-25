@extends('layouts.app')

@section('content')
    <h2>Crear Nuevo Docente</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.docentes.store') }}" method="POST" autocomplete="off">
        <br>
        @csrf
        <div class="form-group mb-3">
            <label for="name">Nombre de Usuario:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                pattern="[a-zA-Z0-9._%+-]+@vr\.edu\.ec" title="El email debe terminar en @vr.edu.ec"
                placeholder="ejemplo@vr.edu.ec" required>
            <!--Agrego que el email deba estar en el dominio vr.edu.ec-->
        </div>
        <div class="form-group mb-3">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password_confirmation">Confirmar Contraseña:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="first_name">Nombre:</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}"
                required>
        </div>
        <div class="form-group mb-3">
            <label for="last_name">Apellido:</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="phone">Teléfono:</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
        </div>
        <button type="submit" class="btn btn-primary">Crear Docente</button>
        <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <br>
    <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">Volver</a>
@endsection