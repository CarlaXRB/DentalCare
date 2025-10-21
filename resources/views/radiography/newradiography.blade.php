@extends('layouts._partials.layout')
@section('title', __('Create Radiography'))
@section('subtitle')
    {{ __('Radiographies') }}
@endsection
@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('files.select') }}" class="botton1">{{ __('Back') }}</a>
</div>

<h3 class="title1">{{ __('SELECT FORMAT') }}</h3>
<div class="flex flex-wrap" style="margin-left: 65px;">
    <a href="{{ route('dicom.uploadRadiography') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/radiography1.png') }}" width="150" height="150" alt="DICOM">
        <h5 class="mt-3">DICOM</h5>
        <p>{{ __('Standard format for medical images. Contains patient and equipment information') }}</p>
    </a>
    <a href="{{ route('radiography.create') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/radiography2.png') }}" width="150" height="150" alt=".dcm">
        <h5 class="mt-3">.DCM</h5>
        <p>{{ __('DICOM image format without additional metadata. Used for simple viewing') }}</p>
    </a>
    <a href="{{ route('radiography.create') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/radiography3.png') }}" width="150" height="150" alt="JPEG, PNG">
        <h5 class="mt-3">.JPEG / .PNG</h5>
        <p>{{ __('Common image formats without embedded medical information') }}</p>
    </a>
    <a href="{{ route('radiography.index') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/info.png') }}" width="150" height="150" alt="STUDIES">
        <h5 class="mt-3">{{ __('STUDIES') }}</h5>
        <p>{{ __('List of uploaded radiographs') }}</p>
    </a>
</div>
@endsection
