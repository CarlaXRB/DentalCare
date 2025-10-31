@extends('layouts._partials.layout')
@section('title', __('Subir Estudio Multimedia'))
@section('subtitle')
{{ __('Subir Estudio Multimedia') }}
@endsection

@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('multimedia.index') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto p-6">
    <form method="POST" action="{{ route('multimedia.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <h1 class="title1 text-center mb-8">{{ __('Información del Estudio Multimedia') }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Patient selection --}}
                <div>
    <label class="title4 block mb-2">{{ __('Paciente') }}:</label>
    <select id="patientSelect" name="patient_id"
        class="border-gray-300 rounded-lg p-3 w-full text-black focus:outline-none focus:ring-2 focus:ring-cyan-500">
        <option value="">{{ __('-- Seleccionar Paciente --') }}</option>
        @foreach($patients as $patient)
            <option value="{{ $patient->id }}"
                data-name="{{ $patient->name_patient }}"
                data-ci="{{ $patient->ci_patient }}"
                {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                {{ $patient->name_patient }} - CI: {{ $patient->ci_patient }}
            </option>
        @endforeach
    </select>
    @error('patient_id') <p class="error mt-1">{{ $message }}</p> @enderror
</div>

{{-- Campos ocultos para enviar nombre y CI automáticamente --}}
<input type="hidden" name="name_patient" id="namePatient" value="{{ old('name_patient') }}">
<input type="hidden" name="ci_patient" id="ciPatient" value="{{ old('ci_patient') }}">

                <div class="flex items-center gap-2 mt-6">
                    <p>{{ __('¿Paciente no registrado?') }}</p>
                    <a href="{{ route('patient.create') }}" class="botton3 ml-5">{{ __('Registrar Paciente') }}</a>
                </div>

                {{-- Paciente --}}


                {{-- Tipo de estudio --}}
                <div>
                    <label class="title4 block mb-2">{{ __('Tipo de Estudio') }}:</label>
                    <input type="text" name="study_type" value="{{ old('study_type') }}"
                        placeholder="{{ __('Ej: Radiografía panorámica, Tomografía dental...') }}"
                        class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                    @error('study_type') <p class="error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label class="title4 block mb-2">{{ __('Descripción') }}:</label>
                    <textarea name="description" rows="3"
                        class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('description') }}</textarea>
                    @error('description') <p class="error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Subir imágenes individuales --}}
                <div>
                    <label class="title4 block mb-2">{{ __('Subir Imágenes (PNG, JPG, JPEG)') }}:</label>
                    <input type="file" name="images[]" multiple
                        class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                    @error('images.*') <p class="error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Subir carpeta ZIP --}}
                <div>
                    <label class="title4 block mb-2">{{ __('Subir Carpeta (ZIP)') }}:</label>
                    <input type="file" name="folder"
                        class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500" />
                    @error('folder') <p class="error mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-center mt-6">
                <button type="submit" class="botton2">{{ __('Subir Estudio') }}</button>
            </div>
    </form>
</div>
<script>
    const patientSelect = document.getElementById('patientSelect');
    const namePatientInput = document.getElementById('namePatient');
    const ciPatientInput = document.getElementById('ciPatient');

    patientSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        namePatientInput.value = selectedOption.dataset.name || '';
        ciPatientInput.value = selectedOption.dataset.ci || '';
    });

    // Llenar automáticamente si hay old() al volver del validate
    window.addEventListener('DOMContentLoaded', () => {
        const selectedOption = patientSelect.options[patientSelect.selectedIndex];
        if (selectedOption) {
            namePatientInput.value = selectedOption.dataset.name || '';
            ciPatientInput.value = selectedOption.dataset.ci || '';
        }
    });
</script>

@endsection