@extends('layouts._partials.layout')
@section('title', __('Editar Presupuesto'))
@section('subtitle')
{{ __('Editar Presupuesto') }}
@endsection
@section('content')

{{-- Button to go back --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('budgets.index') }}" class="botton1">{{ __('Presupuestos') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    <form method="POST" action="{{ route('budgets.update', $budget->id) }}">
        @csrf
        @method('PUT')
        <h1 class="title1 text-center mb-8">{{ __('Información del Presupuesto') }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="title4 block mb-2">{{ __('Código') }}:</label>
                <input type="text" name="budget" value="{{ old('budget', $budget->budget) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('budget') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('Procedimiento') }}:</label>
                <input type="text" name="procedure" value="{{ old('procedure', $budget->procedure) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('procedure') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('Costo Total (Bs)') }}:</label>
                <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $budget->total_amount) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('total_amount') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('Descripción') }}:</label>
                <textarea name="description" value="{{ old('details') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" rows="2"></textarea>
                @error('description') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

        </div>
        <div class="flex justify-center p-5 mt-2">
            <button type="submit" class="botton2">{{ __('Actualizar') }}</button>
        </div>
    </form>
</div>
@endsection