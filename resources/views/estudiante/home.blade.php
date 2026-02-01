@extends('layouts.student')

@section('content')

    <div class="container estudiante-dashboard">

        <h2 class="mb-4">Bienvenido {{ auth()->user()->name }}</h2>

        <div class="row">

            <!-- HORARIOS -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <img src="{{ asset('img/horarios.png') }}" class="card-img-top" alt="Horarios">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mis Horarios</h5>
                        <p class="card-text">Consulta tus clases y horarios.</p>
                        <a href="{{ route('estudiante.horarios') }}" class="btn btn-primary">Ver Horarios</a>
                    </div>
                </div>
            </div>

            <!-- CURSOS -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <img src="{{ asset('img/cursos.png') }}" class="card-img-top" alt="Cursos">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mis Cursos</h5>
                        <p class="card-text">Materias en las que estás inscrito.</p>
                        <a href="{{ route('estudiante.cursos') }}" class="btn btn-success">Ver Cursos</a>
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
                        <a href="{{ route('estudiante.profile') }}" class="btn btn-dark">Mi Perfil</a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="schedule-card-wrapper">

            <div class="schedule-card-header">
                <h3>
                    <i class="fas fa-calendar-day"></i>
                    Mi horario de hoy — {{ $today }}
                </h3>
            </div>

            <div class="schedule-card-body">

                @if($schedulesToday->isEmpty())
                    <div class="no-classes">
                        <i class="fas fa-exclamation-circle"></i>
                        Hoy no tienes clases
                    </div>
                @else
                    <div class="day-view">
                        @foreach($schedulesToday as $schedule)
                            <div class="day-block">
                                <div class="hour">
                                    {{ substr($schedule->start_time, 0, 5) }} -
                                    {{ substr($schedule->end_time, 0, 5) }}
                                </div>

                                <div class="info">
                                    <div class="subject">
                                        {{ $schedule->subject->name }}
                                    </div>
                                    <div class="teacher">
                                        <i class="fas fa-user"></i>
                                        {{ $schedule->teacher->name ?? 'Docente no asignado' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>


    </div>
@endsection