<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Dental Care') }}</title>
        
        <!-- 1. Carga de FUENTES -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- 💥 AÑADE ESTA LÍNEA 💥 -->
        <!-- 2. Carga de Tailwind CSS completo desde CDN para que los estilos funcionen -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- 3. Estilos de Livewire -->
        @livewireStyles
        
        <!-- 4. Librerías Externas -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
    </head>
    
    <body class="font-sans antialiased"> 
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')
        @livewireScripts
        
        <!-- Script de Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        @stack('scripts')
    </body>
</html>