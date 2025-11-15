@extends('layouts._partials.layout')
@section('title', __('Información del Usuario'))
@section('subtitle')
{{ __('Información del Usuario') }}
@endsection

@section('content')

{{-- Botón para volver al listado --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('admin.users')}}" class="botton1">{{ __('Usuarios') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto p-6">
    <h1 class="title1 text-center mb-8">{{ __('Detalle del Usuario') }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Nombre --}}
        <div>
            <label class="title4 block mb-2">{{ __('Nombre') }}:</label>
            <p class="text-gray-700">{{ $user->name }}</p>
        </div>

        {{-- CI --}}
        <div>
            <label class="title4 block mb-2">{{ __('CI') }}:</label>
            <p class="text-gray-700">{{ $user->ci }}</p>
        </div>

        {{-- Email --}}
        <div>
            <label class="title4 block mb-2">{{ __('Email') }}:</label>
            <p class="text-gray-700">{{ $user->email }}</p>
        </div>

        {{-- Rol --}}
        <div>
            <label class="title4 block mb-2">{{ __('Rol') }}:</label>
            <p class="text-gray-700">{{ ucfirst($user->role) }}</p>
        </div>

        {{-- Clínica --}}
        <div>
            <label class="title4 block mb-2">{{ __('Clínica') }}:</label>
            <p class="text-gray-700">{{ $user->clinic ? $user->clinic->name : __('No asignada') }}</p>
        </div>

        {{-- Creado por --}}
        <div>
            <label class="title4 block mb-2">{{ __('Creado por') }}:</label>
            <p class="text-gray-700">{{ $user->created_by ? App\Models\User::find($user->created_by)->name : __('No disponible') }}</p>
        </div>

        {{-- Editado por --}}
        <div>
            <label class="title4 block mb-2">{{ __('Última edición por') }}:</label>
            <p class="text-gray-700">{{ $user->edit_by ? App\Models\User::find($user->edit_by)->name : __('No disponible') }}</p>
        </div>
    </div>
</div>
@endsection
