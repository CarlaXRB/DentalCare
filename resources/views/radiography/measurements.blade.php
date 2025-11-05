@extends('layouts._partials.layout')
@section('title', 'Herramientas de Medición')
@section('subtitle')
{{ __('Herramientas de Medición') }}
@endsection
@section('content')
<div class="flex justify-end p-5">
    <a href="{{ route('radiography.tool', $radiography->id) }}" class="botton1">{{ __('Atrás') }}</a>
</div>
<h1 class="title1">{{ __('Herramientas de Medición') }}</h1>

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
    <form action="{{ route('tool.store',['radiography_id' => $radiography->radiography_id, 'tomography_id' => '0', 'ci_patient' => $radiography->ci_patient, 'id' => $radiography->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="group relative">
            <button id="save" class="btnimg" type="submit"><img src="{{ asset('assets/images/save.png') }}" width="50" height="50"></button>
            <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-800">Guardar</span></div>
        </div>
    </form>
    <div class="group relative">
        <button id="downloadImage" class="btnimg"><img src="{{ asset('assets/images/download.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Decargar</span></div>
    </div>
</div>
<div class="flex justify-end mb-4">
    <label for="scaleSelect" class="mr-3 text-blue-900" style="font-size: 18px; margin-top: 8px;">{{ __('Escala') }}:</label>
    <select id="scaleSelect" style="padding: 8px; padding-right: 30px; padding-left: 12px; border-radius: 10px; background-color: #ffffffff;color: #2000d4ff;border: 2px solid #0063d4ff;font-size: 16px; font-weight: bold; transition: background-color 0.3s, border-color 0.3s;margin-right: 70px;appearance: none; /* Elimina la flecha por defecto */-webkit-appearance: none; /* Para Safari */-moz-appearance: none; /* Para Firefox */">
        <option value="1" style="background-color: white; color: black;">1:1</option>
        <option value="0.5" style="background-color: white; color: black;">1:2</option>
        <option value="0.33333" style="background-color: white; color: black;">1:3</option>
        <option value="0.25" style="background-color: white; color: black;">1:4</option>
        <option value="0.2" style="background-color: white; color: black;">1:5</option>
        <option value="2" style="background-color: white; color: black;">2:1</option>
        <option value="3" style="background-color: white; color: black;">3:1</option>
        <option value="4" style="background-color: white; color: black;">4:1</option>
        <option value="5" style="background-color: white; color: black;">5:1</option>
    </select>
</div>


<div class="flex justify-center mt-[40px] mb-[30px]"><canvas id="canvas"></canvas></div>
<div id="scaleMessage" style="display:none; color: white;font-size: 18px;padding: 10px;text-align: center;">
</div>

<div class="flex justify-center mb-4"><button id="clearButton" class="botton2">{{ __('Limpiar') }}</button></div>
<div class="relative flex justify-center">
    <!-- Canvas -->
    <canvas id="canvas"></canvas>
    <!-- Mensajes sobre la imagen, posición absoluta para no desacomodar -->
    <div id="imageInfo" class="absolute top-2 left-1/2 transform -translate-x-1/2 text-center text-black">
        <p id="originalSize" class="text-m"></p>
        <p id="scaleWarning" class="text-blue-800 font-bold text-base"></p>
        <p id="scaleMessage" class="text-blue-500 font-semibold text-base"></p>
    </div>
</div>

<!-- Applied Tools Table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-4 text-gray-900 shadow-md mt-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="title1 text-center w-full">{{ __('Herramientas Aplicadas') }}</h1>
        <a href="javascript:void(0);" id="updateButton" class="botton3">{{ __('Actualizar') }}</a>
    </div>

    <!-- Column titles -->
    <div class="grid grid-cols-4 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center font-semibold">{{ __('Vista Previa') }}</h3>
        <h3 class="title4 text-center font-semibold">{{ __('Fecha de creación') }}</h3>
        <h3 class="title4 text-center font-semibold">{{ __('ID del Estudio') }}</h3>
    </div>

    <!-- Table body -->
    @forelse($radiography->tools as $tool)
    <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Preview -->
        <div class="flex justify-center">
            <a href="{{ route('tool.show', $tool->id) }}">
                <img src="{{ asset('storage/tools/'.$tool->tool_uri) }}"
                    alt="Tool preview"
                    class="rounded-lg shadow-md w-32 h-auto object-cover" />
            </a>
        </div>

        <!-- Creation date -->
        <div class="text-center">
            <a href="{{ route('tool.show', $tool->id) }}" class="txt hover:text-cyan-600">
                {{ $tool->tool_date }}
            </a>
        </div>

        <!-- Study ID -->
        <div class="text-center">
            <a href="{{ route('tool.show', $tool->id) }}" class="txt hover:text-cyan-600">
                {{ $tool->tool_radiography_id }}
            </a>
        </div>

        <!-- Actions -->
        <div class="flex justify-center">
            <form method="POST" action="{{ route('tool.destroy', $tool->id) }}">
                @csrf
                @method('DELETE')
                <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete" />
            </form>
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('Aún no se han aplicado herramientas.') }}</p>
    @endforelse
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
<script>
const canvas = new fabric.Canvas('canvas');
const imgUrl = "{{ asset('storage/radiographies/'.$radiography->radiography_uri) }}";
let imageScaled = false;
let scaleFactor = 1;

