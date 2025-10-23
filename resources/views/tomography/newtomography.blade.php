@extends('layouts._partials.layout')
@section('title', __('Tomografías'))
@section('subtitle')
    {{ __('Tomografías') }}
@endsection

@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('files.select') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<h3 class="title1">{{ __('SELECCIONAR FORMATO') }}</h3>
<div class="flex flex-wrap" style="margin-left: 65px;">
    <a href="{{ route('dicom.uploadTomography') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/ct1.png') }}" width="150" height="150" alt="DICOM">
        <h5 class="mt-3">{{ __('CARPETA DICOM') }}</h5>
        <p>{{ __('Seleccione una carpeta que contenga varios archivos DICOM con metadatos.') }}</p>
    </a>
    <a href="{{ route('tomography.createdcm') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/ct2.png') }}" width="150" height="150" alt=".dcm">
        <h5 class="mt-3">{{ __('CARPETA .DCM') }}</h5>
        <p>{{ __('Seleccione una carpeta con archivos .dcm sin metadatos.') }}</p>
    </a>
    <a href="{{ route('tomography.create') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/ct3.png') }}" width="150" height="150" alt="JPEG, PNG">
        <h5 class="mt-3">{{ __('ARCHIVO COMPRIMIDO') }}</h5>
        <p>{{ __('Sube un archivo ZIP que contenga imágenes médicas de CT.') }}</p>
    </a>
    <a href="{{ route('tomography.index') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/info.png') }}" width="150" height="150" alt="STUDIES">
        <h5 class="mt-3">{{ __('ESTUDIOS') }}</h5>
        <p>{{ __('Lista de tomografías cargadas.') }}</p>
    </a>
</div>
@endsection
