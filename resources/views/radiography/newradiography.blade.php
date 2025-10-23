@extends('layouts._partials.layout')
@section('title', __('Subir Radiografía'))
@section('subtitle')
    {{ __('Radiografías') }}
@endsection
@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('files.select') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<h3 class="title1">{{ __('SELECCIONAR FORMATO') }}</h3>
<div class="flex flex-wrap" style="margin-left: 65px;">
    <a href="{{ route('dicom.uploadRadiography') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/radiography1.png') }}" width="150" height="150" alt="DICOM">
        <h5 class="mt-3">DICOM</h5>
        <p>{{ __('Formato estándar para imágenes médicas. Contiene información del paciente y del equipo.') }}</p>
    </a>
    <a href="{{ route('radiography.create') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/radiography2.png') }}" width="150" height="150" alt=".dcm">
        <h5 class="mt-3">.DCM</h5>
        <p>{{ __('Formato de imagen DICOM sin metadatos adicionales. Se utiliza para una visualización sencilla.') }}</p>
    </a>
    <a href="{{ route('radiography.create') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/radiography3.png') }}" width="150" height="150" alt="JPEG, PNG">
        <h5 class="mt-3">.JPEG / .PNG</h5>
        <p>{{ __('Formatos de imágenes comunes sin información médica incorporada.') }}</p>
    </a>
    <a href="{{ route('radiography.index') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/info.png') }}" width="150" height="150" alt="STUDIES">
        <h5 class="mt-3">{{ __('ESTUDIOS') }}</h5>
        <p>{{ __('Lista de radiografías cargadas') }}</p>
    </a>
</div>
@endsection
