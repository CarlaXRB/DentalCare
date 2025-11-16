@extends('layouts._partials.layout')
@section('title','Presupuestos')
@section('subtitle')
{{ __('Presupuestos') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <form method="POST" action="{{ route('budgets.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar tratamiento...') }}"
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500" />
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>

    <div class="flex justify-end">
        <a href="{{ route('budgets.create') }}" class="botton1">{{ __('Crear Tratamiento') }}</a>
    </div>
</div>

<h1 class="title1 text-center">{{ __('Lista de Tratamientos') }}</h1>

<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">

    <div class="grid grid-cols-5 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Codigo') }}</h3>
        <h3 class="title4 text-center">{{ __('Procedimiento') }}</h3>
        <h3 class="title4 text-center">{{ __('Costo Total') }}</h3>
        <h3 class="title4 text-center">{{ __('Detalles') }}</h3>
    </div>
    @forelse($budgets as $budget)
    <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <div class="text-center"><a href="{{ route('budgets.show', $budget->id) }}" class="flex justify-center hover:text-cyan-600">{{ $budget->budget }}</a></div>
        <div class="text-center"><a href="{{ route('budgets.show', $budget->id) }}" class="flex justify-center hover:text-cyan-600">{{ $budget->procedure }}</a></div>
        <div class="text-center"><a href="{{ route('budgets.show', $budget->id) }}" class="flex justify-center hover:text-cyan-600">Bs. {{ number_format($budget->total_amount, 2) }}</a></div>
        <div class="text-center"><a href="{{ route('budgets.show', $budget->id) }}" class="flex justify-center hover:text-cyan-600">{{ $budget->description }}</a></div>

        <div class="flex justify-center gap-3">
            <a href="{{ route('budgets.edit', $budget->id) }}" class="botton3">{{ __('Editar') }}</a>
            @auth
            @if(Auth::user()->role === 'admin')
            <form method="POST"
                action="{{ route('budgets.destroy', $budget->id) }}"
                onsubmit="return confirm('{{ __('¿Estás seguro de que quieres eliminar este presupuesto?') }}');">
                @csrf
                @method('DELETE')
                <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete cursor-pointer" />
            </form>
            @endif
            @endauth
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('Aún no hay presupuestos registrados.') }}</p>
    @endforelse

    <div class="pt-4">{{ $budgets->links() }}</div>
</div>
@endsection