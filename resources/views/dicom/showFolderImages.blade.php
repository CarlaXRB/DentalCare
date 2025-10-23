@extends('layouts._partials.layout')
@section('title', __('Tomografía DICOM'))
@section('subtitle')
    {{ __('Tomografía DICOM') }}
@endsection
@section('content')
@if ($dicomRecord)
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dicom.uploadTomography') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<div class="container mt-12 mb-12">
    <div class="mb-8">
        <h1 class="title1 text-center">{{ __('Imágenes DICOM') }}</h1>
    </div>

    <div class="flex justify-center">
        <img id="mainImage" src="{{ asset($images[0]) }}" alt="{{ __('Main DICOM Image') }}"
             class="rounded-lg shadow-md" style="width: 600px; height: auto;">

        <div id="thumbnailContainer"
             class="ml-6 mr-6 p-3 border border-gray-300 rounded-lg shadow-sm bg-white overflow-y-auto"
             style="width: 180px; max-height: 600px;">
            @foreach ($images as $index => $image)
                <div class="mb-3">
                    <img src="{{ asset($image) }}"
                         onclick="changeMainImage('{{ asset($image) }}', this)"
                         style="width: 100%; cursor: pointer; border: 2px solid {{ $index == 0 ? 'blue' : 'transparent' }}; border-radius: 8px;"
                         class="thumbnail">
                    <div class="text-xs text-gray-500 text-center mt-1">
                        {{ basename($image) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<h2 class="title2 text-center mt-10">{{ __('Información del Paciente') }}</h2>

<div class="grid grid-cols-2 gap-6 px-8 text-black mt-5">
    <div class="flex pl-5 ml-5"><h2 class="title4">{{ __('Paciente:') }}</h2><p class="txt ml-5">{{ $dicomRecord['patient_name'] }}</p></div>
    <div class="flex pl-5"><h2 class="title4">{{ __('ID de la tomografía:') }}</h2><p class="txt ml-5">{{ $dicomRecord['patient_id'] }}</p></div>
    <div class="flex pl-5 ml-5"><h2 class="title4">{{ __('Modalidad:') }}</h2><p class="txt ml-5">{{ $dicomRecord['modality'] }}</p></div>
    <div class="flex pl-5"><h2 class="title4">{{ __('Fecha del estudio:') }}</h2><p class="txt ml-5">{{ $dicomRecord['study_date'] }}</p></div>
</div>

<div class="flex justify-start mt-8 ml-12 mb-3">
    <h1 class="txt"><strong>{{ __('Para guardar el estudio del paciente, seleccione su registro:') }}</strong></h1>
</div>

<form method="POST" action="{{ route('dicom.savetomography') }}" class="px-8">
    @csrf
    <div class="flex justify-center mt-5 mb-5">
        <label class="title3 mr-4">{{ __('Paciente:') }}</label>
        <select name="patient_id"
            class="form-select border-gray-300 text-black rounded-lg p-3 w-[75%] max-w-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">{{ __('-- Seleccionar un paciente --') }}</option>
            @foreach($patients as $patient)
                <option value="{{ $patient->id }}" class="text-black" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name_patient }} - {{ __('ID:') }} {{ $patient->ci_patient }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex justify-end items-center">
        <p class="mr-4">{{ __('¿Paciente no registrado?') }}</p>
        <a href="{{ route('patient.create') }}" class="botton3">{{ __('Registrar Paciente') }}</a>
    </div>

    <div class="flex justify-center">
        <button type="submit" class="botton2">{{ __('Guardar Estudio') }}</button>
    </div>
</form>

<h3 class="title2 mt-10 mb-3 text-center">{{ __('Completar Metadatos:') }}</h3>

<pre class="bg-white text-black p-4 rounded-lg mx-8 mb-10 whitespace-pre-wrap break-words text-sm shadow-sm">
{{ json_encode(json_decode($dicomRecord->metadata, true), JSON_PRETTY_PRINT) }}
</pre>

@else
<p class="ml-10 text-lg">{{ __('No se encontraron datos de pacientes para esta carpeta.') }}</p>
@endif

<script>
    function changeMainImage(imageSrc, thumbElement) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = imageSrc;
        document.querySelectorAll('.thumbnail').forEach(img => {
            img.style.border = '2px solid transparent';
        });
        thumbElement.style.border = '2px solid blue';
    }
</script>
@endsection
