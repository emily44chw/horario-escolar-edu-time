<?php

use Illuminate\Support\Facades\DB;

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') { //verificar que el usuario es admin 
    header('Location: /login');//redireccionar al login si no es admin
    exit;
}

//Consultar a la base los docentes 
/*Como la tabla teachers tiene user_id vamos a referenciarlo con la tabla users */
$teachers = DB::table('teachers')
    ->join('users', 'teachers.user_id', '=', 'users.id')
    ->where('users.rol', 'docente')
    ->select('teachers.id', 'teachers.name', 'teachers.email', 'users.email as user_email')
    ->get();

?>
//Tabla de docentes
<!DOCTYPE html>

<head>
    <title>EduTime</title>
</head>

<body>
    <h2>Lista de Docentes</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
        </tr>
    </table>

    <?php foreach ($teachers as $teacher): ?>
        <tr>
            <td><?= $teacher->id ?></td>
            <td><?= $teacher->name ?></td>
            <td><?= $teacher->email ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</body>

</html>