@extends('layouts._partials.layout')
@section('title', __('Subir Estudio Multimedia'))
@section('subtitle')
    {{ __('Subir Estudio Multimedia') }}
@endsection

@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('multimedia.index') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto p-6">
    <form method="POST" action="{{ route('multimedia.store') }}" enctype="multipart/form-data">
        @csrf

        <h1 class="title1 text-center mb-8">{{ __('Información del Estudio Multimedia') }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Patient selection --}}
            <div>
                <label class="title4 block mb-2">{{ __('Paciente') }}:</label>
                <select name="patient_id"
                    class="border-gray-300 rounded-lg p-3 w-full text-black focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Seleccionar Paciente --') }}</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name_patient }} - CI: {{ $patient->ci_patient }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Paciente --}}
            <div>
                <label class="title4 block mb-2">{{ __('Nombre del Paciente') }}:</label>
                <input type="text" name="name_patient" value="{{ old('name_patient') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('name_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('C.I. del Paciente') }}:</label>
                <input type="text" name="ci_patient" value="{{ old('ci_patient') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('ci_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tipo de estudio --}}
            <div>
                <label class="title4 block mb-2">{{ __('Tipo de Estudio') }}:</label>
                <input type="text" name="study_type" value="{{ old('study_type') }}"
                    placeholder="{{ __('Ej: Radiografía panorámica, Tomografía dental...') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('study_type') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Descripción --}}
            <div class="md:col-span-2">
                <label class="title4 block mb-2">{{ __('Descripción') }}:</label>
                <textarea name="description" rows="3"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('description') }}</textarea>
                @error('description') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Subir imágenes individuales --}}
            <div>
                <label class="title4 block mb-2">{{ __('Subir Imágenes (PNG, JPG, JPEG)') }}:</label>
                <input type="file" name="images[]" multiple
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('images.*') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Subir carpeta ZIP --}}
            <div>
                <label class="title4 block mb-2">{{ __('Subir Carpeta (ZIP)') }}:</label>
                <input type="file" name="folder"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('folder') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-center mt-6">
            <button type="submit" class="botton2">{{ __('Subir Estudio') }}</button>
        </div>
    </form>
</div>
@endsection
