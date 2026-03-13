---
name: bao-design-system
description: BAO Organization — Línea gráfica oficial de la página web. Define tipografías, colores, íconos y estilos de componentes para mantener consistencia visual en todos los prompts futuros.
---

# BAO Organization — Design System

## Proyecto
- **Framework**: Astro (static site)
- **Ruta del proyecto**: `c:\Marcos\Proyectos\BAO`
- **Página principal**: `src/pages/index.astro`
- **Estilos globales**: `src/styles/global.css`
- **Layout**: `src/layouts/Layout.astro`

---

## Paleta de Colores

| Nombre         | Hex       | Uso principal                                   |
|---------------|-----------|------------------------------------------------|
| Navy           | `#1D293C` | Fondos oscuros, títulos hero                    |
| Gold           | `#D5AD7F` | Acentos, dividers, botones primarios            |
| Gold Dark      | `#C49A6A` | Hover de botones dorados                        |
| Beige Light    | `#F8F7F4` | Fondo de secciones claras                       |
| Text Dark      | `#1D1D1B` | Texto principal                                 |
| Muted Gray     | `#6B6B6B` | Texto secundario                                |
| Section Navy   | `#2E394B` | Color texto en VALUE PROPOSITION y btn-card     |
| Body Text Sec2 | `#4f4f59` | Párrafo de VALUE PROPOSITION                    |

---

## Tipografías

### Global (definidas en `global.css` via `@theme`)
| Variable CSS         | Familia              | Uso                        |
|---------------------|----------------------|---------------------------|
| `--font-heading`    | Cormorant Garamond   | Títulos generales          |
| `--font-label`      | Raleway              | Labels, etiquetas          |
| `--font-body`       | Inter                | Cuerpo de texto general    |

### Fuentes adicionales (cargadas en `Layout.astro`)
| Familia             | Origen           | Uso                                              |
|--------------------|-----------------|--------------------------------------------------|
| **Cinzel**          | Google Fonts     | Título "VALUE PROPOSITION" (Sección 2)           |
| **Fivo Sans Modern**| Local `/fonts/`  | Párrafo y botones "BOOK NOW" en Sección 2        |

#### @font-face Fivo Sans Modern (ya en `Layout.astro`)
```css
@font-face {
  font-family: 'Fivo Sans Modern';
  src: url('/fonts/FivoSansModern-Regular.otf') format('opentype');
  font-weight: 400;
}
@font-face {
  font-family: 'Fivo Sans Modern';
  src: url('/fonts/FivoSansModern-Medium.otf') format('opentype');
  font-weight: 500;
}
@font-face {
  font-family: 'Fivo Sans Modern';
  src: url('/fonts/FivoSansModern-Bold.otf') format('opentype');
  font-weight: 700;
}
```

---

## Ícono de marca BAO

- **Archivo**: `/images/SVG/Recurso 2bao_.svg`  
- **Uso**: Aparece encima del título en la Sección 2 (VALUE PROPOSITION) y puede reutilizarse en otras secciones.
- **Tamaño sugerido**: `width: 60px; height: auto;`

```html
<img src="/images/SVG/Recurso 2bao_.svg" alt="BAO Icon" style="width:60px;height:auto;" />
```

---

## Sección 2: VALUE PROPOSITION

### Título
- Tipografía: **Cinzel** (Google Fonts)
- Color: `#2E394B`
- "VALUE" → `font-weight: 600`
- "PROPOSITION" → `font-weight: 400`, `letter-spacing: 0.12em`
- Ambas palabras en `text-transform: uppercase`

```html
<h2 class="value-prop-title">
  <span class="value-word-value">VALUE</span>
  <span class="value-word-proposition">PROPOSITION</span>
</h2>
```

### Párrafo principal
- Tipografía: **Fivo Sans Modern**, `font-weight: 400`
- Color: `#4f4f59`
- Solo la frase **"Your home should tell the story of who you are,"** va en `<strong>` (bold, mismo color)
- Sin comillas tipográficas envolviendo el párrafo

