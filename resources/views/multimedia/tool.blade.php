@extends('layouts._partials.layout')
@section('title', __('Ver Estudio Multimedia'))
@section('subtitle')
{{ __('Ver Estudio Multimedia') }}
@endsection

{{-- Estilos necesarios para las herramientas y el carrusel --}}
@section('styles')
<style>
    /* Estilo base para los botones de imagen */
    .btnimg {
        @apply p-2 rounded-xl shadow-md transition duration-200 ease-in-out bg-white dark:bg-gray-700 hover:bg-cyan-500 dark:hover:bg-cyan-600;
        line-height: 1; /* Asegura que el contenido esté centrado */
    }
    .btnimg img {
        @apply w-8 h-8 md:w-10 md:h-10; /* Ajusta el tamaño de los íconos */
    }
    
    /* Estilo para el contenedor del visor de imagen */
    .image-viewer-container {
        position: relative;
        width: 100%;
        max-width: 1100px;
        height: 700px; /* Altura fija para el visor */
        margin: 0 auto;
        overflow: hidden;
        background-color: #f0f0f0; /* Fondo para el área del estudio */
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    }

    .image-viewer-container img {
        /* Propiedades para centrar y aplicar zoom/pan */
        width: auto;
        height: auto;
        max-width: none; 
        max-height: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(1);
        transform-origin: 0 0; /* Punto de origen para el arrastre */
        will-change: transform, filter, left, top; /* Optimización de rendimiento */
    }

    /* Estilos para la tabla */
    .title4 {
        @apply font-semibold text-lg;
    }
    .txt {
        @apply text-base;
    }
    .bottonDelete {
        @apply bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150;
    }

</style>
@endsection

