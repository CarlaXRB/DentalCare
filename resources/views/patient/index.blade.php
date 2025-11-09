@extends('layouts._partials.layout')
@section('title','Pacientes')
@section('subtitle')
    {{ __('Pacientes') }}
@endsection

@section('content')
<div class="flex justify-between items-center p-5 pb-1">
    <!-- Buscador -->
    <form method="POST" action="{{ route('patient.search') }}" class="flex gap-3 items-center">
        @csrf
        <input type="text" name="search" placeholder="{{ __('Buscar paciente...') }}" 
            class="px-4 py-2 rounded-full border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500"/>
        <input class="botton2" type="submit" value="{{ __('Buscar') }}" />
    </form>
    <a href="{{ route('patient.create') }}" class="botton1">{{ __('Crear Paciente') }}</a>
</div>
<h1 class="title1 text-center">{{ __('Lista de Pacientes') }}</h1>
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <div class="grid grid-cols-5 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Carnet de Identidad') }}</h3>
        <h3 class="title4 text-center">{{ __('Nombre del Paciente') }}</h3>
        <h3 class="title4 text-center">{{ __('Fecha de nacimiento') }}</h3>
        <h3 class="title4 text-center">{{ __('Celular') }}</h3>
    </div>

    @forelse($patients as $patient)
    <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <div class="text-center">
            <a href="{{ route('patient.show', $patient->id) }}" class="txt hover:text-cyan-600">{{ $patient->ci_patient }}</a>
        </div>
        <div class="text-center">
            <a href="{{ route('patient.show', $patient->id) }}" class="txt hover:text-cyan-600">{{ $patient->name_patient }}</a>
        </div>
        <div class="text-center">
            <a href="{{ route('patient.show', $patient->id) }}" class="txt hover:text-cyan-600">{{ $patient->birth_date }}</a>
        </div>
        <div class="text-center">
            <a href="{{ route('patient.show', $patient->id) }}" class="txt hover:text-cyan-600">{{ $patient->patient_contact }}</a>
        </div>
        <div class="flex justify-center gap-3">
            <a href="{{ route('patient.edit', $patient->id) }}" class="botton3">{{ __('Editar') }}</a>

            @auth
                @if(Auth::user()->role === 'admin')  
                <form method="POST" 
                      action="{{ route('patient.destroy', $patient->id) }}" 
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
    <p class="text-gray-600 text-center py-4">{{ __('Aún no hay pacientes registrados.') }}</p>
    @endforelse

    <!-- Paginación -->
    <div class="pt-4">
        {{ $patients->links() }}
    </div>
</div>
@endsection
