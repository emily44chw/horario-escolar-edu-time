<?php
session_start();

use Illuminate\Support\Facades\DB;
require_once __DIR__ . '/../vendor/autoload.php';

$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

$user = DB::table('users')
    ->where('email', $email)
    ->first();

if (!$user) {
    header('Location: /login.php?error=email');
    exit;
}

if (!password_verify($password, $user->password)) {
    header('Location: /login.php?error=password');
    exit;
}

$_SESSION['user_id'] = $user->id;
$_SESSION['rol'] = $user->rol;
$_SESSION['name'] = $user->name;


header('Location: /' . $user->rol . '/home.php');


exit;

?>