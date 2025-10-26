<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    * {
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }

    body {
      background-color: #f2f7f5;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .card {
      background: #fff;
      padding: 2.5rem;
      border-radius: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      border: 1px solid #d6e9e3;
    }

    .logo {
      display: flex;
      justify-content: center;
      margin-bottom: 1.5rem;
    }

    .logo img {
      width: 70px;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #2e7d5c;
      font-weight: 600;
    }

    label {
      display: block;
      font-size: 0.9rem;
      font-weight: 600;
      color: #444;
      margin-bottom: 0.3rem;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.7rem;
      border: 1px solid #cce3dc;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      outline: none;
      transition: 0.2s ease;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #2e7d5c;
      box-shadow: 0 0 4px rgba(46, 125, 92, 0.3);
    }

    .remember {
      display: flex;
      align-items: center;
      font-size: 0.9rem;
      color: #555;
      margin-bottom: 1.5rem;
    }

    .remember input {
      margin-right: 0.5rem;
      accent-color: #2e7d5c;
    }

    .actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .actions a {
      color: #2e7d5c;
      text-decoration: none;
      font-size: 0.9rem;
      transition: color 0.2s;
    }

    .actions a:hover {
      color: #1b523b;
    }

    button {
      background-color: #2e7d5c;
      color: white;
      border: none;
      padding: 0.7rem 1.5rem;
      border-radius: 0.7rem;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #24694a;
    }

    .status {
      color: #2e7d5c;
      background: #e7f5ef;
      padding: 0.6rem;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      font-size: 0.9rem;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="logo">
      <img src="logo.png" alt="Logo del sistema">
    </div>

    <h2>Iniciar Sesión</h2>

    <div class="status">Bienvenido, por favor ingrese sus datos</div>

    <form action="#" method="POST">
      <label for="email">Correo Electrónico</label>
      <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com">

      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required placeholder="********">

      <div class="remember">
        <input type="checkbox" id="remember_me">
        <label for="remember_me">Recordarme</label>
      </div>

      <div class="actions">
        <a href="#">¿Olvidaste tu contraseña?</a>
        <button type="submit">Ingresar</button>
      </div>
    </form>
  </div>
</body>
</html>
