<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dental Care</title>

    <!-- Fuentes -->
    <link href="https://fonts.bunny.net/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Estilos -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background-color: #f7fafc;
        }

        .hero {
            position: relative;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        .hero img.banner {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(60%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100%;
            padding: 20px;
            background: rgba(0, 0, 0, 0.4);
        }

        .hero-content img.logo {
            width: 180px;
            height: auto;
            margin-bottom: 25px;
            animation: fadeInDown 1.2s ease;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 1.2s ease;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-top: 15px;
            color: #e0e0e0;
            animation: fadeIn 2s ease;
        }

        .hero-content .btn {
            margin-top: 30px;
            background-color: #00bcd4;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .hero-content .btn:hover {
            background-color: #0097a7;
            transform: translateY(-3px);
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            .hero-content p {
                font-size: 1rem;
            }
            .hero-content img.logo {
                width: 140px;
            }
        }
    </style>
</head>

<body>
    <div class="hero">
        <!-- Banner de fondo -->
        <img src="{{ asset('storage/assets/images/banner.png') }}" alt="Banner Dental Care" class="banner">

        <!-- Contenido -->
        <div class="hero-content">
            <img src="{{ asset('storage/assets/images/logoDen.png') }}" alt="Logo Dental Care" class="logo">
            <h1>¡Bienvenido!</h1>
            <p>Tu sonrisa, nuestra prioridad.</p>

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn">Panel principal</a>
                @else
                    <a href="{{ route('login') }}" class="btn">Ingresar</a>
                @endauth
            @endif
        </div>
    </div>
</body>
</html>
