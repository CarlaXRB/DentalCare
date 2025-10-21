@extends('layouts._partials.layout')
@section('title', __('Edit Tomography'))
@section('subtitle')
    {{ __('Edit Tomography') }}
@endsection
@section('content')
{{-- Botón para volver al dashboard --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Home') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto p-6">
    <form method="POST" action="{{ route('tomography.update', $tomography->id) }}">
        @method('PUT')
        @csrf

        <h1 class="title1 text-center mb-8">{{ __('Edit Tomography Information') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Patient selection --}}
            <div>
                <label class="title4 block mb-2">{{ __('Patient Name') }}:</label>
                <select name="patient_id"
                    class="border-gray-300 rounded-lg p-3 w-full text-black focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="{{ $tomography->patient->id }}">
                        {{ $tomography->patient->name_patient }} - CI: {{ $tomography->patient->ci_patient }}
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

            {{-- Tomography ID --}}
            <div>
                <label class="title4 block mb-2">{{ __('Tomography ID') }}:</label>
                <input type="text" name="tomography_id" value="{{ old('tomography_id', $tomography->tomography_id) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tomography Date --}}
            <div>
                <label class="title4 block mb-2">{{ __('Tomography Date') }}:</label>
                <input type="date" name="tomography_date" value="{{ old('tomography_date', $tomography->tomography_date) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_date') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tomography Type --}}
            <div>
                <label class="title4 block mb-2">{{ __('Tomography Type') }}:</label>
                <input type="text" name="tomography_type" value="{{ old('tomography_type', $tomography->tomography_type) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_type') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Doctor --}}
            <div>
                <label class="title4 block mb-2">{{ __('Doctor') }}:</label>
                <input type="text" name="tomography_doctor" value="{{ old('tomography_doctor', $tomography->tomography_doctor) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_doctor') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Radiologist --}}
            <div>
                <label class="title4 block mb-2">{{ __('Radiologist') }}:</label>
                <input type="text" name="tomography_charge" value="{{ old('tomography_charge', $tomography->tomography_charge) }}"
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('tomography_charge') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Submit button --}}
        <div class="flex justify-center mt-6">
            <button type="submit" class="botton2">{{ __('Update') }}</button>
        </div>
    </form>
</div>
@endsection
