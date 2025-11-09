<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tratamiento {{ $treatment->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #040035ff;
            margin: 0 0 15px 0;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .right {
            text-align: right;
        }
        .totals td {
            font-weight: bold;
        }
        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .info {
            margin-bottom: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>Presupuesto de Tratamiento</h1>

    <p class="info"><strong>Nota:</strong> Los valores presentados a continuación representan un <em>aproximado del costo del tratamiento necesario</em>. Pueden estar sujetos a cambios según el procedimiento final y materiales utilizados.</p>

    <p><strong>Paciente:</strong> {{ $treatment->name ?? 'N/A' }}</p>
    <p><strong>CI:</strong> {{ $treatment->ci_patient }}</p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th class="right">Cantidad</th>
                <th class="right">Costo Unitario</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $budgetCodes = json_decode($treatment->budget_codes, true) ?? [];
            @endphp

            @foreach ($budgets as $budget)
                @php
                    $quantity = $budgetCodes[$budget->id] ?? 1;
                    $lineTotal = $budget->total_amount * $quantity;
                @endphp
                <tr>
                    <td>{{ $budget->budget }}</td>
                    <td>{{ $budget->description }}</td>
                    <td class="right">{{ $quantity }}</td>
                    <td class="right">{{ number_format($budget->total_amount, 2) }}</td>
                    <td class="right">{{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach

            <tr class="totals">
                <td colspan="4" class="right">Subtotal</td>
                <td class="right">{{ number_format($treatment->total_amount, 2) }}</td>
            </tr>
            <tr class="totals">
                <td colspan="4" class="right">Descuento</td>
                <td class="right">{{ number_format($treatment->discount, 2) }}</td>
            </tr>
            <tr class="totals">
                <td colspan="4" class="right">Total Final</td>
                <td class="right">{{ number_format($treatment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p><strong>Detalles:</strong> {{ $treatment->details ?? 'Sin información adicional' }}</p>

    <footer>
        <p>Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Emitido por: <strong>{{ $author }}</strong></p>
    </footer>
</body>
</html>
