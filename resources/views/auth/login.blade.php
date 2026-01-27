<!DOCTYPE html>

<head>
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZmCcaYpPeyyzetH2LBTUeV3oNsYBW5v3ynxwA5dgvm/e9Bf6k1gKXK2HF8A0B5lJdKg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="login-container">
        <div class="login-card">

            <img src="{{ asset('img/logo.png') }}" class="logo" alt="Logo">

            <h2>Iniciar sesión</h2>
            <p class="subtitle">
                Ingresa tu correo electrónico institucional<br>
                y tu contraseña
            </p>
            @if(session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif
            @if(session('success'))
                <p class="success">{{ session('Éxito') }}</p>
            @endif

            <form method="POST" action="{{ route('login.process') }}" autocomplete="off">
                @csrf

                <input type="email" name="email" id="email" placeholder="correo.electronico@vr.edu.ec"
                    value="{{ old('email') }}" required>

                <input type="password" name="password" id="password" placeholder="contraseña" required>

                <button type="submit">Ingresar</button>
            </form>

            <a href="#" class="forgot">
                ¿Olvidaste tu contraseña?
            </a>

        </div>
    </div>

</body>

</html>