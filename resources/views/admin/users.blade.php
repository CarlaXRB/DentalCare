<!-- Table header -->
<div class="grid grid-cols-5 gap-4 border-b border-gray-300 pb-2 mb-3">
    <h3 class="title4 text-center">{{ __('Nombre') }}</h3>
    <h3 class="title4 text-center">{{ __('Email') }}</h3>
    <h3 class="title4 text-center">{{ __('Rol') }}</h3>
    <h3 class="title4 text-center">{{ __('Clínica') }}</h3> <!-- Nueva columna -->
    <h3 class="title4 text-center">{{ __('Acciones') }}</h3>
</div>

<!-- Table body -->
@forelse($users as $user)
<div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 py-3 text-gray-800 hover:bg-gray-50 transition">
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

    <!-- Clinic -->
    <div class="text-center">
        {{ $user->clinic ? $user->clinic->name : __('Sin clínica') }}
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
