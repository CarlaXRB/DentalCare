<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reporte de Estudio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .title-container {
            flex: 1;
            display: flex;
            justify-content: center;
        }
        h1 {
            color: #00027eff;
            font-size: 24px;
            margin: 0;
            text-align: center;
        }
        h2{
            color:#00027eff;
            font-size: 10px;
            margin-left: 10px;
        }
        .logo {
            width: 60px;
            height: auto;
            margin-left: 20px;
        }
        .section {
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .signature {
            margin-top: 80px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #00027eff;
            width: 300px;
            margin-top: 20px;
            margin: 0 auto 5px;
        }
        img.study-image {
            width: 450px;
            height: auto;
            margin-top: 20px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <header>
        <div class="title-container">
            <img src="{{ public_path('storage/assets/images/logoD.png') }}" class="logo" alt="Logo">
            <h1>Reporte Radiológico</h1>
        </div>
    </header>

    <p>
        El paciente {{ $patient->name_patient }} con carnet de identidad {{ $patient->ci_patient }}, nacido en fecha {{ $patient->birth_date }}, con género {{ $patient->gender }},
        se realizó un estudio de 
        @if ($studyType === 'radiography')
        <strong>Radiografía</strong> del tipo {{ $study->radiography_type }} con
            ID de estudio {{ $study->radiography_id }}
            en fecha {{ $study->radiography_date }}
            solicitado por {{ $study->radiography_doctor }}
            realizado por el radiólogo {{ $study->radiography_charge }}.
        @elseif ($studyType === 'tomography')
        <strong>Tomografía</strong> del tipo {{ $study->tomography_type }} con
            ID de estudio {{ $study->tomography_id }}
            en fecha {{ $study->tomography_date }}
            solicitado por {{ $study->tomography_doctor }}
            realizado por el radiólogo {{ $study->tomography_charge }}.
        @elseif ($studyType === 'tool')
        <strong>Radiológico</strong> del tipo {{ $study->radiography->radiography_type ?? $study->tomography->tomography_type }} con
            ID de estudio {{ $study->radiography->radiography_id ?? $study->tomography->tomography_id }}
            en fecha {{ $study->tool_date }}
            solicitado por {{ $study->radiography->radiography_doctor ?? $study->tomography->tomography_doctor }}
            realizado por el radiólogo {{ $study->radiography->radiography_charge ?? $study->tomography->tomography_charge }}.
        @endif
    </p>
    <p><strong>Hallazgos:</strong> {{ $findings }} </p>
    <p><strong>Impresión diagnóstica:</strong> {{ $diagnosis }}</p>
    <p><strong>Conclusiones:</strong> {{ $conclusions }}</p>
    <p><strong>Recomendaciones:</strong> {{ $recommendations }}</p>

    @if (isset($imagePath) && $imagePath && file_exists($imagePath))
        <div class="section" style="text-align: center;">
            <img src="{{ $imagePath }}" alt="Imagen del estudio" class="study-image" />
        </div>
    @endif

    <div class="signature">
        <div class="signature-line"></div>
        <p>Firma</p>
    </div>
</body>
</html>
