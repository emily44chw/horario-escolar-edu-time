<?php
session_start();

require_once '../../config/database.php'; // Incluir configuración de la base de datos

/* Validar admin */

?>

<!-- Formulario para crear un nuevo docente -->
<!DOCTYPE html>
<html>

<head>
    <title>EduTime</title>
</head>

<body>
    <h2>Crear nuevo docente</h2>
    <form action="store.php" method="POST">
        <label for="name">Nombre:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required autocomplete="off"><br><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required autocomplete="on"><br><br>
        <input type="submit" value="Guardar Docente">
    </form>
</body>
<br>
<button onclick="location.href='index.php'">Volver</button>
</body>

</html>