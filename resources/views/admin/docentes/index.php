<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../bootstrap/app.php';

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

// Validar que el usuario esté logueado y sea admin
if (!Session::has('user_id') || Session::get('user_rol') !== 'admin') {
    echo "<script>
            alert('Acceso denegado. Debes ser administrador.');
            window.location.href = '/login';
          </script>";
    exit;
}


// Consulta para obtener la lista de docentes
$teachers = DB::table('teachers')
    ->join('users', 'teachers.user_id', '=', 'users.id')
    ->where('users.rol', 'docente')
    ->select('teachers.id', 'teachers.name', 'teachers.email', 'users.email as user_email')
    ->get();

?>

<!-- Tabla de docentes -->
<!DOCTYPE html>

<head>
    <title>EduTime</title>
</head>

<body>
    <h3>Administracion de docentes</h3>
    <button onclick="location.href='create.php'">Agregar nuevo docente +</button>
    <br><br>
    <h2>Lista de Docentes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
        </tr>

        <?php foreach ($teachers as $teacher): ?>
            <tr>
                <td><?= $teacher['id'] ?></td>
                <td><?= $teacher['name'] ?></td>
                <td><?= $teacher['email'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button onclick="location.href='../index.php'">Volver</button>
</body>

</html>