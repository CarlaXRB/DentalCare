@extends('layouts._partials.layout')
@section('title', __('Información de la cita'))
@section('subtitle')
{{ __('Información de la cita') }}
@endsection
@section('content')

{{-- Calendar --}}
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('events.index')}}" class="botton1">{{ __('Calendario') }}</a>
</div>

<!-- Contenedor principal -->
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">
    <div class="mt-10 mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información de la cita') }}</h1>
    </div>
    <!-- Paciente -->
    <div class="mb-3">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Paciente:') }}</h3>
            <span class="txt">{{ $event->patient->name_patient ?? __('Sin información') }}</span>

            @if($event->patient)
            <a href="{{ route('patient.show', $event->patient->id ) }}"
                class="txt text-green-500 hover:text-green-700 hover:font-bold pl-12">{{ __('Ver Paciente') }}</a>
            @else
            <p class="text-red-500 text-sm">{{ __('Paciente no registrado en la base de datos.') }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Procedimiento:') }}</h3><span class="txt">{{ $event->event }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Detalles:') }}</h3><span class="txt">{{ $event->details ?? __('Sin detalles disponibles') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Hora de Inicio:') }}</h3><span class="txt">{{ \Carbon\Carbon::parse($event->start_date)->format('d-m-Y H:i') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Hora de Finalización:') }}</h3><span class="txt">{{ \Carbon\Carbon::parse($event->end_date)->format('d-m-Y H:i') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Consultorio:') }}</h3><span class="txt">{{ $event->room }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Duración:') }}</h3><span class="txt">{{ $event->duration_minutes }} {{ __('minutos') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Doctor Asignado:') }}</h3><span class="txt">{{ $event->assignedDoctor->name ?? __('No asigando') }}</span>
        </div>

        <div class="flex gap-2">
            <h3 class="title4">{{ __('Creado por:') }}</h3>
            <span class="txt">{{ $event->creator->name ?? __('Sin información') }}</span>
        </div>
    </div>

    <!-- Acciones del administrador -->
    @auth
    @if(Auth::user()->role === 'admin')
    <div>
        <div class="flex justify-center mt-4"><a href="{{ route('events.edit', $event->id ) }}" class="botton3">{{ __('Editar') }}</a></div>
    </div>
    <div>
        <form method="POST" action="{{ route('events.destroy', $event->id) }}"
            onsubmit="return confirm('{{ __('¿Estás seguro de que deseas eliminar esta cita?') }}');">
            @csrf
            @method('DELETE')
            <div class="flex justify-end mr-8"><input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete px-6 py-2" /></div>
        </form>
    </div>
    @endif
    @endauth
</div>
@endsection