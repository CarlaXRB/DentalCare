@extends('layouts._partials.layout')
@section('title', __('Appointment Information'))
@section('subtitle')
{{ __('Appointment Information') }}
@endsection
@section('content')

{{-- Calendar --}}
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('events.index')}}" class="botton1">{{ __('Calendar') }}</a>
</div>

<!-- Contenedor principal -->
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">
    <div class="mt-10 mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Appointment Information') }}</h1>
    </div>
    <!-- Paciente -->
    <div class="mb-3">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Patient:') }}</h3>
            <span class="txt">{{ $event->patient->name_patient ?? __('No information') }}</span>
        </div>
        <div class="ml-[104px]">
            @if($event->patient)
            <a href="{{ route('patient.show', $event->patient->id ) }}"
                class="txt ml-8 text-green-600 hover:text-green-800 text-sm font-semibold">
                {{ __('View Patient') }}
            </a>
            @else
            <p class="text-red-500 text-sm">{{ __('Patient not registered in the database.') }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Event:') }}</h3><span class="txt">{{ $event->event }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Details:') }}</h3><span class="txt">{{ $event->details ?? __('No details available') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Start:') }}</h3><span class="txt">{{ \Carbon\Carbon::parse($event->start_date)->format('d-m-Y H:i') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('End:') }}</h3><span class="txt">{{ \Carbon\Carbon::parse($event->end_date)->format('d-m-Y H:i') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Room:') }}</h3><span class="txt">{{ $event->room }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Duration:') }}</h3><span class="txt">{{ $event->duration_minutes }} {{ __('minutes') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Doctor:') }}</h3><span class="txt">{{ $event->assignedDoctor->name ?? __('Not assigned') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Created by:') }}</h3>
            <span class="txt">{{ $event->creator->name ?? __('No information') }}</span>
        </div>
    </div>

    <!-- Acciones del administrador -->
    @auth
    @if(Auth::user()->role === 'admin')
    <div>
        <div class="flex justify-center mt-4"><a href="{{ route('events.edit', $event->id ) }}" class="botton3">{{ __('Edit') }}</a></div>
    </div>
    <div>
        <form method="POST" action="{{ route('events.destroy', $event->id) }}"
            onsubmit="return confirm('{{ __('Are you sure you want to delete this appointment?') }}');">
            @csrf
            @method('DELETE')
            <div class="flex justify-end mr-8"><input type="submit" value="{{ __('Delete') }}" class="bottonDelete px-6 py-2" /></div>
        </form>
    </div>
    @endif
    @endauth
</div>
@endsection