@extends('layouts._partials.layout')
@section('title','Información del Presupuesto')
@section('subtitle')
    {{ __('Información del Presupuesto') }}
@endsection
@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('budgets.index')}}" class="botton1">{{ __('Tratamientos') }}</a>
</div>

<!-- Contenedor principal -->
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900">
    <div class="mt-10 mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información del Tratamiento') }}</h1>
    </div>

    <!-- Información general del presupuesto -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5">
        <div class="flex gap-2"><h3 class="title4">{{ __('Código:') }}</h3><span class="txt">{{ $budget->budget }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Procedimiento:') }}</h3><span class="txt">{{ $budget->procedure }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Descripción:') }}</h3><span class="txt">{{ $budget->description }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Costo Total:') }}</h3><span class="txt">Bs. {{ number_format($budget->total_amount, 2) }}</span></div>
    </div>

    <!-- Botón presupuesto de tratamiento -->
    <div class="flex justify-center mt-8">
        <a href="{{ route('treatments.create', $budget->id) }}" class="botton3">{{ __('Crear Presupuesto') }}</a>
    </div>
</div>
@endsection
