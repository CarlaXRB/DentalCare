@extends('layouts._partials.layout')
@section('title','Usuarios')
@section('subtitle')
    {{ __('Usuarios') }}
@endsection

@section('content')

    <!-- Create user button -->
    <div class="flex justify-end p-5 pb-1">
        <a href="{{ route('admin.create') }}" class="botton1">{{ __('Crear Usuario') }}</a>
    </div>


<!-- Main title -->
<h1 class="title1 text-center">{{ __('Lista de Usuarios') }}</h1>

<!-- Users table -->
<div class="max-w-6xl mx-auto bg-white rounded-xl p-3 text-gray-900 shadow-md">
    <!-- Table header -->
    <div class="grid grid-cols-4 gap-4 border-b border-gray-300 pb-2 mb-3">
        <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
        <h3 class="title4 text-center">{{ __('Email') }}</h3>
        <h3 class="title4 text-center">{{ __('Rol') }}</h3>
    </div>

    <!-- Table body -->
    @forelse($users as $user)
    <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
        <!-- Name -->
        <div class="text-center">
            {{ $user->name }}
        </div>

        <!-- Email -->
        <div class="text-center">
            {{ $user->email }}
        </div>

        <!-- Role -->
        <div class="text-center">
            {{ ucfirst($user->role) }}
        </div>

        <!-- Actions -->
        <div class="flex justify-center gap-3">
            @auth
                @if(Auth::user()->role === 'admin')
                <form method="POST" action="{{ route('admin.destroy', $user->id) }}"
                      onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');">
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

    <!-- Pagination -->
    <div class="pt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
