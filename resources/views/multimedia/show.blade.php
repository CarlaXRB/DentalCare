@extends('layouts._partials.layout')
@section('title', __('Ver Archivo Multimedia'))
@section('subtitle')
{{ __('Ver Archivo Multimedia') }}
@endsection
@section('content')
<div class="flex justify-end pt-5 pr-5 max-w-5xl mx-auto">
    {{-- Volver al listado multimedia --}}
    <a href="{{ route('multimedia.index') }}" class="botton1">{{ __('Volver a la Lista') }}</a>
</div>

<!-- Contenedor principal -->
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 shadow-2xl">

    <div class="mb-5 border-b pb-4">
        <h1 class="title1 text-center pb-2">{{ __('Detalle del Archivo Multimedia') }}</h1>
    </div>

    <!-- Información general del archivo -->
    {{-- La variable $file debe contener el objeto del archivo multimedia --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-8 text-black">
        <div class="flex gap-2 items-start">
            <h3 class="title4 font-bold">{{ __('Paciente:') }}</h3>
            <span class="txt">{{ $file->name_patient }}</span>
            @if(isset($file->patient))
            {{-- Enlace a la vista del paciente (asumiendo que $file tiene una relación patient) --}}
            <a href="{{ route('patient.show', $file->patient->id ) }}" class="txt text-green-500 hover:text-green-700 hover:font-bold pl-12 transition">{{ __('Ver Paciente') }} <i class="fas fa-external-link-alt ml-1"></i></a>
            @else
            <p class="text-red-500 mb-3 ml-2 text-sm">{{ __('Paciente no registrado.') }}</p>
            @endif
        </div>
        <div class="flex gap-2">
            <h3 class="title4 font-bold">{{ __('Nombre de Archivo:') }}</h3><span class="txt font-mono">{{ $file->file_name }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4 font-bold">{{ __('Tipo MIME:') }}</h3><span class="txt">{{ $file->file_type }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4 font-bold">{{ __('Tipo de Estudio:') }}</h3><span class="txt">{{ $file->study_type }}</span>
        </div>
        <div class="flex gap-2 md:col-span-2">
            <h3 class="title4 font-bold">{{ __('Notas:') }}</h3><span class="txt italic">{{ $file->notes ?? 'N/A' }}</span>
        </div>
    </div>

    <!-- Generar reporte y Herramientas -->
    <div class="flex items-center space-x-6 justify-center pb-8 border-b">
        <div class="title4">{{ __('Acciones:') }}</div>
        
        <!-- Botón de Reporte (Adaptado a multimedia) -->
        <div class="group relative">
            {{-- La lógica para generar reporte debe manejar el tipo de estudio ($file->study_type) --}}
            <button id="report" class="btnimg p-2 rounded-full bg-blue-100 hover:bg-blue-200 transition" 
                onclick="window.location.href='{{ route('report.form', ['type'=>$file->study_type,'id'=>$file->id, 'name'=>$file->name_patient,'ci'=>$file->ci_patient]) }}'">
                {{-- Nota: Asumiendo que tienes una ruta 'report.form' y un asset 'assets/images/report.png' --}}
                <img src="{{ asset('assets/images/report.png') }}" alt="Reporte" width="40" height="40" class="w-10 h-10">
            </button>
            <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 bg-blue-500 text-white text-center rounded-md px-2 py-1 text-xs whitespace-nowrap">
                <span>{{ __('Generar Reporte') }}</span>
            </div>
        </div>

        <!-- Botón de Herramientas/Visor -->
        <div class="group relative">
             {{-- Asumo una ruta para abrir una herramienta de visualización o un visor DICOM/genérico si aplica --}}
             <a href="{{ route('multimedia.tool', $file->id) }}" class="botton2 px-6 py-2 transition duration-300">
                <i class="fas fa-eye mr-2"></i> {{ __('Visor de Archivos') }}
            </a>
            <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 bg-gray-700 text-white text-center rounded-md px-2 py-1 text-xs whitespace-nowrap">
                <span>{{ __('Abrir Herramientas de Visualización') }}</span>
            </div>
        </div>
    </div>

    <!-- Visualización del Archivo -->
    <div class="pt-8">
        <h2 class="title1 text-center mb-4 border-b pb-2">{{ __('Visualización') }}</h2>
        <div class="flex justify-center mt-5 mb-5 p-4 bg-gray-50 rounded-lg shadow-inner min-h-[300px] items-center">
            
            @php
                // Genera la URL del archivo
                $fileUrl = asset('storage/' . $file->file_path);
                $mimeType = $file->file_type;
            @endphp
            
            @if(Str::startsWith($mimeType, 'image/'))
                {{-- IMAGEN --}}
                <img src="{{ $fileUrl }}" 
                     alt="{{ $file->file_name }}"
                     class="shadow-xl rounded-lg"
                     style="max-width:90%; max-height: 80vh; object-fit:contain;"/>
                     
            @elseif(Str::startsWith($mimeType, 'video/'))
                {{-- VIDEO --}}
                <video controls class="w-full max-w-lg shadow-xl rounded-lg">
                    <source src="{{ $fileUrl }}" type="{{ $mimeType }}">
                    Tu navegador no soporta el tag de video.
                </video>
                
            @elseif(Str::startsWith($mimeType, 'application/pdf'))
                {{-- PDF --}}
                <div class="text-center">
                    <i class="fas fa-file-pdf text-red-600 text-6xl mb-4"></i>
                    <p class="text-gray-700 mb-3">{{ __('Haga clic para descargar o previsualizar el PDF.') }}</p>
                    <a href="{{ $fileUrl }}" target="_blank" class="botton2">{{ __('Abrir PDF') }}</a>
                </div>
                
            @else
                {{-- OTROS TIPOS DE ARCHIVO (e.g., ZIP, DOCX) --}}
                <div class="text-center">
                    <i class="fas fa-file-alt text-gray-500 text-6xl mb-4"></i>
                    <p class="text-gray-700 mb-3">{{ __('Tipo de archivo no visualizable directamente.') }}</p>
                    <a href="{{ $fileUrl }}" download class="botton2">{{ __('Descargar Archivo') }}</a>
                </div>
            @endif

        </div>
    </div>

    <!-- Acciones Editar / Eliminar -->
    <div class="flex justify-end pt-6 border-t mt-4 space-x-3">
        {{-- Editar --}}
        <a href="{{ route('multimedia.edit', $file->id ) }}" class="botton3">{{ __('Editar Información') }}</a>
        
        {{-- Eliminar (Solo Admin) --}}
        @auth
        @if(Auth::user()->role === 'admin')
        <form method="POST" action="{{ route('multimedia.destroy', $file->id) }}" 
              onsubmit="return confirm('{{ __('¿Está seguro que desea eliminar este archivo? Esta acción es irreversible.') }}');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bottonDelete">
                <i class="fas fa-trash-alt mr-1"></i> {{ __('Eliminar Archivo') }}
            </button>
        </form>
        @endif
        @endauth
    </div>
</div>
@endsection
