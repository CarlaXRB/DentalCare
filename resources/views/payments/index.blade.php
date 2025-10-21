@extends('layouts._partials.layout')
@section('title','Payments')
@section('subtitle')
    {{ __('Payments') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search form (opcional, si quieres buscar pagos por método o notas) -->
    <form method="POST" action="{{ route('payments.search', $treatment->id ?? 0) }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Search payment...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Search') }}" />
    </form>

    <!-- Buttons -->
    <div class="flex justify-end gap-2">
        <a href="{{ route('treatments.index') }}" class="botton4">{{ __('Treatments') }}</a>
        @isset($treatment)
        <a href="{{ route('payments.create', $treatment->id) }}" class="botton1">{{ __('Add Payment') }}</a>
        @endisset
    </div>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Payments List') }}</h1>

<!-- Payments table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <!-- Table header -->
    <div class="grid grid-cols-8 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Date & Time') }}</h3>
        <h3 class="title4 text-center">{{ __('Name') }}</h3>
        <h3 class="title4 text-center">{{ __('C.I.') }}</h3>
        <h3 class="title4 text-center">{{ __('Total') }}</h3>
        <h3 class="title4 text-center">{{ __('Amount') }}</h3>
        <h3 class="title4 text-center">{{ __('Method') }}</h3>
        <h3 class="title4 text-center">{{ __('Notes') }}</h3>
    </div>

    <!-- Table body -->
    @forelse($payments as $p)
    <div class="grid grid-cols-8 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition text-center">
        <div>{{ $p->created_at->format('d/m/Y H:i') }}</div>
        <div>{{ $p->treatment->name }}</div>
        <div>{{ $p->treatment->ci_patient }}</div>
        <div>Bs {{ number_format($p->treatment->total_amount, 2) }}</div>
        <div>Bs {{ number_format($p->amount, 2) }}</div>
        <div>{{ $p->method ?? '-' }}</div>
        <div>{{ $p->notes ?? '-' }}</div>
        <div class="flex justify-center gap-3">
             <a href="{{ route('payments.show',$p->treatment->id) }}" class="botton2">{{ __('View') }}</a>
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('No payments registered yet.') }}</p>
    @endforelse

    <!-- Pagination -->
    <div class="pt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection
