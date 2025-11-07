@extends('layouts._partials.layout')
@section('title', 'Medición Interactiva')
@section('subtitle')
{{ __('Medición Interactiva') }}
@endsection

@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('multimedia.show', $study->id) }}" class="botton1">Volver al Estudio</a>
</div>

<h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
    Medición Interactiva — {{ $study->name_patient }}
</h1>

<div class="flex justify-center mb-4">
    <select id="imageSelect" class="border border-gray-300 rounded-lg p-2">
        @foreach($imageUrls as $url)
            <option value="{{ $url }}">Imagen {{ $loop->iteration }}</option>
        @endforeach
    </select>
</div>

<!-- Herramientas -->
<div class="relative flex justify-center flex-wrap gap-2 mb-6">
    @php
        $tools = [
            ['id'=>'distance','img'=>'distance.png','title'=>'Medir Distancia'],
            ['id'=>'delimited','img'=>'distances.png','title'=>'Marcar Contorno'],
            ['id'=>'angle','img'=>'angle.png','title'=>'Medir Ángulo'],
            ['id'=>'zoomIn','img'=>'zoom-in.png','title'=>'Acercar'],
            ['id'=>'zoomOut','img'=>'zoom-out.png','title'=>'Alejar'],
            ['id'=>'increaseBrightness','img'=>'bright.png','title'=>'Aumentar Brillo'],
            ['id'=>'decreaseBrightness','img'=>'dark.png','title'=>'Disminuir Brillo'],
            ['id'=>'increaseContrast','img'=>'contrast-up.png','title'=>'Aumentar Contraste'],
            ['id'=>'decreaseContrast','img'=>'contrast-down.png','title'=>'Disminuir Contraste'],
            ['id'=>'invertColors','img'=>'invert.png','title'=>'Negativo'],
            ['id'=>'edgesButton','img'=>'edges.png','title'=>'Detectar Bordes'],
            ['id'=>'downloadImage','img'=>'download.png','title'=>'Descargar']
        ];
    @endphp
    @foreach($tools as $tool)
    <div class="group relative">
        <button id="{{ $tool['id'] }}" class="btnimg">
            <img src="{{ asset('assets/images/'.$tool['img']) }}" width="45" height="45">
        </button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
            <span class="text-xs text-gray-800">{{ $tool['title'] }}</span>
        </div>
    </div>
    @endforeach
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
const canvas = new fabric.Canvas('measureCanvas', { preserveObjectStacking: true });
const imageSelect = document.getElementById('imageSelect');
const output = document.getElementById('measureOutput');
const scaleMessage = document.getElementById('scaleMessage');
const resetBtn = document.getElementById('resetBtn');

let currentImage;
let scaleFactor = 1;
let activeTool = null; // para activar/desactivar herramientas
let imgUrl = imageSelect.value;
let brightness = 0;
let contrast = 1;
let isNegative = false;
let edgesApplied = false;
let zoom = 1;

// Cargar imagen y escalar correctamente
function loadImage(url) {
    imgUrl = url;
    fabric.Image.fromURL(url, function(fabricImg) {
        canvas.clear();
        currentImage = fabricImg;

        const maxWidth = window.innerWidth * 0.9;
        const maxHeight = window.innerHeight * 0.7;

        let scale = 1;
        if (fabricImg.width > maxWidth || fabricImg.height > maxHeight) {
            const widthScale = fabricImg.width / maxWidth;
            const heightScale = fabricImg.height / maxHeight;
            const maxScale = Math.max(widthScale, heightScale);
            scale = Math.ceil(maxScale);
            scaleFactor = 1 / scale;
            fabricImg.scale(scaleFactor);
            scaleMessage.textContent = `Imagen escalada 1:${scale}`;
        } else {
            scaleFactor = 1;
            scaleMessage.textContent = '';
        }

        canvas.setWidth(fabricImg.width * scaleFactor);
        canvas.setHeight(fabricImg.height * scaleFactor);
        fabricImg.set({ left: 0, top: 0, selectable: false });
        canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas));
        output.textContent = "Selecciona una herramienta para comenzar.";
    }, { crossOrigin: 'anonymous' });
}

