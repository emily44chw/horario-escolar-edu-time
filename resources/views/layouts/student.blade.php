<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>EduTime | Estudiante</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS estudiante -->
    <link rel="stylesheet" href="{{ asset('css/estudiante.css') }}">
</head>

<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg estudiante-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('estudiante.home') }}">
                EduTime
            </a>

            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('estudiante.home') }}">Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('estudiante.horarios') }}">Horarios</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('estudiante.cursos') }}">Cursos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('estudiante.profile') }}">Perfil</a>
                    </li>

                    <li class="nav-item ms-3">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <main class="container py-4">
        @yield('content')
    </main>
    @yield('scripts')
</body>

</html>