@extends('layouts._partials.layout')

@section('title', __('Treatments'))
@section('subtitle', __('Treatments List'))

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search form -->
    <form method="POST" action="{{ route('treatments.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Search treatment...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Search') }}" />
    </form>
    <!-- Create budget button -->
    <div class="flex justify-end">
        @auth
        @if(Auth::user()->role === 'admin')  
            <a href="{{ route('budgets.index') }}" class="botton1">{{ __('Budgets') }}</a>
            <a href="{{ route('payments.index') }}" class="botton1">{{ __('Payments') }}</a>
        @endif
        @endauth
        <a href="{{ route('treatments.create') }}" class="botton1">{{ __('Create Treatment') }}</a>
    </div>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Treatments List') }}</h1>

<!-- Treatments table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900">
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
        <div class="text-center">{{ $t->name ?? 'N/A' }}</div>
        <div class="text-center">{{ $t->ci_patient ?? 'N/A' }}</div>
        <div class="text-center">${{ number_format($t->total_amount, 2) }}</div>
        <div class="text-center">${{ number_format($t->discount, 2) }}</div>
        <div class="text-center">${{ number_format($t->amount, 2) }}</div>
        <div>
            @if ($t->pdf_path)
                <a href="{{ asset($t->pdf_path) }}" class="botton2">{{ __('View PDF') }}</a>
            @else
                —
            @endif
        </div>
        <div class="flex justify-end">
            <a href="{{ route('payments.show',$t->id) }}" class="botton3">{{ __('Record') }}</a>
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
    <p class="text-gray-600 text-center py-4">{{ __('No treatments registered yet.') }}</p>
    @endforelse

    <!-- Pagination -->
    <div class="pt-4">
        {{ $treatments->links() }}
    </div>
</div>
@endsection
