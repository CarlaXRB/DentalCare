@extends('layouts._partials.layout')
@section('title', __('Payments'))
@section('subtitle', __('Treatment Payments'))

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <h1 class="title1 pl-5">{{ $treatment->name ?? 'Unnamed treatment' }} - {{ $treatment->ci_patient }}</h1>
    @if($remaining>0)
    <a href="{{ route('payments.create', $treatment->id) }}" class="botton1">{{ __('Register Payment') }}</a>
    @endif
</div>

<div class="max-w-5xl mx-auto bg-white rounded-xl p-4 text-gray-900">
    <div class="mb-4">
        <p class="txt"><strong>{{ __('Total') }}:</strong> Bs {{ number_format($treatment->amount, 2) }}</p>
        <p class="txt"><strong>{{ __('Paid') }}:</strong> Bs {{ number_format($paid, 2) }}</p>
        <p class="txt"><strong>{{ __('Remaining') }}:</strong> Bs {{ number_format($remaining, 2) }}</p>
    </div>
    <h2 class="title2 flex justify-center p-5">{{ __('Payment History') }}</h2>
    @if($payments->isEmpty())
        <p class="text-gray-600 text-center py-4">{{ __('No payments have been recorded yet.') }}</p>
    @else
        <div class="grid grid-cols-5 font-semibold border-b border-gray-300 pb-2 mb-2 text-center">
            <div class="title4 flex justify-center">{{ __('Date') }}</div>
            <div class="title4 flex justify-center">{{ __('Amount') }}</div>
            <div class="title4 flex justify-center">{{ __('Method') }}</div>
            <div class="title4 flex justify-center">{{ __('Details') }}</div>
        </div>
        @foreach($payments as $p)
            <div class="grid grid-cols-5 border-b border-gray-200 py-2 text-center items-center">
                <div>{{ $p->created_at->format('d/m/Y H:i') }}</div>
                <div>Bs {{ number_format($p->amount, 2) }}</div>
                <div>{{ $p->method ?? '-' }}</div>
                <div>{{ $p->notes ?? '-' }}</div>
                <div class="flex justify-center gap-2">
                    <form method="POST" 
                        action="{{ route('payments.destroy', ['treatment' => $treatment->id, 'id' => $p->id]) }}"
                        onsubmit="return confirm('¿Eliminar este pago?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bottonDelete">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
