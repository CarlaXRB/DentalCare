@extends('layouts._partials.layout')
@section('title', __('Editar Pacientes'))
@section('subtitle')
{{ __('Editar Pacientes') }}
@endsection
@section('content')

{{-- Botón para volver al listado --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('patient.index')}}" class="botton1">{{ __('Pacientes') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    <form method="POST" action="{{ route('patient.update', $patient->id) }}">
        @method('PUT')
        @csrf
        <h1 class="title1 text-center mb-8">{{ __('Información del paciente') }}</h1>

        {{-- Dos columnas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nombre del paciente --}}
            <div>
                <label class="title4 block mb-2">{{ __('Nombre del Paciente') }}:</label>
                <input type="text" name="name_patient" value="{{ $patient->name_patient }}" 
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('name_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Carnet de identidad --}}
            <div>
                <label class="title4 block mb-2">{{ __('Carnet de identidad') }}:</label>
                <input type="text" name="ci_patient" value="{{ $patient->ci_patient }}" 
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('ci_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Fecha de nacimiento --}}
            <div>
                <label class="title4 block mb-2">{{ __('Fecha de nacimiento') }}:</label>
                <input type="date" name="birth_date" value="{{ $patient->birth_date }}" 
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('birth_date') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Género --}}
            <div>
                <label class="title4 block mb-2">{{ __('Género') }}:</label>
                <select name="gender" 
                    class="border-gray-300 rounded-lg p-3 w-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Select Gender --') }}</option>
                    <option value="femenino" {{ $patient->gender == 'Femenino' ? 'selected' : '' }}>{{ __('Femenino') }}</option>
                    <option value="masculino" {{ $patient->gender == 'Masculino' ? 'selected' : '' }}>{{ __('Masculino') }}</option>
                </select>
                @error('gender') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Contacto del paciente --}}
            <div>
                <label class="title4 block mb-2">{{ __('Celular del Paciente') }}:</label>
                <input type="text" name="patient_contact" value="{{ $patient->patient_contact }}" 
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('patient_contact') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Botón de actualización centrado --}}
        <div class="flex justify-center p-5 mt-2">
            <button type="submit" class="botton2">{{ __('Actualizar') }}</button>
        </div>
    </form>
</div>
@endsection
