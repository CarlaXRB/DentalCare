@extends('layouts._partials.layout')
@section('title', __('Editar Clínica'))
@section('subtitle')
{{ __('Editar Clínica') }}
@endsection

@section('content')

{{-- Botón para volver al listado --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('clinics.index')}}" class="botton1">{{ __('Clínicas') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    <form method="POST" action="{{ route('clinics.update', $clinic->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h1 class="title1 text-center mb-8">{{ __('Editar Información de la Clínica') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nombre --}}
            <div>
                <label class="title4 block mb-2">{{ __('Nombre de la Clínica') }}:</label>
                <input type="text" name="name" value="{{ old('name', $clinic->name) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('name') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Dirección --}}
            <div>
                <label class="title4 block mb-2">{{ __('Dirección') }}:</label>
                <input type="text" name="address" value="{{ old('address', $clinic->address) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('address') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Teléfono --}}
            <div>
                <label class="title4 block mb-2">{{ __('Teléfono') }}:</label>
                <input type="text" name="phone" value="{{ old('phone', $clinic->phone) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('phone') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Logo --}}
            <div>
                <label class="title4 block mb-2">{{ __('Logo') }}:</label>
                @if($clinic->logo)
                    <div class="mb-2">
                        <img src="{{ asset($clinic->logo) }}" alt="Logo" class="h-20">
                    </div>
                @endif
                <input type="file" name="logo" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('logo') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Número de salas --}}
            <div>
                <label class="title4 block mb-2">{{ __('Número de Salas') }}:</label>
                <input type="number" name="rooms_count" value="{{ old('rooms_count', $clinic->rooms_count) }}" min="1" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('rooms_count') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Botón de envío centrado --}}
        <div class="flex justify-center p-5 mt-2">
            <button type="submit" class="botton2">{{ __('Actualizar Clínica') }}</button>
        </div>
    </form>
</div>
@endsection
