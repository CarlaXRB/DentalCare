import sys
import cv2
import numpy as np

def main():
    if len(sys.argv) < 2:
        print("Error: no se recibió imagen")
        return

    image_path = sys.argv[1]
    image = cv2.imread(image_path)

    if image is None:
        print("Error al cargar imagen")
        return

    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    edges = cv2.Canny(gray, 50, 150)

    # Ejemplo de cálculo (solo un placeholder)
    area = np.sum(edges > 0)
    print(f"Área detectada (pixeles): {area}")

if __name__ == "__main__":
    main()
