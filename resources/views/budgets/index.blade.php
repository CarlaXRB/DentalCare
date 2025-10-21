@extends('layouts._partials.layout')
@section('title','Budgets')
@section('subtitle')
    {{ __('Budgets') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search form -->
    <form method="POST" action="{{ route('budgets.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Search budget...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Search') }}" />
    </form>

    <!-- Create budget button -->
    <div class="flex justify-end">
        <a href="{{ route('treatments.index') }}" class="botton4">{{ __('Treatments') }}</a>
        <a href="{{ route('budgets.create') }}" class="botton1">{{ __('Create Budget') }}</a>
    </div>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Budgets List') }}</h1>

<!-- Budgets table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <!-- Table header -->
    <div class="grid grid-cols-4 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Code') }}</h3>
        <h3 class="title4 text-center">{{ __('Procedure') }}</h3>
        <h3 class="title4 text-center">{{ __('Total Amount') }}</h3>
        <h3 class="title4 text-center">{{ __('Actions') }}</h3>
    </div>

    <!-- Table body -->
    @forelse($budgets as $budget)
    <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Budget code -->
        <div class="text-center">
            <a href="{{ route('budgets.show', $budget->id) }}" class="txt hover:text-cyan-600">{{ $budget->budget }}</a>
        </div>

        <!-- Procedure -->
        <div class="text-center">
            <a href="{{ route('budgets.show', $budget->id) }}" class="txt hover:text-cyan-600">{{ $budget->procedure }}</a>
        </div>

        <!-- Total amount -->
        <div class="text-center">
            ${{ number_format($budget->total_amount, 2) }}
        </div>

        <!-- Actions -->
        <div class="flex justify-center gap-3">
            <a href="{{ route('budgets.edit', $budget->id) }}" class="botton3">{{ __('Edit') }}</a>

            @auth
                @if(Auth::user()->role === 'admin')  
                <form method="POST" 
                      action="{{ route('budgets.destroy', $budget->id) }}" 
                      onsubmit="return confirm('{{ __('Are you sure you want to delete this budget?') }}');">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="{{ __('Delete') }}" class="bottonDelete cursor-pointer"/>
                </form>
                @endif
            @endauth
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('No budgets registered yet.') }}</p>
    @endforelse

    <!-- Pagination -->
    <div class="pt-4">
        {{ $budgets->links() }}
    </div>
</div>
@endsection
