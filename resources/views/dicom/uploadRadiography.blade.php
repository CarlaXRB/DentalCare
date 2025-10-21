@extends('layouts._partials.layout')

@section('title', __('Show Radiography'))
@section('subtitle')
    {{ __('DICOM') }}
@endsection

@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Home') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center mb-5">{{ __('DICOM File with Metadata') }}</h1>

<div class="max-w-4xl mx-auto bg-white rounded-xl p-6 shadow-md">
    <p class="text-gray-800 text-lg mb-5">
        {{ __('You can upload DICOM files here for processing and analysis. The system will extract and display relevant metadata, facilitating radiology image management.') }}
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
            <button type="submit" class="botton2">{{ __('Upload File') }}</button>
        </div>
    </form>
</div>
@endsection
