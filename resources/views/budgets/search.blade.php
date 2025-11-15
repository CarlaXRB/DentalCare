@extends('layouts._partials.layout')
@section('title','Presupuestos')
@section('subtitle')
    {{ __('Presupuestos') }}
@endsection
@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Buscador -->
    <form method="POST" action="{{ route('budgets.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar tratamiento...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>

    <!-- Botón atrás -->
    <a href="{{ route('budgets.index') }}" class="botton1">{{ __('Atrás') }}</a>
</div>

<!-- Título principal -->
<h1 class="title1 text-center">{{ __('Resultados de la búsqueda') }}</h1>

<!-- Contenedor principal -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">

    <!-- Encabezado -->
    <div class="grid grid-cols-4 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Código') }}</h3>
        <h3 class="title4 text-center">{{ __('Procedimineto') }}</h3>
        <h3 class="title4 text-center">{{ __('Costo Total') }}</h3>
    </div>

    <!-- Resultados -->
    @forelse($budgets as $budget)
    <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Budget Code -->
        <div class="text-center">
            <a href="{{ route('budgets.show', $budget->id) }}" class="txt hover:text-cyan-600">{{ $budget->budget }}</a>
        </div>

        <!-- Procedure -->
        <div class="text-center">
            <a href="{{ route('budgets.show', $budget->id) }}" class="txt hover:text-cyan-600">{{ $budget->procedure }}</a>
        </div>

        <!-- Total Amount -->
        <div class="text-center">
            Bs. {{ number_format($budget->total_amount, 2) }}
        </div>

        <!-- Acciones -->
        <div class="flex justify-center gap-3">
            <a href="{{ route('budgets.edit', $budget->id) }}" class="botton3">{{ __('Editar') }}</a>

            @auth
                @if(Auth::user()->role === 'admin')  
                <form method="POST" 
                      action="{{ route('budgets.destroy', $budget->id) }}" 
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
    <p class="text-gray-600 text-center py-4">{{ __('No se encontraron resultados para la búsqueda.') }}</p>
    @endforelse
</div>
@endsection
