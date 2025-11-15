@extends('layouts._partials.layout')
@section('title', __('Editar Usuario'))
@section('subtitle')
{{ __('Editar Usuario') }}
@endsection

@section('content')

{{-- Botón para volver al listado --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('admin.users')}}" class="botton1">{{ __('Usuarios') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    <form method="POST" action="{{ route('admin.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h1 class="title1 text-center mb-8">{{ __('Editar Información del Usuario') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nombre --}}
            <div>
                <label class="title4 block mb-2">{{ __('Nombre') }}:</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" required/>
                @error('name') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="title4 block mb-2">{{ __('Email') }}:</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" required/>
                @error('email') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Clínica --}}
            <div>
                <label class="title4 block mb-2">{{ __('Clínica') }}:</label>
                <select name="clinic_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white">
                    <option value="">{{ __('Selecciona una clínica (opcional)') }}</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}" {{ old('clinic_id', $user->clinic_id) == $clinic->id ? 'selected' : '' }}>
                            {{ $clinic->name }}
                        </option>
                    @endforeach
                </select>
                @error('clinic_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Rol --}}
            <div>
                <label class="title4 block mb-2">{{ __('Rol') }}:</label>
                <select name="rol" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" required>
                    <option value="doctor" {{ old('rol', $user->role) == 'doctor' ? 'selected' : '' }}>{{ __('Doctor') }}</option>
                    <option value="recepcionist" {{ old('rol', $user->role) == 'recepcionist' ? 'selected' : '' }}>{{ __('Recepcionista') }}</option>
                    <option value="admin" {{ old('rol', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('Administrador') }}</option>
                </select>
                @error('rol') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Contraseña opcional --}}
            <div class="md:col-span-2">
                <label class="title4 block mb-2">{{ __('Contraseña (opcional)') }}:</label>
                <input type="password" name="password" placeholder="{{ __('Dejar vacío si no desea cambiarla') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white"/>
                @error('password') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Botón de actualización centrado --}}
        <div class="flex justify-center p-5 mt-2">
            <button type="submit" class="botton2">{{ __('Actualizar Usuario') }}</button>
        </div>
    </form>
</div>
@endsection
