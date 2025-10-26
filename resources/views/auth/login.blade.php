<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - Dental Care</title>
  <!-- Usamos CSS estático para evitar Vite y Node -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    /* 1. Reset Básico */
    * {
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    /* 2. Cuerpo y Centrado */
    body {
      background-color: #f2f7f5;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    /* 3. Tarjeta Principal */
    .card {
      background: #fff;
      padding: 2.5rem;
      border-radius: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Sombra más fuerte */
      width: 100%;
      max-width: 420px; /* Un poco más ancho */
      border: 1px solid #d6e9e3;
    }

    /* 4. Logo */
    .logo {
      display: flex;
      justify-content: center;
      margin-bottom: 2rem;
    }

    .logo img {
      width: 80px; /* Un poco más grande */
      border-radius: 50%; /* Si el logo es cuadrado, se ve mejor circular */
      box-shadow: 0 0 10px rgba(46, 125, 92, 0.2);
    }

    /* 5. Encabezado */
    h2 {
      text-align: center;
      margin-bottom: 2rem;
      color: #2e7d5c;
      font-weight: 700; /* Más audaz */
      font-size: 1.8rem;
    }

    /* 6. Formulario y Campos */
    label {
      display: block;
      font-size: 0.95rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 0.5rem;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.8rem;
      border: 2px solid #cce3dc; /* Borde más grueso */
      border-radius: 0.7rem;
      margin-bottom: 1.25rem;
      outline: none;
      transition: 0.3s ease;
      background-color: #fafafa;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #2e7d5c;
      box-shadow: 0 0 6px rgba(46, 125, 92, 0.5);
      background-color: #fff;
    }

    /* 7. Recordarme */
    .remember {
      display: flex;
      align-items: center;
      font-size: 0.9rem;
      color: #555;
      margin-bottom: 2rem;
    }

    .remember input {
      margin-right: 0.6rem;
      accent-color: #2e7d5c;
      transform: scale(1.1); /* Checkbox un poco más grande */
    }

    /* 8. Acciones (Botón y Link) */
    .actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .actions a {
      color: #2e7d5c;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: color 0.2s;
    }

    .actions a:hover {
      color: #1b523b;
      text-decoration: underline;
    }

    button {
      background-color: #2e7d5c;
      color: white;
      border: none;
      padding: 0.8rem 2rem;
      border-radius: 0.7rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }

    button:hover {
      background-color: #24694a;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* 9. Mensaje de Estado */
    .status {
      color: #2e7d5c;
      background: #e7f5ef;
      padding: 0.8rem;
      border-radius: 0.7rem;
      margin-bottom: 1.5rem;
      font-size: 0.95rem;
      text-align: center;
      border: 1px solid #cce3dc;
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="logo">
      <!-- Asegúrate de que esta ruta sea correcta para tu logo -->
      <img src="{{ asset('assets/images/logoDe.png') }}" alt="Logo del sistema"> 
    </div>

    <h2>Iniciar Sesión</h2>

    <!-- Simulación de mensaje de estado, si Laravel lo usa -->
    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @else
        <div class="status">Bienvenido, por favor ingrese sus datos</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <label for="email">Correo Electrónico</label>
      <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="ejemplo@correo.com">
      @error('email')
        <p style="color: red; font-size: 0.85rem; margin-top: -1rem; margin-bottom: 1rem;">{{ $message }}</p>
      @enderror

      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required placeholder="********">
      @error('password')
        <p style="color: red; font-size: 0.85rem; margin-top: -1rem; margin-bottom: 1rem;">{{ $message }}</p>
      @enderror

      <div class="remember">
        <input type="checkbox" id="remember_me" name="remember">
        <label for="remember_me">Recordarme</label>
      </div>

      <div class="actions">
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        @endif
        <button type="submit">Ingresar</button>
      </div>
    </form>
  </div>
</body>
</html>