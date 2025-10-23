@extends('layouts._partials.layout')
@section('title', __('Carpeta DICOM'))
@section('subtitle')
    {{ __('Carpeta DICOM') }}
@endsection

@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('tomography.new') }}" class="botton1">{{ __('Menú Tomografías') }}</a>
</div>

<!-- Main title -->
<h1 class="title1 text-center mb-5">{{ __('Carpeta DICOM con Metadatos') }}</h1>

<div class="max-w-4xl mx-auto bg-white rounded-xl p-6 shadow-md">
    <p class="text-gray-800 text-lg mb-5">
        {{ __('Cargue una carpeta con varios archivos DICOM para su análisis y procesamiento. El sistema extraerá y mostrará los metadatos esenciales, optimizando así la gestión de imágenes radiológicas.') }}
    </p>

    <!-- Folder upload -->
    <div class="p-4">
        <div class="flex justify-center mb-4">
            <input type="file" id="folderInput" webkitdirectory directory multiple
                class="border border-blue-300 rounded-md p-3 w-full max-w-md"/>
        </div>

        <div class="flex justify-center">
            <button onclick="uploadFolder()" class="botton2 mt-2">{{ __('Subir Carpeta') }}</button>
        </div>
    </div>

    <!-- Success message & view images -->
    <div id="message" class="hidden text-center mt-5">
        <p id="successMessage" class="text-green-500 font-semibold mb-3"></p>
        <a href="#" id="viewImagesBtn">
            <button class="botton3 mb-2">{{ __('Ver imágenes procesada') }}</button>
        </a>
    </div>
</div>

<!-- JavaScript to handle folder upload -->
<script>
function uploadFolder() {
    let files = document.getElementById("folderInput").files;
    if (files.length === 0) {
        alert("{{ __('Please select a folder first.') }}");
        return;
    }

    let formData = new FormData();
    for (let file of files) {
        formData.append("files[]", file);
    }

    fetch("{{ route('process.folder') }}", {
        method: "POST",
        body: formData,
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("successMessage").innerText = data.message;
        document.getElementById("message").classList.remove("hidden");
        document.getElementById("viewImagesBtn").href = data.folderUrl;
    })
    .catch(error => console.error(error));
}
</script>
@endsection
