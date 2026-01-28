@extends('layouts.admin')

@section('content')

    <div class="form-page">

        <h1>Crear nuevo docente</h1>

        @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.docentes.store') }}" method="POST" autocomplete="off" class="form-card">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Nombre de usuario</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Email institucional</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@vr.edu.ec"
                        pattern="[a-zA-Z0-9._%+-]+@vr\.edu\.ec" title="Debe terminar en @vr.edu.ec" required>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required>
                </div>

                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('admin.docentes.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Crear docente</button>
            </div>

        </form>

    </div>

@endsection