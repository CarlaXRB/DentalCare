@extends('layouts._partials.layout')

@section('title', 'Tomographies')

@section('subtitle')
    {{ __('Tomographies') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search bar -->
    <form method="POST" action="{{ route('tomography.search') }}" class="flex gap-3 items-center">
        @csrf
        <input 
            type="text" 
            name="search" 
            placeholder="{{ __('Search tomography...') }}"
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"
        />
        <input class="botton2" type="submit" value="{{ __('Search') }}" />
    </form>

    <!-- Menu button -->
    <a href="{{ route('tomography.new') }}" class="botton1">{{ __('Tomography Menu') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Tomographies List') }}</h1>

<!-- Tomographies table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <!-- Table header -->
    <div class="grid grid-cols-5 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Patient Name') }}</h3>  
        <h3 class="title4 text-center">{{ __('Identity Card') }}</h3>
        <h3 class="title4 text-center">{{ __('Tomography Date') }}</h3>
        <h3 class="title4 text-center">{{ __('Tomography ID') }}</h3>
        <h3 class="title4 text-center">{{ __('Tomography Type') }}</h3>
    </div>

    <!-- Table body -->
    @forelse($tomographies as $tomography)
    <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Patient name -->
        <div class="text-center">
            <a href="{{ route('tomography.show', $tomography->id) }}" class="txt hover:text-cyan-600">
                {{ $tomography->name_patient }}
            </a>
        </div>

        <!-- CI -->
        <div class="text-center">
            <a href="{{ route('tomography.show', $tomography->id) }}" class="txt hover:text-cyan-600">
                {{ $tomography->ci_patient }}
            </a>
        </div>

        <!-- Date -->
        <div class="text-center">
            <a href="{{ route('tomography.show', $tomography->id) }}" class="txt hover:text-cyan-600">
                {{ $tomography->tomography_date }}
            </a>
        </div>

        <!-- Tomography ID -->
        <div class="text-center">
            <a href="{{ route('tomography.show', $tomography->id) }}" class="txt hover:text-cyan-600">
                {{ $tomography->tomography_id }}
            </a>
        </div>

        <!-- Tomography Type -->
        <div class="text-center">
            <a href="{{ route('tomography.show', $tomography->id) }}" class="txt hover:text-cyan-600">
                {{ $tomography->tomography_type }}
            </a>
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('No tomographies registered yet.') }}</p>
    @endforelse
</div>
@endsection
