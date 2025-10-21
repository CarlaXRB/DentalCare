@extends('layouts._partials.layout')
@section('title','Report')
@section('subtitle')
{{ __('Report') }}
@endsection
@section('content')

<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Home') }}</a>
</div>

<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">

    <div class="mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Generate Report') }}</h1>
    </div>
    <form method="POST" action="{{ route('report.pdfreport') }}" enctype="multipart/form-data">
        @csrf

        <div class="text-gray-900 dark:text-white">
            <h1 class="title2">{{ __('Patient data') }}</h1>

            @if($patient)
            <div class="grid grid-cols-2 gap-4 text-gray-900 dark:text-white">
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Name patient') }}:</h3>
                    <p class="txt">{{ $patient->name_patient }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('C.I.') }}:</h3>
                    <p class="txt">{{ $patient->ci_patient }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Birthdate') }}:</h3>
                    <p class="txt">{{ $patient->birth_date }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Gender') }}:</h3>
                    <p class="txt">{{ $patient->gender }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Contact') }}{{ __('Study ID') }}:</h3>
                    <p class="txt">{{ $patient->patient_contact }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Family contact') }}:</h3>
                    <p class="txt">{{ $patient->family_contact }} </p>
                </div>
            </div>
            @else
            <div class="grid grid-cols-2 gap-4 text-gray-900 dark:text-white">
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Name patient') }}:</h3>
                    <p class="txt">{{ $name ?? 'N/A' }}</p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('C.I.') }}:</h3>
                    <p class="txt">{{ $ci ?? 'N/A' }}</p>
                </div>
            </div>
            <h1 class="flex justify-center text-red-500 mt-5 ml-10 mb-5">{{ __('Patient not registered in the database') }}.</h1>
            @endif
            <h1 class="title2">{{ __('Study data') }}</h1>
            <div class="grid grid-cols-2 gap-4 text-gray-900 dark:text-white">
                @if($studyType === 'radiography')
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study ID') }}:</h3>
                    <p class="txt">{{ $study->radiography_id }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study date') }}:</h3>
                    <p class="txt">{{ $study->radiography_date }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study type') }}:</h3>
                    <p class="txt">{{ $study->radiography_type }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Doctor') }}:</h3>
                    <p class="txt">{{ $study->radiography_doctor }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Radiologist') }}:</h3>
                    <p class="txt">{{ $study->radiography_charge }} </p>
                </div>
                @elseif($studyType === 'tomography')
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study ID') }}:</h3>
                    <p class="txt">{{ $study->tomography_id }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study date') }}:</h3>
                    <p class="txt">{{ $study->tomography_date }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study type') }}:</h3>
                    <p class="txt">{{ $study->tomography_type }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Doctor') }}:</h3>
                    <p class="txt">{{ $study->tomography_doctor }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Radiologist') }}:</h3>
                    <p class="txt">{{ $study->tomography_charge }} </p>
                </div>
                @elseif($studyType === 'tool')
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study ID') }}:</h3>
                    <p class="txt">{{ $study->radiography->radiography_id ?? $study->tomography->tomography_id }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study date') }}:</h3>
                    <p class="txt">{{ $study->tool_date }} </p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Study type') }}:</h3>
                    <p class="txt"> {{ $study->radiography->radiography_type ?? $study->tomography->tomography_type }}</p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Doctor') }}:</h3>
                    <p class="txt">{{ $study->radiography->radiography_doctor ?? $study->tomography->tomography_doctor }}</p>
                </div>
                <div class="flex gap-2">
                    <h3 class="title4">{{ __('Radiologist') }}:</h3>
                    <p class="txt">{{ $study->radiography->radiography_charge ?? $study->tomography->tomography_charge }} </p>
                </div>
                @endif
            </div>
            <div class="flex justify-center mb-4">
                <button id="showImageBtn" type="button" class="botton2">{{ __('Show') }}</button>
            </div>
            <div id="imageContainer" class="flex justify-center mb-4" style="display: none;">
                @php
                $imageUri = '';
                if ($studyType === 'radiography') {
                $imageUri = $study->radiography_uri ?? '';
                } elseif ($studyType === 'tomography') {
                // Corregido aquí para usar $selectedImage
                $imageUri = $selectedImage ?? '';
                } elseif ($studyType === 'tool') {
                $imageUri = $study->tool_uri ?? '';
                }
                @endphp

                @if($imageUri)
                <img src="{{ $studyType === 'tomography' 
        ? asset('storage/tomographies/converted_images/' . $study->id . '/' . $imageUri)
        : asset('storage/' . ($studyType === 'radiography' ? 'radiographies/' : 'tools/') . $imageUri) }}"
                    alt="Imagen del estudio" class="max-w-full h-auto">
                @else
                <p>No hay imagen disponible.</p>
                @endif

            </div>

            <div class="grid grid-cols-5 gap-4 items-start mb-4">
                <label class="title4 col-span-1 pt-2">{{ __('Findings') }}:</label>
                <textarea name="findings" class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500 col-span-4" rows="3">{{ old('findings') }}</textarea>
            </div>
            <div class="grid grid-cols-5 gap-4 items-start mb-4">
                <label class="title4 col-span-1 pt-2">{{ __('Diagnostic Impression') }}:</label>
                <textarea name="diagnosis" class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500 col-span-4" rows="3">{{ old('diagnosis') }}</textarea>
            </div>
            <div class="grid grid-cols-5 gap-4 items-start mb-4">
                <label class="title4 col-span-1 pt-2">{{ __('Conclusions') }}:</label>
                <textarea name="conclusions" class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500 col-span-4" rows="3">{{ old('conclusions') }}</textarea>
            </div>
            <div class="grid grid-cols-5 gap-4 items-start mb-4">
                <label class="title4">{{ __('Recomendaciones') }}:</label>
                <textarea name="recommendations" class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500 col-span-4" rows="3">{{ old('recommendations') }}</textarea>
            </div>

            <input type="hidden" name="study_id" value="{{ $study->id }}">
            <input type="hidden" name="study_type" value="{{ $studyType }}">
            <input type="hidden" name="selected_image" id="selected_image_input" value="{{ $selectedImage }}"> {{-- input oculto para enviar la imagen seleccionada --}}

            <div class="flex justify-center mb-4">
                <button type="submit" class="botton3">{{ __('Download PDF') }}</button>
            </div>

        </div>
    </form>
</div>

<script>
    document.getElementById('showImageBtn').addEventListener('click', function() {
        var imageContainer = document.getElementById('imageContainer');
        if (imageContainer.style.display === 'none') {
            imageContainer.style.display = 'flex';
        } else {
            imageContainer.style.display = 'none';
        }
    });

    function changeImage(index) {
        if (index >= 0 && index < totalImages) {
            $(`#image-${currentIndex}`).hide();
            $(`#image-${index}`).fadeIn(100);
            $('#image-name').text(images[index]);
            $('#selected_image').val(images[index]);
            currentIndex = index;
        }
    }
</script>

@endsection