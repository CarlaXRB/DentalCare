import pydicom
import numpy as np
import os
import sys
from PIL import Image
from pydicom.misc import is_dicom

if len(sys.argv) < 2:
    print("Error: No se proporcionó el nombre del archivo o carpeta.")
    sys.exit(1)

output_base_path = sys.argv[1] 
input_path = output_base_path

print(f"Procesando: {input_path}")

def process_dicom(file_path, output_path):
    try:
        print(f"Procesando archivo DICOM: {file_path}")

        dataset = pydicom.dcmread(file_path)
        image_data = dataset.pixel_array.astype(np.float32)
        image_data = (image_data - np.min(image_data)) / (np.max(image_data) - np.min(image_data)) * 255
        image_data = image_data.astype(np.uint8)

        image_pil = Image.fromarray(image_data)

        output_name = os.path.splitext(os.path.basename(file_path))[0] + '.png'
        output_file = os.path.join(output_path, output_name)
        image_pil.save(output_file, "PNG")

        print(f"Imagen guardada: {output_file}")

    except Exception as e:
        print(f"Error procesando {file_path}: {str(e)}")

if os.path.isfile(input_path) and is_dicom(input_path):
    process_dicom(input_path, os.path.dirname(input_path))

elif os.path.isdir(input_path):
    for file in os.listdir(input_path):
        file_path = os.path.join(input_path, file)
        if os.path.isfile(file_path) and is_dicom(file_path):
            process_dicom(file_path, input_path)

else:
    print("Error: El archivo o carpeta no existe.")
