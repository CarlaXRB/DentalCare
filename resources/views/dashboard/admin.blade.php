<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ¡{{ __('Bienvenido ') }} {{ Auth::user()->name }}!
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <section id="services">
                    <h3 class="text-3xl font-bold text-center p-5 text-blue-900">{{ __('OPCIONES') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- Citas -->
                        <a href="{{ route('events.index') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/calendar.png') }}" alt="Citas" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('Citas') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Programe, modifique y visualice las citas de los pacientes.') }}</p>
                            </div>
                        </a>
                        <!-- Presupuestos -->
                        <a href="{{ route('treatments.index') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/budget.png') }}" alt="Presupuestos" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('Tratamientos') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Cree y gestione tratamientos de manera eficiente.') }}</p>
                            </div>
                        </a>

                        <!-- Pacientes -->
                        <a href="{{ route('patient.index') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/patient.png') }}" alt="Pacientes" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('Pacientes') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Acceder y gestionar registros y datos de pacientes.') }}</p>
                            </div>
                        </a>

                        <!-- Archivos -->
                        <a href="{{ route('files.select') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/file.png') }}" alt="Archivos" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('Archivos') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Subir, visualizar y gestionar archivos de radiografías o tomografías.') }}</p>
                            </div>
                        </a>

                        <a href="{{ route('budgets.index') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/report.png') }}" alt="Reportes" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('Presupuestos') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Gestione, cree, edite y elimine presupuestos.') }}</p>
                            </div>
                        </a>
                        <a href="{{ route('payments.index') }}"
                            class="flex items-center p-6 bg-gray-20 rounded-2xl shadow-md hover:shadow-xl transition transform hover:scale-105 duration-300">
                            <img src="{{ asset('storage/assets/images/finance.png') }}" alt="Finanzas" class="w-32 h-32 object-contain mr-6">
                            <div>
                                <h5 class="text-2xl font-semibold text-blue-600">{{ __('Pagos') }}</h5>
                                <p class="text-gray-600 mt-2 text-sm">{{ __('Realizar seguimiento y gestionar operaciones pagos.') }}</p>
                            </div>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>