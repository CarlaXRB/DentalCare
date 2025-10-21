@extends('layouts._partials.layout')
@section('title', __('Create Tomography'))
@section('subtitle')
    {{ __('Tomographies') }}
@endsection

@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('files.select') }}" class="botton1">{{ __('Back') }}</a>
</div>

<h3 class="title1">{{ __('SELECT FORMAT') }}</h3>
<div class="flex flex-wrap" style="margin-left: 65px;">
    <a href="{{ route('dicom.uploadTomography') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/ct1.png') }}" width="150" height="150" alt="DICOM">
        <h5 class="mt-3">{{ __('DICOM FOLDER') }}</h5>
        <p>{{ __('Select a folder containing multiple DICOM files with metadata') }}</p>
    </a>
    <a href="{{ route('tomography.createdcm') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/ct2.png') }}" width="150" height="150" alt=".dcm">
        <h5 class="mt-3">{{ __('.DCM FOLDER') }}</h5>
        <p>{{ __('Select a folder with .dcm files') }}</p>
    </a>
    <a href="{{ route('tomography.create') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/ct3.png') }}" width="150" height="150" alt="JPEG, PNG">
        <h5 class="mt-3">{{ __('COMPRESSED FILE') }}</h5>
        <p>{{ __('Upload a ZIP file containing CT medical images') }}</p>
    </a>
    <a href="{{ route('tomography.index') }}" class="card1">
        <img class="img-fluid mx-auto" src="{{ asset('storage/assets/images/info.png') }}" width="150" height="150" alt="STUDIES">
        <h5 class="mt-3">{{ __('STUDIES') }}</h5>
        <p>{{ __('List of uploaded tomographies') }}</p>
    </a>
</div>
@endsection
