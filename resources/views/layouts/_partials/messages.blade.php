<!-- Mensaje de éxito -->
@if ($message = Session::get('success'))
    <div class="w-full p-2 bg-green-100 text-green-800 border border-green-400 rounded-none text-center flex items-center justify-center gap-2 shadow-md">
        <!-- Icono de check -->
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 13l4 4L19 7" />
        </svg>
        <p class="font-medium">{{ $message }}</p>
    </div>
@endif

<!-- Mensaje de error -->
@if ($message = Session::get('danger'))
    <div class="w-full p-2 bg-red-100 text-red-800 border border-red-400 rounded-none text-center flex items-center justify-center gap-2 shadow-md">
        <!-- Icono de alerta -->
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 002-2V7a2 2 0 00-2-2H5.07a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <p class="font-medium">{{ $message }}</p>
    </div>
@endif
