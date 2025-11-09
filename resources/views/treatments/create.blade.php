@extends('layouts._partials.layout')
@section('title', __('Crear Tratamiento'))
@section('subtitle')
{{ __('Crear Tratamiento') }}
@endsection
@section('content')

{{-- Button to go back --}}
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('treatments.index') }}" class="botton1">{{ __('Tratamientos') }}</a>
</div>

<div class="bg-white rounded-lg max-w-5xl mx-auto p-5">
    <form method="POST" id="treatmentForm" action="{{ route('treatments.store') }}" enctype="multipart/form-data" id="treatmentForm">
        @csrf
        <h1 class="title1 text-center mb-8">{{ __('Información del tratamiento') }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="title4 block mb-2">{{ __('Nombre del paciente') }}:</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('name') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('C.I.') }}:</label>
                <input type="text" name="ci_patient" value="{{ old('ci_patient') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                @error('ci_patient') <p class="error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="mb-6">
            <h3 class="title4 mb-2">{{ __('Selecciona un tratamiento') }}:</h3>
            <div class="flex items-center gap-3">
                <select id="budgetSelect" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white">
                    <option value="">{{ __('-- Selecciona un tratamiento --') }}</option>
                    @foreach($budgets as $budget)
                    <option value="{{ $budget->id }}" data-description="{{ $budget->description }}" data-procedure="{{ $budget->procedure }}" data-price="{{ $budget->total_amount }}">
                        {{ $budget->procedure }} — ${{ number_format($budget->total_amount, 2) }}
                    </option>
                    @endforeach
                </select>
                <button type="button" id="addBudget" class="botton2">{{ __('Añadir') }}</button>
            </div>
        </div>

        {{-- Selected Budgets list --}}
        <div id="selectedBudgets" class="mb-6 hidden">
            <h3 class="title4 mb-2">{{ __('Cantidad') }}:</h3>
            <div id="budgetsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
        </div>

        {{-- Totals and discount --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 mt-8">
            <div>
                <label class="title4 block mb-2">{{ __('Costo Total (Bs)') }}:</label>
                <input type="text" name="total_amount" id="totalAmount" readonly
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('Descuento') }}:</label>
                <input type="number" name="discount" id="discount" min="0" value="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
            </div>
            <div>
                <label class="title4 block mb-2">{{ __('Tipo de Descuento') }}:</label>
                <select id="discountType" name="discount_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
                <option value="fixed">{{ __('Cantidad fija') }}</option>
                <option value="percentage">{{ __('Porcentaje (%)') }}</option>
                </select>
            </div>

            <div>
                <label class="title4 block mb-2">{{ __('Costo Final (Bs)') }}:</label>
                <input type="text" name="amount" id="amount" readonly
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" />
            </div>
        </div>
        <div>
            <label class="title4 block mb-2">{{ __('Detalles') }}:</label>
            <textarea name="details" value="{{ old('details') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-300 focus:ring-opacity-50 transition duration-200 ease-in-out text-gray-700 bg-white" rows="2"></textarea>
                @error('details') <p class="error mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex justify-center gap-4 mt-6">
            <button type="submit" class="botton2">{{ __('Crear Tratamiento') }}</button>
        </div>
    </form>
</div>

{{-- Script for dynamic budgets and calculations --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addButton = document.getElementById('addBudget');
        const budgetSelect = document.getElementById('budgetSelect');
        const container = document.getElementById('budgetsContainer');
        const selectedSection = document.getElementById('selectedBudgets');

        const totalAmountInput = document.getElementById('totalAmount');
        const discountInput = document.getElementById('discount');
        const discountTypeSelect = document.getElementById('discountType');
        const amountInput = document.getElementById('amount');

        let selectedBudgets = {};

        addButton.addEventListener('click', function() {
            const selectedOption = budgetSelect.options[budgetSelect.selectedIndex];
            const id = selectedOption.value;
            if (!id) return;
            if (selectedBudgets[id]) return alert('This budget is already added.');

            const procedure = selectedOption.dataset.procedure;
            const description = selectedOption.dataset.description;
            const price = parseFloat(selectedOption.dataset.price);

            selectedBudgets[id] = {
                price: price,
                quantity: 1
            };

            selectedSection.classList.remove('hidden');

            const div = document.createElement('div');
            div.classList.add('flex', 'items-center', 'gap-2', 'border', 'p-3', 'rounded-lg', 'hover:bg-gray-50');
            div.dataset.id = id;
            div.innerHTML = `
            <input type="checkbox" name="selected_budgets[]" value="${id}" class="budget-checkbox" checked data-price="${price}">
            <div>
                <span class="font-semibold">${procedure}</span><br>
                <span class="text-gray-600">${description}</span><br>
                <span class="text-gray-800 font-bold">$${price.toFixed(2)}</span>
            </div>
            <div class="ml-auto flex gap-2 items-center">
                <input type="number" name="quantity[${id}]" min="1" value="1"
                    class="border rounded-lg p-1 w-16 quantity-input" data-price="${price}">
                <button type="button" class="removeBudget text-red-600 hover:text-red-800 text-sm">×</button>
            </div>
        `;
            container.appendChild(div);

            updateTotals();
        });

        // Remove budget
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeBudget')) {
                const parent = e.target.closest('div[data-id]');
                const id = parent.dataset.id;
                delete selectedBudgets[id];
                parent.remove();
                updateTotals();
                if (Object.keys(selectedBudgets).length === 0) selectedSection.classList.add('hidden');
            }
        });

        // Update totals when inputs change
        container.addEventListener('input', updateTotals);
        discountInput.addEventListener('input', updateTotals);
        discountTypeSelect.addEventListener('change', updateTotals);

        function updateTotals() {
            let total = 0;
            const rows = container.querySelectorAll('[data-id]');
            rows.forEach(row => {
                const checkbox = row.querySelector('.budget-checkbox');
                const quantityInput = row.querySelector('.quantity-input');
                if (checkbox.checked) {
                    const quantity = parseInt(quantityInput.value) || 1;
                    const price = parseFloat(checkbox.dataset.price);
                    total += price * quantity;
                }
            });

            totalAmountInput.value = total.toFixed(2);

            const discountType = discountTypeSelect.value;
            const discountValue = parseFloat(discountInput.value) || 0;
            let finalAmount = total;

            if (discountType === 'percentage') {
                const discount = (discountValue / 100) * total;
                finalAmount = total - discount;
            } else {
                finalAmount = total - discountValue;
            }

            amountInput.value = finalAmount.toFixed(2);
        }
    });
    document.getElementById('treatmentForm').addEventListener('submit', function(e) {
        e.preventDefault(); // evita que recargue la página directamente

        const form = this;
        const formData = new FormData(form);

        // Envía la solicitud normalmente
        fetch(form.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    // Convierte la respuesta a un blob (archivo)
                    return response.blob();
                } else {
                    throw new Error('Error al generar el archivo.');
                }
            })
            .then(blob => {
                // Crea un enlace temporal para descargar el archivo
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;

                // Opcional: define el nombre del archivo
                a.download = 'treatment.pdf';
                document.body.appendChild(a);
                a.click();

                // Espera un poco y redirige al index
                setTimeout(() => {
                    window.location.href = "{{ route('treatments.index') }}";
                }, 2000);
            })
            .catch(error => {
                console.error(error);
                alert('Hubo un problema al generar el archivo.');
            });
    });
</script>
@endsection