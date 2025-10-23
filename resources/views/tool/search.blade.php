@extends('layouts._partials.layout')
@section('title', __('Herramientas'))
@section('subtitle')
    {{ __('Herramientas Aplicadas') }}
@endsection

@section('content')
<!-- Header buttons -->
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dashboard') }}" class="botton1">{{ __('Inicio') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center">{{ __('Herramientas aplicadas') }}</h1>

<!-- Tools table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <!-- Table header -->
    <div class="grid grid-cols-3 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Vista previa') }}</h3>
        <h3 class="title4 text-center">{{ __('Fecha de creación') }}</h3>
    </div>

    <!-- Table body -->
    @forelse($tools as $tool)
    <div class="grid grid-cols-3 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Preview -->
        <div class="flex justify-center">
            <a href="{{ route('tool.show', $tool->id) }}">
                <img src="{{ asset('storage/tools/' . $tool->tool_uri) }}" 
                     alt="{{ __('Tool Preview') }}" 
                     class="rounded-lg shadow-md w-32 h-auto object-cover"/>
            </a>
        </div>

        <!-- Creation Date -->
        <div class="text-center">
            <a href="{{ route('tool.show', $tool->id) }}" class="txt hover:text-cyan-600">
                {{ $tool->tool_date }}
            </a>
        </div>

        <!-- Actions -->
        @auth
        <div class="flex justify-center">
            @if(auth()->user()->role === 'admin')
            <form method="POST" action="{{ route('tool.destroy', $tool->id) }}" 
                  onsubmit="return confirm('{{ __('Are you sure you want to delete this tool?') }}');">
                @csrf
                @method('DELETE')
                <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete"/>
            </form>
            @endif
        </div>
        @endauth
    </div>
    @empty
    <p class="text-center text-gray-600 py-4">{{ __('Todavia se aplicaron herramientas.') }}</p>
    @endforelse
</div>
@endsection
