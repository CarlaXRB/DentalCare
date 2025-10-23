@extends('layouts._partials.layout')

@section('title', __('Subir Radiografía DICOM'))
@section('subtitle')
    {{ __('Subir Radiografía DICOM') }}
@endsection

@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('radiography.new') }}" class="botton1">{{ __('Menú Radiografías') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center mb-5">{{ __('DICOM - Archivo con Metadatos') }}</h1>

<div class="max-w-4xl mx-auto bg-white rounded-xl p-6 shadow-md">
    <p class="text-gray-800 text-lg mb-5">
        {{ __('Puede cargar archivos DICOM aquí para su procesamiento y análisis. El sistema extraerá y mostrará los metadatos relevantes, lo que facilita la gestión de imágenes radiológicas.') }}
    </p>

    <!-- Upload form -->
    <form action="{{ route('process.dicom') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="flex justify-center">
            <input type="file" name="file" required 
                class="border border-blue-300 rounded-md p-3 w-full max-w-md"/>
        </div>

        @error('file')
        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
        @enderror

        <div class="flex justify-center p-5">
            <button type="submit" class="botton2">{{ __('Subir Archivo') }}</button>
        </div>
    </form>
</div>
@endsection
