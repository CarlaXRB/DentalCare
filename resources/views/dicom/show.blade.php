@extends('layouts._partials.layout')
@section('title', __('DICOM Radiography'))
@section('subtitle')
    {{ __('DICOM') }}
@endsection
@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Home') }}</a>
</div>
<h1 class="title1 text-center mb-8">{{ __('STUDY INFORMATION') }}</h1>

    <div class="flex justify-center p-4">
        <img src="{{ asset($imageUrl) }}" alt="Imagen DICOM procesada" style="max-width: 100%; max-height: 500px;">
    </div>
    <h2 class="title2 mt-8 text-center">{{ __('Patient Information') }}</h2>

<div class="grid grid-cols-2 gap-6 px-8 text-black mt-5">
    <div class="flex pl-5 ml-5"><h2 class="title4">{{ __('Patient:') }}</h2><p class="txt ml-5">{{ $dicomData['patient_name'] }}</p></div>
    <div class="flex pl-5"><h2 class="title4">{{ __('Study ID:') }}</h2><p class="txt ml-5">{{ $dicomData['patient_id'] }}</p></div>
    <div class="flex pl-5 ml-5"><h2 class="title4">{{ __('Modality:') }}</h2><p class="txt ml-5">{{ $dicomData['modality'] }}</p></div>
    <div class="flex pl-5"><h2 class="title4">{{ __('Study Date:') }}</h2><p class="txt ml-5">{{ $dicomData['study_date'] }}</p></div>
</div>
<div class="flex justify-start mt-5 ml-12">
    <h1 class="txt"><strong>{{ __('To save the patient\'s study, select their record:') }}</strong></h1>
</div>
    <form method="POST" action="{{ route('dicom.saveradiography') }}" class="px-8">
    @csrf
    <div class="flex justify-center mt-5 ml-5 mb-5">
        <label class="title3 mr-4">{{ __('Patient:') }}</label>
        <select name="patient_id" class="form-select border-gray-300 text-black rounded-lg p-3 w-[75%] max-w-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">{{ __('-- Select a patient --') }}</option>
            @foreach($patients as $patient)
                <option value="{{ $patient->id }}" class="text-black" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name_patient }} - {{ __('ID:') }} {{ $patient->ci_patient }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex justify-end items-center">
        <p class="mr-4">{{ __('Patient not registered?') }}</p>
        <a href="{{ route('patient.create') }}" class="botton3">{{ __('Register Patient') }}</a>
    </div>
    <div class="flex justify-center"><button type="submit" class="botton2">{{ __('Save Study') }}</button></div>
</form>
<h3 class="title2 mt-10 mb-3 text-center">{{ __('Complete Metadata:') }}</h3>

<pre class="bg-white text-black p-4 rounded-lg mx-8 mb-10 whitespace-pre-wrap break-words text-sm shadow-sm">
{{ json_encode($dicomData['dicom_info'], JSON_PRETTY_PRINT) }}
</pre>
@endsection
