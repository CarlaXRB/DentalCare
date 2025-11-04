@extends('layouts._partials.layout')
@section('title', __('Crear Cita'))
@section('subtitle')
{{ __('Crear Cita') }}
@endsection
@section('content')

{{-- Botón para ir al calendario --}}
<div class="flex justify-end p-3">
    <a href="{{ route('events.index')}}" class="botton1">{{ __('Calendario') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    {{-- Formulario para crear nueva cita --}}
    <form method="POST" action="{{ route('events.store') }}">
        @csrf
        <h1 class="title1 text-center mb-6">{{ __('Detalles de la cita') }}</h1>
        <div class="mb-4">
            <label class="title4 block mb-2">{{ __('Paciente') }}:</label>
            <select name="patient_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white">
                <option value="">{{ __('-- Seleccione un paciente --') }}</option>
                @foreach($patients as $patient)
                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name_patient }} - CI: {{ $patient->ci_patient }}
                </option>
                @endforeach
            </select>
            @error('patient_id') <p class="error mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end mb-6">
            <p>{{ __('Paciente no registrado?') }}</p>
            <a href="{{ route('patient.create')}}" class="botton3 ml-5">{{ __('Registrar Paciente') }}</a>
        </div>

        <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Hora de Inicio') }}:</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('start_date') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="title4 block mb-1">{{ __('Duración Aproximada (minutos)') }}:</label>
                <input type="number" name="duration_minutes" min="1" required value="{{ old('duration_minutes') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('duration_minutes') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[150px]">
                <label class="title4 block mb-1">{{ __('Consultorio') }}:</label>
                <select name="room" required class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Selecciona un consultorio --') }}</option>
                    <option value="Consultorio 1">{{ __('Consultorio 1') }}</option>
                    <option value="Consultorio 2">{{ __('Consultorio 2') }}</option>
                </select>
                @error('room') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Doctor') }}:</label>
                <select name="assigned_doctor" class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Selecciona al Doctor asignado --') }}</option>
                    @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ old('assigned_doctor') == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                    @endforeach
                </select>
                @error('assigned_doctor') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Procedimiento') }}:</label>
                <input type="text" name="event" value="{{ old('event') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('event') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Detalles') }}:</label>
                <input type="text" name="details" value="{{ old('details') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('details') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="flex justify-center p-3 mb-2">
            <button type="submit" class="botton2">{{ __('Registrar Cita') }}</button>
        </div>
    </form>
</div>
@endsection