@extends('layouts._partials.layout')
@section('title', __('Ver Estudio Multimedia'))
@section('subtitle')
{{ __('Ver Estudio Multimedia') }}
@endsection

@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('multimedia.index') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">

    <div class="mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información del Estudio') }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5 text-black dark:text-white">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('C.I. del Paciente:') }}</h3>
            <span class="txt">{{ $study->ci_patient }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Código de Estudio:') }}</h3>
            <span class="txt">{{ $study->study_code }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Fecha del Estudio:') }}</h3>
            <span class="txt">{{ $study->study_date }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Tipo de Estudio:') }}</h3>
            <span class="txt">{{ $study->study_type }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Cantidad de Imágenes:') }}</h3>
            <span class="txt">{{ $study->image_count }}</span>
        </div>
    </div>

    {{-- Descripción --}}
    @if($study->description)
    <div class="mb-6">
        <h3 class="title4">{{ __('Descripción:') }}</h3>
        <p class="txt">{{ $study->description }}</p>
    </div>
    @endif

    {{-- Imágenes --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-8">
        @forelse($imageUrls as $img)
            <div class="flex justify-center">
                <img src="{{ $img }}" alt="Imagen del estudio" class="rounded-lg shadow-lg max-h-64 object-cover" />
            </div>
        @empty
            <p class="text-gray-500 text-center col-span-full">{{ __('No hay imágenes disponibles para este estudio.') }}</p>
        @endforelse
    </div>

    {{-- Botón eliminar --}}
    <div class="flex justify-end mt-6">
        <form method="POST" action="{{ route('multimedia.destroy', $study->id) }}"
              onsubmit="return confirm('{{ __('¿Seguro que deseas eliminar este estudio?') }}');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bottonDelete">{{ __('Eliminar Estudio') }}</button>
        </form>
    </div>
</div>
@endsection
