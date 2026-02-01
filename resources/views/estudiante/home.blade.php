@extends('layouts.student')

@section('content')

    <div class="container estudiante-dashboard">

        <h1 class="mb-4">Bienvenido {{ auth()->user()->name }}</h1>
        <div class="today-mini-card">
            <div class="mini-header">
                <i class="fas fa-clock"></i>
                <span>Horario de hoy — {{ $today }}</span>
            </div>
            <div id="clock" class="dashboard-clock mb-4"></div>

            @if($schedulesToday->isEmpty())
                <div class="mini-empty">
                    Hoy no tienes clases
                </div>
            @else
                @foreach($schedulesToday as $schedule)
                    <div class="mini-item">
                        <span class="mini-hour">
                            {{ substr($schedule->start_time, 0, 5) }}
                        </span>
                        <span class="mini-subject">
                            {{ $schedule->subject->name }}
                        </span>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="row">

            <!-- HORARIOS -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <img src="{{ asset('img/horarios.png') }}" class="card-img-top" alt="Horarios">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mis Horarios</h5>
                        <p class="card-text">Consulta tus clases y horarios.</p>
                        <a href="{{ route('estudiante.horarios') }}" class="btn btn-dark">Ver Horarios</a>
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
                        <a href="{{ route('estudiante.cursos') }}" class="btn btn-dark">Ver Cursos</a>
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
    </div>

    <script>
        function updateClock() {
            const now = new Date();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('clock').innerText =
                `${hours}:${minutes}:${seconds}`;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>

@endsection