@extends('layouts._partials.layout')
@section('title', __('Editar Estudio Multimedia'))
@section('subtitle')
{{ __('Editar Estudio Multimedia') }}
@endsection

@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('multimedia.index') }}" class="botton1">{{ __('Atrás') }}</a>
</div>
<div class="bg-white rounded-lg max-w-5xl mx-auto p-6">
    <form method="POST" action="{{ route('multimedia.update', $multimedia->id) }}">
        @csrf
        @method('PUT')
        <h1 class="title1 text-center mb-8">{{ __('Editar Información del Estudio') }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="title4 block mb-2">{{ __('Nombre del paciente') }}:</label>
                <input type="text" name="name_patient"
                    value="{{ old('name_patient', $multimedia->name_patient) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('name_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('C.I. del paciente') }}:</label>
                <input type="text" name="ci_patient"
                    value="{{ old('ci_patient', $multimedia->ci_patient) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('ci_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('Tipo de Estudio') }}:</label>
                <input type="text" name="study_type"
                    value="{{ old('study_type', $multimedia->study_type) }}"
                    placeholder="{{ __('Ej: Radiografía panorámica, Tomografía dental...') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('study_type') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="title4 block mb-2">{{ __('Descripción') }}:</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('description') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="flex justify-center mt-6">
            <button type="submit" class="botton2">{{ __('Actualizar Información') }}</button>
        </div>
    </form>
</div>
@endsection