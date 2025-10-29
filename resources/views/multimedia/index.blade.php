@extends('layouts._partials.layout')
@section('title','Estudios')
@section('subtitle')
    {{ __('Estudios') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search bar -->
    <form method="POST" action="{{ route('multimedia.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar estudio...') }}"
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>

    <!-- Menu button -->
    <a href="{{ route('files.select') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Lista de estudios') }}</h1>

<!-- Radiographies table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <!-- Table header -->
    <div class="grid grid-cols-6 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Vista previa') }}</h3>
        <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
        <h3 class="title4 text-center">{{ __('C.I.') }}</h3>
        <h3 class="title4 text-center">{{ __('Fecha') }}</h3>
        <h3 class="title4 text-center">{{ __('ID del estudio') }}</h3>
        <h3 class="title4 text-center">{{ __('Tipo') }}</h3>
    </div>

    <!-- Table body -->
    @forelse($multimediaFiles as $file)
    <div class="grid grid-cols-6 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Preview -->
        <div class="flex justify-center">
    {{-- 1. Cambiar la variable de $radiography a $file --}}
    <a href="{{ route('multimedia.show', $file->id) }}">
        {{-- 2. Cambiar la ruta del asset: usar 'multimedia' y el campo 'file_path' --}}
        <img src="{{ asset('storage/multimedia/'.$file->file_path) }}" 
             alt="Vista previa de {{ $file->study_type }}" 
             {{-- Asegúrate de usar las clases de la nueva vista (w-24 h-20) --}}
             class="rounded-lg shadow-md w-24 h-20 object-cover border border-gray-100"
             {{-- 3. Agregar un fallback en caso de que la imagen no cargue (¡CRÍTICO!) --}}
             onerror="this.onerror=null;this.src='https://placehold.co/100x70/E0F2F7/4A5568?text=NO+IMG';"
        />
    </a>
</div>

        <!-- Patient name -->
        <div class="text-center">
            <a href="{{ route('multimedia.show', $file->id) }}" class="txt hover:text-cyan-600">
                {{ $multimedia->name_patient }}
            </a>
        </div>

        <!-- CI -->
        <div class="text-center">
            <a href="{{ route('multimedia.show', $file->id) }}" class="txt hover:text-cyan-600">
                {{ $multimedia->ci_patient }}
            </a>
        </div>

        <!-- Date -->
        <div class="text-center">
            <a href="{{ route('multimedia.show', $file->id) }}" class="txt hover:text-cyan-600">
                {{ $multimedia->radiography_date }}
            </a>
        </div>

        <!-- Radiography ID -->
        <div class="text-center">
            <a href="{{ route('multimedia.show', $file->id) }}" class="txt hover:text-cyan-600">
                {{ $multimedia->file_type }}
            </a>
        </div>

        <!-- Radiography Type -->
        <div class="text-center">
            <a href="{{ route('multimedia.show', $file->id) }}" class="txt hover:text-cyan-600">
                {{ $multimedia->study_type }}
            </a>
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('No hay radiografías registradas aún.') }}</p>
    @endforelse
</div>
Estudios