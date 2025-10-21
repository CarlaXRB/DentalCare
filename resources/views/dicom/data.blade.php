
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar archivo DICOM</title>
</head>
<body>
    <h1>Cargar archivo DICOM para ver los datos</h1>

    <form action="{{ route('dicom.updata') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        <label for="dicom_file">Selecciona un archivo DICOM:</label>
        <input type="file" name="dicom_file" id="dicom_file" required>
        <button type="submit">Subir archivo</button>
    </form>

    @isset($dicomInfo)
    <h2>Información del Paciente</h2>
    <p><strong>Paciente:</strong> {{ $patientName }}</p>
    <p><strong>ID del Paciente:</strong> {{ $patientID }}</p>
    <p><strong>Modalidad:</strong> {{ $modality }}</p>
    <p><strong>Fecha del Estudio:</strong> {{ $studyDate }}</p>
    <p><strong>Tamaño de la imagen:</strong> {{ $rows }}x{{ $columns }}</p>

    <h3>Metadatos completos:</h3>
    <pre>
        {{ json_encode($dicomInfo, JSON_PRETTY_PRINT) }}
    </pre>
    @endisset
</body>
</html>