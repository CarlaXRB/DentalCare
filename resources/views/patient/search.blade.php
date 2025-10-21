@extends('layouts._partials.layout')
@section('title','Patients')
@section('subtitle')
    {{ __('Patients') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Buscador -->
    <form method="POST" action="{{ route('patient.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Search patient...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Search') }}" />
    </form>

    <!-- Botón atrás -->
    <a href="{{ route('patient.index') }}" class="botton1">{{ __('Back') }}</a>
</div>

<!-- Título principal -->
<h1 class="title1 text-center">{{ __('Search Results') }}</h1>

<!-- Contenedor principal -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">

    <!-- Encabezado -->
    <div class="grid grid-cols-4 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Identity Card') }}</h3>
        <h3 class="title4 text-center">{{ __('Patient Name') }}</h3>
        <h3 class="title4 text-center">{{ __('Contact') }}</h3>
        <h3 class="title4 text-center">{{ __('Actions') }}</h3>
    </div>

    <!-- Resultados -->
    @forelse($patients as $patient)
    <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Carnet -->
        <div class="text-center">
            <a href="{{ route('patient.show', $patient->id) }}" class="txt hover:text-cyan-600">{{ $patient->ci_patient }}</a>
        </div>

        <!-- Nombre -->
        <div class="text-center">
            <a href="{{ route('patient.show', $patient->id) }}" class="txt hover:text-cyan-600">{{ $patient->name_patient }}</a>
        </div>

        <!-- Contacto -->
        <div class="text-center">
            <a href="{{ route('patient.show', $patient->id) }}" class="txt hover:text-cyan-600">{{ $patient->patient_contact }}</a>
        </div>

        <!-- Acciones -->
        <div class="flex justify-center gap-3">
            <a href="{{ route('patient.edit', $patient->id) }}" class="botton3">{{ __('Edit') }}</a>

            @auth
                @if(Auth::user()->role === 'admin')  
                <form method="POST" 
                      action="{{ route('patient.destroy', $patient->id) }}" 
                      onsubmit="return confirm('{{ __('Are you sure you want to delete this patient?') }}');">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="{{ __('Delete') }}" class="bottonDelete cursor-pointer"/>
                </form>
                @endif
            @endauth
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('No results found for your search.') }}</p>
    @endforelse
</div>
@endsection
