@extends('layouts._partials.layout')
@section('title', __('Crear Usuario'))
@section('subtitle')
{{ __('Crear Usuario') }}
@endsection
@section('content')

<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('admin.users')}}" class="botton1">{{ __('Usuarios') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto">
    <form method="POST" action="{{ route('admin.store') }}">
        @csrf
        <h1 class="title1 text-center mb-8">{{ __('Información del Usuario') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="title4 block mb-2">{{ __('Nombre') }}:</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" required autofocus/>
                @error('name') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('Email') }}:</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" required/>
                @error('email') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            @if(Auth::user()->role === 'superadmin')
            <div>
                <label class="title4 block mb-2">{{ __('Clínica') }}:</label>
                <select name="clinic_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white">
                    <option value="">{{ __('Selecciona una clínica (opcional)') }}</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}" {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                            {{ $clinic->name }}
                        </option>
                    @endforeach
                </select>
                @error('clinic_id') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            @endif
            <div>
                <label class="title4 block mb-2">{{ __('Rol') }}:</label>
                <select name="rol" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" required>
                    <option value="doctor" {{ old('rol') == 'doctor' ? 'selected' : '' }}>{{ __('Doctor') }}</option>
                    <option value="recepcionist" {{ old('rol') == 'recepcionist' ? 'selected' : '' }}>{{ __('Recepcionista') }}</option>
                    <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>{{ __('Administrador') }}</option>
                </select>
                @error('rol') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('Contraseña') }}:</label>
                <input type="password" name="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" required/>
                @error('password') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="flex justify-center p-5 mt-2">
            <button type="submit" class="botton2">{{ __('Crear Usuario') }}</button>
        </div>
    </form>
</div>
@endsection
