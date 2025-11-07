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
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
<script>
const canvas = new fabric.Canvas('measureCanvas', { preserveObjectStacking: true });
const imageSelect = document.getElementById('imageSelect');
const output = document.getElementById('measureOutput');
const scaleMessage = document.getElementById('scaleMessage');
const resetBtn = document.getElementById('resetBtn');

let currentImage = null;
let scaleFactor = 1;
let activeTool = null;

// === CARGAR IMAGEN ===
function loadImage(url) {
    fabric.Image.fromURL(url, function(img) {
        canvas.clear();
        currentImage = img;
        currentImage.crossOrigin = "anonymous";

        // Escalado proporcional (mejorado)
        const maxWidth = 800;
        const maxHeight = 600;
        let scale = Math.max(img.width / maxWidth, img.height / maxHeight);
        if (scale < 1) scale = 1;
        scaleFactor = 1 / scale;
        img.scale(scaleFactor);

        canvas.setWidth(img.width * scaleFactor);
        canvas.setHeight(img.height * scaleFactor);

        img.set({ left: 0, top: 0, selectable: false });
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
        scaleMessage.textContent = `Imagen escalada 1:${Math.round(scale)}`;
        output.textContent = "Selecciona una herramienta para comenzar.";
    }, { crossOrigin: 'anonymous' });
}

imageSelect.addEventListener('change', e => loadImage(e.target.value));

// === CAMBIO DE HERRAMIENTAS ===
function activateTool(tool) {
    activeTool = tool;
    output.textContent = `Herramienta activa: ${tool}`;
    canvas.off('mouse:down');
    if (tool === 'distance') activateDistanceTool();
    if (tool === 'angle') activateAngleTool();
    if (tool === 'delimited') activateContourTool();
    if (tool === 'arco') activateArcTool();
}

// === MEDIR DISTANCIA ===
function activateDistanceTool() {
    let p1 = null;
    canvas.on('mouse:down', e => {
        const p = canvas.getPointer(e.e);
        if (!p1) {
            p1 = p;
            canvas.add(new fabric.Circle({ left:p.x-3, top:p.y-3, radius:3, fill:'red', selectable:false }));
        } else {
            const p2 = p;
            canvas.add(new fabric.Circle({ left:p2.x-3, top:p2.y-3, radius:3, fill:'red', selectable:false }));
            const line = new fabric.Line([p1.x,p1.y,p2.x,p2.y], { stroke:'lime', strokeWidth:2, selectable:false });
            canvas.add(line);
            const dist = Math.hypot(p2.x - p1.x, p2.y - p1.y) * scaleFactor;
            canvas.add(new fabric.Text(`Distancia: ${dist.toFixed(2)} px`, {
                left:(p1.x+p2.x)/2, top:(p1.y+p2.y)/2 - 20,
                fontSize:16, fill:'lime', selectable:false
            }));
            p1 = null;
        }
    });
}

// === MEDIR ÁNGULO ===
function activateAngleTool() {
    let pts = [];
    canvas.on('mouse:down', e => {
        const p = canvas.getPointer(e.e);
        pts.push(p);
        canvas.add(new fabric.Circle({ left:p.x-3, top:p.y-3, radius:3, fill:'#29ff1b', selectable:false }));
        if (pts.length === 3) {
            const [A,B,C] = pts;
            canvas.add(new fabric.Line([A.x,A.y,B.x,B.y], { stroke:'#29ff1b', strokeWidth:2, selectable:false }));
            canvas.add(new fabric.Line([B.x,B.y,C.x,C.y], { stroke:'#29ff1b', strokeWidth:2, selectable:false }));
            const angle = calculateAngle(A,B,C);
            canvas.add(new fabric.Text(`Ángulo: ${angle.toFixed(1)}°`, {
                left:B.x, top:B.y - 30, fontSize:16, fill:'#29ff1b', selectable:false
            }));
            pts = [];
        }
    });
}

function calculateAngle(A,B,C) {
    const AB = {x:A.x-B.x, y:A.y-B.y}, CB = {x:C.x-B.x, y:C.y-B.y};
    const dot = AB.x*CB.x + AB.y*CB.y;
    return Math.acos(dot / (Math.hypot(AB.x,AB.y)*Math.hypot(CB.x,CB.y))) * 180 / Math.PI;
}

