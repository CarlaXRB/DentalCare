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

<!-- Botones de herramientas de medición -->
<div class="relative flex justify-center space-x-2 mb-4">
    <!-- Medición -->
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

    <!-- Filtros de imagen -->
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

    <!-- Guardar y descargar -->
    <form action="{{ route('tool.store',['radiography_id' => $radiography->radiography_id, 'tomography_id' => '0', 'ci_patient' => $radiography->ci_patient, 'id' => $radiography->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="group relative">
            <button id="save" class="btnimg" type="submit"><img src="{{ asset('assets/images/save.png') }}" width="50" height="50"></button>
            <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-800">Guardar</span></div>
        </div>
    </form>
    <div class="group relative">
        <button id="downloadImage" class="btnimg"><img src="{{ asset('assets/images/download.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Descargar</span></div>
    </div>
</div>

<!-- Escala -->
<div class="flex justify-end mb-4">
    <label for="scaleSelect" class="mr-3 text-blue-900" style="font-size: 18px; margin-top: 8px;">{{ __('Escala') }}:</label>
    <select id="scaleSelect" class="border rounded px-2 py-1 text-lg font-bold">
        <option value="1">1:1</option>
        <option value="0.5">1:2</option>
        <option value="0.33333">1:3</option>
        <option value="0.25">1:4</option>
        <option value="0.2">1:5</option>
        <option value="2">2:1</option>
        <option value="3">3:1</option>
        <option value="4">4:1</option>
        <option value="5">5:1</option>
    </select>
</div>

<div class="flex justify-center mt-[40px] mb-[30px]">
    <canvas id="canvas"></canvas>
</div>

<div class="flex justify-center mb-4">
    <button id="clearButton" class="botton2">{{ __('Limpiar') }}</button>
</div>

<!-- Applied Tools Table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-4 text-gray-900 shadow-md mt-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="title1 text-center w-full">{{ __('Herramientas Aplicadas') }}</h1>
        <a href="javascript:void(0);" id="updateButton" class="botton3">{{ __('Actualizar') }}</a>
    </div>

    <div class="grid grid-cols-4 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center font-semibold">{{ __('Vista Previa') }}</h3>
        <h3 class="title4 text-center font-semibold">{{ __('Fecha de creación') }}</h3>
        <h3 class="title4 text-center font-semibold">{{ __('ID del Estudio') }}</h3>
    </div>

    @forelse($radiography->tools as $tool)
    <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <div class="flex justify-center">
            <a href="{{ route('tool.show', $tool->id) }}">
                <img src="{{ asset('storage/tools/'.$tool->tool_uri) }}" alt="Tool preview" class="rounded-lg shadow-md w-32 h-auto object-cover" />
            </a>
        </div>
        <div class="text-center">
            <a href="{{ route('tool.show', $tool->id) }}" class="txt hover:text-cyan-600">{{ $tool->tool_date }}</a>
        </div>
        <div class="text-center">
            <a href="{{ route('tool.show', $tool->id) }}" class="txt hover:text-cyan-600">{{ $tool->tool_radiography_id }}</a>
        </div>
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

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
<script>
const canvas = new fabric.Canvas('canvas');
const imgUrl = "{{ asset('storage/radiographies/'.$radiography->radiography_uri) }}";
let scaleFactor = 1;
let measuringDist=false, measuringAngles=false, measuringContours=false, measuringArcs=false, painting=false;
let pointDist=[], anglePoints=[], contourPoints=[], arcPoints=[], previousPoint=null;

// Variables de filtros
let zoom = 1, brightness = 0, contrast = 1, isNegative=false, edgesApplied=false;

// Cargar imagen
function loadImage(){
    fabric.Image.fromURL(imgUrl, function(fabricImg){
        const maxWidth = window.innerWidth * 0.9;
        const maxHeight = window.innerHeight * 0.7;
        const factorX = maxWidth / fabricImg.width;
        const factorY = maxHeight / fabricImg.height;
        scaleFactor = Math.min(factorX,factorY,1);
        fabricImg.set({left:0, top:0, selectable:false});
        fabricImg.scale(scaleFactor*zoom);
        canvas.setWidth(fabricImg.width*scaleFactor*zoom);
        canvas.setHeight(fabricImg.height*scaleFactor*zoom);
        canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas));
        applyFilters();
    });
}

