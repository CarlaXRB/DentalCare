@extends('layouts._partials.layout')
@section('title', __('Edit Appointment'))
@section('subtitle')
{{ __('Edit Appointment') }}
@endsection
@section('content')

{{-- Botón para ir al calendario --}}
<div class="flex justify-end p-3">
    <a href="{{ route('events.index')}}" class="botton1">{{ __('Calendar') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    {{-- Form to edit appointment --}}
    <form method="POST" action="{{ route('events.update', $event->id) }}">
        @csrf
        @method('PUT')

        <h1 class="title1 text-center mb-6">{{ __('Edit Appointment Details') }}</h1>

        {{-- ROW: Patient + Register link --}}
        <div class="flex flex-wrap items-center gap-4 mb-4">
            <div class="flex-1 min-w-[280px]">
                <label class="title4 block mb-1">{{ __('Patient') }}:</label>
                <select name="patient_id" class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="">{{ __('-- Select a patient --') }}</option>
                    @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ (old('patient_id', $event->patient_id) == $patient->id) ? 'selected' : '' }}>
                        {{ $patient->name_patient }} - CI: {{ $patient->ci_patient }}
                    </option>
                    @endforeach
                </select>
                @error('patient_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        {{-- Register new patient --}}
        <div class="flex justify-end mb-6">
            <p>{{ __('Patient not registered?') }}</p>
            <a href="{{ route('patient.create')}}" class="botton3 ml-5">{{ __('Register Patient') }}</a>
        </div>

        {{-- ROW: Start time + Duration --}}
        <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[240px]">
                <label class="title4 block mb-1">{{ __('Start Time') }}:</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date', $event->start_date) }}"
                    class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('start_date') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="title4 block mb-1">{{ __('Study Duration (minutes)') }}:</label>
                <input type="number" name="duration_minutes" min="1" required
                    value="{{ old('duration_minutes', $event->duration_minutes) }}"
                    class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('duration_minutes') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- ROW: Room + Doctor --}}
        <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[200px]">
                <label for="room" class="title4 block mb-1">{{ __('Room') }}:</label>
                <select name="room" id="room"
                    class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" required>
                    <option value="{{ $event->room }}">{{ $event->room ?? __('-- Select a room --') }}</option>
                    <option value="Room 1">{{ __('Room 1') }}</option>
                    <option value="Room 2">{{ __('Room 2') }}</option>
                </select>
                @error('room') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 min-w-[250px]">
                <label class="title4 block mb-1">{{ __('Doctor') }}:</label>
                <select name="assigned_doctor"
                    class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="{{ $event->assigned_doctor }}">
                        {{ $event->assignedDoctor->name ?? __('-- Select a doctor --') }}
                    </option>
                    @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}"
                        {{ old('assigned_doctor', $event->assigned_doctor) == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                    @endforeach
                </select>
                @error('assigned_doctor') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- ROW: Radiologist + Study --}}
        <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex-1 min-w-[250px]">
                <label class="title4 block mb-1">{{ __('Study Name') }}:</label>
                <input type="text" name="event" value="{{ old('event', $event->event) }}"
                    class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('event') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex-1 min-w-[250px]">
                <label class="title4 block mb-1">{{ __('Details') }}:</label>
                <input type="text" name="details" value="{{ old('details', $event->details) }}"
                    class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                @error('details') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Details --}}
        <div class="mb-6">
            <label class="title4 block mb-1">{{ __('Radiologist') }}:</label>
            <select name="assigned_radiologist"
                class="border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">
                <option value="{{ $event->assigned_radiologist }}">
                    {{ $event->assignedRadiologist->name ?? __('-- Select a radiologist --') }}
                </option>
                @foreach($radiologists as $radiology)
                <option value="{{ $radiology->id }}"
                    {{ old('assigned_radiologist', $event->assigned_radiologist) == $radiology->id ? 'selected' : '' }}>
                    {{ $radiology->name }}
                </option>
                @endforeach
            </select>
            @error('assigned_radiologist') <p class="error mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-center pb-6">
            <button type="submit" class="botton2">{{ __('Update') }}</button>
        </div>

    </form>
</div>
@endsection