// Cargar imagen y escalar si es muy grande
function loadImage() {
    const img = new Image();
    img.src = imgUrl;
    img.onload = function() {
        const maxWidth = window.innerWidth * 0.9;
        const maxHeight = window.innerHeight * 0.7;
        let factorX = maxWidth / img.width;
        let factorY = maxHeight / img.height;
        scaleFactor = Math.min(factorX, factorY, 1);
        let roundedScale = Math.round(1 / scaleFactor);
        if(scaleFactor < 1){
            document.getElementById('scaleMessage').textContent = `La imagen fue escalada a 1:${roundedScale}`;
            document.getElementById('scaleMessage').style.display = 'block';
            imageScaled = true;
        }
        canvas.setWidth(img.width * scaleFactor);
        canvas.setHeight(img.height * scaleFactor);

        fabric.Image.fromURL(imgUrl, function(fabricImg) {
            fabricImg.set({ left: 0, top: 0, selectable: false });
            fabricImg.scale(scaleFactor);
            canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas));
        });

        document.getElementById('originalSize').textContent = `Tamaño original: ${img.width} x ${img.height} px`;
        document.getElementById('scaleWarning').textContent = "Verifique la escala para mediciones precisas.";
    }
}
loadImage();

// Variables para herramientas
let measuringDist = false, pointDist = [];
let measuringAngles = false, anglePoints = [];
let measuringContours = false, contourPoints = [];
let measuringArcs = false, arcPoints = [], centerPoint;
let painting = false, previousPoint = null;

// Selección de escala manual
document.getElementById('scaleSelect').addEventListener('change', function(e){
    scaleFactor = parseFloat(e.target.value);
});

// Funciones comunes
function drawCircle(x,y,color='red'){
    const circle = new fabric.Circle({ left:x-4, top:y-4, radius:4, fill:color, selectable:false });
    canvas.add(circle);
}

