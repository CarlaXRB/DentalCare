@extends('layouts._partials.layout')
@section('title','Estudios Multimedia')
@section('subtitle')
    {{ __('Estudios Multimedia') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Inicio') }}</a>
    <a href="{{ route('multimedia.create') }}" class="botton2">{{ __('Nuevo Estudio') }}</a>
</div>

<h1 class="title1 text-center">{{ __('Lista de Estudios Multimedia') }}</h1>

<div class="max-w-6xl mx-auto bg-white rounded-xl p-4 text-gray-900 shadow-md">
    <div class="grid grid-cols-6 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Vista Previa') }}</h3>
        <h3 class="title4 text-center">{{ __('C.I. Paciente') }}</h3>
        <h3 class="title4 text-center">{{ __('Código de Estudio') }}</h3>
        <h3 class="title4 text-center">{{ __('Fecha') }}</h3>
        <h3 class="title4 text-center">{{ __('Tipo') }}</h3>
        <h3 class="title4 text-center">{{ __('Acciones') }}</h3>
    </div>

    @forelse($studies as $study)
    <div class="grid grid-cols-6 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        {{-- Preview --}}
        <div class="flex justify-center">
            @php
                $files = glob(public_path($study->study_uri . '/*.{png,jpg,jpeg}'), GLOB_BRACE);
                $preview = isset($files[0]) ? asset(str_replace(public_path(), '', $files[0])) : asset('assets/images/no-image.png');
            @endphp
            <a href="{{ route('multimedia.show', $study->id) }}">
                <img src="{{ $preview }}" class="rounded-lg shadow-md w-28 h-28 object-cover" alt="preview"/>
            </a>
        </div>

        {{-- CI Paciente --}}
        <div class="text-center">{{ $study->ci_patient }}</div>

        {{-- Código --}}
        <div class="text-center">{{ $study->study_code }}</div>

        {{-- Fecha --}}
        <div class="text-center">{{ $study->study_date }}</div>

        {{-- Tipo --}}
        <div class="text-center">{{ $study->study_type }}</div>

        {{-- Acciones --}}
        <div class="flex justify-center gap-2">
            <a href="{{ route('multimedia.show', $study->id) }}" class="botton3">{{ __('Ver') }}</a>
            <form method="POST" action="{{ route('multimedia.destroy', $study->id) }}"
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
