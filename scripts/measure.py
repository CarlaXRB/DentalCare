import sys, math, json

# Recibir argumentos
image_path = sys.argv[1]
x1, y1, x2, y2, zoom = map(float, sys.argv[2:7])

# Calcular distancia en píxeles
dist_pixels = math.sqrt((x2 - x1)**2 + (y2 - y1)**2)

# Ajustar según zoom
dist_real = dist_pixels / zoom

# Enviar salida a Laravel
print(json.dumps({
    "distance_pixels": dist_pixels,
    "distance_real": dist_real
}))
