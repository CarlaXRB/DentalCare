@extends('layouts._partials.layout')
@section('title', __('Subir Estudio Multimedia'))
@section('subtitle')
{{ __('Subir Estudio Multimedia') }}
@endsection

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Medición de Estudio</h1>

    <div class="bg-white shadow-lg rounded-xl p-6 space-y-4">
        <form id="measureForm" enctype="multipart/form-data" method="POST">
            @csrf
            <label class="block font-semibold text-gray-700 mb-2">Seleccionar imagen del estudio</label>
            <input type="file" name="image"
                class="border-gray-300 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-cyan-500"
                accept="image/png, image/jpeg, image/jpg" required>

            <button type="submit"
                class="mt-4 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-6 py-2 rounded-lg">
                Medir Estudio
            </button>
        </form>

        <div id="result" class="hidden mt-6 p-4 bg-gray-100 rounded-lg">
            <h2 class="font-bold text-gray-700">Resultado:</h2>
            <pre id="output" class="text-sm text-gray-800 mt-2"></pre>
        </div>
    </div>
</div>

<script>
document.getElementById('measureForm').addEventListener('submit', async (e) => {
    e.preventDefault(); // Evitar recargar la página

    const form = e.target;
    const formData = new FormData(form);

    const resultBox = document.getElementById('result');
    const output = document.getElementById('output');

    resultBox.classList.add('hidden');
    output.innerText = "Procesando...";

    try {
        const response = await fetch('{{ route("analyze") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        if (data.error) {
            output.innerText = "Error: " + data.error;
        } else {
            output.innerText = data.result;
            resultBox.classList.remove('hidden');
        }
    } catch (err) {
        output.innerText = "Error en la conexión: " + err;
    }
});
</script>
@endsection
