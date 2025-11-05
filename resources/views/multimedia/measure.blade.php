@extends('layouts._partials.layout')
@section('title', __('Medición de Estudio'))
@section('subtitle', __('Medición de Estudio'))

@section('content')
<div class="flex justify-end pt-5 pr-5">
    <a href="{{ route('multimedia.show', $study->id) }}" class="botton1">Volver al Estudio</a>
</div>

<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
        Medición Interactiva — {{ $study->name_patient }}
    </h1>

    <div class="flex justify-center mb-4">
        <select id="imageSelect" class="border border-gray-300 rounded-lg p-2">
            @foreach($imageUrls as $url)
                <option value="{{ $url }}">Imagen {{ $loop->iteration }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-6">
        <canvas id="measureCanvas" class="border rounded-lg w-full h-[600px]"></canvas>

        <div class="mt-4 flex justify-between items-center">
            <p id="measureOutput" class="font-semibold text-gray-700">
                Haz clic en dos puntos para medir distancia.
            </p>
            <button id="resetBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                Reiniciar
            </button>
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById('measureCanvas');
const ctx = canvas.getContext('2d');
const imageSelect = document.getElementById('imageSelect');
const output = document.getElementById('measureOutput');
const resetBtn = document.getElementById('resetBtn');

let img = new Image();
let points = [];

function loadImage(url) {
    img.onload = () => {
        // Ajustar tamaño del canvas según imagen
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);
        points = [];
        output.textContent = "Haz clic en dos puntos para medir distancia.";
    };
    img.src = url;
}

imageSelect.addEventListener('change', (e) => loadImage(e.target.value));

// Cargar la primera imagen automáticamente
if (imageSelect.options.length > 0) {
    loadImage(imageSelect.value);
}

canvas.addEventListener('click', (e) => {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    points.push({ x, y });

    if (points.length === 2) {
        drawMeasurement();
    } else {
        drawPoint(x, y);
    }
});

function drawPoint(x, y) {
    ctx.fillStyle = "red";
    ctx.beginPath();
    ctx.arc(x, y, 5, 0, 2 * Math.PI);
    ctx.fill();
}

function drawMeasurement() {
    const [p1, p2] = points;
    ctx.strokeStyle = "lime";
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(p1.x, p1.y);
    ctx.lineTo(p2.x, p2.y);
    ctx.stroke();

    const distPx = Math.sqrt((p2.x - p1.x)**2 + (p2.y - p1.y)**2);
    output.textContent = `Distancia medida: ${distPx.toFixed(2)} px`;

    points = [];
}

resetBtn.addEventListener('click', () => {
    ctx.drawImage(img, 0, 0);
    points = [];
    output.textContent = "Haz clic en dos puntos para medir distancia.";
});
</script>
@endsection