// Aplicar filtros
function applyFilters(){
    const bg = canvas.backgroundImage;
    if(!bg) return;
    let filters = [];
    filters.push(new fabric.Image.filters.Brightness({brightness: brightness}));
    filters.push(new fabric.Image.filters.Contrast({contrast: contrast}));
    if(isNegative) filters.push(new fabric.Image.filters.Invert());
    if(edgesApplied) filters.push(new fabric.Image.filters.Convolute({matrix:[-1,-1,-1,-1,8,-1,-1,-1,-1]}));
    bg.filters = filters;
    bg.applyFilters();
    canvas.renderAll();
}
loadImage();

// Botones filtros
document.getElementById('zoomIn').onclick = ()=>{ zoom+=0.1; loadImage(); };
document.getElementById('zoomOut').onclick = ()=>{ zoom=Math.max(0.1, zoom-0.1); loadImage(); };
document.getElementById('invertColors').onclick = ()=>{ isNegative=!isNegative; applyFilters(); };
document.getElementById('increaseBrightness').onclick = ()=>{ brightness=Math.min(1, brightness+0.1); applyFilters(); };
document.getElementById('decreaseBrightness').onclick = ()=>{ brightness=Math.max(-1, brightness-0.1); applyFilters(); };
document.getElementById('increaseContrast').onclick = ()=>{ contrast+=0.1; applyFilters(); };
document.getElementById('decreaseContrast').onclick = ()=>{ contrast=Math.max(0.1, contrast-0.1); applyFilters(); };
document.getElementById('edgesButton').onclick = ()=>{ edgesApplied=!edgesApplied; applyFilters(); };

// Herramientas de medición
document.getElementById('distance').onclick = ()=>{ measuringDist=!measuringDist; pointDist=[]; };
document.getElementById('angle').onclick = ()=>{ measuringAngles=!measuringAngles; anglePoints=[]; };
document.getElementById('delimited').onclick = ()=>{ measuringContours=!measuringContours; contourPoints=[]; };
document.getElementById('arco').onclick = ()=>{ measuringArcs=!measuringArcs; arcPoints=[]; };
document.getElementById('paint').onclick = ()=>{ painting=!painting; if(!painting) canvas.off('mouse:move', paint); };

// Dibujar mediciones
canvas.on('mouse:down', function(opt){
    const pointer = canvas.getPointer(opt.e);

    // Distancia
    if(measuringDist){
        addCircle(pointer.x,pointer.y,'cyan'); pointDist.push({x:pointer.x,y:pointer.y});
        if(pointDist.length===2){
            const line = new fabric.Line([pointDist[0].x,pointDist[0].y,pointDist[1].x,pointDist[1].y],{stroke:'cyan',strokeWidth:2,selectable:false});
            canvas.add(line);
            const distVal = Math.sqrt(Math.pow(pointDist[1].x-pointDist[0].x,2)+Math.pow(pointDist[1].y-pointDist[0].y,2))*scaleFactor;
            const txt = new fabric.Text(`Distancia: ${Math.round(distVal)} mm`,{left:(pointDist[0].x+pointDist[1].x)/2,top:(pointDist[0].y+pointDist[1].y)/2-20,fontSize:16,fill:'cyan',selectable:false});
            canvas.add(txt); pointDist=[];
        } return;
    }

    // Pintura
    if(painting){
        previousPoint={x:pointer.x,y:pointer.y};
        canvas.on('mouse:move', paint);
    }
});

function addCircle(x,y,color='red'){ canvas.add(new fabric.Circle({left:x-4,top:y-4,radius:4,fill:color,selectable:false})); }
function paint(opt){
    if(!painting||!previousPoint) return;
    const pointer = canvas.getPointer(opt.e);
    canvas.add(new fabric.Line([previousPoint.x,previousPoint.y,pointer.x,pointer.y],{stroke:'red',strokeWidth:1,selectable:false}));
    previousPoint={x:pointer.x,y:pointer.y};
}

document.getElementById('clearButton').onclick=()=>{ canvas.clear(); loadImage(); pointDist=[]; anglePoints=[]; contourPoints=[]; arcPoints=[]; previousPoint=null; };

// Descargar imagen
document.getElementById('downloadImage').onclick=()=>{
    const dataURL = canvas.toDataURL({format:'png',quality:1});
    const link = document.createElement('a'); link.href=dataURL;
    link.download=`mediciones_{{ $radiography->radiography_id }}_{{ $radiography->ci_patient }}.png`; link.click();
};
</script>
@endsection
