<?php

use Illuminate\Support\Facades\Session;

if (!Session::has('user_id')) {
    echo "<script>
            alert('Debes iniciar sesión');
            window.location.href = '/login';
          </script>";
    exit;
}

if (Session::get('user_rol') !== 'estudiante') {
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
    <h1>Home Estudiante</h1>
    <p>Bienvenido estudiante</p>
    <br><br>
    <button onclick="window.location.href='/logout'">
        Cerrar sesión
    </button>

</body>

</html>