---
description: Cómo optimizar y convertir imágenes a WebP en el proyecto BAO
---

Este workflow asegura que todas las imágenes del proyecto mantengan la mejor calidad y rendimiento mediante el formato WebP.

1. Agrega las nuevas imágenes (PNG, JPG, JPEG) a la carpeta `public/images` (o sus subdirectorios).
// turbo
2. Ejecuta el script de conversión:
```bash
node scripts/convert-to-webp.js
```
3. Verifica que se hayan generado los archivos `.webp` correspondientes.
4. Actualiza las referencias en el código (`.astro`, `.css`) para usar la extensión `.webp`.
5. (Opcional) Elimina los archivos originales si ya no son necesarios, una vez que la versión WebP esté verificada.
