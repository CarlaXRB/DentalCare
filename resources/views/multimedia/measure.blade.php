@extends('layouts._partials.layout')
@section('title', 'Herramientas de Medición')
@section('subtitle')
{{ __('Herramientas de Medición') }}
@endsection

@section('content')
<div class="flex justify-end p-5">
    <a href="{{ route('multimedia.show', $study->id) }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<h1 class="title1 text-center mb-6">{{ __('Herramientas de Medición') }}</h1>

<!-- Botones de herramientas -->
<div class="relative flex justify-center flex-wrap gap-2 mb-6">

    <!-- Medición -->
    @php
        $tools = [
            ['id' => 'distance', 'img' => 'distance.png', 'label' => 'Medir_Distancia'],
            ['id' => 'delimited', 'img' => 'distances.png', 'label' => 'Marcar_Contorno'],
            ['id' => 'angle', 'img' => 'angle.png', 'label' => 'Medir_Ángulo'],
            ['id' => 'arco', 'img' => 'arco.png', 'label' => 'Medir_Arco'],
            ['id' => 'paint', 'img' => 'paint.png', 'label' => 'Pintar'],
        ];

        $filters = [
            ['id' => 'zoomIn', 'img' => 'zoom.png', 'label' => 'Acercar'],
            ['id' => 'zoomOut', 'img' => 'unzoom.png', 'label' => 'Alejar'],
            ['id' => 'invertColors', 'img' => 'negative.png', 'label' => 'Negativo'],
            ['id' => 'increaseBrightness', 'img' => 'filter3.png', 'label' => 'Más_Brillo'],
            ['id' => 'decreaseBrightness', 'img' => 'filter4.png', 'label' => 'Menos_Brillo'],
            ['id' => 'increaseContrast', 'img' => 'filter1.png', 'label' => 'Más_Contraste'],
            ['id' => 'decreaseContrast', 'img' => 'filter2.png', 'label' => 'Menos_Contraste'],
            ['id' => 'edgesButton', 'img' => 'edge.png', 'label' => 'Marcar_Bordes'],
        ];
    @endphp

    @foreach ($tools as $t)
    <div class="group relative">
        <button id="{{ $t['id'] }}" class="btnimg">
            <img src="{{ asset('assets/images/'.$t['img']) }}" width="50" height="50">
        </button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
            <span class="text-xs text-gray-800">{{ $t['label'] }}</span>
        </div>
    </div>
    @endforeach

    @foreach ($filters as $f)
    <div class="group relative">
        <button id="{{ $f['id'] }}" class="btnimg">
            <img src="{{ asset('assets/images/'.$f['img']) }}" width="50" height="50">
        </button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
            <span class="text-xs text-gray-800">{{ $f['label'] }}</span>
        </div>
    </div>
    @endforeach

    <!-- Descargar -->
    <div class="group relative">
        <button id="downloadImage" class="btnimg">
            <img src="{{ asset('assets/images/download.png') }}" width="50" height="50">
        </button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
            <span class="text-xs text-gray-800">Descargar</span>
        </div>
    </div>
</div>

<!-- Escala -->
<div class="flex justify-end mb-4">
    <label for="scaleSelect" class="mr-3 text-blue-900" style="font-size: 18px; margin-top: 8px;">{{ __('Escala') }}:</label>
    <select id="scaleSelect" class="border rounded px-2 py-1 text-lg font-bold">
        <option value="1">1:1</option>
        <option value="0.5">1:2</option>
        <option value="0.3333">1:3</option>
        <option value="0.25">1:4</option>
        <option value="0.2">1:5</option>
        <option value="2">2:1</option>
        <option value="3">3:1</option>
        <option value="4">4:1</option>
        <option value="5">5:1</option>
    </select>
</div>

<!-- Canvas -->
<div class="flex justify-center mt-[40px] mb-[30px]">
    <canvas id="canvas"></canvas>
</div>

<div class="flex justify-center mb-4">
    <button id="clearButton" class="botton2">{{ __('Limpiar') }}</button>
</div>

<!-- Mostrar la imagen actual -->
@if(count($imageUrls) > 0)
<div class="text-center mb-4">
    <img id="currentImage" src="{{ $imageUrls[0] }}" alt="Imagen del estudio" class="mx-auto rounded-lg shadow-md max-w-full h-auto">
</div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
<script>
const canvas = new fabric.Canvas('canvas');
const imgUrl = document.getElementById('currentImage')?.src;
let scaleFactor = 1;
let zoom = 1, brightness = 0, contrast = 1, isNegative = false, edgesApplied = false;
let measuringDist = false, painting = false;
let pointDist = [], previousPoint = null;

