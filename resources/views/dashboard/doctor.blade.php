<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome ') }} {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <section id="services">
                    <h3 class="text-3xl font-bold text-center p-5 text-blue-900">{{ __('OPTIONS') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- Citas -->
                        <a href="{{ route('events.index') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/budget.png') }}" alt="Citas" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('APPOINTMENTS') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Schedule, modify, and view patient appointments.') }}</p>
                            </div>
                        </a>
                        <!-- Presupuestos -->
                        <a href="{{ route('treatments.index') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/budget.png') }}" alt="Presupuestos" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('BUDGETS') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Create and manage budgets efficiently.') }}</p>
                            </div>
                        </a>

                        <!-- Pacientes -->
                        <a href="{{ route('patient.index') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/patient.png') }}" alt="Pacientes" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('PATIENTS') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Access and manage patient records and data.') }}</p>
                            </div>
                        </a>

                        <!-- Archivos -->
                        <a href="{{ route('files.select') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/file.png') }}" alt="Archivos" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('FILES') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Upload, visualize, and manage radiography or tomography files.') }}</p>
                            </div>
                        </a>

                        <!-- Finanzas 
                        <a href="{{ route('tomography.new') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/finance.png') }}" alt="Finanzas" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('FINANCES') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Track and manage financial operations and reports.') }}</p>
                            </div>
                        </a>

                         Reportes
                        <a href="{{ route('tomography.new') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/report.png') }}" alt="Reportes" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('REPORTS') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">Generate, review, and download detailed reports.</p>
                            </div>
                        </a>
                        -->
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>