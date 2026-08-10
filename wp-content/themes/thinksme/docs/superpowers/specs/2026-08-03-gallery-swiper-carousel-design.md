# Gallery Section — Swiper Carousel

## Contexto

`template-parts/gallery.php` (sección "GIFs+illustrations", Figma node `815:11379`) hoy renderiza un grid estático de 5 imágenes. El diseño real es un carousel tipo coverflow: 5 tarjetas rotadas (-11.82°/+11.82°/-7.45°/+7.45°/0°), la central al frente sin rotar y más grande, en loop con autoplay.

## Decisión

Usar [Swiper.js](https://swiperjs.com/) (v14), vendoreado como build estático (sin bundler JS nuevo — el theme solo tiene Tailwind CLI).

**Efecto:** `creative` (no `coverflow`). El efecto `coverflow` de Swiper rota en eje Y con perspectiva 3D (look "flip" tipo iTunes viejo) — no coincide con el diseño, que es rotación 2D plana en eje Z (fotos "tiradas" sobre mesa, sin perspectiva). `creative` permite configurar `rotate: [0,0,±deg]` (eje Z) + `translate` + `scale` por slide vecino, con `limitProgress` para que el efecto alcance más de un vecino a cada lado — se descubrió y corrigió esto en verificación visual contra Local (ver sección Testing).

Alternativas descartadas:
- **CDN**: choca con filosofía "sin bloat"/dependencias externas en cada carga del theme.
- **CSS-only custom**: reimplementar drag/touch/loop-infinito a mano, más trabajo y menos robusto que Swiper.

## Contenido

Sin cambios al content model: sigue 5 campos ACF fijos (`gallery_image_1..5`) en la página Home, como ya está documentado en el spec de homepage. Se descartó pasar a CPT variable — las rotaciones son fijas por posición según Figma, un N variable rompería ese layout.

## Arquitectura

**Vendoreo de Swiper** (`assets/js/vendor/swiper/`, `assets/css/vendor/swiper/`):
- `swiper` en `devDependencies` (fuente, no runtime dep)
- npm script `vendor:swiper` copia `swiper-bundle.min.js`/`swiper-bundle.min.css` de `node_modules/swiper/` a los directorios vendor, commiteados en git (mismo patrón que `assets/fonts/`)

**Markup** (`template-parts/gallery.php`):
- `<section id="gallery">` → `<div class="swiper gallery-swiper">` → `<div class="swiper-wrapper">` → 5× `<div class="swiper-slide">` con la imagen de cada `gallery_image_{i}`
- Loop PHP igual (`for $i 1..5`, `thinksme_field()`), solo cambian clases del wrapper

**JS** (`assets/js/gallery-slider.js`, propio, no vendor):
- Init Swiper: `effect: 'creative'`, `loop: true`, `loopAdditionalSlides: 2` (necesario para que el loop tenga suficientes duplicados a cada lado dado `limitProgress: 2`, si no Swiper tira warning y el loop no funciona bien con solo 5 slides), `grabCursor: true`, `centeredSlides: true`, `slidesPerView: 1`
- `autoplay: { delay: 3000, disableOnInteraction: false, pauseOnMouseEnter: true }`
- `creativeEffect: { limitProgress: 2, prev: { translate: ['-72%','6%',0], rotate: [0,0,-8], scale: 0.94 }, next: { translate: ['72%','6%',0], rotate: [0,0,8], scale: 0.94 } }` — tuneado para acercarse a las rotaciones Figma (no es 1:1 pixel-perfect, es aproximación decorativa)
- Respeta `prefers-reduced-motion`: si `matchMedia('(prefers-reduced-motion: reduce)').matches`, no pasa `autoplay` al config

**Enqueue** (`functions.php`):
- `wp_enqueue_style( 'swiper', .../vendor/swiper/swiper-bundle.min.css', [], SWIPER_VERSION )`
- `wp_enqueue_script( 'swiper', .../vendor/swiper/swiper-bundle.min.js', [], SWIPER_VERSION, true )`
- `wp_enqueue_script( 'thinksme-gallery-slider', .../gallery-slider.js', ['swiper'], THINKSME_VERSION, true )`

## Interacción

Autoplay + swipe/drag manual habilitado (Swiper default). Se pausa autoplay al hacer hover/interactuar, retoma después (`pauseOnMouseEnter`, `disableOnInteraction: false`).

## Fuera de alcance

- Paginación/flechas de nav (Figma no las muestra, es puramente autoplay + swipe)
- Cambiar cantidad de imágenes a variable (queda como posible fase futura si el cliente lo pide)

## Testing

- Verificar loop infinito no muestra huecos/saltos al pasar de última a primera imagen
- Verificar autoplay se detiene con `prefers-reduced-motion: reduce` (probar en DevTools emulation)
- Verificar swipe manual en mobile/touch y drag en desktop
- Comparar visual contra screenshot Figma (rotación/overlap del efecto coverflow)