// Filtros CSS simulados con Fabric.js
function updateFilters() {
    if (!currentImage) return;
    currentImage.filters = [];

    currentImage.filters.push(new fabric.Image.filters.Brightness({ brightness: brightness }));
    currentImage.filters.push(new fabric.Image.filters.Contrast({ contrast: contrast - 1 }));
    if (isNegative) currentImage.filters.push(new fabric.Image.filters.Invert());

    currentImage.applyFilters();
    canvas.renderAll();
}

document.getElementById('zoomIn').onclick = () => {
    zoom += 0.1;
    canvas.setZoom(zoom);
};
document.getElementById('zoomOut').onclick = () => {
    if (zoom > 0.5) {
        zoom -= 0.1;
        canvas.setZoom(zoom);
    }
};
document.getElementById('increaseBrightness').onclick = () => {
    brightness = Math.min(brightness + 0.1, 1);
    updateFilters();
};
document.getElementById('decreaseBrightness').onclick = () => {
    brightness = Math.max(brightness - 0.1, -1);
    updateFilters();
};
document.getElementById('increaseContrast').onclick = () => {
    contrast = Math.min(contrast + 0.1, 3);
    updateFilters();
};
document.getElementById('decreaseContrast').onclick = () => {
    contrast = Math.max(contrast - 0.1, 0);
    updateFilters();
};
document.getElementById('invertColors').onclick = () => {
    isNegative = !isNegative;
    updateFilters();
};

// Detección de bordes simple
document.getElementById('edgesButton').onclick = () => {
    if (!currentImage) return;
    if (edgesApplied) {
        loadImage(imgUrl);
        edgesApplied = false;
        return;
    }
    const filter = new fabric.Image.filters.Convolute({
        matrix: [ -1,-1,-1, -1,8,-1, -1,-1,-1 ]
    });
    currentImage.filters.push(filter);
    currentImage.applyFilters();
    canvas.renderAll();
    edgesApplied = true;
};

// Medición solo cuando el usuario elige la herramienta
let points = [];
let line, lineText;

document.getElementById('distance').addEventListener('click', () => {
    activeTool = 'distance';
    output.textContent = "Haz clic en dos puntos para medir distancia.";
});

canvas.on('mouse:down', function(options) {
    if (activeTool !== 'distance') return;
    const pointer = canvas.getPointer(options.e);
    points.push({x: pointer.x, y: pointer.y});

    const circle = new fabric.Circle({
        left: pointer.x - 4,
        top: pointer.y - 4,
        radius: 4,
        fill: 'red',
        selectable: false
    });
    canvas.add(circle);

    if(points.length === 2){
        line = new fabric.Line([points[0].x, points[0].y, points[1].x, points[1].y], {
            strokeWidth: 2,
            stroke: 'lime',
            selectable: false
        });
        canvas.add(line);

        const distPx = Math.sqrt((points[1].x - points[0].x)**2 + (points[1].y - points[0].y)**2);
        lineText = new fabric.Text(`Distancia: ${distPx.toFixed(2)} px`, {
            left: (points[0].x + points[1].x)/2,
            top: (points[0].y + points[1].y)/2 - 20,
            fontSize: 16,
            fill: 'lime',
            selectable: false
        });
        canvas.add(lineText);
        points = [];
    }
});

document.getElementById('downloadImage').addEventListener('click', () => {
    const link = document.createElement('a');
    link.download = 'imagen_filtrada.png';
    link.href = canvas.toDataURL();
    link.click();
});

resetBtn.addEventListener('click', () => loadImage(imgUrl));
imageSelect.addEventListener('change', (e) => loadImage(e.target.value));

if(imageSelect.options.length > 0){
    loadImage(imageSelect.value);
}
</script>
@endsection
