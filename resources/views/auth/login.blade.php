<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduTime</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        form {
            max-width: 300px;
        }

        input {
            display: block;
            margin: 10px 0;
            padding: 8px;
            width: 100%;
        }

        button {
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>Iniciar Sesión en EduTime</h2>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif
    @if(session('success'))
        <p class="success">{{ session('Éxito') }}</p>
    @endif

    <!-- Formulario de login -->
    <form action="{{ route('login.process') }}" method="POST">
        @csrf <!-- Protección CSRF de Laravel -->
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        <!-- Mantener valor anterior -->

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Ingresar</button>
    </form>

</body>

</html>