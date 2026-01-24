<?php
session_start(); // Iniciar sesión

require_once '../../config/database.php'; // Incluir configuración de la base de datos

/* Validar admin */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header('Location: /login.php');
    exit;
}

// Recibir datos del formulario
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($name == '' || $email == '' || $password == '') {
    die('Todos los campos son obligatorios');
}

// Hashear la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insertar en tabla users

try {

    $sqlCheck = "SELECT id FROM users WHERE email = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$email]);

    if ($stmtCheck->rowCount() > 0) {
        die('El correo ya está registrado');
    }

    $sqlUser = "
        INSERT INTO users (name, email, password, rol, created_at, updated_at)
        VALUES (?, ?, ?, 'docente', NOW(), NOW())
    ";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute([$name, $email, $passwordHash]);

    /* Obtener ID del usuario creado */
    $userId = $pdo->lastInsertId();

    $sqlTeacher = "
        INSERT INTO teachers (user_id, name, email, created_at, updated_at)
        VALUES (?, ?, ?, NOW(), NOW())
    ";
    $stmtTeacher = $pdo->prepare($sqlTeacher);
    $stmtTeacher->execute([$userId, $name, $email]);

    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    die("Error al guardar el docente: " . $e->getMessage());
}
?>