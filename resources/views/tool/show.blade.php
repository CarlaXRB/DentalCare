@extends('layouts._partials.layout')
@section('title', 'Herramientas')
@section('subtitle')
    {{ __('Visualizador') }}
@endsection
@section('content')

<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dashboard')}}" class="botton1">{{ __('Inicio') }}</a>
</div>

<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">

    <div class="mb-5">
        <h1 class="title1 text-center pb-5">{{ __('ESTUDIO DEL PACIENTE') }}</h1>
    </div>

    <div class="relative flex justify-center items-center space-x-3">
        <div class="group relative">
            <button id="zoomIn" class="btnimg">
                <img src="{{ asset('storage/assets/images/zoom.png') }}" width="50" height="50">
            </button>
            <div class="hidden group-hover:block absolute bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
                <span class="text-sm text-gray-800">Acercar</span>
            </div>
        </div>
        <div class="group relative">
            <button id="zoomOut" class="btnimg">
                <img src="{{ asset('storage/assets/images/unzoom.png') }}" width="50" height="50">
            </button>
            <div class="hidden group-hover:block absolute bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
                <span class="text-sm text-gray-800">Alejar</span>
            </div>
        </div>
        <div class="group relative">
            <button id="draw" class="btnimg" onclick="window.location.href='{{ route('tool.measurements', $tool->id) }}'">
                <img src="{{ asset('storage/assets/images/draw.png') }}" width="50" height="50">
            </button>
            <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
                <span class="text-sm text-gray-800">Mediciones</span>
            </div>
        </div>
        <div class="group relative ml-5">
            <button id="report" class="btnimg" onclick="window.location.href='{{ route('report.form', ['type'=>'tool','id'=>$tool->id, 'name'=>$tool->ci_patient,'ci'=>$tool->ci_patient]) }}'">
                <img src="{{ asset('storage/assets/images/report.png') }}" width="50" height="50">
            </button>
            <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
                <span class="text-sm text-gray-800">Reporte</span>
            </div>
        </div>
        <div class="group relative">
            <button id="downloadImage" class="btnimg">
                <img src="{{ asset('storage/assets/images/download.png') }}" width="50" height="50">
            </button>
            <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
                <span class="text-xs text-gray-800">Descargar</span>
            </div>
        </div>
    </div>

<div class="relative flex justify-center mt-[30px] mb-[30px]">
    <div class="overflow-auto" style="width: 1100px; height: 700px; position: relative;">
        <img id="radiographyImage" 
             src="{{ asset('storage/tools/'.$tool->tool_uri) }}" 
             style="width: auto; height: auto; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />
        <div id="magnifierLens" style="display: none; position: absolute; border: 1px solid #000; border-radius: 50%; pointer-events: none;"></div> <!-- Lente de la lupa -->
    </div>
</div>

    <h1 class="title2 mb-4">{{ __('INFORMACIÓN DEL PACIENTE') }}</h1>
    @if($tool->patient)
        <div class="grid grid-cols-2 gap-4 text-gray-900 dark:text-white mb-6">
            <div class="flex gap-2"><h3 class="title4">{{ __('Paciente') }}:</h3><p class="txt">{{ $tool->patient->name_patient }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('C.I.') }}:</h3><p class="txt">{{ $tool->patient->ci_patient }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Fecha de nacimiento') }}:</h3><p class="txt">{{ $tool->patient->birth_date }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Género') }}:</h3><p class="txt">{{ $tool->patient->gender }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Celular') }}:</h3><p class="txt">{{ $tool->patient->patient_contact }}</p></div>
        </div>
    @else
        @isset($tool->radiography)
            <div class="grid grid-cols-2 gap-4 text-gray-900 dark:text-white mb-6">
                <div class="flex gap-2"><h3 class="title4">{{ __('Paciente') }}:</h3><p>{{ $tool->radiography->name_patient }}</p></div>
                <div class="flex gap-2"><h3 class="title4">{{ __('C.I.') }}:</h3><p>{{ $tool->radiography->ci_patient }}</p></div>
            </div>
        @elseif(isset($tool->tomography))
            <div class="grid grid-cols-2 gap-4 text-gray-900 dark:text-white mb-6">
                <div class="flex gap-2"><h3 class="title4">{{ __('Paciente') }}:</h3><p>{{ $tool->tomography->name_patient }}</p></div>
                <div class="flex gap-2"><h3 class="title4">{{ __('C.I.') }}:</h3><p>{{ $tool->tomography->ci_patient }}</p></div>
            </div>
        @else
            <h1 class="text-red-500 mb-6">{{ __('Paciente no registrado') }}.</h1>
        @endisset
    @endif

    <h1 class="title2 mb-4">{{ __('Información del estudio') }}</h1>
    <div class="grid grid-cols-2 gap-4 mb-10">
        @isset($tool->radiography)
            <div class="flex gap-2"><h3 class="title4">{{ __('ID del estudio') }}:</h3><p class="txt">{{ $tool->tool_radiography_id }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Fecha') }}:</h3><p class="txt">{{ $tool->radiography->radiography_date }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Tipo de estudio') }}:</h3><p class="txt">{{ $tool->radiography->radiography_type }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Doctor') }}:</h3><p class="txt">{{ $tool->radiography->radiography_doctor }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Radiologo') }}:</h3><p class="txt">{{ $tool->radiography->radiography_charge }}</p></div>
        @elseif(isset($tool->tomography))
            <div class="flex gap-2"><h3 class="title4">{{ __('ID del estudio') }}:</h3><p class="txt">{{ $tool->tool_tomography_id }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Fecha') }}:</h3><p class="txt">{{ $tool->tomography->tomography_date }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Tipo de estudio') }}:</h3><p class="txt">{{ $tool->tomography->tomography_type }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Doctor') }}:</h3><p class="txt">{{ $tool->tomography->tomography_doctor }}</p></div>
            <div class="flex gap-2"><h3 class="title4">{{ __('Radiologo') }}:</h3><p class="txt">{{ $tool->tomography->tomography_charge }}</p></div>
        @else
            <p class="txt">{{ __('No se encontro información del estudio') }}.</p>
        @endisset
    </div>
</div>

<script>
    let zoomLevel = 1;
    let initialPosition = { left: '50%', top: '50%' };
    let isDragging = false;
    let startX, startY, initialMouseX, initialMouseY;
    let isNegative = false;
    let sharpnessLevel = 1;
    let isMagnifierActive = false;
    let isEdgeDetectionActive = false;

    const img = document.getElementById('radiographyImage');
    const magnifierLens = document.getElementById('magnifierLens');
    const zoomInButton = document.getElementById('zoomIn');
    const zoomOutButton = document.getElementById('zoomOut');

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
    //Descarga
    const downloadButton = document.getElementById('downloadImage');
    const radiographyImage = document.getElementById('radiographyImage');

    downloadButton.addEventListener('click', () => {
        const imageUrl = radiographyImage.src;
        const link = document.createElement('a');
        link.href = imageUrl;
        link.download = 'Estudio_del_paciente.png';
        link.click();
        link.remove();
    });

</script>
@endsection