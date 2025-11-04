@extends('layouts._partials.layout')
@section('title', __('Subir Estudio Multimedia'))
@section('subtitle')
{{ __('Subir Estudio Multimedia') }}
@endsection

@section('content')
<div class="container mx-auto text-center">
    <h2 class="text-xl font-semibold mb-4">Herramienta de Medición</h2>

    <div id="image-container" style="display:inline-block; position:relative;">
        <img id="target-image"
             src="{{ asset('storage/uploads/ejemplo_radiografia.jpg') }}"
             alt="Imagen dental"
             style="max-width: 90%; cursor: crosshair;">
        <canvas id="canvas" style="position:absolute; top:0; left:0;"></canvas>
    </div>

    <p class="mt-4 text-gray-700">
        Click en dos puntos para medir distancia. Usa la rueda del ratón para hacer zoom.
    </p>

    <div id="result" class="mt-3 text-lg font-bold text-green-700"></div>
</div>

<script>
const img = document.getElementById('target-image');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
let points = [];
let zoom = 1;

function resizeCanvas() {
    canvas.width = img.clientWidth;
    canvas.height = img.clientHeight;
}
window.addEventListener('resize', resizeCanvas);
img.onload = resizeCanvas;

canvas.addEventListener('wheel', e => {
    e.preventDefault();
    zoom += e.deltaY * -0.001;
    zoom = Math.min(Math.max(.5, zoom), 3);
    img.style.transform = `scale(${zoom})`;
});

canvas.addEventListener('click', e => {
    const rect = canvas.getBoundingClientRect();
    const x = (e.clientX - rect.left) / zoom;
    const y = (e.clientY - rect.top) / zoom;
    points.push({x, y});
    if (points.length === 2) {
        medir();
    }
});

async function medir() {
    const res = await fetch('{{ route("measure.run") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({
            image: 'uploads/ejemplo_radiografia.jpg',
            x1: points[0].x, y1: points[0].y,
            x2: points[1].x, y2: points[1].y,
            zoom: zoom
        })
    });
    const data = await res.json();
    document.getElementById('result').textContent = `Distancia: ${data.distance_real.toFixed(2)} px`;
    points = [];
}
</script>
@endsection