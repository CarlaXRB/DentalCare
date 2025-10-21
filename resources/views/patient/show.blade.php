@extends('layouts._partials.layout')
@section('title','Patient Information')
@section('subtitle')
    {{ __('Patient Information') }}
@endsection
@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('patient.index')}}" class="botton1">{{ __('Patients') }}</a>
</div>

<!-- Contenedor principal -->
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">
    <div class="mt-10 mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Patient Information') }}</h1>
    </div>

    <!-- Información general del paciente -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5">
        <div class="flex gap-2"><h3 class="title4">{{ __('Patient Name:') }}</h3><span class="txt">{{ $patient->name_patient }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Identity Card:') }}</h3><span class="txt">{{ $patient->ci_patient }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Birth Date:') }}</h3><span class="txt">{{ $patient->birth_date }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Gender:') }}</h3><span class="txt">{{ $patient->gender }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Patient Contact:') }}</h3><span class="txt">{{ $patient->patient_contact }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Family Contact:') }}</h3><span class="txt">{{ $patient->family_contact }}</span></div>
    </div>

    <!-- Citas del paciente -->
    <div class="mt-8">
        <h1 class="title1 text-center pb-4">{{ __('Patient Appointments') }}</h1>

        @if($patient->events->isEmpty())
            <p class="text-gray-700 pl-4">{{ __('The patient has no scheduled appointments.') }}</p>
        @else
        <div class="grid grid-cols-4 gap-4 font-semibold border-b border-gray-300 pb-2 mb-2">
            <span class="title3">{{ __('Date') }}</span>
            <span class="title3">{{ __('Type') }}</span>
            <span class="title3">{{ __('Doctor') }}</span>
            <span class="title3">{{ __('Actions') }}</span>
        </div>

        @foreach($patient->events as $event)
        <div class=" grid grid-cols-4 gap-4 items-center border-b border-gray-200 mb-2 p-2">
            <div class="flex justify-center"><span class="txt">{{ \Carbon\Carbon::parse($event->start_date)->format('d-m-Y H:i') }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ __('Appointment') }} - {{ $event->details }}</span></div>
            <div class="flex justify-center"><span class="txt">{{ $event->assignedDoctor->name ?? __('Not assigned') }}</span></div>
            <div class="flex justify-center">
                <a href="{{ route('events.show', $event->id ) }}" class="botton2">{{ __('Details') }}</a>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <div class="mt-8">
        <h1 class="title1 text-center pb-4">{{ __('Patient Treatments') }}</h1>

        @if($patient->treatments->isEmpty())
            <p class="text-gray-700 pl-4">{{ __('The patient has no treatments.') }}</p>
        @else
        <div class="grid grid-cols-5 gap-4 font-semibold border-b border-gray-300 pb-2 mb-2">
            <span class="title3">{{ __('C.I.') }}</span>
            <span class="title3">{{ __('Total') }}</span>
            <span class="title3">{{ __('Discount') }}</span>
            <span class="title3">{{ __('Final') }}</span>
            <span class="title3">{{ __('Actions') }}</span>
        </div>

        @foreach($patient->treatments as $treatment)
        <div class=" grid grid-cols-5 gap-4 items-center border-b border-gray-200 mb-2 p-2">
            <div class="flex text-center"><span class="txt">{{ $treatment->ci_patient ?? 'N/A' }}</span></div>
            <div class="flex text-center"><span class="txt">${{ number_format($treatment->total_amount, 2) }}</span></div>
            <div class="flex text-center"><span class="txt">${{ number_format($treatment->discount, 2) }}</span></div>
            <div class="flex text-center"><span class="txt">${{ number_format($treatment->amount, 2) }}</span></div>
            <div class="flex justify-center">
                <a href="{{ route('payments.show',$treatment->id) }}" class="botton3">{{ __('Record') }}</a>
                <a href="{{ asset($treatment->pdf_path) }}"class="botton2">{{ __('PDF') }}</a>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    <!-- Estudios del paciente -->
    <div class="mt-8">
        <h1 class="title1 text-center pb-4">{{ __('Patient Studies') }}</h1>

        @php
            $allStudies = $patient->radiographies->merge($patient->tomographies);
        @endphp

        @if($allStudies->isEmpty())
            <p class="text-gray-700 pl-4">{{ __('The patient has no studies performed.') }}</p>
        @else
            <div class="grid grid-cols-4 gap-4 font-semibold border-b border-gray-300 pb-2 mb-2">
                <span class="title3">{{ __('Date') }}</span>
                <span class="title3">{{ __('Type') }}</span>
                <span class="title3">{{ __('Charge') }}</span>
                <span class="title3">{{ __('Actions') }}</span>
            </div>

            @foreach($patient->radiographies as $radiography)
            <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 mb-2 p-2">
                <div class="flex justify-center"><span class="txt">{{ \Carbon\Carbon::parse($radiography->radiography_date)->format('d-m-Y H:i') }}</span></div>
                <div class="flex justify-center"><span class="txt">{{ __('Radiography') }} - {{ $radiography->radiography_type }}</span></div>
                <div class="flex justify-center"><span class="txt">{{ $radiography->radiography_charge }}</span></div>
                <div class="flex justify-center">
                    <a href="{{ route('radiography.show', $radiography->id ) }}" class="botton2">{{ __('View Study') }}</a>
                </div>
            </div>
            @endforeach

            @foreach($patient->tomographies as $tomography)
            <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 mb-2 p-2">
                <div class="flex justify-center"><span class="txt">{{ \Carbon\Carbon::parse($tomography->tomography_date)->format('d-m-Y H:i') }}</span></div>
                <div class="flex justify-center"><span class="txt">{{ __('Tomography') }} - {{ $tomography->tomography_type }}</span></div>
                <div class="flex justify-center"><span class="txt">{{ $tomography->tomography_charge }}</span></div>
                <div class="flex justify-center">
                    <a href="{{ route('tomography.show', $tomography->id ) }}" class="botton2">{{ __('View Study') }}</a>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <!-- Reportes del paciente -->
    <div class="mt-8">
        <h1 class="title1 text-center pb-4">{{ __('Patient Reports') }}</h1>

        @if($patient->reports->isEmpty())
            <p class="text-gray-700 pl-4">{{ __('The patient has no saved reports.') }}</p>
        @else
            <div class="grid grid-cols-4 gap-4 font-semibold border-b border-gray-300 pb-2 mb-2">
                <span class="title3">{{ __('Date') }}</span>
                <span class="title3">{{ __('Type') }}</span>
                <span class="title3">{{ __('Created By') }}</span>
                <span class="title3">{{ __('Actions') }}</span>
            </div>
            @foreach($patient->reports as $report)
            <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 mb-2 p-2">
                <div class="flex justify-center"><span class="txt">{{ \Carbon\Carbon::parse($report->report_date)->format('d-m-Y H:i') }}</span></div>
                <div class="flex justify-center"><span class="txt">{{ __('Report') }} - {{ $report->report_id }}</span></div>
                <div class="flex justify-center"><span class="txt">{{ $report->created_by }}</span></div>
                <div class="flex justify-center">
                    <a href="{{ route('report.view', $report->id ) }}" target="_blank" class="botton3">{{ __('View Report') }}</a>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
