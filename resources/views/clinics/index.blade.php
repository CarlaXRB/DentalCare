@extends('layouts._partials.layout')
@section('title','Clínicas')
@section('subtitle')
    {{ __('Clínicas') }}
@endsection
@section('content')
<div class="flex justify-between items-center p-5 pb-2">
    <form method="POST" action="{{ route('clinics.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar clínica...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>
    <a href="{{ route('clinics.create') }}" class="botton1">{{ __('Crear Clínica') }}</a>
</div>
<h1 class="title1 text-center">{{ __('Lista de Clínicas') }}</h1>
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <div class="grid grid-cols-5 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
        <h3 class="title4 text-center">{{ __('Dirección') }}</h3>
        <h3 class="title4 text-center">{{ __('Teléfono') }}</h3>
        <h3 class="title4 text-center">{{ __('Número de Salas') }}</h3>
        <h3 class="title4 text-center">{{ __('Acciones') }}</h3>
    </div>
    @forelse($clinics as $clinic)
    <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">

        <div class="flex justify-center hover:text-cyan-600">
            <a href="{{ route('clinics.show', $clinic->id) }}">{{ $clinic->name }}</a>
        </div>
        <div class="flex justify-center hover:text-cyan-600">
            <a href="{{ route('clinics.show', $clinic->id) }}">{{ $clinic->address ?? '-' }}</a>
        </div>
        <div class="flex justify-center hover:text-cyan-600">
            <a href="{{ route('clinics.show', $clinic->id) }}">{{ $clinic->phone ?? '-' }}</a>
        </div>
        <div class="flex justify-center hover:text-cyan-600">
            <a href="{{ route('clinics.show', $clinic->id) }}">{{ $clinic->rooms_count }}</a>
        </div>

        <div class="flex justify-center gap-3">
            <a href="{{ route('clinics.edit', $clinic->id) }}" class="botton3">{{ __('Editar') }}</a>

            @auth
                @if(Auth::user()->role === 'superadmin')  
                <form method="POST" 
                      action="{{ route('clinics.destroy', $clinic->id) }}" 
                      onsubmit="return confirm('{{ __('¿Estás seguro de que quieres eliminar esta clínica?') }}');">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete cursor-pointer"/>
                </form>
                @endif
            @endauth
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('Aún no hay clínicas registradas.') }}</p>
    @endforelse
    <div class="pt-4">
        {{ $clinics->links() }}
    </div>
</div>
@endsection
