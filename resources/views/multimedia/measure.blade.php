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
<!--
<div class="relative flex justify-center space-x-2 mb-6">
    @php
        $tools = [
            ['id'=>'distance','img'=>'distance.png','title'=>'Medir Distancia'],
            ['id'=>'delimited','img'=>'distances.png','title'=>'Marcar Contorno'],
            ['id'=>'angle','img'=>'angle.png','title'=>'Medir Ángulo'],
            ['id'=>'arco','img'=>'arco.png','title'=>'Medir Arco'],
            ['id'=>'paint','img'=>'paint.png','title'=>'Pintar'],
            ['id'=>'downloadImage','img'=>'download.png','title'=>'Descargar']
        ];
    @endphp
    @foreach($tools as $tool)
    <div class="group relative">
        <button id="{{ $tool['id'] }}" class="btnimg">
            <img src="{{ asset('assets/images/'.$tool['img']) }}" width="50" height="50">
        </button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
            <span class="text-xs text-gray-800">{{ $tool['title'] }}</span>
        </div>
    </div>
    @endforeach
</div>
    -->
<div class="relative flex justify-center space-x-2">
    <div class="group relative">
        <button id="distance" class="btnimg"><img src="{{ asset('assets/images/distance.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Medir_Distancia</span></div>
    </div>
    <div class="group relative">
        <button id="delimited" class="btnimg"><img src="{{ asset('assets/images/distances.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Marcar_Contorno</span></div>
    </div>
    <div class="group relative">
        <button id="angle" class="btnimg"><img src="{{ asset('assets/images/angle.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Medir_Ángulo</span></div>
    </div>
    <div class="group relative">
        <button id="arco" class="btnimg"><img src="{{ asset('assets/images/arco.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Medir_Arco</span></div>
    </div>
    <div class="group relative">
        <button id="paint" class="btnimg"><img src="{{ asset('assets/images/paint.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Pintar</span></div>
    </div>
    <div class="group relative">
        <button id="downloadImage" class="btnimg"><img src="{{ asset('assets/images/download.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Decargar</span></div>
    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
<script>
const canvas = new fabric.Canvas('measureCanvas', {
    preserveObjectStacking: true,
    selection: false
});

const imageSelect = document.getElementById('imageSelect');
const scaleMessage = document.getElementById('scaleMessage');
const resetBtn = document.getElementById('resetBtn');
const measureBtn = document.getElementById('distance');
let imgUrl = imageSelect.value;
let currentImage, scaleFactor = 1;
let zoom = 1;

// Estado de herramienta activa
let activeTool = null;

// 🖼️ Cargar imagen sin recortarla ni deformarla
function loadImage(url) {
    fabric.Image.fromURL(url, function (fabricImg) {
        canvas.clear();
        currentImage = fabricImg;

        const maxWidth = window.innerWidth * 0.9;
        const maxHeight = window.innerHeight * 0.7;
        const scale = Math.min(maxWidth / fabricImg.width, maxHeight / fabricImg.height);

        scaleFactor = scale < 1 ? scale : 1;
        fabricImg.scale(scaleFactor);

        canvas.setWidth(fabricImg.width * scaleFactor);
        canvas.setHeight(fabricImg.height * scaleFactor);

        fabricImg.set({ left: 0, top: 0, selectable: false });
        canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas));

        scaleMessage.textContent = scaleFactor < 1 ? `Imagen escalada 1:${Math.round(1/scaleFactor)}` : '';
    }, { crossOrigin: 'anonymous' });
}

imageSelect.addEventListener('change', e => loadImage(e.target.value));
resetBtn.addEventListener('click', () => loadImage(imgUrl));

// 🧭 Activar herramienta de medición solo al pulsar el botón
measureBtn.addEventListener('click', () => {
    activeTool = activeTool === 'distance' ? null : 'distance';
    measureBtn.classList.toggle('bg-blue-300', activeTool === 'distance');
});

// 📏 Lógica de medición (solo si está activa la herramienta)
let points = [];
canvas.on('mouse:down', function (options) {
    if (activeTool !== 'distance') return;

    const pointer = canvas.getPointer(options.e);
    points.push({ x: pointer.x, y: pointer.y });

    const circle = new fabric.Circle({
        left: pointer.x - 4,
        top: pointer.y - 4,
        radius: 4,
        fill: 'red',
        selectable: false
    });
    canvas.add(circle);

    if (points.length === 2) {
        const line = new fabric.Line(
            [points[0].x, points[0].y, points[1].x, points[1].y],
            { strokeWidth: 2, stroke: 'lime', selectable: false }
        );
        canvas.add(line);

        const dist = Math.sqrt(
            Math.pow(points[1].x - points[0].x, 2) +
            Math.pow(points[1].y - points[0].y, 2)
        );
        const label = new fabric.Text(
            `${dist.toFixed(2)} px`,
            {
                left: (points[0].x + points[1].x) / 2,
                top: (points[0].y + points[1].y) / 2 - 20,
                fontSize: 16,
                fill: 'lime',
                selectable: false
            }
        );
        canvas.add(label);
        points = [];
    }
});

// 🔍 Zoom con la rueda del ratón
canvas.on('mouse:wheel', function(opt) {
    let delta = opt.e.deltaY;
    zoom *= 0.999 ** delta;
    zoom = Math.min(Math.max(zoom, 0.5), 3);
    canvas.setZoom(zoom);
    opt.e.preventDefault();
    opt.e.stopPropagation();
});

// ✋ Pan (arrastrar imagen)
let isPanning = false;
canvas.on('mouse:down', opt => {
    if (opt.e.altKey) {
        isPanning = true;
        canvas.selection = false;
        canvas.setCursor('grab');
    }
});
canvas.on('mouse:move', opt => {
    if (isPanning && opt && opt.e) {
        const e = opt.e;
        const vpt = canvas.viewportTransform;
        vpt[4] += e.movementX;
        vpt[5] += e.movementY;
        canvas.requestRenderAll();
    }
});
canvas.on('mouse:up', () => {
    isPanning = false;
    canvas.setCursor('default');
});

// Cargar la primera imagen
if (imageSelect.options.length > 0) {
    loadImage(imageSelect.value);
}
</script>

@endsection
