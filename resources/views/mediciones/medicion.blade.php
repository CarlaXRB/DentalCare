@extends('layouts._partials.layout')
@section('title', __('Subir Estudio Multimedia'))
@section('subtitle')
{{ __('Subir Estudio Multimedia') }}
@endsection

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Medición de Estudio</h1>

    <div class="bg-white shadow-lg rounded-xl p-6 space-y-4">
        <form id="measureForm" enctype="multipart/form-data">
            @csrf
            <label class="block font-semibold text-gray-700">Seleccionar imagen del estudio</label>
            <input type="file" name="image"
                class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"
                accept="image/png, image/jpeg, image/jpg" required>

            <button type="button" id="measureBtn"
                class="mt-4 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-6 py-2 rounded-lg">
                Medir Estudio
            </button>
        </form>

        <div id="result" class="hidden mt-6 p-4 bg-gray-100 rounded-lg">
            <h2 class="font-bold text-gray-700">Resultado:</h2>
            <pre id="output" class="text-sm text-gray-800 mt-2"></pre>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white p-6 rounded-xl max-w-2xl w-full relative shadow-xl">
            <button id="closeModal"
                class="absolute top-2 right-3 text-gray-500 hover:text-red-500 font-bold text-lg">&times;</button>
            <h2 class="text-xl font-bold mb-4">Medir Estudio</h2>
            <canvas id="measureCanvas" class="border rounded-lg w-full h-[400px]"></canvas>
        </div>
    </div>
</div>

<script>
    const measureBtn = document.getElementById('measureBtn');
    const resultBox = document.getElementById('result');
    const output = document.getElementById('output');
    const modal = document.getElementById('modal');
    const closeModal = document.getElementById('closeModal');

    measureBtn.addEventListener('click', async () => {
        const form = document.getElementById('measureForm');
        const formData = new FormData(form);

        // Mostrar modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const response = await fetch('{{ route("analyze") }}', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.error) {
            output.innerText = "Error: " + data.error;
        } else {
            output.innerText = data.result;
            resultBox.classList.remove('hidden');
        }
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
</script>
@endsection