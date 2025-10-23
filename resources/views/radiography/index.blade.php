@extends('layouts._partials.layout')
@section('title','Radiografías')
@section('subtitle')
    {{ __('Radiografías') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search bar -->
    <form method="POST" action="{{ route('radiography.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar radiografía...') }}"
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>

    <!-- Menu button -->
    <a href="{{ route('radiography.new') }}" class="botton1">{{ __('Menú de Radiografías') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Lista de Radiografías') }}</h1>

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
    @forelse($radiographies as $radiography)
    <div class="grid grid-cols-6 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Preview -->
        <div class="flex justify-center">
            <a href="{{ route('radiography.show', $radiography->id) }}">
                <img src="{{ asset('storage/radiographies/'.$radiography->radiography_uri) }}" 
                     alt="Radiography preview" 
                     class="rounded-lg shadow-md w-32 h-auto object-cover"/>
            </a>
        </div>

        <!-- Patient name -->
        <div class="text-center">
            <a href="{{ route('radiography.show', $radiography->id) }}" class="txt hover:text-cyan-600">
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
                {{ $radiography->radiography_date }}
            </a>
        </div>

        <!-- Radiography ID -->
        <div class="text-center">
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
    <p class="text-gray-600 text-center py-4">{{ __('No hay radiografías registradas aún.') }}</p>
    @endforelse
</div>
@endsection
