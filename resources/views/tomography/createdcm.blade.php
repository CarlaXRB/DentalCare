@extends('layouts._partials.layout')
@section('title', __('Crear Tomografía'))
@section('subtitle')
    {{ __('Crere Tomogrfía') }}
@endsection

@section('content')
{{-- Botón para volver al listado de tomografías --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('tomography.new') }}" class="botton1">{{ __('Menú Tomografías') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto p-6 shadow-md">
    <form method="POST" action="{{ route('tomography.storedcm') }}" enctype="multipart/form-data">
        @csrf

        <h1 class="title1 text-center mb-8">{{ __('Información de la Tomografía') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Patient selection --}}
            <div>
                <label class="title4 block mb-2">{{ __('Nombre del Paciente') }}:</label>
                <select name="patient_id"
                    class="border-gray-300 rounded-lg p-3 w-full text-black focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <optio¿n ciente no registrado --') }}</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name_patient }} - CI: {{ $patient->ci_patient }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Register patient --}}
            <div class="flex items-center gap-2 mt-6">
                <p>{{ __('¿Paciente no registrado?') }}</p>
                <a href="{{ route('patient.create') }}" class="botton3 ml-5">{{ __('Registrar Paciente') }}</a>
            </div>

            {{-- Tomography ID --}}
            <div>
                <label class="title4 block mb-2">{{ __('ID de la Tomografía') }}:</label>
                <input type="text" name="tomography_id" value="{{ old('tomography_id') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tomography Date --}}
            <div>
                <label class="title4 block mb-2">{{ __('Fecha de la Tomografía') }}:</label>
                <input type="date" name="tomography_date" value="{{ old('tomography_date') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_date') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tomography Type --}}
            <div>
                <label class="title4 block mb-2">{{ __('Tipo de Tomografía') }}:</label>
                <input type="text" name="tomography_type" value="{{ old('tomography_type') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_type') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tomography Folder Upload --}}
            <div>
                <label class="title4 block mb-2">{{ __('Subir Carpeta') }}:</label>
                <input type="file" name="tomography_folder[]" webkitdirectory directory multiple
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_folder') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Doctor --}}
            <div>
                <label class="title4 block mb-2">{{ __('Doctor') }}:</label>
                <input type="text" name="tomography_doctor" value="{{ old('tomography_doctor') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_doctor') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Radiologist --}}
            <div>
                <label class="title4 block mb-2">{{ __('Radiologo') }}:</label>
                <input type="text" name="tomography_charge" value="{{ old('tomography_charge') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_charge') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Submit button --}}
        <div class="flex justify-center mt-6">
            <button type="submit" class="botton2">{{ __('Subir Tomografía') }}</button>
        </div>
    </form>
</div>
@endsection
