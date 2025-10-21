@extends('layouts._partials.layout')
@section('title', 'Treatments')
@section('subtitle')
    {{ __('Treatments') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search form -->
    <form method="POST" action="{{ route('treatments.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Search treatment...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Search') }}" />
    </form>

    <!-- Back button -->
    <a href="{{ route('treatments.index') }}" class="botton1">{{ __('Back') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Search Results') }}</h1>

<!-- Treatments results -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">

    <!-- Table header -->
    <div class="grid grid-cols-7 gap-4 border-b border-gray-300 pb-2 mb-3 text-center font-semibold">
        <div class="title4 text-center">{{ __('Patient') }}</div>
        <div class="title4 text-center">{{ __('C.I.') }}</div>
        <div class="title4 text-center">{{ __('Total') }}</div>
        <div class="title4 text-center">{{ __('Discount') }}</div>
        <div class="title4 text-center">{{ __('Final') }}</div>
    </div>

    <!-- Table body -->
    @forelse($treatments as $t)
    <div class="grid grid-cols-7 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition text-center">
        <!-- Patient name -->
        <div>{{ $t->name ?? 'N/A' }}</div>

        <!-- Patient CI -->
        <div>{{ $t->ci_patient ?? 'N/A' }}</div>

        <!-- Total -->
        <div>${{ number_format($t->total_amount, 2) }}</div>

        <!-- Discount -->
        <div>${{ number_format($t->discount, 2) }}</div>

        <!-- Final amount -->
        <div>${{ number_format($t->amount, 2) }}</div>

        <!-- PDF -->
        <div>
            @if ($t->pdf_path)
                <a href="{{ asset($t->pdf_path) }}" class="botton2">{{ __('View PDF') }}</a>
            @else
                —
            @endif
        </div>

        <!-- Actions -->
        <div class="flex justify-end">
            <a href="{{ route('treatments.show', $t->id) }}" class="botton3">{{ __('Record') }}</a>
            @auth
                @if(Auth::user()->role === 'admin')  
                <form method="POST" 
                      action="{{ route('treatments.destroy', $t->id) }}" 
                      onsubmit="return confirm('{{ __('Are you sure you want to delete this treatment?') }}');">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="{{ __('Delete') }}" class="bottonDelete cursor-pointer"/>
                </form>
                @endif
            @endauth
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('No results found for your search.') }}</p>
    @endforelse
</div>
@endsection
