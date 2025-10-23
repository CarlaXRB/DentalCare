import pydicom
import json
import sys

# Asegurar que se pasa un argumento
if len(sys.argv) < 2:
    print(json.dumps({"error": "No se proporcionó la ruta del archivo."}))
    sys.exit(1)

file_path = sys.argv[1]

try:
    # Leer el archivo DICOM
    dicom_data = pydicom.dcmread(file_path)

    # Extraer metadatos con seguridad
    response = {
        "dicom_info": {tag: str(dicom_data.get(tag, "N/A")) for tag in dicom_data.keys()},
        "patient_name": str(getattr(dicom_data, "PatientName", "Desconocido")),
        "patient_id": str(getattr(dicom_data, "PatientID", "N/A")),
        "modality": str(getattr(dicom_data, "Modality", "N/A")),
        "study_date": str(getattr(dicom_data, "StudyDate", "N/A")),
        "rows": int(getattr(dicom_data, "Rows", 0)),
        "columns": int(getattr(dicom_data, "Columns", 0))
    }

    # Devolver JSON válido
    print(json.dumps(response))

except Exception as e:
    print(json.dumps({"error": f"Error al leer DICOM: {str(e)}"}))
    sys.exit(1)  # Salir con código de error
