
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros DICOM</title>
</head>
<body>
    <h1>Registros DICOM Guardados</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>ID</th>
                <th>Modalidad</th>
                <th>Fecha del Estudio</th>
                <th>Tamaño</th>
                <th>Metadatos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td>{{ $record->patient_name }}</td>
                    <td>{{ $record->patient_id }}</td>
                    <td>{{ $record->modality }}</td>
                    <td>{{ $record->study_date }}</td>
                    <td>{{ $record->rows }}x{{ $record->columns }}</td>
                    <td>
                        <pre>{{ json_encode($record->metadata, JSON_PRETTY_PRINT) }}</pre>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