@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('multimedia.index') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<div class="max-w-6xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:bg-gray-800 dark:text-white shadow-xl">

    {{-- Información del Estudio --}}
    <div class="mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información del Estudio') }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5 text-black dark:text-white">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Nombre del Paciente:') }}</h3>
            <span class="txt">{{ $study->name_patient }}</span>
        </div>    
        <div class="flex gap-2">
            <h3 class="title4">{{ __('C.I. del Paciente:') }}</h3>
            <span class="txt">{{ $study->ci_patient }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Código de Estudio:') }}</h3>
            <span class="txt">{{ $study->study_code }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Fecha del Estudio:') }}</h3>
            <span class="txt">{{ $study->study_date }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Tipo de Estudio:') }}</h3>
            <span class="txt">{{ $study->study_type }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Cantidad de Imágenes:') }}</h3>
            <span class="txt">{{ count($imageUrls) }}</span> {{-- Usamos count($imageUrls) para la cantidad real --}}
        </div>
    </div>

    @if($study->description)
    <div class="mb-6">
        <h3 class="title4">{{ __('Descripción:') }}</h3>
        <p class="txt">{{ $study->description }}</p>
    </div>
    @endif

    {{-- --- BARRA DE HERRAMIENTAS --- --}}
    <h1 class="title1 mt-8">{{ __('Herramientas') }}</h1>
    <div class="relative flex flex-wrap justify-center space-x-2 py-4">
        {{-- Botones de Herramienta --}}
        {{-- Utilizamos íconos de Font Awesome con Tailwind (se asume que están cargados en layout) --}}
        @php
            $tools = [
                ['id' => 'zoomIn', 'icon' => 'fas fa-search-plus', 'tooltip' => 'Acercar'],
                ['id' => 'zoomOut', 'icon' => 'fas fa-search-minus', 'tooltip' => 'Alejar'],
                ['id' => 'invertColors', 'icon' => 'fas fa-adjust', 'tooltip' => 'Negativo'],
                ['id' => 'increaseBrightness', 'icon' => 'fas fa-sun', 'tooltip' => 'Más Brillo'],
                ['id' => 'decreaseBrightness', 'icon' => 'fas fa-moon', 'tooltip' => 'Menos Brillo'],
                ['id' => 'increaseContrast', 'icon' => 'fas fa-circle-half-stroke', 'tooltip' => 'Más Contraste'],
                ['id' => 'decreaseContrast', 'icon' => 'fas fa-low-vision', 'tooltip' => 'Menos Contraste'],
                ['id' => 'edgesButton', 'icon' => 'fas fa-border-all', 'tooltip' => 'Marcar Bordes'],
                //['id' => 'magnifierToggle', 'icon' => 'fas fa-magnifying-glass-location', 'tooltip' => 'Lupa'],
            ];
        @endphp

        @foreach($tools as $tool)
        <div class="group relative">
            <button id="{{ $tool['id'] }}" class="btnimg">
                <i class="{{ $tool['icon'] }} text-xl text-gray-800 dark:text-white group-hover:text-white"></i>
            </button>
            <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 bg-blue-300 bg-opacity-70 text-center rounded-md px-2 py-1 whitespace-nowrap">
                <span class="text-sm text-gray-800">{{ $tool['tooltip'] }}</span>
            </div>
        </div>
        @endforeach

        {{-- Botón Guardar --}}
        <div class="group relative">
            <button id="save" class="btnimg bg-green-500 hover:bg-green-600">
                <i class="fas fa-save text-xl text-white"></i>
            </button>
            <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 bg-green-300 bg-opacity-70 text-center rounded-md px-2 py-1 whitespace-nowrap">
                <span class="text-sm text-gray-800">Guardar</span>
            </div>
        </div>

        {{-- Botón Descargar --}}
        <div class="group relative">
            <button id="downloadImage" class="btnimg bg-blue-500 hover:bg-blue-600">
                <i class="fas fa-download text-xl text-white"></i>
            </button>
            <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 bg-blue-300 bg-opacity-70 text-center rounded-md px-2 py-1 whitespace-nowrap">
                <span class="text-xs text-gray-800">Descargar</span>
            </div>
        </div>
        
        {{-- Botón Mediciones (Redirección) --}}
        {{-- ASUMO que tienes una ID de radiografía o un ID que puedes usar para la ruta de medición --}}
        {{-- Aquí se usa $study->id como placeholder, ajústalo según tu ruta de mediciones --}}
        {{-- @if (isset($radiography->id)) --}}
        <div class="group relative">
            <button id="draw" class="btnimg bg-purple-500 hover:bg-purple-600" onclick="window.location.href='#'"> {{-- route('radiography.measurements', $radiography->id) --}}
                <i class="fas fa-ruler-combined text-xl text-white"></i>
            </button>
            <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 bg-purple-300 bg-opacity-70 text-center rounded-md px-2 py-1 whitespace-nowrap">
                <span class="text-sm text-gray-800">Mediciones</span>
            </div>
        </div>
        {{-- @endif --}}
    </div>

    {{-- --- VISOR DE IMAGEN Y CARRUSEL --- --}}
    @if(count($imageUrls) > 0)
    <div class="relative mt-8">
        {{-- VISOR CENTRAL --}}
        <div class="image-viewer-container" id="imageViewerContainer">
            <img id="currentImage" 
                 src="{{ $imageUrls[0] }}" 
                 alt="Imagen del estudio 1" 
                 crossorigin="anonymous" 
            />
            {{-- <div id="magnifierLens" style="display: none; position: absolute; border: 4px solid #fff; border-radius: 50%; pointer-events: none; width: 150px; height: 150px; background-repeat: no-repeat; box-shadow: 0 0 10px rgba(0,0,0,0.5);"></div> --}}
        </div>
        
        {{-- CONTROLES DEL CARRUSEL --}}
        <div class="flex justify-center items-center space-x-4 mt-4">
            <button id="prevImage" class="botton1 p-2">
                <i class="fas fa-chevron-left"></i> {{ __('Anterior') }}
            </button>
            <span id="imageCounter" class="txt text-lg">
                1 / {{ count($imageUrls) }}
            </span>
            <button id="nextImage" class="botton1 p-2">
                {{ __('Siguiente') }} <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        {{-- DATA JSON oculta para JS --}}
        <script>
            // Pasamos las URLs de las imágenes y la info del estudio a JavaScript
            const IMAGE_URLS = @json($imageUrls);
            const STUDY_DATA = @json([
                'id' => $study->id,
                'study_code' => $study->study_code,
                // Agrega otros IDs o datos necesarios para tu ruta 'tool.store'
            ]);
            // Esta URL asume que 'tool.store' espera los datos en el body
            const SAVE_URL = "{{ route('tool.store', ['radiography_id' => $study->id, 'tomography_id' => 0, 'ci_patient' => $study->ci_patient, 'id' => $study->id]) }}";
            const CSRF_TOKEN = '{{ csrf_token() }}';
        </script>
        
    @else
        <p class="text-gray-500 text-center col-span-full">{{ __('No hay imágenes disponibles para este estudio.') }}</p>
    @endif


    {{-- --- HERRAMIENTAS APLICADAS (TABLA) --- --}}
    <div class="mt-12">
        <h1 class="title2">{{ __('Herramientas aplicadas') }}</h1>
        <div class="flex justify-end"><a href="javascript:void(0);" class="botton3" id="updateButton">{{ __('Actualizar') }}</a></div>
        
        {{-- Tools Table --}}
        {{-- ASUMO que la relación 'tools' existe en tu modelo MultimediaFile --}}
        <div class="max-w-6xl mx-auto bg-white dark:bg-gray-700 rounded-xl p-3 text-gray-900 shadow-md mt-4">
            
            {{-- Table Header --}}
            <div class="grid grid-cols-4 gap-4 border-b border-gray-300 pb-2 mb-3">
                <h3 class="title4 text-center text-black dark:text-white">{{ __('Vista Previa') }}</h3>
                <h3 class="title4 text-center text-black dark:text-white">{{ __('Fecha de creación') }}</h3>
                <h3 class="title4 text-center text-black dark:text-white">{{ __('ID') }}</h3>
                <h3 class="title4 text-center text-black dark:text-white">{{ __('Acciones') }}</h3>
            </div>
            
            {{-- Table Body --}}
            @forelse($study->tools as $tool)
            <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                {{-- Preview --}}
                <div class="flex justify-center">
                    {{-- Usamos la misma lógica de ruta protegida para la herramienta guardada --}}
                    <a href="{{ route('tool.show', $tool->id) }}">
                        <img src="{{ asset('storage/tools/'.$tool->tool_uri) }}" 
                             alt="Tool preview" 
                             class="rounded-lg shadow-md w-32 h-auto object-cover"/>
                    </a>
                </div>

                {{-- Creation Date --}}
                <div class="text-center">
                    <a href="{{ route('tool.show', $tool->id) }}" class="txt hover:text-cyan-600">
                        {{ $tool->tool_date }}
                    </a>
                </div>

                {{-- Study ID --}}
                <div class="text-center">
                    <a href="{{ route('tool.show', $tool->id) }}" class="txt hover:text-cyan-600">
                        {{ $tool->id }} {{-- Usamos el ID de la herramienta o el que corresponda --}}
                    </a>
                </div>

                {{-- Actions --}}
                <div class="flex justify-center">
                    <form method="POST" action="{{ route('tool.destroy', $tool->id) }}"
                          onsubmit="return confirm('{{ __('¿Seguro que deseas eliminar esta herramienta?') }}');">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete"/>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-600 text-center py-4 dark:text-gray-400">{{ __('Aún no se han aplicado herramientas.') }}</p>
            @endforelse
        </div>
    </div>
    
    {{-- Botón eliminar estudio --}}
    <div class="flex justify-end mt-6">
        <form method="POST" action="{{ route('multimedia.destroy', $study->id) }}"
              onsubmit="return confirm('{{ __('¿Seguro que deseas eliminar este estudio?') }}');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bottonDelete">{{ __('Eliminar Estudio') }}</button>
        </form>
    </div>

</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // --- VARIABLES DE ESTADO ---
    const img = document.getElementById('currentImage');
    const container = document.getElementById('imageViewerContainer');
    const imageCounter = document.getElementById('imageCounter');
    // const magnifierLens = document.getElementById('magnifierLens');
    
    let currentImageIndex = 0;
    let zoom = 1;
    let brightness = 0;  
    let contrast = 1;   
    let isNegative = false;
    let edgesApplied = false;
    let isDragging = false;
    let startX = 0, startY = 0;
    let currentX = 0, currentY = 0;
    // let isMagnifierOn = false; // Estado de la lupa
    
    if (IMAGE_URLS.length === 0) return; // Salir si no hay imágenes

    // --- FUNCIONES CORE ---
    
    // 1. Actualiza el estado visual de la imagen (filtros y zoom)
    function updateVisualState() {
        let filters = [];
        filters.push(`brightness(${1 + brightness})`);
        filters.push(`contrast(${contrast})`);
        if (isNegative) filters.push('invert(1)');
        
        img.style.filter = filters.join(' ');
        
        // Aplica el zoom y el pan (arrastre)
        img.style.transform = `translate(-50%, -50%) translate(${currentX}px, ${currentY}px) scale(${zoom})`;
    }
    
    // 2. Resetea los filtros y el zoom al cambiar de imagen
    function resetImageState() {
        zoom = 1;
        brightness = 0;
        contrast = 1;
        isNegative = false;
        edgesApplied = false;
        currentX = 0;
        currentY = 0;
        img.style.left = '50%';
        img.style.top = '50%';
        updateVisualState();
        // if (isMagnifierOn) toggleMagnifier(); // Desactiva la lupa
    }
    
    // 3. Cambia la imagen y resetea el estado
    function changeImage(newIndex) {
        if (newIndex >= 0 && newIndex < IMAGE_URLS.length) {
            currentImageIndex = newIndex;
            img.src = IMAGE_URLS[currentImageIndex];
            imageCounter.textContent = `${currentImageIndex + 1} / ${IMAGE_URLS.length}`;
            resetImageState();
            // Asegúrate de que crossOrigin se establece para evitar problemas de CORS/Canvas
            img.setAttribute('crossorigin', 'anonymous');
        }
    }
    
    // 4. Aplica el filtro de detección de bordes (Sobel)
    function applyEdgesFilter() {
        if (!img.complete || img.naturalWidth === 0) {
            console.error('La imagen no se ha cargado completamente.');
            return;
        }

        if (edgesApplied) {
            // Quitar bordes, restaurar original
            changeImage(currentImageIndex); // Vuelve a cargar la imagen actual
            edgesApplied = false;
            return;
        }
        
        // Crear canvas temporal
        let canvas = document.createElement('canvas');
        let ctx = canvas.getContext('2d');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        
        // Aplicar filtros actuales a la imagen antes del Sobel
        ctx.filter = img.style.filter; 
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        
        // Obtener datos
        let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let data = imageData.data;
        
        // Lógica de Sobel (Sobel Kernel)
        const width = canvas.width;
        const height = canvas.height;
        let output = new Uint8ClampedArray(data.length);

        const kernelX = [-1, 0, 1, -2, 0, 2, -1, 0, 1];
        const kernelY = [-1, -2, -1, 0, 0, 0, 1, 2, 1];

        // Función para obtener el valor de un píxel, gestionando los bordes
        function getPixel(x, y, c) {
            x = Math.max(0, Math.min(width - 1, x));
            y = Math.max(0, Math.min(height - 1, y));
            // Solo necesitamos un canal (rojo) para el cálculo de gris
            return data[(y * width + x) * 4 + 0]; 
        }

        for (let y = 0; y < height; y++) {
            for (let x = 0; x < width; x++) {
                let gx = 0, gy = 0;
                for (let ky = -1; ky <= 1; ky++) {
                    for (let kx = -1; kx <= 1; kx++) {
                        let val = getPixel(x + kx, y + ky, 0); 
                        let idx = (ky + 1) * 3 + (kx + 1);
                        gx += kernelX[idx] * val;
                        gy += kernelY[idx] * val;
                    }
                }
                let mag = Math.sqrt(gx * gx + gy * gy);
                mag = Math.min(255, Math.max(0, mag)); // Limitar el valor

                let i = (y * width + x) * 4;
                output[i] = output[i+1] = output[i+2] = mag; // Escala de grises
                output[i+3] = 255; // Alpha
            }
        }
        
        // Copiar el resultado al imageData original
        for (let i = 0; i < data.length; i++) {
             data[i] = output[i];
        }
        
        ctx.putImageData(imageData, 0, 0);
        
        // Cargar el resultado al elemento <img>
        img.src = canvas.toDataURL('image/png');
        edgesApplied = true;
        // NOTA: Mantenemos los filtros CSS (brillo, contraste) si se aplicaron antes.
    }

    // --- EVENT LISTENERS DEL CARRUSEL ---
    document.getElementById('prevImage').addEventListener('click', () => {
        changeImage(currentImageIndex - 1);
    });
    document.getElementById('nextImage').addEventListener('click', () => {
        changeImage(currentImageIndex + 1);
    });

    // --- EVENT LISTENERS DE HERRAMIENTAS ---
    
    // Zoom y Pan
    img.style.cursor = 'grab';

    document.getElementById('zoomIn').addEventListener('click', () => {
        zoom = Math.min(zoom + 0.2, 5); // Zoom máximo 5x
        updateVisualState();
    });
    document.getElementById('zoomOut').addEventListener('click', () => {
        zoom = Math.max(zoom - 0.2, 0.5); // Zoom mínimo 0.5x
        updateVisualState();
    });

    // Filtros de Imagen (Brillo, Contraste, Invertir)
    document.getElementById('increaseBrightness').addEventListener('click', () => {
        brightness = Math.min(brightness + 0.1, 1);
        updateVisualState();
    });
    document.getElementById('decreaseBrightness').addEventListener('click', () => {
        brightness = Math.max(brightness - 0.1, -1);
        updateVisualState();
    });
    document.getElementById('increaseContrast').addEventListener('click', () => {
        contrast = Math.min(contrast + 0.2, 3);
        updateVisualState();
    });
    document.getElementById('decreaseContrast').addEventListener('click', () => {
        contrast = Math.max(contrast - 0.2, 0.5);
        updateVisualState();
    });
    document.getElementById('invertColors').addEventListener('click', () => {
        isNegative = !isNegative;
        updateVisualState();
    });
    
    // Detección de Bordes (Sobel)
    document.getElementById('edgesButton').addEventListener('click', applyEdgesFilter);
    
    // Descargar Imagen
    document.getElementById('downloadImage').addEventListener('click', () => {
        const link = document.createElement('a');
        // El nombre de archivo incluye el código del estudio y el índice de la imagen
        link.download = `Estudio_${STUDY_DATA.study_code}_Img${currentImageIndex + 1}_Editada.png`;
        
        // Para descargar con filtros aplicados, necesitamos un canvas.
        if (edgesApplied) {
            // Si Sobel está aplicado, la fuente (img.src) ya es un DataURL con los bordes
            link.href = img.src;
        } else {
            // Si Sobel NO está aplicado, dibujamos en el canvas para aplicar filtros CSS
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            
            // Replicar filtros CSS en el contexto del canvas
            ctx.filter = img.style.filter; 
            
            // Dibujar la imagen
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            link.href = canvas.toDataURL('image/png');
        }
        
        link.click();
    });

    // Guardar Imagen (Usando Fetch API)
    document.getElementById('save').addEventListener('click', (event) => {
        event.preventDefault();
        
        // Crear canvas para obtener la imagen con todos los filtros/bordes aplicados
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        
        // Si Sobel fue aplicado, la imagen ya tiene la transformación en su src
        if (edgesApplied) {
            // Dibujamos la imagen (que es un DataURL) al canvas y luego aplicamos los filtros CSS adicionales
            const tempImg = new Image();
            tempImg.crossOrigin = "anonymous";
            tempImg.onload = () => {
                ctx.filter = img.style.filter; 
                ctx.drawImage(tempImg, 0, 0, canvas.width, canvas.height);
                
                // Generar y enviar DataURL
                const dataURL = canvas.toDataURL('image/png');
                sendImageToServer(dataURL);
            };
            tempImg.src = img.src;
        } else {
            // Si no hay Sobel, solo aplicamos filtros CSS sobre la imagen original
            ctx.filter = img.style.filter;
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/png');
            sendImageToServer(dataURL);
        }
        
    });
    
    // Función de envío de imagen al servidor
    function sendImageToServer(dataURL) {
        // Enviar la imagen base64 al controlador
        fetch(SAVE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({ image: dataURL, original_study_id: STUDY_DATA.id })
        })
        .then(response => {
             // Si la respuesta no es OK, arrojar un error para que caiga en el catch
            if (!response.ok) {
                return response.json().then(error => { throw new Error(error.message || 'Error al guardar la imagen en el servidor.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Usamos un modal o un mensaje personalizado en lugar de alert()
                alert("Herramienta aplicada y guardada exitosamente. ¡Actualiza para verla!"); 
                document.getElementById('updateButton').click(); // Opcional: recargar la tabla
            } else {
                alert("Error al guardar la imagen: " + (data.message || "Respuesta incompleta del servidor."));
            }
        })
        .catch(error => {
            console.error("Error al guardar la imagen:", error);
            alert("Ocurrió un error inesperado al intentar guardar: " + error.message);
        });
    }

    // --- EVENT LISTENERS DE ARRASTRE (PAN) ---
    container.addEventListener('mousedown', (e) => {
        // Solo permitir arrastrar si hay zoom
        if (zoom > 1) { 
            e.preventDefault();
            isDragging = true;
            img.style.cursor = 'grabbing';
            // Calcular la posición inicial del arrastre relativa a la posición actual de la imagen
            startX = e.clientX - currentX;
            startY = e.clientY - currentY;
        }
    });
    
    container.addEventListener('mouseup', () => {
        isDragging = false;
        img.style.cursor = 'grab';
    });
    container.addEventListener('mouseleave', () => {
        isDragging = false;
        img.style.cursor = 'grab';
    });
    
    container.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        
        // Calcular nuevo desplazamiento
        currentX = e.clientX - startX;
        currentY = e.clientY - startY;

        // Limitar el arrastre para que la imagen no se salga completamente del contenedor
        // Aunque la lógica de CSS transform: translate() lo manejaría, 
        // updateVisualState() aplica el transform completo.
        updateVisualState();
    });

    // --- INICIALIZACIÓN ---
    changeImage(0); // Cargar la primera imagen al iniciar
    document.getElementById('updateButton').addEventListener('click', function () {
        location.reload(); // Recarga la página para refrescar la tabla de herramientas
    });
});
</script>
@endsection