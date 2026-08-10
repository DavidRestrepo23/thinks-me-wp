# Think SME — Homepage Theme Design

## Contexto

Sitio "Think SME" (asesoría/incorporación de empresas en Singapur). Diseño en Figma:
https://www.figma.com/design/CgqSxvxd3aQeQSkLPhc48q/Think-SME--INTERNAL?node-id=773-16067

Alcance de esta fase: **solo Homepage**. El resto de páginas del diseño se abordan en fases posteriores con el mismo patrón.

## Objetivo

Theme de WordPress custom, fiel al diseño de Figma (pixel-perfect), administrable por el cliente sin poder romper el layout — el cliente solo edita contenido (textos, imágenes, listas), nunca estructura.

## Secciones de la Homepage (según Figma, node `773:16067`)

1. Header (nav)
2. Hero (título, subtítulo, 2 botones, ilustración de fondo)
3. Galería de imágenes/ilustraciones flotantes (5 items fijos)
4. Barra de logos de partners/certificaciones (fila 1)
5. Overview — 3 cards de servicios (card grande + 2 cards imagen, cada una con título, ArrowUpRight icon)
   - Card 1 tiene descripción + 4 beneficios (lista de checks)
6. Barra de logos de clientes (fila 2)
7. Testimonios (instancia de componente — lista variable)
8. FAQ (instancia de componente — lista variable)
9. CTA final
10. Footer
11. Botón flotante "Chat with Think SME on WhatsApp"

## Entorno

- **Local dev:** Local by WP Engine, sitio `thinksme-local`
- **Ruta WP:** `/Users/davidrestrepo/Desktop/studio/thinks-me/app/public`
- **Theme:** `/Users/davidrestrepo/Desktop/studio/thinks-me/app/public/wp-content/themes/thinksme` (repo git independiente)
- **PHP/MySQL:** los que trae Local por defecto ("Preferred" environment)

## Arquitectura

### Theme base
Custom, arrancando de `_s` (Underscores) — sin page builder, sin bloat. Nombre del theme: `thinksme`.

### Estilos
Tailwind CSS, compilado vía npm (`npm run dev` watch / `npm run build` producción). Config de colores/spacing/tipografía mapeada desde los estilos/variables del archivo Figma.

### Estructura de templates

```
thinksme/
  style.css                (header del theme)
  functions.php            (setup, enqueue, registro CPTs, registro ACF field groups vía JSON)
  front-page.php
  header.php
  footer.php
  template-parts/
    hero.php
    gallery.php
    logos-bar.php
    overview.php
    testimonials.php
    faq.php
    cta.php
  assets/
    css/ (output de Tailwind)
    js/
    images/ (assets exportados de Figma)
  src/
    tailwind.config.js
    input.css
  package.json
```

### Modelo de contenido (sin ACF PRO)

**Custom Post Types** — para listas de largo variable, editables por el cliente como posts normales (agregar/editar/borrar desde el menú lateral de WP, sin tocar código ni layout):

- `testimonial` — título = nombre, campos ACF: cargo/empresa, texto testimonio, foto (o featured image)
- `faq_item` — título = pregunta, campo ACF: respuesta (WYSIWYG)
- `client_logo` — título = nombre cliente, featured image = logo

**ACF (free)** — field group asignado directo a la página "Home" (no Options Page, que es PRO-only), para contenido de cantidad fija:

- Hero: título, subtítulo, texto botón 1 + link, texto botón 2 + link
- Galería: 5 campos de imagen (cantidad fija según diseño)
- Overview: 3 cards fijas (título, descripción, imagen, link) + 4 campos de beneficio (texto) en la card 1
- Barra de logos (fila 1, certificaciones): campos de imagen fijos (no CPT, ya que en el diseño es fija y no la edita el cliente frecuentemente) — a confirmar si el cliente necesita editarla; si sí, se pasa a CPT también
- CTA final: título, texto, botón (texto + link)
- Link de WhatsApp (footer flotante)

Field groups de ACF exportados a JSON (`acf-json/`) y versionados en git — reproducible entre entornos, no dependen de exportar/importar manual.

### Assets

Imágenes, iconos y vectores se exportan desde Figma (MCP `download_assets`), se optimizan, y se colocan en `assets/images/`. Vectores decorativos complejos (el fondo tipo "constelación" en la sección de Overview) se evalúan caso a caso: si es puramente decorativo y no cambia, se exporta como SVG estático en vez de recrearlo en código.

### Responsive

Diseño Figma provisto es Desktop. Se construye mobile-first con Tailwind, replicando breakpoints razonables (mobile, tablet, desktop) aunque el Figma no incluya esas variantes explícitamente — ajuste de buen criterio donde no haya spec.

## Fuera de alcance (esta fase)

- Páginas más allá de Home
- Multi-idioma
- Hosting/deploy a producción
- SEO plugin / analytics (se agrega después si se pide)
- Reordenar CPTs por drag-and-drop (usa orden por fecha/alfabético de WP por defecto; se agrega plugin de ordering solo si el cliente lo pide)

## Testing / verificación

- Levantar sitio en Local, revisar Homepage renderizada contra screenshot de Figma (comparación visual sección por sección)
- Probar edición de contenido como lo haría el cliente: agregar un testimonio, un FAQ, cambiar texto del hero — confirmar que no rompe layout
- Chequeo responsive en mobile/tablet/desktop
