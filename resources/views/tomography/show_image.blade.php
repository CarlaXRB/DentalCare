@extends('layouts._partials.layout')
@section('title', 'Show Tomography')
@section('subtitle')
    {{ __('Herramientas') }}
@endsection
@section('content')
<div class="flex justify-end">
    <a href="{{ route('tomography.show', $tomography->id)}}" class="botton1">Atrás</a>
</div>
<h1 class="txt-title2">Herramientas</h1>
<div class="relative flex justify-center space-x-2">
    <div class="group relative">
        <button id="zoomIn" class="btnimg"><img src="{{ asset('assets/images/zoom.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-100">Acercar</span></div>
    </div>
    <div class="group relative">
        <button id="zoomOut" class="btnimg"><img src="{{ asset('assets/images/unzoom.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-100">Alejar</span></div>
    </div>
    <div class="group relative">
        <button id="magnifier" class="btnimg"><img src="{{ asset('assets/images/lupa.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-100">Ampliar</span></div>
    </div>
    <div class="group relative">
        <button id="invertColors" class="btnimg"><img src="{{ asset('assets/images/negative.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-100">Negativo</span></div>
    </div>
        <div class="group relative">
        <button id="increaseBrightness" class="btnimg"><img src="{{ asset('assets/images/filter3.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-100">Más_Brillo</span></div>
    </div>
    <div class="group relative">
        <button id="decreaseBrightness" class="btnimg"><img src="{{ asset('assets/images/filter4.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-100">Menos_Brillo</span></div>
    </div>
    <div class="group relative">
        <button id="increaseSharpness" class="btnimg"><img src="{{ asset('assets/images/filter1.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-100">Más_Contraste</span></div>
    </div>
    <div class="group relative">
        <button id="decreaseSharpness" class="btnimg"><img src="{{ asset('assets/images/filter2.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-100">Menos_Contraste</span></div>
    </div>
    <div class="group relative">
        <button id="overlayButton" class="btnimg"><img src="{{ asset('assets/images/sup.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-100">Superposición</span></div>
    </div>
    <div class="group relative">
        <button id="edgesButton" class="btnimg"><img src="{{ asset('assets/images/edge.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-100">Bordes</span></div>
    </div>
    <div class="group relative">
        <button id="downloadImage" class="btnimg"><img src="{{ asset('assets/images/download.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-100">Decargar</span></div>
    </div>
    <div class="group relative">
        <button id="draw" class="btnimg" onclick="window.location.href='{{ route('tomography.measurements', $tomography->id) }}'"><img src="{{ asset('assets/images/draw.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-gray-500 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-100">Mediciones</span></div>
    </div>
</div>

<div class="relative flex justify-center mt-[50px] mb-[30px]">
    <div class="overflow-auto" style="width: 1100px; height: 700px; position: relative;">
        <img id="tomographyImage" src="{{ asset('storage/tomographies/converted_images/'.$tomographyId.'/'.$image) }}"
             style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.2s; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
        <div id="magnifierLens" style="display: none; position: absolute; border: 1px solid #000; border-radius: 50%; pointer-events: none;"></div>
    </div>
</div>
<canvas id="imageCanvas" style="display: none;"></canvas>

