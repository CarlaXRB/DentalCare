@extends('layouts._partials.layout')
@section('title', __('Información General'))
@section('subtitle', __('Descripción general del sistema'))
@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8"><h1 class="title1">{{ __('Registros del Sistema') }}</h1></div>
    <div class="w-4/5 mx-auto mb-5">
        <canvas id="dashboardChart"></canvas>
    </div>
    <section id="services" class="text-center">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-8">
            <div data-chart="pacientes" class="transform hover:scale-105 transition duration-300 p-6 bg-white rounded-lg shadow-md cursor-pointer">
                <h5 class="text-xl font-semibold mb-4 text-blue-700">{{ __('Pacientes') }}</h5>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalPatients }}</p>
            </div>
            <div data-chart="citas" class="transform hover:scale-105 transition duration-300 p-6 bg-white rounded-lg shadow-md cursor-pointer">
                <h5 class="text-xl font-semibold mb-4 text-blue-700">{{ __('Citas') }}</h5>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalEvents }}</p>
            </div>
            <div data-chart="tratamientos" class="transform hover:scale-105 transition duration-300 p-6 bg-white rounded-lg shadow-md cursor-pointer">
                <h5 class="text-l font-semibold mb-4 text-blue-700">{{ __('Tratamientos') }}</h5>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalTreatments }}</p>
            </div>
            <div data-chart="usuarios" class="transform hover:scale-105 transition duration-300 p-6 bg-white rounded-lg shadow-md cursor-pointer">
                <h5 class="text-xl font-semibold mb-4 text-blue-700">{{ __('Usuarios') }}</h5>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalUsers }}</p>
            </div>
            <div data-chart="reportes" class="transform hover:scale-105 transition duration-300 p-6 bg-white rounded-lg shadow-md cursor-pointer">
                <h5 class="text-xl font-semibold mb-4 text-blue-700">{{ __('Reportes') }}</h5>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalReports }}</p>
            </div>
                        <div data-chart="radiografias" class="transform hover:scale-105 transition duration-300 p-6 bg-white rounded-lg shadow-md cursor-pointer">
                <h5 class="text-xl font-semibold mb-4 text-blue-700">{{ __('RX') }}</h5>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalRadiographies }}</p>
            </div>
            <div data-chart="tomografias" class="transform hover:scale-105 transition duration-300 p-6 bg-white rounded-lg shadow-md cursor-pointer">
                <h5 class="text-xl font-semibold mb-4 text-blue-700">{{ __('CT') }}</h5>
                <p class="text-3xl font-bold mt-2 text-gray-800">{{ $totalTomographies }}</p>
            </div>
    </section>
    <div class="p-5"><h1 class="title2 text-center">{{ __('INFORME MENSUAL 2025') }}</h1></div>
    <div class="w-4/5 mx-auto mb-5">
        <canvas id="dashboardMounth"></canvas>
    </div>
    <div class="mt-10 mb-4"><h1 class="title1 text-center">{{ __('RESUMEN') }} 2025</h1></div>
    <div class="overflow-x-auto pl-10 pr-10">
        <table class="min-w-full border border-blue-400 rounded-lg overflow-hidden bg-white text-gray-700">
            <thead class="bg-blue-400 text-white">
                <tr>
                    <th class="px-4 py-2 border border-blue-400">{{ __('Mes') }}</th>
                    <th class="px-4 py-2 border border-blue-400">{{ __('Pacientes') }}</th>
                    <th class="px-4 py-2 border border-blue-400">{{ __('Citas') }}</th>
                    <th class="px-4 py-2 border border-blue-400">{{ __('Tratamientos') }}</th>
                    <th class="px-4 py-2 border border-blue-400">{{ __('RX') }}</th>
                    <th class="px-4 py-2 border border-blue-400">{{ __('CT') }}</th>
                    <th class="px-4 py-2 border border-blue-400">{{ __('Reportes') }}</th>
                    <th class="px-4 py-2 border border-blue-400">{{ __('Usuarios') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $months = [
                        1 => __('Enero'), 2 => __('Febrero'), 3 => __('Marzo'), 4 => __('Abril'),
                        5 => __('Mayo'), 6 => __('Junio'), 7 => __('Julio'), 8 => __('Agosto'),
                        9 => __('Septiembre'), 10 => __('Octubre'), 11 => __('Noviembre'), 12 => __('Diciembre')
                    ];
                @endphp

                @foreach($months as $i => $monthName)
                    <tr class="text-center">
                        <td class="px-4 py-2 border border-blue-400">{{ $monthName }}</td>
                        <td class="px-4 py-2 border border-blue-400">{{ $monthlyPatients[$i-1] ?? 0 }}</td>
                        <td class="px-4 py-2 border border-blue-400">{{ $monthlyEvents[$i-1] ?? 0 }}</td>
                        <td class="px-4 py-2 border border-blue-400">{{ $monthlyTreatments[$i-1] ?? 0 }}</td>
                        <td class="px-4 py-2 border border-blue-400">{{ $monthlyRadiographies[$i-1] ?? 0 }}</td>
                        <td class="px-4 py-2 border border-blue-400">{{ $monthlyTomographies[$i-1] ?? 0 }}</td>
                        <td class="px-4 py-2 border border-blue-400">{{ $monthlyReports[$i-1] ?? 0 }}</td>
                        <td class="px-4 py-2 border border-blue-400">{{ $monthlyUsers[$i-1] ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    const dataValues = {!! json_encode([
        
        $totalEvents,
        $totalPatients,
        $totalTreatments,
        $totalRadiographies,
        $totalTomographies,
        $totalReports,
        $totalUsers,
    ]) !!};

    const data = {
        labels: [
             'Citas','Pacientes', 'Tratamientos', 'Radiografías', 'Tomografías', 'Reportes','Usuarios'
        ],
        datasets: [{
            label: 'Registros totales',
            data: dataValues,
            backgroundColor: 'rgba(99, 115, 255, 0.63)',
            borderColor: 'rgba(43, 55, 226, 1)',
            borderWidth: 2,
            borderRadius: 5
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: 'black',
                        font: { size: 14 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'black',
                        font: { size: 12 }
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: 'black',
                        font: { size: 12 }
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    }
                }
            }
        }
    };

    new Chart(
        document.getElementById('dashboardChart'), config
    );

    const monthLabels = [
        'Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
    ];

    const monthlyData = {
        pacientes: @json($monthlyPatients),
        citas: @json($monthlyEvents),
        radiografias: @json($monthlyRadiographies),
        tomografias: @json($monthlyTomographies),
        reportes: @json($monthlyReports),
        usuarios: @json($monthlyUsers),
        tratamientos: @json($monthlyTreatments),
    };
    
    const monthlyGeneral = monthLabels.map((_, i) =>
        monthlyData.pacientes[i] +
        monthlyData.citas[i] +
        monthlyData.radiografias[i] +
        monthlyData.tomografias[i] +
        monthlyData.reportes[i] +
        monthlyData.usuarios[i] +
        monthlyData.tratamientos[i]
    );

    const ctxMonth = document.getElementById('dashboardMounth').getContext('2d');
    let monthChart = new Chart(ctxMonth, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Registros 2025 (General)',
                data: monthlyGeneral,
                borderColor: '#007451ff',  
                backgroundColor: '#008d76cc',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: { color: 'black' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: 'black' },
                    grid: { color: 'rgba(255,255,255,0.06)' }
                },
                x: {
                    ticks: { color: 'black' },
                    grid: { color: 'rgba(255, 255, 255, 0.06)' }
                }
            }
        }
    });
    function updateMonthChart(type) {
        const dataset = type === 'general'
            ? monthlyGeneral
            : monthlyData[type];

        monthChart.data.datasets[0].data = dataset;
        monthChart.data.datasets[0].label =
            type === 'general'
                ? 'Registros 2025 (General)'
                : `Registros 2025 (${type})`;

        monthChart.update();
    }
    document.querySelectorAll('[data-chart]').forEach(el => {
        el.addEventListener('click', e => {
            e.preventDefault();
            const type = el.getAttribute('data-chart');
            updateMonthChart(type);
        });
    });
</script>
@endpush

@endsection