// Cargar imagen en el canvas
function loadImage() {
    if (!imgUrl) return;
    fabric.Image.fromURL(imgUrl, function(fabricImg) {
        const maxWidth = window.innerWidth * 0.9;
        const maxHeight = window.innerHeight * 0.7;
        const factorX = maxWidth / fabricImg.width;
        const factorY = maxHeight / fabricImg.height;
        scaleFactor = Math.min(factorX, factorY, 1);

        fabricImg.set({ left: 0, top: 0, selectable: false });
        fabricImg.scale(scaleFactor * zoom);
        canvas.setWidth(fabricImg.width * scaleFactor * zoom);
        canvas.setHeight(fabricImg.height * scaleFactor * zoom);
        canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas));
        applyFilters();
    });
}

// Aplicar filtros
function applyFilters() {
    const bg = canvas.backgroundImage;
    if (!bg) return;
    let filters = [
        new fabric.Image.filters.Brightness({ brightness: brightness }),
        new fabric.Image.filters.Contrast({ contrast: contrast })
    ];
    if (isNegative) filters.push(new fabric.Image.filters.Invert());
    if (edgesApplied) filters.push(new fabric.Image.filters.Convolute({ matrix: [-1,-1,-1,-1,8,-1,-1,-1,-1] }));
    bg.filters = filters;
    bg.applyFilters();
    canvas.renderAll();
}
loadImage();

// Botones de filtro
document.getElementById('zoomIn').onclick = () => { zoom += 0.1; loadImage(); };
document.getElementById('zoomOut').onclick = () => { zoom = Math.max(0.1, zoom - 0.1); loadImage(); };
document.getElementById('invertColors').onclick = () => { isNegative = !isNegative; applyFilters(); };
document.getElementById('increaseBrightness').onclick = () => { brightness = Math.min(1, brightness + 0.1); applyFilters(); };
document.getElementById('decreaseBrightness').onclick = () => { brightness = Math.max(-1, brightness - 0.1); applyFilters(); };
document.getElementById('increaseContrast').onclick = () => { contrast += 0.1; applyFilters(); };
document.getElementById('decreaseContrast').onclick = () => { contrast = Math.max(0.1, contrast - 0.1); applyFilters(); };
document.getElementById('edgesButton').onclick = () => { edgesApplied = !edgesApplied; applyFilters(); };

// Herramientas de medición
document.getElementById('distance').onclick = () => { measuringDist = !measuringDist; pointDist = []; };
document.getElementById('paint').onclick = () => { painting = !painting; if (!painting) canvas.off('mouse:move', paint); };

canvas.on('mouse:down', function(opt) {
    const pointer = canvas.getPointer(opt.e);
    if (measuringDist) {
        addCircle(pointer.x, pointer.y, 'cyan');
        pointDist.push({ x: pointer.x, y: pointer.y });
        if (pointDist.length === 2) {
            const line = new fabric.Line([pointDist[0].x, pointDist[0].y, pointDist[1].x, pointDist[1].y], {
                stroke: 'cyan', strokeWidth: 2, selectable: false
            });
            canvas.add(line);
            const distVal = Math.sqrt(Math.pow(pointDist[1].x - pointDist[0].x, 2) + Math.pow(pointDist[1].y - pointDist[0].y, 2)) * scaleFactor;
            const txt = new fabric.Text(`Distancia: ${Math.round(distVal)} mm`, {
                left: (pointDist[0].x + pointDist[1].x) / 2,
                top: (pointDist[0].y + pointDist[1].y) / 2 - 20,
                fontSize: 16, fill: 'cyan', selectable: false
            });
            canvas.add(txt);
            pointDist = [];
        }
    }
    if (painting) {
        previousPoint = { x: pointer.x, y: pointer.y };
        canvas.on('mouse:move', paint);
    }
});

function addCircle(x, y, color = 'red') {
    canvas.add(new fabric.Circle({ left: x - 4, top: y - 4, radius: 4, fill: color, selectable: false }));
}

function paint(opt) {
    if (!painting || !previousPoint) return;
    const pointer = canvas.getPointer(opt.e);
    canvas.add(new fabric.Line([previousPoint.x, previousPoint.y, pointer.x, pointer.y], {
        stroke: 'red', strokeWidth: 1, selectable: false
    }));
    previousPoint = { x: pointer.x, y: pointer.y };
}

document.getElementById('clearButton').onclick = () => {
    canvas.clear(); loadImage(); pointDist = []; previousPoint = null;
};

// Descargar imagen
document.getElementById('downloadImage').onclick = () => {
    const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
    const link = document.createElement('a');
    link.href = dataURL;
    link.download = `mediciones_{{ $study->study_code }}_{{ $study->ci_patient }}.png`;
    link.click();
};
</script>
@endsection
