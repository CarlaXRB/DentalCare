@extends('layouts._partials.layout')
@section('title', __('Edit Radiography'))
@section('subtitle')
    {{ __('Edit Radiography') }}
@endsection

@section('content')
{{-- Botón para volver al dashboard --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Home') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto p-6">
    <form method="POST" action="{{ route('radiography.update', $radiography->id) }}">
        @method('PUT')
        @csrf

        <h1 class="title1 text-center mb-8">{{ __('Edit Radiography Information') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Patient selection --}}
            <div>
                <label class="title4 block mb-2">{{ __('Patient Name') }}:</label>
                <select name="patient_id"
                    class="border-gray-300 rounded-lg p-3 w-full text-black focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="{{ $radiography->patient->id }}">
                        {{ $radiography->patient->name_patient }} - CI: {{ $radiography->patient->ci_patient }}
                    </option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name_patient }} - CI: {{ $patient->ci_patient }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Register new patient --}}
            <div class="flex items-center gap-2 mt-6">
                <p>{{ __('Patient not registered?') }}</p>
                <a href="{{ route('patient.create') }}" class="botton3">{{ __('Register Patient') }}</a>
            </div>

            {{-- Radiography ID --}}
            <div>
                <label class="title4 block mb-2">{{ __('Radiography ID') }}:</label>
                <input type="text" name="radiography_id" value="{{ old('radiography_id', $radiography->radiography_id) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Radiography Date --}}
            <div>
                <label class="title4 block mb-2">{{ __('Radiography Date') }}:</label>
                <input type="date" name="radiography_date" value="{{ old('radiography_date', $radiography->radiography_date) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_date') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Radiography Type --}}
            <div>
                <label class="title4 block mb-2">{{ __('Radiography Type') }}:</label>
                <input type="text" name="radiography_type" value="{{ old('radiography_type', $radiography->radiography_type) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_type') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Doctor --}}
            <div>
                <label class="title4 block mb-2">{{ __('Doctor') }}:</label>
                <input type="text" name="radiography_doctor" value="{{ old('radiography_doctor', $radiography->radiography_doctor) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_doctor') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Radiologist --}}
            <div>
                <label class="title4 block mb-2">{{ __('Radiologist') }}:</label>
                <input type="text" name="radiography_charge" value="{{ old('radiography_charge', $radiography->radiography_charge) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('radiography_charge') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Submit button --}}
        <div class="flex justify-center mt-6">
            <button type="submit" class="botton2">{{ __('Update') }}</button>
        </div>
    </form>
</div>
@endsection
