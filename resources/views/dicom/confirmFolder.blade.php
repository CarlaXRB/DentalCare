@extends('layouts._partials.layout')
@section('title','Show Tomography')
@section('subtitle')
    {{ __('SERVER DICOM') }}
@endsection

@section('content')
<div class="flex justify-end">
    <a href="{{ route('dashboard') }}" class="botton1">Inicio</a>
</div>

<h1 class="txt-title2">ESTUDIO DICOM PROCESADO</h1>

<div class="mx-auto mb-3 px-8">
    <p class="text-[17px] p-5">
        La carpeta DICOM ha sido procesada correctamente.  
        Puede revisar las imágenes procesadas y los metadatos del estudio.
    </p>

    <div class="flex justify-center mb-4">
        <a href="{{ route('dicom.showFolderImages', $folderName) }}">
            <button class="botton3 mt-2 mb-8">Ver Imágenes Procesadas</button>
        </a>
    </div>
</div>
@endsection
