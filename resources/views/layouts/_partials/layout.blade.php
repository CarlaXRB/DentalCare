<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- FUENTES: Poppins (Mantener) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- 💥 SOLUCIÓN FINAL: Cargar Tailwind completo desde CDN 💥 -->
    <!-- Esto habilita todas las clases como bg-white, py-12, etc. -->
    <script src="https://cdn.tailwindcss.com"></script> 
    
    <!-- Estilos personalizados (Si tienes algún CSS custom que no sea Tailwind) -->
    <!-- Si no tienes CSS custom, puedes omitir esto. Por ahora, lo mantenemos por si acaso. -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    @livewireStyles
    <title>@yield('title')</title>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
</head>
<!-- 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @livewireStyles
    @livewireScripts
    <link rel="stylesheet" href="/css/app.css">
    <title>@yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
</head>
-->
<body>
    @include('layouts._partials.messages')
    @livewire('navigation-menu') 
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @yield('subtitle')
            </h2>
        </div>
    </header>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
               @yield('content')
            </div>
        </div>
    </div>
</body>
</html>