@extends('layouts._partials.layout')
@section('title', __('Subir Archivo Multimedia'))
@section('subtitle')
    {{ __('Subir Archivo Multimedia') }}
@endsection

@section('content')
{{-- Botón para volver al dashboard --}}
<div class="flex justify-end p-5 pb-1">
    {{-- Asumo que 'files.select' es tu ruta para volver al menú principal de archivos --}}
    <a href="{{ route('files.select') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto p-6 shadow-xl">
    
    {{-- Mensajes de validación y estado --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('multimedia.store') }}" enctype="multipart/form-data">
        @csrf

        <h1 class="title1 text-center mb-8">{{ __('Registro de Archivo Multimedia') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Patient selection (patient_id) --}}
            <div>
                <label class="title4 block mb-2">{{ __('Paciente') }}:</label>
                <select name="patient_id" required
                    class="border-gray-300 rounded-lg p-3 w-full text-black focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Seleccionar Paciente --') }}</option>
                    {{-- La variable $patients DEBE ser pasada desde el controlador MultimediaFileController@create --}}
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name_patient }} - CI: {{ $patient->ci_patient }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 mt-6">
                <p class="text-gray-600">{{ __('¿Paciente no registrado?') }}</p>
                <a href="{{ route('patient.create') }}" class="botton3 ml-5">{{ __('Registrar Paciente') }}</a>
            </div>

            {{-- Study Type (study_type) --}}
            <div>
                <label class="title4 block mb-2">{{ __('Tipo de Estudio/Archivo') }}:</label>
                <select id="study_type" name="study_type" required
                    class="border-gray-300 rounded-lg p-3 w-full text-black focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Seleccione el tipo --') }}</option>
                    <option value="radiography" {{ old('study_type') == 'radiography' ? 'selected' : '' }}>Radiografía</option>
                    <option value="tomography" {{ old('study_type') == 'tomography' ? 'selected' : '' }}>Tomografía</option>
                    <option value="ecography" {{ old('study_type') == 'ecography' ? 'selected' : '' }}>Ecografía</option>
                    <option value="general" {{ old('study_type') == 'general' ? 'selected' : '' }}>General / Otro</option>
                </select>
                @error('study_type') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- File Input (file) --}}
            <div>
                <label class="title4 block mb-2">{{ __('Subir Archivo(s)') }}:</label>
                {{-- Se usa name="file[]" y multiple para permitir varios archivos o un ZIP --}}
                <input type="file" name="file[]" multiple accept="image/*,.zip" required
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                <small class="text-gray-500 mt-1 block">{{ __('JPG, PNG, o ZIP (se extraerán imágenes).') }}</small>
                @error('file') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                @error('file.*') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
            
            {{-- Optional Notes/Description --}}
            <div class="md:col-span-2">
                <label class="title4 block mb-2">{{ __('Descripción / Notas (Opcional)') }}:</label>
                <textarea name="notes" rows="3"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('notes') }}</textarea>
                @error('notes') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Submit button --}}
        <div class="flex justify-center mt-10">
            <button type="submit" class="botton2 shadow-lg hover:shadow-xl transition duration-300">
                <i class="fas fa-upload mr-2"></i> {{ __('Procesar y Subir Archivo(s)') }}
            </button>
        </div>
    </form>
</div>
@endsection