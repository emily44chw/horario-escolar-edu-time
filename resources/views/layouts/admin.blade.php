<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <div class="admin-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2>EduTime</h2>

            <p class="section">Gestión</p>
            <ul>
                <li><a href="{{ route('admin.home') }}">Inicio</a></li>
                <li><a href="{{ route('admin.docentes.index') }}">Docentes</a></li>
                <li><a href="{{ route('admin.estudiantes.index') }}">Estudiantes</a></li>
                <li><a href="{{ route('admin.horarios.index') }}">Horarios</a></li>
            </ul>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout">Cerrar sesión</button>
            </form>
        </aside>

        <!-- CONTENIDO -->
        <main class="content">
            @yield('content')
        </main>

    </div>

</body>

</html>