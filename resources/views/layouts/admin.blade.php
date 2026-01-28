<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>EduTime | Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

    <div class="admin-layout">

        <aside class="sidebar">
            <h2>EduTime</h2>

            <p class="section">Descubrir</p>
            <ul>
                <li>
                    <a href="{{ route('admin.home') }}">
                        <i class="fa-solid fa-house"></i>
                        Inicio
                    </a>
                </li>
            </ul>
            <br>
            <p class="section">Gestión</p>
            <ul>
                <li>
                    <a href="{{ route('admin.docentes.index') }}">
                        <i class="fa-solid fa-user-tie"></i>
                        Docentes
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.estudiantes.index') }}">
                        <i class="fa-solid fa-user-graduate"></i>
                        Estudiantes
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.materias.index') }}">
                        <i class="fa-solid fa-book"></i>
                        Materias
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.cursos.index') }}">
                        <i class="fa-solid fa-chalkboard"></i>
                        Aulas
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.horarios.index') }}">
                        <i class="fa-solid fa-calendar-days"></i>
                        Horarios
                    </a>
                </li>
            </ul>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar sesión
                </button>
            </form>
        </aside>


        <main class="content">
            @yield('content')
        </main>

    </div>

</body>

</html>