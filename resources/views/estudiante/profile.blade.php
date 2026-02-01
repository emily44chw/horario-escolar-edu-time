@extends('layouts.app')

@section('content')
    <div class="container estudiante-dashboard">

        <h2 class="mb-4">Bienvenido {{ auth()->user()->name }}</h2>

        <div class="row">

            <!-- HORARIOS -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <img src="{{ asset('img/horarios.jpg') }}" class="card-img-top" alt="Horarios">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mis Horarios</h5>
                        <p class="card-text">Consulta tus clases y horarios.</p>
                        <a href="#" class="btn btn-primary">Ver Horarios</a>
                    </div>
                </div>
            </div>

            <!-- CURSOS -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <img src="{{ asset('img/cursos.jpg') }}" class="card-img-top" alt="Cursos">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mis Cursos</h5>
                        <p class="card-text">Materias en las que estás inscrito.</p>
                        <a href="#" class="btn btn-success">Ver Cursos</a>
                    </div>
                </div>
            </div>

            <!-- PERFIL -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <img src="{{ asset('img/perfil.jpg') }}" class="card-img-top" alt="Perfil">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mi Perfil</h5>
                        <p class="card-text">Actualiza tu contraseña.</p>
                        <a href="#" class="btn btn-dark">Mi Perfil</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection