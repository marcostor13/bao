# BAO Organization — Claude Code Config

## Project
Sitio web de BA Organization (Monica Avila & Beatriz Miranda).
Home organization luxury brand con enfoque wellness/non-toxic.
Stack: **Astro 5 + Tailwind CSS 4 + TypeScript strict**

## Design System
- **Navy**: `#1D293C` — fondos oscuros, footer, secciones hero
- **Navy text**: `#2E394B` — títulos de sección en fondos claros (ej. THE WELLNESS STANDARD)
- **Gold**: `#D5AD7F` — botones, acentos, títulos en fondos oscuros
- **Beige**: `#F8F7F4` — fondos de secciones claras
- **White**: `#FFFFFF`
- **Text dark**: `#1D1D1B`
- **Text muted**: `#6B6B6B`
- **Font heading**: Cormorant Garamond (serif, italic en heroes)
- **Font label**: Raleway (uppercase, tracking-widest)
- **Font body**: Inter

### Medidas de sección Wellness (referencia para secciones similares)
| Elemento | Valor |
|----------|-------|
| Título principal (ej. THE WELLNESS STANDARD) | 44px, color `#2E394B`, font-weight 400 |
| Subtítulo (ej. WHY NON-TOXIC MATTERS.) | 25px, color `#D5AD7F` |
| Títulos de bloque (CONTENT, THE IMPACT) | 25px, color `#2E394B` |
| Iconos de sección | 37.5px × 37.5px |

## Estructura de páginas
- `/` → `src/pages/index.astro` — Home
- `/about-us` → `src/pages/about-us.astro` — About
- `/the-process` → `src/pages/the-process.astro` — The Process (4 pasos)
- `/contact-us` → `src/pages/contact-us.astro` — Contact + form

## Componentes clave
- `src/layouts/Layout.astro` — shell HTML con Navbar + Footer
- `src/components/Navbar.astro`
- `src/components/Footer.astro`
- `src/components/HeroSection.astro`
- `src/components/ServiceCard.astro`
- `src/components/ProcessStep.astro`
- `src/components/PortfolioSlider.astro`

## Skills disponibles (slash commands)

### /add-section
Agrega una nueva sección a una página existente.
Uso: `/add-section [page] [section-type] [descripción]`
Ejemplo: `/add-section index testimonials "3 testimonios de clientes"`

### /add-component
Crea un componente Astro reutilizable con props TypeScript.
Uso: `/add-component [NombreComponente] [descripción]`

### /add-page
Crea una nueva página Astro con Layout, Hero y estructura base.
Uso: `/add-page [nombre] [título] [descripción]`

### /update-colors
Actualiza los tokens de color del design system en global.css.
Uso: `/update-colors`

### /check-design
Verifica que los colores, fuentes y espaciados sean consistentes con el design system.

## Agents

### page-builder
Para construir o reconstruir páginas completas fieles al PDF de diseño.
Contexto: usar siempre las fuentes, colores y patrones de Layout del design system.

### component-refactor
Para refactorizar componentes grandes en sub-componentes reutilizables.
Regla: nunca romper la API de props existente sin avisar.

### seo-agent
Agrega meta tags, og:image, structured data (LocalBusiness) y sitemap.

## Convenciones
- Componentes: PascalCase
- Páginas: kebab-case
- Clases Tailwind: primero layout → spacing → color → typography
- Imágenes placeholder: `https://picsum.photos/seed/[nombre]/[w]/[h]`
- No usar `@apply` salvo para clases muy repetidas en global.css
- Siempre importar `Layout.astro` en cada página

## Comandos dev
```bash
source /root/.nvm/nvm.sh   # activar Node 22
npm run dev                 # localhost:4321
npm run build               # dist/
npm run preview             # preview build
```

## Contacto real del negocio
- Email: info@baorganization.com
- Phone: (678) 749-0931
- Service Areas: Georgia
- Instagram: @baorganization
