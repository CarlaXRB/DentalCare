@extends('layouts._partials.layout')
@section('title', __('Añadir Pago'))
@section('subtitle', __('Registrar nuevo pago'))
@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <h1 class="title1 pl-5">{{ __('Nuevo Pago por ') . $treatment->name . ' - ' . $treatment->ci_patient }}</h1>
    <a href="{{ route('payments.show', $treatment->id) }}" class="botton1">{{ __('Atrás') }}</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 max-w-3xl mx-auto bg-white rounded-xl p-6 text-gray-900">
    <div>
        <p class="txt mb-4"><strong>{{ __('Total') }}:</strong> Bs. {{ number_format($treatment->amount, 2) }}</p>
        <p class="txt mb-4"><strong>{{ __('Pagado') }}:</strong> Bs. {{ number_format($paid, 2) }}</p>
        <p class="txt mb-4"><strong>{{ __('Restante') }}:</strong> Bs. {{ number_format($remaining, 2) }}</p>
    </div>
    <form action="{{ route('payments.store', $treatment->id) }}" method="POST" class="grid gap-4">
        @csrf
        <div>
            <label class="block text-gray-700">{{ __('Método de pago') }}</label>
            <select name="method" class="input">
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-800">{{ __('Monto') }}(Bs)</label>
            <input type="number" step="0.01" name="amount" required class="input1 w-full">
        </div>
        <div>
            <label class="block text-gray-800">{{ __('Detalles') }}</label>
            <textarea name="notes" class="input1 w-full" rows="2"></textarea>
        </div>
        <div class="text-right">
            <button type="submit" class="botton2">{{ __('Guardar') }}</button>
        </div>
    </form>
</div>
@endsection