<script>
    let zoomLevel = 1;
    let brightness = 0;
    let initialPosition = { left: '50%', top: '50%' };
    let isDragging = false;
    let startX, startY, initialMouseX, initialMouseY;
    let isNegative = false;
    let sharpnessLevel = 1;
    let isMagnifierActive = false;
    let isEdgeDetectionActive = false;

    const img = document.getElementById('tomographyImage');
    const magnifierLens = document.getElementById('magnifierLens');
    const zoomInButton = document.getElementById('zoomIn');
    const zoomOutButton = document.getElementById('zoomOut');
    const invertColorsButton = document.getElementById('invertColors');
    const increaseSharpnessButton = document.getElementById('increaseSharpness');
    const decreaseSharpnessButton = document.getElementById('decreaseSharpness');
    const magnifierButton = document.getElementById('magnifier');
    const edgesButton = document.getElementById('edgesButton');
    const canvas = document.getElementById('imageCanvas');
    const ctx = canvas.getContext('2d');

    img.style.left = initialPosition.left;
    img.style.top = initialPosition.top;

    img.onload = function() {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);
    };

    // Máscaras de Sobel
    const sobelX = [
        [-1, 0, 1],
        [-2, 0, 2],
        [-1, 0, 1]
    ];

    const sobelY = [
        [-1, -2, -1],
        [0, 0, 0],
        [1, 2, 1]
    ];

    // Arrastre
    img.addEventListener('mousedown', (event) => {
        if (zoomLevel > 1) {
            isDragging = true;
            startX = img.offsetLeft;
            startY = img.offsetTop;
            initialMouseX = event.clientX;
            initialMouseY = event.clientY;
            event.preventDefault();
        }
    });

    document.addEventListener('mousemove', (event) => {
        if (isDragging) {
            const dx = event.clientX - initialMouseX;
            const dy = event.clientY - initialMouseY;
            img.style.left = `${startX + dx}px`;
            img.style.top = `${startY + dy}px`;
        }

        if (isMagnifierActive) {
            const rect = img.getBoundingClientRect();
            const lensSize = 100; 
            const offset = 20; 
            const x = event.clientX - rect.left - lensSize / 2 + offset; 
            const y = event.clientY - rect.top - lensSize / 2 + offset;

            magnifierLens.style.width = `${lensSize}px`;
            magnifierLens.style.height = `${lensSize}px`;
            magnifierLens.style.left = `${x}px`;
            magnifierLens.style.top = `${y}px`;
            magnifierLens.style.display = 'block';

            // lupa
            magnifierLens.style.backgroundImage = `url(${img.src})`;
            magnifierLens.style.backgroundSize = `${img.width * 2}px ${img.height * 2}px`; 
            magnifierLens.style.backgroundPosition = `-${(x - offset) * 2}px -${(y - offset) * 2}px`; 
        }
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        magnifierLens.style.display = 'none';
    });

    zoomInButton.addEventListener('click', () => {
        zoomLevel += 0.1; 
        img.style.transform = `translate(-50%, -50%) scale(${zoomLevel})`; 
    });

    zoomOutButton.addEventListener('click', () => {
        if (zoomLevel > 1) { 
            zoomLevel -= 0.1; 
            img.style.transform = `translate(-50%, -50%) scale(${zoomLevel})`; 
        }

        if (zoomLevel <= 1) {
            zoomLevel = 1; 
            img.style.transform = `translate(-50%, -50%) scale(${zoomLevel})`; 
            img.style.left = initialPosition.left; 
            img.style.top = initialPosition.top;
        }
    });

    // Negativo
    invertColorsButton.addEventListener('click', () => {
        isNegative = !isNegative; 
        if (isNegative) {
            img.style.filter = isNegative ? 'invert(1)' : 'none';
        } else {
            img.style.filter = img.style.filter.replace(' invert(1)', ''); 
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
    // Aumentar contraste
    increaseSharpnessButton.addEventListener('click', () => {
        sharpnessLevel += 0.1; 
        img.style.filter = `contrast(${sharpnessLevel})`; 
    });

    // Disminuir contraste
    decreaseSharpnessButton.addEventListener('click', () => {
        if (sharpnessLevel > 1) {
            sharpnessLevel -= 0.1; 
            img.style.filter = `contrast(${sharpnessLevel})`; 
        }
    });

    // Lupa
    magnifierButton.addEventListener('click', () => {
        isMagnifierActive = !isMagnifierActive; 
        magnifierLens.style.display = isMagnifierActive ? 'block' : 'none';
    });

    // Borde
    edgesButton.addEventListener('click', () => {
    isEdgeDetectionActive = !isEdgeDetectionActive;

        if (isEdgeDetectionActive) {
            img.style.display = 'none';
            canvas.style.display = 'block';
            applyEdgeDetection();
        } else {
            canvas.style.display = 'none';
            img.style.display = 'block';
        }
    });

    function applyEdgeDetection() {
        const imgData = ctx.getImageData(0, 0, img.width, img.height);
        const data = imgData.data;
        const output = ctx.createImageData(img.width, img.height);
        const outData = output.data;

        for (let y = 1; y < img.height - 1; y++) {
        for (let x = 1; x < img.width - 1; x++) {
            const sobelXValue = applySobelKernel(x, y, sobelX, data, img.width);
            const sobelYValue = applySobelKernel(x, y, sobelY, data, img.width);

            const magnitude = Math.sqrt(sobelXValue ** 2 + sobelYValue ** 2);
            const edgeColor = magnitude > 255 ? 255 : magnitude;
            const index = (y * img.width + x) * 4;

            outData[index] = outData[index + 1] = outData[index + 2] = edgeColor;
            outData[index + 3] = 255;
        }
    }

        ctx.putImageData(output, 0, 0);
    }

    function applySobelKernel(x, y, kernel, data, width) {
    let sum = 0;

    for (let ky = -1; ky <= 1; ky++) {
        for (let kx = -1; kx <= 1; kx++) {
            const px = x + kx;
            const py = y + ky;
            const index = (py * width + px) * 4;

            const r = data[index];
            const g = data[index + 1];
            const b = data[index + 2];
            const gray = (r + g + b) / 3;

            sum += kernel[ky + 1][kx + 1] * gray;
        }
    }

    return sum;
    }
</script>
@endsection
