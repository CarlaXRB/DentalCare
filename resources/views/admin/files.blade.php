@extends('layouts._partials.layout')
@section('title', __('Subir archivos'))
@section('subtitle')
    {{ __('Archivos') }}
@endsection
@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Inicio') }}</a>
</div>

<h3 class="title1 text-center">{{ __('SELECCIONA EL TIPO DE ARCHIVO') }}</h3>

<div class="flex justify-center items-center flex-wrap gap-11 mb-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

        <!-- Radiographies -->
        <a href="{{ route('radiography.new') }}"
           class="flex flex-col items-center justify-center p-5 bg-white rounded-3xl shadow-lg hover:shadow-xl transition transform hover:scale-105 duration-300 min-w-[320px] min-h-[320px]">
            <img src="{{ asset('storage/assets/images/rx.png') }}" alt="Radiographies"
                 class="w-[180px] h-[180px] object-contain mb-4">
            <h5 class="text-2xl font-semibold text-blue-800">{{ __('RADIOGRAFÍAS') }}</h5>
        </a>

        <!-- Tomographies -->
        <a href="{{ route('tomography.new') }}"
           class="flex flex-col items-center justify-center p-5 bg-white rounded-3xl shadow-lg hover:shadow-xl transition transform hover:scale-105 duration-300 min-w-[300px] min-h-[320px]">
            <img src="{{ asset('storage/assets/images/ct.png') }}" alt="Tomographies"
                 class="w-[180px] h-[180px] object-contain mb-4">
            <h5 class="text-2xl font-semibold text-blue-800">{{ __('TOMOGRAFÍAS') }}</h5>
        </a>

    </div>
</div>
@endsection
