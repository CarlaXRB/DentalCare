@extends('layouts.app') {{-- Reemplaza con tu layout base si es diferente --}}

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow-xl rounded-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">
            <i class="fas fa-list-alt text-green-500 mr-2"></i> Listado de Archivos Multimedia
        </h1>

        {{-- Botón y Barra de Búsqueda --}}
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('multimedia.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                <i class="fas fa-plus mr-2"></i> Nuevo Archivo
            </a>
            
            <form action="{{ route('multimedia.search') }}" method="GET" class="w-1/3">
                <div class="relative">
                    <input type="text" name="search" placeholder="Buscar por Nombre, CI o Tipo de Estudio..."
                           class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </form>
        </div>

        {{-- Mensajes de Sesión --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('danger'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">{{ session('danger') }}</div>
        @endif

        {{-- Tabla de Archivos --}}
        @if ($files->isEmpty())
            <div class="text-center py-10 text-gray-500">
                <p>No hay archivos multimedia cargados todavía.</p>
            </div>
        @else
            <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">ID</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nombre del Paciente</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">CI</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nombre del Archivo</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tipo de Estudio</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tamaño</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($files as $file)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $file->id }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $file->name_patient }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $file->ci_patient }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <span class="{{ $file->file_type == 'folder' ? 'font-bold text-blue-600' : '' }}">
                                    @if ($file->file_type != 'folder')
                                        <i class="fas fa-file-image mr-1 text-purple-500"></i>
                                    @else
                                        <i class="fas fa-folder-open mr-1 text-orange-500"></i>
                                    @endif
                                    {{ Str::limit($file->file_name, 35) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ucfirst($file->study_type) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $file->readable_size }}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium">
                                @if ($file->file_type != 'folder')
                                <a href="{{ route('multimedia.show', $file->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Ver Detalle"><i class="fas fa-eye"></i></a>
                                @endif
                                
                                <form action="{{ route('multimedia.destroy', $file->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de que desea eliminar este archivo? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection