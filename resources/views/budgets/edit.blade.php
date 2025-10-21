@extends('layouts._partials.layout')
@section('title', __('Edit Budget'))
@section('subtitle')
{{ __('Edit Budget') }}
@endsection
@section('content')

{{-- Button to go back --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('budgets.index') }}" class="botton1">{{ __('Budgets') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    <form method="POST" action="{{ route('budgets.update', $budget->id) }}">
        @csrf
        @method('PUT')
        <h1 class="title1 text-center mb-8">{{ __('Edit Budget Information') }}</h1>

        {{-- Two columns --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Budget Code --}}
            <div>
                <label class="title4 block mb-2">{{ __('Budget Code') }}:</label>
                <input type="text" name="budget" value="{{ old('budget', $budget->budget) }}" 
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('budget') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Procedure --}}
            <div>
                <label class="title4 block mb-2">{{ __('Procedure') }}:</label>
                <input type="text" name="procedure" value="{{ old('procedure', $budget->procedure) }}" 
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('procedure') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Total Amount --}}
            <div>
                <label class="title4 block mb-2">{{ __('Total Amount ($)') }}:</label>
                <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $budget->total_amount) }}" 
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
                @error('total_amount') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="title4 block mb-2">{{ __('Description') }}:</label>
                <textarea name="description" 
                    class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('description', $budget->description) }}</textarea>
                @error('description') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Submit button centered --}}
        <div class="flex justify-center p-5 mt-2">
            <button type="submit" class="botton2">{{ __('Update') }}</button>
        </div>
    </form>
</div>
@endsection
