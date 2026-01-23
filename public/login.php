<!DOCTYPE html>

<head>
    <title>Iniciar Sesión</title>
</head>

<body>
    <h2>Iniciar Sesión</h2>

    <?php
    $error = $_GET['error'] ?? '';
    ?>

    <?php if ($error === 'email' || $error === 'password'): ?>
        <p style="color:red;">Dato incorrecto. Vuelva a intentarlo</p>
    <?php endif; ?>


    <form action="/login-procesar" method="POST" autocomplete="off">
        <input type="hidden" name="_token" value="<?= csrf_token() ?>">

        <label>Email</label><br>
        <input type="email" name="email" required autocomplete="off"><br><br>

        <label>Contraseña</label><br>
        <input type="password" name="password" required autocomplete="off"><br><br>

        <button type="submit">Ingresar</button>
    </form>

</body>

</html>