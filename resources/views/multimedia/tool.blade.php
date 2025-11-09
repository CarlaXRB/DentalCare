@extends('layouts._partials.layout')
@section('title', 'Herramientas')
@section('subtitle', __('Herramientas'))
@endsection

@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('multimedia.show', $study->id) }}" class="botton1">Volver al Estudio</a>
</div>

<h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
    {{ $study->name_patient }} - {{ $study->ci_patient }}
</h1>

<div class="flex justify-center mb-4">
    <select id="imageSelect" class="border border-gray-300 rounded-lg p-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
        @foreach($imageUrls as $url)
            <option value="{{ $url }}">Imagen {{ $loop->iteration }}</option>
        @endforeach
    </select>
</div>

{{-- Contenedor de herramientas --}}
<div class="flex flex-wrap justify-center gap-3 bg-gray-100 rounded-xl p-4 shadow-md max-w-3xl mx-auto mb-6">
    @php
        $tools = [
            ['id'=>'zoomIn','img'=>'zoom.png','title'=>'Acercar'],
            ['id'=>'zoomOut','img'=>'unzoom.png','title'=>'Alejar'],
            ['id'=>'invertColors','img'=>'negative.png','title'=>'Negativo'],
            ['id'=>'increaseBrightness','img'=>'filter3.png','title'=>'Más Brillo'],
            ['id'=>'decreaseBrightness','img'=>'filter4.png','title'=>'Menos Brillo'],
            ['id'=>'increaseContrast','img'=>'filter1.png','title'=>'Más Contraste'],
            ['id'=>'decreaseContrast','img'=>'filter2.png','title'=>'Menos Contraste'],
            ['id'=>'edgesButton','img'=>'edge.png','title'=>'Marcar Bordes'],
            ['id'=>'downloadImage','img'=>'download.png','title'=>'Descargar']
        ];
    @endphp

    @foreach($tools as $tool)
        <div class="group relative">
            <button id="{{ $tool['id'] }}" class="transition-transform transform hover:scale-110 focus:outline-none">
                <img src="{{ asset('assets/images/'.$tool['img']) }}" width="50" height="50" class="rounded-md">
            </button>
            <div class="hidden group-hover:block absolute left-1/2 -translate-x-1/2 mt-2 bg-blue-200 text-gray-800 text-xs px-2 py-1 rounded shadow-md">
                {{ $tool['title'] }}
            </div>
        </div>
    @endforeach
</div>

{{-- Imagen principal centrada --}}
<div class="flex justify-center items-center">
    <img id="multimediaImage"
         class="max-w-full max-h-[80vh] border rounded-lg shadow-lg transition-all duration-200"
         src="{{ $imageUrls[0] ?? '' }}"
         alt="Imagen multimedia">
</div>

<div class="flex justify-center mt-6">
    <button id="resetBtn"
            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-5 py-2 rounded-lg shadow-md transition-all duration-150">
        Reiniciar Filtros
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const img = document.getElementById('multimediaImage');
    const imageSelect = document.getElementById('imageSelect');

    let zoom = 1;
    let brightness = 0;
    let contrast = 1;
    let isNegative = false;
    let edgesApplied = false;

    // 🔹 Actualizar filtros
    function updateFilters() {
        const filters = [`brightness(${1 + brightness})`, `contrast(${contrast})`];
        if (isNegative) filters.push('invert(1)');
        img.style.filter = filters.join(' ');
    }

    // 🔹 Zoom
    document.getElementById('zoomIn').addEventListener('click', () => {
        zoom += 0.1;
        img.style.transform = `scale(${zoom})`;
    });

    document.getElementById('zoomOut').addEventListener('click', () => {
        if (zoom > 0.5) zoom -= 0.1;
        img.style.transform = `scale(${zoom})`;
    });

    // 🔹 Brillo / Contraste
    document.getElementById('increaseBrightness').addEventListener('click', () => {
        brightness = Math.min(brightness + 0.1, 1);
        updateFilters();
    });

    document.getElementById('decreaseBrightness').addEventListener('click', () => {
        brightness = Math.max(brightness - 0.1, -1);
        updateFilters();
    });

    document.getElementById('increaseContrast').addEventListener('click', () => {
        contrast = Math.min(contrast + 0.1, 3);
        updateFilters();
    });

    document.getElementById('decreaseContrast').addEventListener('click', () => {
        contrast = Math.max(contrast - 0.1, 0);
        updateFilters();
    });

    // 🔹 Negativo
    document.getElementById('invertColors').addEventListener('click', () => {
        isNegative = !isNegative;
        updateFilters();
    });

    // 🔹 Detección de bordes (filtro Sobel)
    document.getElementById('edgesButton').addEventListener('click', () => {
        if (edgesApplied) {
            img.src = imageSelect.value;
            edgesApplied = false;
            updateFilters();
            return;
        }

        const tempCanvas = document.createElement('canvas');
        const ctx = tempCanvas.getContext('2d');
        tempCanvas.width = img.naturalWidth;
        tempCanvas.height = img.naturalHeight;

        const imageObj = new Image();
        imageObj.crossOrigin = 'anonymous';
        imageObj.src = img.src;

        imageObj.onload = () => {
            ctx.drawImage(imageObj, 0, 0);
            const imageData = ctx.getImageData(0, 0, tempCanvas.width, tempCanvas.height);
            const data = imageData.data;
            const width = tempCanvas.width;
            const height = tempCanvas.height;

            const sobel = new Uint8ClampedArray(width * height);
            const kernelX = [-1,0,1,-2,0,2,-1,0,1];
            const kernelY = [-1,-2,-1,0,0,0,1,2,1];

            for (let y = 1; y < height - 1; y++) {
                for (let x = 1; x < width - 1; x++) {
                    let gx = 0, gy = 0;
                    for (let ky = -1; ky <= 1; ky++) {
                        for (let kx = -1; kx <= 1; kx++) {
                            const pos = ((y + ky) * width + (x + kx)) * 4;
                            const gray = (data[pos] + data[pos + 1] + data[pos + 2]) / 3;
                            const idx = (ky + 1) * 3 + (kx + 1);
                            gx += gray * kernelX[idx];
                            gy += gray * kernelY[idx];
                        }
                    }
                    const magnitude = Math.sqrt(gx * gx + gy * gy);
                    sobel[y * width + x] = magnitude > 100 ? 255 : 0;
                }
            }

            for (let i = 0; i < width * height; i++) {
                const j = i * 4;
                const val = sobel[i];
                data[j] = data[j + 1] = data[j + 2] = val;
            }

            ctx.putImageData(imageData, 0, 0);
            img.src = tempCanvas.toDataURL();
            edgesApplied = true;
        };
    });

    // 🔹 Descargar imagen
    document.getElementById('downloadImage').addEventListener('click', () => {
        const a = document.createElement('a');
        a.href = img.src;
        a.download = 'imagen_multimedia.png';
        a.click();
    });

    // 🔹 Cambio de imagen
    imageSelect.addEventListener('change', (e) => {
        img.src = e.target.value;
        reset();
    });

    // 🔹 Reiniciar
    function reset() {
        zoom = 1;
        brightness = 0;
        contrast = 1;
        isNegative = false;
        edgesApplied = false;
        img.style.transform = `scale(1)`;
        updateFilters();
    }

    document.getElementById('resetBtn').addEventListener('click', reset);
});
</script>
@endsection
