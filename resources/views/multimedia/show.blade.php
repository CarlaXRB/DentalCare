@extends('layouts._partials.layout')
@section('title', __('Ver Estudio'))
@section('subtitle')
{{ __('Ver Estudio') }}
@endsection
@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Inicio') }}</a>
</div>

<!-- Contenedor principal -->
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">

    <div class="mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información del Estudio') }}</h1>
    </div>

    <!-- Información general de la radiografía -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5 text-black dark:text-white">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Paciente:') }}</h3>
            <span class="txt">{{ $file->name_patient }}</span>
            @if($file->patient)
            <a href="{{ route('patient.show', $file->patient->id ) }}" class="txt text-green-500 hover:text-green-700 hover:font-bold pl-12">{{ __('Ver Paciente') }}</a>
            @else
            <p class="text-red-500 mb-3">{{ __('Paciente no registrado en la base de datos.') }}</p>
            @endif
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('ID de la radiografía:') }}</h3><span class="txt">{{ $file->radiography_id }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Fecha del estudio:') }}</h3><span class="txt">{{ $file->radiography_id }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Tipo de radiografía:') }}</h3><span class="txt">{{ $file->radiography_id}}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Doctor:') }}</h3><span class="txt">{{ $file->radiography_id}}</span>
        </div>
    </div>

    <!-- Generar reporte -->
    <div class="flex items-center space-y-4 ml-0 mb-6">
        <div class="title4 mb-5">{{ __('Generar Resporte:') }}</div>
        <div class="group relative ml-5">
            <button id="report" class="btnimg" onclick="window.location.href='{{ route('report.form', ['type'=>'radiography','id'=>$file->id, 'name'=>$file->name_patient,'ci'=>$file->ci_patient]) }}'">
                <img src="{{ asset('assets/images/report.png') }}" width="50" height="50">
            </button>
            <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1">
                <span class="text-sm text-gray-800">{{ __('Reporte') }}</span>
            </div>
        </div>
    </div>

    <div class="flex justify-center mt-[30px] mb-[30px] bg-gray-50 p-4 rounded-xl shadow-inner">
    <img src="{{ asset('storage/multimedia/'.$file->file_path) }}" 
         alt="Imagen del estudio: {{ $file->study_type }}" 
         style="max-width:90%; max-height: 80vh; width: auto; height: auto; object-fit: contain; border-radius: 8px;"
         class="shadow-xl border-4 border-white transform hover:scale-[1.01] transition-transform duration-300"
    />
</div>
    <!-- Herramientas -->
    <div class="flex justify-center">
        <a href="{{ route('radiography.tool', $file->id) }}" class="botton2">{{ __('Herramientas') }}</a>
    </div>

    <!-- Acciones Editar / Eliminar -->
    <div class="flex justify-end pl-3">
        <a href="{{ route('radiography.edit', $file->id ) }}" class="botton3">{{ __('Editar') }}</a>
        @auth
        @if(Auth::user()->role === 'admin')
        <form method="POST" action="{{ route('multimedia.destroy', $file->id) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this study?') }}');">
            @csrf
            @method('DELETE')
            <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete" />
        </form>
        @endif
        @endauth
    </div>
</div>
@endsection