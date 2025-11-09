@extends('layouts._partials.layout')
@section('title','Estudios RX')
@section('subtitle')
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
    <a href="{{ route('multimedia.create') }}" class="botton1">{{ __('Subir Estudio') }}</a>
</div>

<h1 class="title1 text-center">{{ __('Lista de Estudios Multimedia') }}</h1>

<div class="max-w-6xl mx-auto bg-white rounded-xl p-4 text-gray-900 shadow-md">
    <div class="grid grid-cols-6 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Fecha') }}</h3>
        <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
        <h3 class="title4 text-center">{{ __('C.I. Paciente') }}</h3>
        <h3 class="title4 text-center">{{ __('Código') }}</h3>
        <h3 class="title4 text-center">{{ __('Tipo') }}</h3>
    </div>

    @forelse($studies as $study)
    <div class="grid grid-cols-6 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <div class="txt hover:text-cyan-600"><a href="{{ route('multimedia.show', $study->id) }}">{{ $study->study_date }}</a></div>
        <div class="txt hover:text-cyan-600"><a href="{{ route('multimedia.show', $study->id) }}">{{ $study->name_patient }}</a></div>
        <div class="txt hover:text-cyan-600"><a href="{{ route('multimedia.show', $study->id) }}">{{ $study->ci_patient }}</a></div>
        <div class="txt hover:text-cyan-600"><a href="{{ route('multimedia.show', $study->id) }}">{{ $study->study_code }}</a></div>
        <div class="txt hover:text-cyan-600"><a href="{{ route('multimedia.show', $study->id) }}">{{ $study->study_type }}</a></div>
        <div class="flex justify-center gap-2 p-1">
            <a href="{{ route('multimedia.edit', $study->id) }}" class="botton3">{{ __('Editar') }}</a>
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