<?php

use Illuminate\Support\Facades\Session;

if (!Session::has('user_id')) {
    echo "<script>
            alert('Debes iniciar sesión');
            window.location.href = '/login';
          </script>";
    exit;
}

if (Session::get('user_rol') !== 'docente') {
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
    <h1>Home Docente</h1>
    <p>Bienvenido docente</p>
    <br><br>
    <a href="/logout">Cerrar sesión</a>
</body>

</html>