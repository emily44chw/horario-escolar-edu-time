@extends('layouts.student')

@section('content')

    <h2 class="page-title">
        <i class="fas fa-user-circle"></i> Mi perfil
    </h2>

    <div class="profile-card">

        <h4>Cambiar contraseña</h4>

        <form method="POST" action="{{ route('estudiante.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Contraseña actual</label>
                <input type="password" name="current_password" required>
            </div>

            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button class="btn-primary">
                Actualizar contraseña
            </button>
        </form>

    </div>

@endsection

@section('scripts')
    @if(session('success'))
        <script>alert('{{ session('success') }}');</script>
    @endif

    @if(session('error'))
        <script>alert('{{ session('error') }}');</script>
    @endif
@endsection