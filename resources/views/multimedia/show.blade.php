@extends('layouts.app') {{-- Reemplaza con tu layout base si es diferente --}}

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow-xl rounded-lg p-6">
        <div class="flex justify-between items-center mb-6 border-b pb-2">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-file-alt text-purple-500 mr-2"></i> Detalle del Archivo
            </h1>
            <a href="{{ route('multimedia.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Volver al listado
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Columna de Visualización (Imagen) --}}
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold text-gray-700 mb-3">Visualización</h2>
                <div class="border border-gray-300 rounded-lg p-2 bg-gray-100 flex justify-center items-center overflow-hidden">
                    {{-- Si es una imagen (PNG, JPG), la mostramos --}}
                    @if (Str::startsWith($file->file_type, 'image/'))
                        <img src="{{ asset($file->file_path) }}" 
                             alt="{{ $file->file_name }}" 
                             class="max-w-full h-auto rounded-lg shadow-lg"
                             style="max-height: 80vh;">
                    @else
                        {{-- Mensaje para otros tipos de archivo --}}
                        <div class="text-center py-20 text-gray-500">
                            <i class="fas fa-file-archive text-5xl mb-3"></i>
                            <p class="text-lg">Tipo de archivo no visualizable en línea ({{ $file->file_type }}).</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Columna de Información del Archivo --}}
            <div class="md:col-span-1">
                <h2 class="text-xl font-semibold text-gray-700 mb-3">Información</h2>
                <div class="space-y-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p><strong>Archivo:</strong> <span class="block font-mono text-sm text-gray-600">{{ $file->file_name }}</span></p>
                    <p><strong>Paciente:</strong> <span class="block text-gray-600">{{ $file->name_patient }}</span></p>
                    <p><strong>C.I.:</strong> <span class="block text-gray-600">{{ $file->ci_patient }}</span></p>
                    <p><strong>Tipo de Estudio:</strong> <span class="block text-gray-600">{{ ucfirst($file->study_type) }}</span></p>
                    <p><strong>Tamaño:</strong> <span class="block text-gray-600">{{ $file->readable_size }}</span></p>
                    <p><strong>Tipo MIME:</strong> <span class="block text-gray-600">{{ $file->file_type }}</span></p>
                    <p><strong>Ruta Pública:</strong> <span class="block font-mono text-xs text-gray-500 truncate">{{ asset($file->file_path) }}</span></p>
                    <p><strong>Fecha de Carga:</strong> <span class="block text-gray-600">{{ $file->created_at->format('d/m/Y H:i') }}</span></p>
                </div>

                {{-- Acciones --}}
                <div class="mt-6 flex flex-col space-y-3">
                    <a href="{{ asset($file->file_path) }}" download 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 transition duration-150">
                        <i class="fas fa-download mr-2"></i> Descargar Archivo
                    </a>
                    
                    <form action="{{ route('multimedia.destroy', $file->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este archivo y su registro?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-600 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 transition duration-150">
                            <i class="fas fa-trash mr-2"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
