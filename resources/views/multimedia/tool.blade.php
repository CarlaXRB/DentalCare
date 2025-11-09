@extends('layouts._partials.layout')
@section('title', 'Herramientas')
@section('subtitle')
{{ __('Herramientas') }}
@endsection

@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('multimedia.show', $study->id) }}" class="botton1">Volver al Estudio</a>
</div>
<h1 class="text-2xl font-bold text-gray-800 mb-6 text-center"> {{ $study->name_patient }} - {{ $study->ci_patient }}</h1>
<div class="flex justify-center mb-4">
    <select id="imageSelect" class="border border-gray-300 rounded-lg p-2">
        @foreach($imageUrls as $url)
            <option value="{{ $url }}">Imagen {{ $loop->iteration }}</option>
        @endforeach
    </select>
</div>
<div class="relative flex justify-center space-x-2">
    <div class="group relative">
        <button id="zoomIn" class="btnimg"><img src="{{ asset('assets/images/zoom.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-800">Acercar</span></div>
    </div>
    <div class="group relative">
        <button id="zoomOut" class="btnimg"><img src="{{ asset('assets/images/unzoom.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-800">Alejar</span></div>
    </div>
    <div class="group relative">
        <button id="invertColors" class="btnimg"><img src="{{ asset('assets/images/negative.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-800">Negativo</span></div>
    </div>
    <div class="group relative">
        <button id="increaseBrightness" class="btnimg"><img src="{{ asset('assets/images/filter3.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Más_Brillo</span></div>
    </div>
    <div class="group relative">
        <button id="decreaseBrightness" class="btnimg"><img src="{{ asset('assets/images/filter4.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Menos_Brillo</span></div>
    </div>
    <div class="group relative">
        <button id="increaseContrast" class="btnimg"><img src="{{ asset('assets/images/filter1.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Más_Contraste</span></div>
    </div>
    <div class="group relative">
        <button id="decreaseContrast" class="btnimg"><img src="{{ asset('assets/images/filter2.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Menos_Contraste</span></div>
    </div>
    <div class="group relative">
        <button id="edgesButton" class="btnimg"><img src="{{ asset('assets/images/edge.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Marcar_Bordes</span></div>
    </div>
    <div class="group relative">
        <button id="downloadImage" class="btnimg"><img src="{{ asset('assets/images/download.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Descargar</span></div>
    </div>
</div>

<div class="flex justify-center mt-4 mb-2">
    <canvas id="measureCanvas" class="border rounded-lg"></canvas>
</div>

<p id="scaleMessage" class="text-center text-sm text-red-600 mb-2"></p>

<p id="measureOutput" class="font-semibold text-gray-700 text-center mb-4">
    Selecciona una herramienta para comenzar.
</p>

<div class="flex justify-center mb-4">
    <button id="resetBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
        Reiniciar
    </button>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const img = document.getElementById('radiographyImage');
    const container = img.parentElement;
    const magnifierLens = document.getElementById('magnifierLens');

    let zoom = 1;
    let brightness = 0;  
    let contrast = 1;   
    let isNegative = false;
    let edgesApplied = false;

    function updateFilters() {
        let filters = [];
        filters.push(`brightness(${1 + brightness})`);
        filters.push(`contrast(${contrast})`);
        if (isNegative) filters.push('invert(1)');
        img.style.filter = filters.join(' ');
    }

    updateFilters();
    img.style.transform = 'translate(-50%, -50%) scale(1)';

    document.getElementById('zoomIn').addEventListener('click', () => {
        zoom += 0.1;
        img.style.transform = `translate(-50%, -50%) scale(${zoom})`;
    });
    document.getElementById('zoomOut').addEventListener('click', () => {
        if (zoom > 0.5) {
            zoom -= 0.1;
            img.style.transform = `translate(-50%, -50%) scale(${zoom})`;
        }
    });
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
    document.getElementById('invertColors').addEventListener('click', () => {
        isNegative = !isNegative;
        updateFilters();
    });

    // 🔹 Detección de bordes
    document.getElementById('edgesButton').addEventListener('click', () => {
        if (edgesApplied) {
            // Quitar bordes, restaurar original
            img.src = "{{ asset('storage/radiographies/' . $radiography->radiography_uri) }}";
            edgesApplied = false;
            updateFilters();
            return;
        }
        let canvas = document.createElement('canvas');
        let ctx = canvas.getContext('2d');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let data = imageData.data;
        const width = canvas.width;
        const height = canvas.height;
        let output = new Uint8ClampedArray(data.length);
        const kernelX = [-1, 0, 1, -2, 0, 2, -1, 0, 1];
        const kernelY = [-1, -2, -1, 0, 0, 0, 1, 2, 1];

        function getPixel(x, y, c) {
            x = Math.max(0, Math.min(x, width - 1));
            y = Math.max(0, Math.min(y, height - 1));
            return data[(y * width + x) * 4 + c];
        }

        for (let y = 0; y < height; y++) {
            for (let x = 0; x < width; x++) {
                let gx = 0, gy = 0;
                for (let ky = -1; ky <= 1; ky++) {
                    for (let kx = -1; kx <= 1; kx++) {
                        let px = x + kx;
                        let py = y + ky;
                        let val = getPixel(px, py, 0);
                        let idx = (ky + 1) * 3 + (kx + 1);
                        gx += kernelX[idx] * val;
                        gy += kernelY[idx] * val;
                    }
                }
                let mag = Math.sqrt(gx * gx + gy * gy);
                mag = Math.min(255, mag);
                let i = (y * width + x) * 4;
                output[i] = output[i + 1] = output[i + 2] = mag;
                output[i + 3] = 255;
            }
        }

        for (let i = 0; i < data.length; i++) {
            data[i] = output[i];
        }

        ctx.putImageData(imageData, 0, 0);
        img.src = canvas.toDataURL();
        edgesApplied = true;
        updateFilters();
    });

    // 🔹 Descargar imagen
    document.getElementById('downloadImage').addEventListener('click', () => {
        let canvas = document.createElement('canvas');
        let ctx = canvas.getContext('2d');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        ctx.filter = img.style.filter;
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        let link = document.createElement('a');
        link.download = 'radiography.png';
        link.href = canvas.toDataURL();
        link.click();
    });

    // 🔹 Guardar edición
    document.getElementById('save').onclick = function(event) {
        event.preventDefault();
        const canvas = document.createElement('canvas');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        const ctx = canvas.getContext('2d');
        ctx.filter = img.style.filter;
        ctx.drawImage(img, 0, 0);
        const dataURL = canvas.toDataURL('image/png');

        fetch("{{ route('tool.store', ['radiography_id' => $radiography->radiography_id, 'tomography_id' => 0, 'ci_patient' => $radiography->ci_patient, 'id' => $radiography->id]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ image: dataURL })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Imagen guardada exitosamente");
            } else {
                alert("Error al guardar la imagen");
            }
        })
        .catch(error => {
            console.error("Error al guardar la imagen:", error);
        });
    };

    // 🔹 Arrastre de imagen
    let isDragging = false;
    let startX, startY;
    let currentX = 0, currentY = 0;
    img.style.cursor = 'grab';

    container.addEventListener('mousedown', (e) => {
        e.preventDefault();
        isDragging = true;
        startX = e.clientX - currentX;
        startY = e.clientY - currentY;
        img.style.cursor = 'grabbing';
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
        currentX = e.clientX - startX;
        currentY = e.clientY - startY;
        img.style.left = `calc(50% + ${currentX}px)`;
        img.style.top = `calc(50% + ${currentY}px)`;
    });
});

// 🔹 Actualizar imagen
document.getElementById('updateButton').addEventListener('click', function () {
    location.reload();
});
</script>

@endsection
