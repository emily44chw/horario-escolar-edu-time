<?php

use Illuminate\Support\Facades\Session;

if (!Session::has('user_id')) {
    echo "<script>
            alert('Debes iniciar sesión');
            window.location.href = '/login';
          </script>";
    exit;
}

if (Session::get('user_rol') !== 'admin') {
    echo "<script>
            alert('Acceso denegado');
            window.location.href = '/login';
          </script>";
    exit;
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>EduTime</title>
    <h1>Bienvenido <?php echo Session::get('user_name'); ?></h1>
</head>

<body>
    <ul>
        <li><a href="/docentes">Gestionar Docentes</a></li>
        <li><a href="/estudiantes">Gestionar Estudiantes</a></li>
    </ul>

    <br><br>
    <button onclick="window.location.href='/logout'">
        Cerrar sesión
    </button>
</body>

</html>