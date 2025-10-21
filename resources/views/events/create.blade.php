@extends('layouts._partials.layout')
@section('title', __('Create Appointment'))
@section('subtitle')
{{ __('Create Appointment') }}
@endsection
@section('content')

{{-- Botón para ir al calendario --}}
<div class="flex justify-end p-3">
    <a href="{{ route('events.index')}}" class="botton1">{{ __('Calendar') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    {{-- Formulario para crear nueva cita --}}
    <form method="POST" action="{{ route('events.store') }}">
        @csrf
        <h1 class="title1 text-center mb-6">{{ __('Enter Appointment Details') }}</h1>

        {{-- Selección del paciente --}}
        <div class="mb-4">
            <label class="title4 block mb-2">{{ __('Patient') }}:</label>
            <select name="patient_id" class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                <option value="">{{ __('-- Select a patient --') }}</option>
                @foreach($patients as $patient)
                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name_patient }} - CI: {{ $patient->ci_patient }}
                </option>
                @endforeach
            </select>
            @error('patient_id') <p class="error mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Enlace para registrar paciente --}}
        <div class="flex justify-end mb-6">
            <p>{{ __('Patient not registered?') }}</p>
            <a href="{{ route('patient.create')}}" class="botton3 ml-5">{{ __('Register Patient') }}</a>
        </div>


        {{-- FILA: Inicio + Duración --}}
        <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Start Time') }}:</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('start_date') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="title4 block mb-1">{{ __('Study Duration (minutes)') }}:</label>
                <input type="number" name="duration_minutes" min="1" required value="{{ old('duration_minutes') }}" class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('duration_minutes') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- FILA: Sala + Estudio --}}
        <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[150px]">
                <label class="title4 block mb-1">{{ __('Room') }}:</label>
                <select name="room" required class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('Select a room') }}</option>
                    <option value="Sala 1">{{ __('Sala 1') }}</option>
                    <option value="Sala 2">{{ __('Sala 2') }}</option>
                </select>
                @error('room') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Doctor') }}:</label>
                <select name="assigned_doctor" class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Select a doctor --') }}</option>
                    @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ old('assigned_doctor') == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                    @endforeach
                </select>
                @error('assigned_doctor') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- FILA: Doctor + Radiólogo --}}
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Study Name') }}:</label>
                <input type="text" name="event" value="{{ old('event') }}" class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('event') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Details') }}:</label>
                <input type="text" name="details" value="{{ old('details') }}" class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('details') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Detalles --}}
        <div class="mb-4">
            <label class="title4 block mb-1">{{ __('Radiologist') }}:</label>
            <select name="assigned_radiologist" class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                <option value="">{{ __('-- Select a radiologist --') }}</option>
                @foreach($radiologists as $radiology)
                <option value="{{ $radiology->id }}" {{ old('assigned_radiologist') == $radiology->id ? 'selected' : '' }}>{{ $radiology->name }}</option>
                @endforeach
            </select>
            @error('assigned_radiologist') <p class="error mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Botón de envío --}}
        <div class="flex justify-center p-3 mb-2">
            <button type="submit" class="botton2">{{ __('Create') }}</button>
        </div>

    </form>
</div>
@endsection