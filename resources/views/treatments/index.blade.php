@extends('layouts._partials.layout')
@section('title', __('Tratamientos'))
@section('subtitle', __('Lista de tratamientos'))
@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <form method="POST" action="{{ route('treatments.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar tratamiento...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>
    <div class="flex justify-end">
        <a href="{{ route('treatments.create') }}" class="botton1">{{ __('Crear tratamiento') }}</a>
    </div>
</div>
<h1 class="title1 text-center">{{ __('Lista de tratamientos') }}</h1>
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900">

    <div class="grid grid-cols-6 gap-4 border-b border-gray-300 pb-2 mb-3 text-center font-semibold">
        <div class="title4 text-center">{{ __('Paciente') }}</div>
        <div class="title4 text-center">{{ __('C.I.') }}</div>
        <div class="title4 text-center">{{ __('Total') }}</div>
        <div class="title4 text-center">{{ __('Descuento') }}</div>
        <div class="title4 text-center">{{ __('Costo Final') }}</div>
    </div>

    @forelse($treatments as $treatment)
    <div class="grid grid-cols-6 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition text-center">
        <div><a href="{{ route('payments.show',$treatment->id) }}" class="txt hover:text-cyan-600">{{ $treatment->name ?? 'N/A' }}</a></div>
        <div><a href="{{ route('payments.show',$treatment->id) }}" class="txt hover:text-cyan-600">{{ $treatment->ci_patient ?? 'N/A' }}</a></div>
        <div><a href="{{ route('payments.show',$treatment->id) }}" class="txt hover:text-cyan-600">Bs. {{ number_format($treatment->total_amount, 2) }}</a></div>
        <div><a href="{{ route('payments.show',$treatment->id) }}" class="txt hover:text-cyan-600">Bs. {{ number_format($treatment->discount, 2) }}</a></div>
        <div><a href="{{ route('payments.show',$treatment->id) }}" class="txt hover:text-cyan-600">Bs. {{ number_format($treatment->amount, 2) }}</a></div>
        <div class="flex justify-end">
            <a href="{{ route('payments.show',$treatment->id) }}" class="botton3">{{ __('Pagos') }}</a>
                @auth
                @if(Auth::user()->role === 'admin')  
                <form method="POST" 
                      action="{{ route('treatments.destroy', $treatment->id) }}" 
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
    <div class="pt-4">
        {{ $treatments->links() }}
    </div>
</div>
@endsection
