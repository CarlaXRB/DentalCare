@extends('layouts._partials.layout')
@section('title','Pagos')
@section('subtitle')
    {{ __('Pagos') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <form method="POST" action="{{ route('payments.search', $treatment->id ?? 0) }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar pago...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>

    <!-- Buttons -->
    <div class="flex justify-end gap-2">
        <a href="{{ route('treatments.index') }}" class="botton4">{{ __('Tratamientos') }}</a>
        @isset($treatment)
        <a href="{{ route('payments.create', $treatment->id) }}" class="botton1">{{ __('Añadir Pago') }}</a>
        @endisset
    </div>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Lista de Pagos') }}</h1>

<!-- Payments table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <!-- Table header -->
    <div class="grid grid-cols-7 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Fecha') }}</h3>
        <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
        <h3 class="title4 text-center">{{ __('C.I.') }}</h3>
        <h3 class="title4 text-center">{{ __('Total') }}</h3>
        <h3 class="title4 text-center">{{ __('Monto') }}</h3>
        <h3 class="title4 text-center">{{ __('Método') }}</h3>
        <h3 class="title4 text-center">{{ __('Detalles') }}</h3>
    </div>

    <!-- Table body -->
    @forelse($payments as $p)
    <div class="grid grid-cols-7 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition text-center">
        <div><a href="{{ route('payments.show',$p->treatment->id) }}" class="flex justify-center hover:text-cyan-600">{{ $p->created_at->format('d/m/Y H:i') }}</a></div>
        <div><a href="{{ route('payments.show',$p->treatment->id) }}" class="flex justify-center hover:text-cyan-600">{{ $p->treatment->name }}</a></div>
        <div><a href="{{ route('payments.show',$p->treatment->id) }}" class="flex justify-center hover:text-cyan-600">{{ $p->treatment->ci_patient }}</a></div>
        <div><a href="{{ route('payments.show',$p->treatment->id) }}" class="flex justify-center hover:text-cyan-600">Bs. {{ number_format($p->treatment->amount, 2) }}</a></div>
        <div><a href="{{ route('payments.show',$p->treatment->id) }}" class="flex justify-center hover:text-cyan-600">Bs. {{ number_format($p->amount, 2) }}</a></div>
        <div><a href="{{ route('payments.show',$p->treatment->id) }}" class="flex justify-center hover:text-cyan-600">{{ $p->method ?? '-' }}</a></div>
        <div><a href="{{ route('payments.show',$p->treatment->id) }}" class="flex justify-center hover:text-cyan-600">{{ $p->notes ?? '-' }}</a></div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('Aún no se han registrado pagos.') }}</p>
    @endforelse

    <!-- Pagination -->
    <div class="pt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection
