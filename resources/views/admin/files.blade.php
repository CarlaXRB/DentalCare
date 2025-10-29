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

    <a href="{{ route('radiography.create') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('assets/images/radiography3.png') }}" width="150" height="150" alt="JPEG, PNG">
        <h5 class="mt-3">.JPEG / .PNG</h5>
        <p>{{ __('Formatos de imágenes comunes sin información médica incorporada.') }}</p>
    </a>
    <a href="{{ route('radiography.index') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('assets/images/info.png') }}" width="150" height="150" alt="STUDIES">
        <h5 class="mt-3">{{ __('ESTUDIOS') }}</h5>
        <p>{{ __('Lista de radiografías cargadas') }}</p>
    </a>
        <a href="{{ route('tomography.create') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('assets/images/ct3.png') }}" width="150" height="150" alt="JPEG, PNG">
        <h5 class="mt-3">{{ __('ARCHIVO COMPRIMIDO') }}</h5>
        <p>{{ __('Sube un archivo ZIP que contenga imágenes médicas de CT.') }}</p>
    </a>
    <a href="{{ route('tomography.index') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('assets/images/info.png') }}" width="150" height="150" alt="STUDIES">
        <h5 class="mt-3">{{ __('ESTUDIOS') }}</h5>
        <p>{{ __('Lista de tomografías cargadas.') }}</p>
    </a>

    </div>
</div>
@endsection