```html
<p class="value-quote">
  <strong>Your home should tell the story of who you are,</strong> and be a collection of what you
  love. We don't just tidy up; we implement intuitive systems that calm your nervous
  system and protect your family's health.
</p>
```

### Botones en las tarjetas de imagen
- Texto: **BOOK NOW**
- Tipografía: **Fivo Sans Modern**, `font-weight: 500`
- Color de texto: `#2E394B`
- Fondo: `#Cbb08e` (beige dorado)
- Hover: `#bfa07a`
- Class CSS: `.btn-card`

```html
<a href="/the-process" class="btn-card mt-6">BOOK NOW</a>
```

---

## Clases CSS Clave (Sección 2)

```css
/* Título VALUE PROPOSITION */
.value-prop-title { font-size: clamp(1.8rem, 3.5vw, 2.8rem); letter-spacing: 0.05em; }
.value-word-value { font-family: 'Cinzel', serif; font-weight: 600; color: #2E394B; text-transform: uppercase; }
.value-word-proposition { font-family: 'Cinzel', serif; font-weight: 400; color: #2E394B; text-transform: uppercase; letter-spacing: 0.12em; }

/* Párrafo */
.value-quote { font-family: 'Fivo Sans Modern', sans-serif; font-size: 1rem; color: #4f4f59; line-height: 1.7; font-weight: 400; }
.value-quote strong { font-family: 'Fivo Sans Modern', sans-serif; font-weight: 700; color: #4f4f59; }

/* Botón tarjetas */
.btn-card { font-family: 'Fivo Sans Modern', sans-serif; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.12em; text-transform: uppercase; color: #2E394B; background: #Cbb08e; padding: 12px 24px; }
```

---

## Botones globales

| Clase               | Descripción                                | Color texto  | Fondo        |
|--------------------|---------------------------------------------|-------------|-------------|
| `.btn-gold`         | Botón primario relleno dorado               | `#1D293C`   | `#D5AD7F`   |
| `.btn-outline-gold` | Botón secundario contorno dorado            | `#D5AD7F`   | Transparente |
| `.btn-card`         | Botón en tarjetas Sección 2                 | `#2E394B`   | `#Cbb08e`   |

---

## Optimización de Imágenes

Todas las imágenes del proyecto (excepto SVGs e íconos) deben estar en formato **WebP** para garantizar el mejor rendimiento sin pérdida de calidad.

### Estándar de Calidad
- **Formato**: WebP (`.webp`)
- **Calidad**: 90 (Alta calidad, cerca de lossless)
- **Compresión**: Nivel 6 (Máximo esfuerzo)

### Flujo de Trabajo (Script de Conversión)
Existe un script automatizado para convertir imágenes PNG/JPG a WebP:
- **Archivo**: `scripts/convert-to-webp.js`
- **Comando**: `node scripts/convert-to-webp.js`

El script recorre recursivamente `public/images`, ignora la carpeta `SVG`, y genera una versión `.webp` para cada archivo compatible.

> [!TIP]
> Siempre ejecuta el script después de agregar nuevas imágenes al proyecto para mantener el estándar de optimización.

---

## Imanes para Futuros Prompts

- Al editar o crear nuevas secciones, usar siempre **Cormorant Garamond** para títulos generales.
- Sección 2 tiene su propia tipografía especial: **Cinzel** para el heading y **Fivo Sans Modern** para el cuerpo/botones.
- El ícono de marca BAO (`Recurso 2bao_.svg`) debe usarse como ornamental header en secciones destacadas.
- **Optimización**: Todas las nuevas imágenes deben convertirse a WebP usando `node scripts/convert-to-webp.js` antes de referenciarlas en el código.
- Mantener la paleta de colores definida en `--color-*` variables; solo usar hexadecimales directos como excepción documentada.
