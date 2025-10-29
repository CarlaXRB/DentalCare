@extends('layouts._partials.layout')
@section('title','Radiografías')
@section('subtitle')
    {{ __('Radiografías') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1 max-w-6xl mx-auto">
    <!-- Search bar -->
    {{-- Se usa POST porque así está en el template, aunque GET es más común para búsquedas --}}
    <form method="POST" action="{{ route('radiography.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar radiografía por nombre, CI o ID...') }}"
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 w-80"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>

    <!-- Menu button -->
    <a href="{{ route('files.select') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center mb-6">{{ __('Lista de Radiografías') }}</h1>

<!-- Radiographies table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-6 text-gray-900 shadow-2xl">
    @if(session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
            {{ session('status') }}
        </div>
    @endif
    
    <!-- Table header -->
    <div class="grid grid-cols-6 gap-4 border-b border-gray-300 pb-3 mb-3 font-semibold text-sm uppercase">
        <h3 class="title4 text-center">{{ __('Vista previa') }}</h3>
        <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
        <h3 class="title4 text-center">{{ __('C.I.') }}</h3>
        <h3 class="title4 text-center">{{ __('Fecha') }}</h3>
        <h3 class="title4 text-center">{{ __('ID del estudio') }}</h3>
        <h3 class="title4 text-center">{{ __('Tipo') }}</h3>
    </div>

    <!-- Table body -->
    {{-- Asumo que la variable $radiographies es un array de objetos con las propiedades requeridas --}}
    @forelse($radiographies as $radiography)
    <div class="grid grid-cols-6 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        
        <!-- Preview -->
        <div class="flex justify-center">
            <a href="{{ route('radiography.show', $radiography->id) }}">
                {{-- Asegúrate que 'storage/radiographies/' sea el path correcto a tu archivo --}}
                <img src="{{ asset('storage/radiographies/'.$radiography->radiography_uri) }}" 
                     alt="Radiography preview" 
                     class="rounded-lg shadow-md w-24 h-20 object-cover border border-gray-100 transition transform hover:scale-105"/>
            </a>
        </div>

        <!-- Patient name -->
        <div class="text-center">
            <a href="{{ route('radiography.show', $radiography->id) }}" class="txt hover:text-cyan-600 font-medium">
                {{ $radiography->name_patient }}
            </a>
        </div>

        <!-- CI -->
        <div class="text-center">
            <a href="{{ route('radiography.show', $radiography->id) }}" class="txt hover:text-cyan-600">
                {{ $radiography->ci_patient }}
            </a>
        </div>

        <!-- Date -->
        <div class="text-center">
            <a href="{{ route('radiography.show', $radiography->id) }}" class="txt hover:text-cyan-600">
                {{ \Carbon\Carbon::parse($radiography->radiography_date)->format('d/m/Y') }}
            </a>
        </div>

        <!-- Radiography ID -->
        <div class="text-center text-sm font-mono">
            <a href="{{ route('radiography.show', $radiography->id) }}" class="txt hover:text-cyan-600">
                {{ $radiography->radiography_id }}
            </a>
        </div>

        <!-- Radiography Type -->
        <div class="text-center">
            <a href="{{ route('radiography.show', $radiography->id) }}" class="txt hover:text-cyan-600">
                {{ $radiography->radiography_type }}
            </a>
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-8 text-lg">{{ __('No hay radiografías registradas aún.') }}</p>
    @endforelse
    
    {{-- Paginación (si aplica) --}}
    @if(isset($radiographies) && method_exists($radiographies, 'links'))
    <div class="mt-4">
        {{ $radiographies->links() }}
    </div>
    @endif
</div>
@endsection