// DISTANCIA
document.getElementById('distance').onclick = () => {
    measuringDist = !measuringDist;
    pointDist = [];
};
canvas.on('mouse:down', function(opt){
    const pointer = canvas.getPointer(opt.e);

    // DISTANCIA
    if(measuringDist){
        drawCircle(pointer.x,pointer.y,'cyan');
        pointDist.push({x:pointer.x, y:pointer.y});
        if(pointDist.length===2){
            const line = new fabric.Line([pointDist[0].x, pointDist[0].y, pointDist[1].x, pointDist[1].y], {strokeWidth:2, stroke:'cyan', selectable:false});
            canvas.add(line);
            const dist = Math.sqrt(Math.pow(pointDist[1].x-pointDist[0].x,2)+Math.pow(pointDist[1].y-pointDist[0].y,2)) * scaleFactor;
            const txt = new fabric.Text(`Distancia: ${Math.round(dist)} mm`, { left:(pointDist[0].x+pointDist[1].x)/2, top:(pointDist[0].y+pointDist[1].y)/2-20, fontSize:16, fill:'cyan', selectable:false });
            canvas.add(txt);
            pointDist = [];
        }
        return;
    }

    // ANGULO
    if(measuringAngles){
        drawCircle(pointer.x,pointer.y,'green');
        anglePoints.push({x:pointer.x,y:pointer.y});
        if(anglePoints.length===3){
            const line1 = new fabric.Line([anglePoints[0].x,anglePoints[0].y,anglePoints[1].x,anglePoints[1].y],{strokeWidth:2, stroke:'green', selectable:false});
            const line2 = new fabric.Line([anglePoints[1].x,anglePoints[1].y,anglePoints[2].x,anglePoints[2].y],{strokeWidth:2, stroke:'green', selectable:false});
            canvas.add(line1,line2);
            const angle = Math.acos((Math.pow(dist(anglePoints[0],anglePoints[1]),2)+Math.pow(dist(anglePoints[1],anglePoints[2]),2)-Math.pow(dist(anglePoints[0],anglePoints[2]),2))/(2*dist(anglePoints[0],anglePoints[1])*dist(anglePoints[1],anglePoints[2])));
            const txt = new fabric.Text(`Ángulo: ${Math.round(angle*180/Math.PI)}°`, { left:(anglePoints[0].x+anglePoints[1].x+anglePoints[2].x)/3, top:(anglePoints[0].y+anglePoints[1].y+anglePoints[2].y)/3-20, fontSize:16, fill:'green', selectable:false });
            canvas.add(txt);
            anglePoints=[];
        }
        return;
    }

    // CONTORNO
    if(measuringContours){
        drawCircle(pointer.x,pointer.y,'purple');
        contourPoints.push({x:pointer.x,y:pointer.y});
        if(contourPoints.length>1){
            const path = contourPoints.map((p,i)=> i===0?`M ${p.x} ${p.y}`:`L ${p.x} ${p.y}`).join(' ');
            const line = new fabric.Path(path,{stroke:'purple',strokeWidth:2,fill:'',selectable:false});
            canvas.add(line);
            const length = contourPoints.reduce((sum,p,i,a)=> i>0?sum+dist(p,a[i-1]):sum,0) * scaleFactor;
            const txt = new fabric.Text(`Longitud: ${Math.round(length)} mm`, { left:contourPoints[contourPoints.length-1].x, top:contourPoints[contourPoints.length-1].y-20, fontSize:16, fill:'purple', selectable:false });
            canvas.add(txt);
        }
        return;
    }

    // ARCO
    if(measuringArcs){
        drawCircle(pointer.x,pointer.y,'blue');
        arcPoints.push({x:pointer.x,y:pointer.y});
        if(arcPoints.length===3){
            drawArc(arcPoints[0],arcPoints[1],arcPoints[2]);
            arcPoints=[];
        }
        return;
    }

    // PINTURA
    if(painting){
        previousPoint = {x:pointer.x, y:pointer.y};
        canvas.on('mouse:move', paint);
    }
});

function dist(p1,p2){return Math.sqrt(Math.pow(p2.x-p1.x,2)+Math.pow(p2.y-p1.y,2));}

function drawArc(p1,p2,center){
    const radius = dist(center,p1);
    const angle1 = Math.atan2(p1.y-center.y,p1.x-center.x);
    const angle2 = Math.atan2(p2.y-center.y,p2.x-center.x);
    const path = new fabric.Path(`M ${p1.x} ${p1.y} A ${radius} ${radius} 0 0 1 ${p2.x} ${p2.y}`, {stroke:'blue', strokeWidth:2, fill:'', selectable:false});
    canvas.add(path);
    const arcLength = radius*Math.abs(angle2-angle1)*scaleFactor;
    const txt = new fabric.Text(`Longitud: ${Math.round(arcLength)} mm`, { left:(p1.x+p2.x)/2, top:(p1.y+p2.y)/2-20, fontSize:16, fill:'blue', selectable:false });
    canvas.add(txt);
}

// EVENTOS BOTONES
document.getElementById('angle').onclick = () => { measuringAngles=!measuringAngles; anglePoints=[]; };
document.getElementById('delimited').onclick = () => { measuringContours=!measuringContours; contourPoints=[]; };
document.getElementById('arco').onclick = () => { measuringArcs=!measuringArcs; arcPoints=[]; };
document.getElementById('paint').onclick = () => { painting=!painting; if(!painting) canvas.off('mouse:move', paint); };
document.getElementById('clearButton').onclick = () => { canvas.clear(); loadImage(); pointDist=[]; anglePoints=[]; contourPoints=[]; arcPoints=[]; previousPoint=null; };

function paint(opt){
    if(!painting || !previousPoint) return;
    const pointer = canvas.getPointer(opt.e);
    const line = new fabric.Line([previousPoint.x, previousPoint.y, pointer.x, pointer.y],{stroke:'red',strokeWidth:1,selectable:false});
    canvas.add(line);
    previousPoint = {x:pointer.x,y:pointer.y};
}

// DESCARGAR
document.getElementById('downloadImage').onclick = () => {
    const dataURL = canvas.toDataURL({format:'png',quality:1.0});
    const link = document.createElement('a');
    link.href = dataURL;
    link.download = `mediciones_{{ $radiography->radiography_id }}_{{ $radiography->ci_patient }}.png`;
    link.click();
};
</script>

@endsection