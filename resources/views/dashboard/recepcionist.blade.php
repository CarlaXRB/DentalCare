<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Recepcionista') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <section id="services" class="text-center p-6">
                    <h3 class="txt-title2">OPCIONES</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">

                        <a href="{{ route('patient.index') }}" class="transform hover:scale-105 transition duration-300 p-6">
                            <h5 class="text-2xl font-semibold mb-4 text-cyan-700 dark:text-cyan-300 underline decoration-cyan-400 underline-offset-4">
                                PACIENTES
                            </h5>
                            <img class="mx-auto mb-2" src="{{ asset('assets/images/patient.png') }}" width="175" height="175" alt="Pacientes">
                        </a>

                        <a href="{{ route('events.index') }}" class="transform hover:scale-105 transition duration-300 p-6">
                            <h5 class="text-2xl font-semibold mb-4 text-purple-700 dark:text-cyan-300 underline decoration-cyan-400 underline-offset-4">
                                CITAS
                            </h5>
                            <img class="mx-auto mb-2" src="{{ asset('assets/images/citas.png') }}" width="170" height="170" alt="Citas">
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