// === MEDIR CONTORNO ===
function activateContourTool() {
    let pts = [];
    let poly;
    canvas.on('mouse:down', e => {
        const p = canvas.getPointer(e.e);
        pts.push(p);
        canvas.add(new fabric.Circle({ left:p.x-3, top:p.y-3, radius:3, fill:'#8607f7', selectable:false }));
        if (pts.length >= 2) {
            const pathStr = pts.map((pt,i)=>i===0?`M ${pt.x} ${pt.y}`:`L ${pt.x} ${pt.y}`).join(' ');
            if (poly) canvas.remove(poly);
            poly = new fabric.Path(pathStr, { stroke:'#8607f7', strokeWidth:2, fill:'', selectable:false });
            canvas.add(poly);
            const len = calculateContourLength(pts)*scaleFactor;
            output.textContent = `Longitud total: ${len.toFixed(2)} px`;
        }
    });
}

function calculateContourLength(pts) {
    let len = 0;
    for (let i=1;i<pts.length;i++)
        len += Math.hypot(pts[i].x - pts[i-1].x, pts[i].y - pts[i-1].y);
    return len;
}

// === MEDIR ARCO ===
function activateArcTool() {
    let pts = [];
    canvas.on('mouse:down', e => {
        const p = canvas.getPointer(e.e);
        pts.push(p);
        canvas.add(new fabric.Circle({ left:p.x-3, top:p.y-3, radius:3, fill:'blue', selectable:false }));
        if (pts.length === 3) {
            drawArc(pts[0], pts[1], pts[2]);
            pts = [];
        }
    });
}

function drawArc(p1, p2, center) {
    const r = Math.hypot(center.x - p1.x, center.y - p1.y);
    const a1 = Math.atan2(p1.y - center.y, p1.x - center.x);
    const a2 = Math.atan2(p2.y - center.y, p2.x - center.x);
    const path = new fabric.Path(`M ${p1.x} ${p1.y} A ${r} ${r} 0 0 1 ${p2.x} ${p2.y}`, {
        stroke:'blue', strokeWidth:2, fill:'', selectable:false
    });
    canvas.add(path);
    const angle = Math.abs(a2 - a1) * (180 / Math.PI);
    const arcLen = r * (angle * Math.PI / 180) * scaleFactor;
    canvas.add(new fabric.Text(`Arco: ${arcLen.toFixed(2)} px`, {
        left:center.x, top:center.y - 20, fontSize:16, fill:'blue', selectable:false
    }));
}

// === FILTROS ===
function applyFilter(callback) {
    if (!currentImage) return;
    callback(currentImage);
    currentImage.applyFilters();
    canvas.renderAll();
}

document.getElementById('invertColors')?.addEventListener('click', () => {
    applyFilter(img => img.filters.push(new fabric.Image.filters.Invert()));
});
document.getElementById('increaseBrightness')?.addEventListener('click', () => {
    applyFilter(img => img.filters.push(new fabric.Image.filters.Brightness({ brightness: 0.1 })));
});
document.getElementById('decreaseBrightness')?.addEventListener('click', () => {
    applyFilter(img => img.filters.push(new fabric.Image.filters.Brightness({ brightness: -0.1 })));
});
document.getElementById('increaseContrast')?.addEventListener('click', () => {
    applyFilter(img => img.filters.push(new fabric.Image.filters.Contrast({ contrast: 0.1 })));
});
document.getElementById('decreaseContrast')?.addEventListener('click', () => {
    applyFilter(img => img.filters.push(new fabric.Image.filters.Contrast({ contrast: -0.1 })));
});
document.getElementById('edgesButton')?.addEventListener('click', () => {
    applyFilter(img => img.filters.push(new fabric.Image.filters.Convolute({
        matrix: [ -1, -1, -1, -1,  8, -1, -1, -1, -1 ]
    })));
});

// === RESET ===
resetBtn.onclick = () => {
    canvas.clear();
    loadImage(imageSelect.value);
    activeTool = null;
};

// === CARGA INICIAL ===
if (imageSelect.options.length > 0) {
    loadImage(imageSelect.value);
}
</script>

@endsection
