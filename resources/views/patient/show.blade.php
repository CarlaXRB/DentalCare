@extends('layouts._partials.layout')
@section('title','Información del Paciente')
@section('subtitle')
{{ __('Información del Paciente') }}
@endsection
@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('patient.index')}}" class="botton1">{{ __('Pacientes') }}</a>
</div>

<!-- Contenedor principal -->
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">
    <div class="mt-10 mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información del Paciente') }}</h1>
    </div>

    <!-- Información general del paciente -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Nombre del paciente:') }}</h3><span class="txt">{{ $patient->name_patient }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Carnet de Identidad:') }}</h3><span class="txt">{{ $patient->ci_patient }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Fecha de nacimiento:') }}</h3><span class="txt">{{ $patient->birth_date }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Género:') }}</h3><span class="txt">{{ $patient->gender }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Número de celular:') }}</h3><span class="txt">{{ $patient->patient_contact }}</span>
        </div>
    </div>

    @auth
    @if(auth()->user()->role === 'superadmin')
    <!-- Información del sistema -->
    <div class="mt-10 mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información del Registro') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-12 gap-y-4 pb-5">

            <div class="flex gap-2">
                <h3 class="title4">{{ __('Clínica asignada:') }}</h3>
                <span class="txt">
                    {{ $patient->clinic->name ?? 'Sin clínica' }}
                </span>
            </div>

            <div class="flex gap-2">
                <h3 class="title4">{{ __('Creado por:') }}</h3>
                <span class="txt">
                    {{ $patient->creator->name ?? 'N/A' }}
                </span>
            </div>

            <div class="flex gap-2">
                <h3 class="title4">{{ __('Última edición por:') }}</h3>
                <span class="txt">
                    {{ $patient->editor->name ?? 'Sin ediciones' }}
                </span>
            </div>

        </div>
    </div>
    @endif
    @endauth
    <div class="mt-8">
        <h1 class="title1 text-center pb-4">{{ __('Tratamientos del Paciente') }}</h1>

        @if($patient->treatments->isEmpty())
        <p class="text-gray-700 pl-4">{{ __('El paciente no tiene tratamientos.') }}</p>
        @else
        <div class="grid grid-cols-6 gap-4 font-semibold border-b border-gray-300 pb-2 mb-2">
            <span class="title3">{{ __('C.I.') }}</span>
            <span class="title3">{{ __('Nombre') }}</span>
            <span class="title3">{{ __('Total') }}</span>
            <span class="title3">{{ __('Descuento') }}</span>
            <span class="title3">{{ __('Costo Final') }}</span>
        </div>

        @foreach($patient->treatments as $treatment)
        <div class=" grid grid-cols-6 gap-4 items-center border-b border-gray-200 mb-2 p-2">
            <div class="flex text-center"><span class="txt">{{ $treatment->ci_patient ?? 'N/A' }}</span></div>
            <div class="flex text-center"><span class="txt">{{ $treatment->name ?? 'N/A' }}</span></div>
            <div class="flex text-center"><span class="txt">Bs. {{ number_format($treatment->total_amount, 2) }}</span></div>
            <div class="flex text-center"><span class="txt">Bs. {{ number_format($treatment->discount, 2) }}</span></div>
            <div class="flex text-center"><span class="txt">Bs. {{ number_format($treatment->amount, 2) }}</span></div>
            <div class="flex justify-end">
                <a href="{{ route('payments.show',$treatment->id) }}" class="botton3">{{ __('Ver Pagos') }}</a>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- Citas del paciente -->
    <div class="mt-8">
        <h1 class="title1 text-center pb-4">{{ __('Citas del Paciente') }}</h1>

        @if($patient->events->isEmpty())
        <p class="text-gray-700 pl-4">{{ __('El paciente no tiene citas programadas.') }}</p>
        @else
        <div class="grid grid-cols-5 gap-4 font-semibold border-b border-gray-300 pb-2 mb-2">
            <span class="title3">{{ __('Fecha') }}</span>
            <span class="title3">{{ __('Descripción') }}</span>
            <span class="title3">{{ __('Doctor') }}</span>
            <span class="title3">{{ __('Consultorio') }}</span>
        </div>

        @foreach($patient->events as $event)
        <div class=" grid grid-cols-5 gap-4 items-center border-b border-gray-200 mb-2 p-2">
            <div class="flex justify-center"><span class="txt">{{ \Carbon\Carbon::parse($event->start_date)->format('d-m-Y H:i') }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ $event->details }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ $event->assignedDoctor->name ?? __('Not assigned') }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ $event->room }}</span></div>
            <div class="flex justify-center">
                <a href="{{ route('events.show', $event->id ) }}" class="botton2">{{ __('Detalles') }}</a>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    <div class="mt-8">
        <h1 class="title1 text-center pb-4">{{ __('Estudios del Paciente') }}</h1>

        @php
        $allStudies = $patient->multimediaFiles;
        @endphp

        @if($allStudies->isEmpty())
        <p class="text-gray-700 pl-4">{{ __('El paciente no tiene estudios realizados.') }}</p>
        @else
        <div class="grid grid-cols-5 gap-4 font-semibold border-b border-gray-300 pb-2 mb-2">
            <span class="title3">{{ __('Fecha') }}</span>
            <span class="title3">{{ __('Estudio') }}</span>
            <span class="title3">{{ __('Descripción') }}</span>
            <span class="title3">{{ __('Doctor/Radiologo') }}</span>
        </div>

        @foreach($patient->radiographies as $radiography)
        <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 mb-2 p-2">
            <div class="flex justify-center"><span class="txt">{{ \Carbon\Carbon::parse($radiography->radiography_date)->format('d-m-Y') }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ __('Radiography') }}</span></div>
            <div class="flex justify-center"><span class="txt"> {{ $radiography->radiography_type }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ $radiography->radiography_charge }}</span></div>
            <div class="flex justify-center">
                <a href="{{ route('radiography.show', $radiography->id ) }}" class="botton2">{{ __('Ver Estudio') }}</a>
            </div>
        </div>
        @endforeach

        @foreach($patient->tomographies as $tomography)
        <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 mb-2 p-2">
            <div class="flex justify-center"><span class="txt">{{ \Carbon\Carbon::parse($tomography->tomography_date)->format('d-m-Y') }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ __('Tomography') }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ $tomography->tomography_type }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ $tomography->tomography_charge }}</span></div>
            <div class="flex justify-center">
                <a href="{{ route('tomography.show', $tomography->id ) }}" class="botton2">{{ __('Ver Estudio') }}</a>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection