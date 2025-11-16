@extends('layouts._partials.layout')
@section('title', __('Usuarios'))
@section('subtitle')
{{ __('Usuarios') }}
@endsection
@section('content')

<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('admin.create') }}" class="botton1">{{ __('Crear Usuario') }}</a>
</div>
<h1 class="title1 text-center">{{ __('Usuarios registrados') }}</h1>
<div class="bg-white rounded-lg max-w-6xl mx-auto p-5 shadow-sm">
    <div class="grid grid-cols-5 gap-4 border-b border-gray-300 pb-2 mb-3 text-gray-700 font-semibold">
        <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
        <h3 class="title4 text-center">{{ __('Email') }}</h3>
        <h3 class="title4 text-center">{{ __('Rol') }}</h3>
        <h3 class="title4 text-center">{{ __('Clínica') }}</h3>
        <h3 class="title4 text-center">{{ __('Acciones') }}</h3>
    </div>
    @forelse($users as $user)
    <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <div class="text-center"><a href="{{ route('admin.show', $user->id) }}" class="flex justify-center hover:text-cyan-600">{{ $user->name }}</a></div>
        <div class="text-center"><a href="{{ route('admin.show', $user->id) }}" class="flex justify-center hover:text-cyan-600">{{ $user->email }}</a></div>
        <div class="text-center"><a href="{{ route('admin.show', $user->id) }}" class="flex justify-center hover:text-cyan-600">{{ ucfirst($user->role) }}</a></div>
        <div class="text-center"><a href="{{ route('admin.show', $user->id) }}" class="flex justify-center hover:text-cyan-600">{{ $user->clinic ? $user->clinic->name : __('Sin clínica') }}</a></div>
        <div class="flex justify-center gap-3">
            @auth
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')
                    {{-- Editar (opcional) --}}
                    <a href="{{ route('admin.edit', $user->id) }}" class="botton1">{{ __('Editar') }}</a>
                    <form method="POST" action="{{ route('admin.destroy', $user->id) }}"
                          onsubmit="return confirm('{{ __('¿Seguro que deseas eliminar este usuario?') }}');">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete cursor-pointer"/>
                    </form>
                @endif
            @endauth
        </div>
    </div>
    @empty
    <p class="text-gray-600 text-center py-4">{{ __('No se registraron usuarios.') }}</p>
    @endforelse
    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection
