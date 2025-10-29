@extends('layouts._partials.layout')
@section('title', __('Tratamientos'))
@section('subtitle', __('Lista de tratamientos'))

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Search form -->
    <form method="POST" action="{{ route('treatments.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar tratamiento...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>
    <!-- Create budget button -->
    <div class="flex justify-end">
        <a href="{{ route('treatments.create') }}" class="botton1">{{ __('Crear tratamiento') }}</a>
    </div>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Lista de tratamientos') }}</h1>

<!-- Treatments table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900">
    <!-- Table header -->
    <div class="grid grid-cols-7 gap-4 border-b border-gray-300 pb-2 mb-3 text-center font-semibold">
        <div class="title4 text-center">{{ __('Paciente') }}</div>
        <div class="title4 text-center">{{ __('C.I.') }}</div>
        <div class="title4 text-center">{{ __('Total') }}</div>
        <div class="title4 text-center">{{ __('Descuento') }}</div>
        <div class="title4 text-center">{{ __('Costo Final') }}</div>
    </div>

    <!-- Table body -->
    @forelse($treatments as $t)
    <div class="grid grid-cols-7 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition text-center">
        <div class="text-center">{{ $t->name ?? 'N/A' }}</div>
        <div class="text-center">{{ $t->ci_patient ?? 'N/A' }}</div>
        <div class="text-center">Bs. {{ number_format($t->total_amount, 2) }}</div>
        <div class="text-center">Bs. {{ number_format($t->discount, 2) }}</div>
        <div class="text-center">Bs. {{ number_format($t->amount, 2) }}</div>
        <div>
            @if ($t->pdf_path)
                <a href="{{ route('treatments.downloadPdf', $treatment->id) }}" class="botton2">{{ __('Ver PDF') }}</a>
            @else
                —
            @endif
        </div>
        <div class="flex justify-end">
            <a href="{{ route('payments.show',$t->id) }}" class="botton3">{{ __('Pagar') }}</a>
                @auth
                @if(Auth::user()->role === 'admin')  
                <form method="POST" 
                      action="{{ route('treatments.destroy', $t->id) }}" 
                      onsubmit="return confirm('{{ __('¿Estás seguro de que quieres eliminar este presupuesto?') }}');">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete cursor-pointer"/>
                </form>
                @endif
            @endauth
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('Aún no hay tratamientos registrados.') }}</p>
    @endforelse

    <!-- Pagination -->
    <div class="pt-4">
        {{ $treatments->links() }}
    </div>
</div>
@endsection
