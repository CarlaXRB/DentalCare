<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Radiológico</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 40px;
    color: #333333;
    line-height: 1.6;
    }
    h1, h2, h3 {
        color: #0D47A1;
    }
    h1 {
        text-align: center;
        font-size: 28px;
        margin-bottom: 10px;
    }
    h1 span {
        font-size: 18px;
        display: block;
        margin-top: 5px;
        color: #1976D2;
    }
    h2 {
        border-bottom: 2px solid #1976D2;
        padding-bottom: 5px;
        margin-bottom: 15px;
        font-size: 22px;
    }
    p {
        margin: 8px 0;
    }
    p b {
        color: #0D47A1;
    }
    .section {
        margin-bottom: 30px;
    }
    .signature-section {
        margin-top: 100px;
        text-align: center;
    }
    .signature-line {
        margin-top: 30px;
        border-top: 1px solid #1976D2;
        width: 300px;
        margin-left: auto;
        margin-right: auto;
    }
    .footer-section {
        text-align: center;
        font-size: 12px;
        color: #616161;
        padding: 10px;
        font-family: 'Arial', sans-serif;
    }
    </style>
</head>
<body>
    <h1>
        Informe Radiológico
    </h1>
    
    <div class="section">
        <h2>Información del Paciente</h2>
        <p><b>Nombre:</b> {{ $data['name_patient'] }}</p>
        <p><b>ID:</b> {{ $data['ci_patient'] }}</p>
        <p><b>Fecha de nacimiento:</b> {{ $data['birth_date'] }}</p>
        <p><b>Género:</b> {{ $data['gender'] }}</p>
        <p><b>Código de asegurado:</b> {{ $data['insurance_code'] }}</p>
        <p><b>Contacto del paciente:</b> {{ $data['patient_contact'] }}</p>
        <p><b>Contacto de familiar:</b> {{ $data['family_contact'] }}</p>
    </div>

    <div class="section">
        <h2>Información del estudio</h2>
        <p><b>ID de la radiografía:</b> {{ $data['radiography_id'] }}</p>
        <p><b>Fecha del estudio:</b> {{ $data['radiography_date'] }}</p>
        <p><b>Tipo de estudio:</b> {{ $data['radiography_type'] }}</p>
        <p><b>Doctor:</b> {{ $data['radiography_doctor'] }}</p>
        <p><b>Radiólogo:</b> {{ $data['radiography_charge'] }}</p>
    </div>

    <div class="section">
        <h2>Observaciones</h2>
        <p><b>Hallazgos:</b> {{ $data['findings'] }}</p>
        <p><b>Diagnóstico:</b> {{ $data['diagnosis'] }}</p>
        <p><b>Recomendaciones:</b> {{ $data['recommendations'] }}</p>
        <p><b>Conclusiones:</b> {{ $data['conclusions'] }}</p>
    </div>

    @if($imagePath && file_exists($imagePath))
    <div style="text-align: center; margin-top: 20px;">
        <img src="{{ $imagePath }}" style="max-width: 100%; height: auto;">
    </div>
    @endif


    <div class="footer-section">
        <p>Este informe es confidencial y está destinado únicamente para el uso del paciente y su médico tratante. </p>
    </div>

    <div class="signature-section">
        <div class="signature-line"></div>
        <p>Radiólogo</p>
    </div>
</body>
</html>

