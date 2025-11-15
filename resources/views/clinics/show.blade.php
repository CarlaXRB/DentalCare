@extends('layouts._partials.layout')
@section('title', __('Información de la Clínica'))
@section('subtitle', __('Información de la Clínica'))
@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('clinics.index') }}" class="botton1">{{ __('Clínicas') }}</a>
</div>
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">
    <div class="mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información de la Clínica') }}</h1>
    </div>
    @isset($clinic)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5 text-black dark:text-white">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Nombre:') }}</h3><span class="txt">{{ $clinic->name }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Dirección:') }}</h3><span class="txt">{{ $clinic->address ?? __('No disponible') }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Teléfono:') }}</h3><span class="txt">{{ $clinic->phone ?? __('No disponible') }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Número de Salas:') }}</h3><span class="txt">{{ $clinic->rooms_count ?? __('No disponible') }}</span>
        </div>
        <div class="flex gap-2 items-center">
            <h3 class="title4">{{ __('Logo:') }}</h3>
            @if($clinic->logo)
                <img src="{{ asset($clinic->logo) }}"
                     alt="{{ $clinic->name }}"
                     class="h-24 w-24 object-contain rounded shadow" />
            @else
                <span class="txt">{{ __('No disponible') }}</span>
            @endif

        </div>
    </div>
    <div class="flex justify-center mt-8 gap-4">
        <a href="{{ route('clinics.edit', $clinic->id) }}" class="botton3">{{ __('Editar') }}</a>
        <form method="POST" 
              action="{{ route('clinics.destroy', $clinic->id) }}" 
              onsubmit="return confirm('{{ __('¿Estás seguro de que quieres eliminar esta clínica?') }}');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bottonDelete cursor-pointer">{{ __('Eliminar') }}</button>
        </form>
    </div>
    @else
        <p class="text-center text-gray-500">{{ __('La información de la clínica no está disponible.') }}</p>
    @endisset
</div>
@endsection
