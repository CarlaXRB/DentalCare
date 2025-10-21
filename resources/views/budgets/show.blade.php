@extends('layouts._partials.layout')

@section('title','Budget Information')
@section('subtitle')
    {{ __('Budget Information') }}
@endsection

@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('budgets.index')}}" class="botton1">{{ __('Budgets') }}</a>
</div>

<!-- Contenedor principal -->
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900">
    <div class="mt-10 mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Budget Information') }}</h1>
    </div>

    <!-- Información general del presupuesto -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5">
        <div class="flex gap-2"><h3 class="title4">{{ __('Budget Code:') }}</h3><span class="txt">{{ $budget->budget }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Procedure:') }}</h3><span class="txt">{{ $budget->procedure }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Description:') }}</h3><span class="txt">{{ $budget->description }}</span></div>
        <div class="flex gap-2"><h3 class="title4">{{ __('Total Amount:') }}</h3><span class="txt">${{ number_format($budget->total_amount, 2) }}</span></div>
    </div>

    <!-- Botón presupuesto de tratamiento -->
    <div class="flex justify-center mt-8">
        <a href="{{ route('treatments.create', $budget->id) }}" class="botton3">{{ __('Treatment Budget') }}</a>
    </div>
</div>
@endsection
