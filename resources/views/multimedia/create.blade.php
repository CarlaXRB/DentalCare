@extends('layouts._partials.layout')
@section('title', __('Crear Radiografía'))
@section('subtitle')
    {{ __('Crear Radiografía') }}
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
    
    <form method="POST" action="{{ route('radiography.store') }}" enctype="multipart/form-data">
        @csrf

        <h1 class="title1 text-center mb-8">{{ __('Información de la Radiografía') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Patient selection --}}
            <div>
                <label class="title4 block mb-2">{{ __('Paciente') }}:</label>
                <select name="patient_id" required
                    class="border-gray-300 rounded-lg p-3 w-full text-black focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Seleccionar Paciente --') }}</option>
                    {{-- La variable $patients DEBE ser pasada desde el controlador --}}
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
                {{-- Asumo que 'patient.create' es la ruta para crear pacientes --}}
                <a href="{{ route('patient.create') }}" class="botton3 ml-5">{{ __('Registrar Paciente') }}</a>
            </div>

            {{-- Radiography ID --}}
            <div>
                <label class="title4 block mb-2">{{ __('ID de la radiografía') }}:</label>
                <input type="text" name="radiography_id" value="{{ old('radiography_id') }}" required
                    placeholder="{{ __('Ej: RX-2023-1234') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_id') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Radiography Date --}}
            <div>
                <label class="title4 block mb-2">{{ __('Fecha de la radiografía') }}:</label>
                <input type="date" name="radiography_date" value="{{ old('radiography_date') }}" required
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_date') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Radiography Type --}}
            <div>
                <label class="title4 block mb-2">{{ __('Tipo de Radiografía') }}:</label>
                <input type="text" name="radiography_type" value="{{ old('radiography_type') }}" required
                    placeholder="{{ __('Ej: Tórax PA, Mandíbula Lateral') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_type') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Radiography File --}}
            <div>
                <label class="title4 block mb-2">{{ __('Subir Archivo (JPG, PNG)') }}:</label>
                <input type="file" name="radiography_file" required accept="image/jpeg,image/png"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_file') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Doctor --}}
            <div>
                <label class="title4 block mb-2">{{ __('Doctor') }}:</label>
                <input type="text" name="radiography_doctor" value="{{ old('radiography_doctor') }}"
                    placeholder="{{ __('Dr. Pérez (Solicitante)') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_doctor') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Radiologist --}}
            <div>
                <label class="title4 block mb-2">{{ __('Radiólogo') }}:</label>
                <input type="text" name="radiography_charge" value="{{ old('radiography_charge') }}"
                    placeholder="{{ __('Tec. González (Realizó)') }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_charge') <p class="error mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Submit button --}}
        <div class="flex justify-center mt-10">
            <button type="submit" class="botton2 shadow-lg hover:shadow-xl transition duration-300">
                <i class="fas fa-file-upload mr-2"></i> {{ __('Subir Radiografía') }}
            </button>
        </div>
    </form>
</div>
@endsection
