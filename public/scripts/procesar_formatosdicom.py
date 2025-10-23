import os
import sys
import pydicom
import numpy as np
from PIL import Image

def is_dicom(file_path):
    """Verifica si un archivo es DICOM."""
    try:
        pydicom.dcmread(file_path, stop_before_pixels=True)
        return True
    except:
        return False

def dicom_to_png(dicom_path, output_path):
    """Convierte un DICOM a PNG usando Pillow."""
    ds = pydicom.dcmread(dicom_path)
    if 'PixelData' in ds:
        image = ds.pixel_array
        # Normalizar a 0-255
        image = (np.maximum(image, 0) / image.max()) * 255.0
        image = np.uint8(image)
        im = Image.fromarray(image)
        im.save(output_path)

def process_folder(folder_path):
    output_folder = os.path.join(folder_path, "processed")
    os.makedirs(output_folder, exist_ok=True)

    for root, dirs, files in os.walk(folder_path):
        for file in files:
            file_path = os.path.join(root, file)

            # Procesar solo archivos DICOM o archivos sin extensión
            if file.lower().endswith(".dcm") or '.' not in file:
                if is_dicom(file_path):
                    try:
                        ds = pydicom.dcmread(file_path)
                        patient = getattr(ds, 'PatientName', 'Desconocido')
                        study_date = getattr(ds, 'StudyDate', 'Desconocido')
                        modality = getattr(ds, 'Modality', 'N/A')

                        print(f"Procesando {file} | Paciente: {patient} | Fecha: {study_date} | Modalidad: {modality}")

                        # Guardar como PNG
                        png_name = os.path.splitext(file)[0] + ".png"
                        png_path = os.path.join(output_folder, png_name)
                        dicom_to_png(file_path, png_path)

                    except Exception as e:
                        print(f"No se pudo procesar {file}: {e}")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Se requiere la ruta de la carpeta como argumento")
        sys.exit(1)

    folder_path = sys.argv[1]
    process_folder(folder_path)
