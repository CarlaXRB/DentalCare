@extends('layouts._partials.layout')
@section('title','Estudios RX')
@section('subtifile')
{{ __('Estudios RX') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search bar -->
    <form method="POST" action="{{ route('multimedia.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar estudio...') }}"
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500" />
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>

    <!-- Menu button -->
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<h1 class="title1 text-center">{{ __('Resultados de Búsqueda') }}</h1>

<div class="max-w-6xl mx-auto bg-white rounded-xl p-4 text-gray-900 shadow-md">
    <div class="grid grid-cols-6 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Fecha') }}</h3>
        <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
        <h3 class="title4 text-center">{{ __('C.I. Paciente') }}</h3>
        <h3 class="title4 text-center">{{ __('Código') }}</h3>
        <h3 class="title4 text-center">{{ __('Tipo') }}</h3>
        <h3 class="title4 text-center">{{ __('Acciones') }}</h3> {{-- Añadido título para Acciones --}}
    </div>

    @forelse($files as $file)
    <div class="grid grid-cols-6 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        {{-- Fecha --}}
        <div class="text-center">{{ $file->study_date }}</div>
        {{-- Nombre del Paciente --}}
        {{-- CORRECCIÓN: Usar $file en lugar de $study --}}
        <div class="text-center">{{ $file->name_patient }}</div>
        {{-- CI Paciente --}}
        <div class="text-center">{{ $file->ci_patient }}</div>
        {{-- Código --}}
        <div class="text-center">{{ $file->study_code }}</div>
        {{-- Tipo --}}
        <div class="text-center">{{ $file->study_type }}</div>
        
        {{-- Acciones --}}
        <div class="flex justify-center gap-2">
            {{-- CORRECCIÓN: Usar $file en lugar de $study --}}
            <a href="{{ route('multimedia.show', $file->id) }}" class="botton2">{{ __('Ver') }}</a>
            <a href="{{ route('multimedia.edit', $file->id) }}" class="botton3">{{ __('Editar') }}</a>
        
            {{-- Formulario Eliminar --}}
            <form method="POST" action="{{ route('multimedia.destroy', $file->id) }}"
                onsubmit="return confirm('{{ __('¿Seguro que deseas eliminar este estudio?') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bottonDelete">{{ __('Eliminar') }}</button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('No hay estudios multimedia registrados aún.') }}</p>
    @endforelse
</div>
@endsection
