@extends('layouts._partials.layout')
@section('title', __('Crear Paciente'))
@section('subtitle')
{{ __('Crear Paciente') }}
@endsection
@section('content')

{{-- Botón para volver al listado --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('patient.index')}}" class="botton1">{{ __('Pacientes') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    <form method="POST" action="{{ route('patient.store') }}" clas">
        @csrf
        <h1 class="title1 text-center mb-8">{{ __('Información del Paciente') }}</h1>

        {{-- Dos columnas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nombre del paciente --}}
            <div>
                <label class="title4 block mb-2">{{ __('Nombre del paciente') }}:</label>
                <input type="text" name="name_patient" value="{{ old('name_patient') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('name_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Carnet de identidad --}}
            <div>
                <label class="title4 block mb-2">{{ __('C.I.') }}:</label>
                <input type="text" name="ci_patient" value="{{ old('ci_patient') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('ci_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Fecha de nacimiento --}}
            <div>
                <label class="title4 block mb-2">{{ __('Fecha de Nacimiento') }}:</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                
                @error('birth_date') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Género --}}
            <div>
                <label class="title4 block mb-2">{{ __('Género') }}:</label>
                <select name="gender" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                <option value="">{{ __('-- Seleccionar género --') }}</option>
                    <option value="femenino" {{ old('gender') == 'Femenino' ? 'selected' : '' }}>{{ __('Femenino') }}</option>
                    <option value="masculino" {{ old('gender') == 'Masculino' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                </select>
                @error('gender') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Contacto del paciente --}}
            <div>
                <label class="title4 block mb-2">{{ __('Número de celular') }}:</label>
                <input type="text" name="patient_contact" value="{{ old('patient_contact') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('patient_contact') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Botón de envío centrado --}}
        <div class="flex justify-center p-5 mt-2">
            <button type="submit" class="botton2">{{ __('Crear Paciente') }}</button>
        </div>
    </form>
</div>
@endsection