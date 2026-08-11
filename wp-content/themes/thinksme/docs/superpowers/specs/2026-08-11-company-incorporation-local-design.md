# Think SME — Página "Company Incorporation Local"

## Contexto

Tercera página interna del tema `thinksme`, después de `page-contact-us.php` y
`page-registered-office-address.php`. Sigue el mismo patrón que esas dos: un
template con `Template Name:` **y** filename que matchea el slug, más un grupo
ACF localizado en ese template.

La página ya existe en WordPress: post ID **338**, título "Company Incorporation
Local", slug `company-incorporation-local`, status `publish`, sin template
asignado. Hoy recibe los campos `cta_*` de `group_thinksme_page_cta` (localizado
en `page_template == default`); al asignarle el template nuevo ese grupo sale y
el grupo de esta página toma el relevo, exactamente como pasó con ROA.

Diseño en el archivo Figma "Untitled" (`vzdpOnH1U36oXcFcugiyE5`), el mismo del
que salieron el header, el footer y la página ROA.

## Objetivo

Publicar la página con cinco secciones nuevas más las cuatro compartidas que ya
existen en el tema, sin tocar el homepage ni la página ROA, y dejando todo el
contenido editable por el cliente sin que pueda romper el layout.

## Secciones y orden

```
ci-hero                                    (markup nuevo, campos propios)
logos-slider   → grupo `certifications`    (compartido, verbatim)
ci-pricing     → Figma 85:1351
ci-tools       → Figma 85:1665
ci-includes    → Figma 85:1703
ci-ways        → Figma 85:1757
ci-why         → Figma 85:1830
testimonials                               (compartido, verbatim)
[contenido del editor, si no está vacío]
faq                                        (compartido, verbatim)
cta                                        (compartido, verbatim)
```

El orden de las cinco secciones nuevas es el que pidió el cliente, no un orden
derivado de Figma: en el archivo son frames sueltos en el canvas, no una página
armada, así que Figma no define secuencia.

### Detalle por sección

**`ci-hero`** (91:2069) — Hat pill, headline 72px en tres líneas con brush stroke
desktop-only bajo la última, texto de intro, y **dos** botones: split amarillo
primario + outline con el teléfono. A la derecha, foto en card redondeada con un
badge de bombilla que sobresale por la esquina superior izquierda.

*(Este nodo llegó después de escribir el spec; la versión inicial daba por hecho
que el hero no tenía diseño y reusaba la forma de `roa-hero.php`.)*

El badge son nueve vectores sueltos en Figma, fusionados en un SVG. El wrapper de
la imagen es el grupo completo de 609×554, no la card de 583×485 — el badge
sobresale, así que el grupo es más grande que la card y ambos hijos se colocan en
porcentajes de esa caja. Las dos capas de foto de Figma se resuelven en una sola:
muestreando el frame exportado, todo lo que hay sobre el borde superior de la
capa inferior es fondo de canvas.

**`ci-pricing`** (85:1351) — Panel navy redondeado con tres cards: Essential
(blanca), Standard (navy, destacada, con pestaña "MOST POPULAR" encima) y
Premium (blanca). Cada card: círculo amarillo con icono, título, descripción,
label "FROM" + precio, dos badges pill, lista de features con check verde
separadas por líneas, y un botón debajo de la card (no dentro).

**`ci-tools`** (85:1665) — Hat + título + subtítulo, tres tabs
(`Company Name Check`, `Business Activity (SSIC)`, `Tax Calculator`) y un panel
navy con título, descripción, input + botón, y disclaimer.

Figma diseña **un solo panel**, no tres — los otros dos tabs no tienen contenido
dibujado. Es el mismo caso que `roa-block.php`, donde Figma escribe copy para una
card y las otras tres comparten la forma.

Decisión del cliente: **el contenido de los tres paneles queda como texto lorem
por ahora**, editable por ACF. No se implementa validación de nombres ACRA, ni
buscador SSIC, ni calculadora de impuestos. Lo único funcional es el cambio de
tab.

**`ci-includes`** (85:1697) — Heading centrado "What's Included in Every
Incorporation" arriba; debajo, dos columnas: foto a la izquierda (576px, card
redondeada) y cuatro pill cards apiladas a la derecha (624px), cada una con
círculo amarillo + icono, título y una línea de texto.

**Corrección:** el nodo que se pasó primero (`85:1703`) es solo la columna de
cards, no la sección — implementarlo aislado se comía el heading y toda la
columna de la foto. La sección es su padre, `85:1697` ("Group 2368"). Cuando un
screenshot vuelve mucho más angosto que la página, conviene subir al padre antes
de escribir nada.

Figma apila dos capas de foto en `85:1699`; solo la de abajo se ve — la de
arriba queda íntegramente detrás — así que no se reproduce. Los offsets de crop
son la colocación de ese export concreto, de modo que una foto subida por el
cliente pasa a `object-cover` plano, igual que en `roa-hero.php`.

**`ci-ways`** (85:1757) — Dos columnas. Izquierda: hat, heading, texto y dos
cards de acción (DIY / especialista) con flecha. Derecha: card de paquete con
badge "MOST POPULAR", título, descripción, precio, dos badges, tres features con
check y botón.

**`ci-why`** (85:1830) — Hat, heading, texto y un bento grid de cinco cards en
cuatro variantes visuales: foto con texto en overlay, blanca, solo-foto, navy y
amarilla.

Las medidas, colores y tipografías de cada sección se leen con
`get_design_context` sobre su nodo justo antes de implementarla, no desde este
spec: acá está la estructura y el modelo de contenido, no los píxeles.

## Arquitectura

### Templates

```
page-company-incorporation-local.php     Template Name + slug match; corre The Loop
template-parts/
  ci-hero.php
  ci-pricing.php
  ci-tools.php
  ci-includes.php
  ci-ways.php
  ci-why.php
```

Igual que ROA y Contact, el template corre las secciones **dentro** de The Loop
(`while ( have_posts() ) : the_post();`) para que `thinksme_field()` sin post ID
lea los campos de esta página. El contenido del editor se renderiza entre
`ci-why` y el FAQ solo si no está vacío, sin `<h1>` (el hero ya lo lleva).

### Modelo de contenido

Grupo `group_thinksme_ci` ("Company Incorporation Local Page Content"), keys
`field_thinksme_ci_*`, nombres de campo `ci_*`, localizado en
`page_template == page-company-incorporation-local.php`. Organizado en tabs de
ACF, una por sección.

Repite los nombres `testimonials_*`, `faq_*` y `cta_*` bajo su propio prefijo de
key. Eso es lo que permite que `testimonials.php`, `faq.php` y `cta.php` se usen
verbatim sin volverse page-aware.

**Todos los campos son planos** — ACF free no tiene Repeater, igual que
`office_1..4_*` y `roa_plan_benefit_N`. Deja el grupo en ~135 campos, navegable
por los tabs. Es también la única forma que respeta la restricción clave del
proyecto: si el cliente pudiera agregar una cuarta card de pricing, la grid de
tres columnas se rompe. Cantidades fijas ⇒ campos planos; listas variables ⇒ CPT.
Ninguna de estas cinco secciones es una lista variable.

Cada botón tiene su propio campo de link (3 en pricing, 2 en ways, 1 en package
card, 1 en hero), con default a `/contact-us`.

Los campos vacíos se saltan, no renderizan bloques vacíos — mismo criterio que
`roa-plan.php` con sus benefits y `offices-map.php` con sus oficinas.

### Assets

**Fondo del pricing.** En Figma el fondo del panel navy son 149 nodos `Vector`
sueltos. Se exporta como **un** SVG a `assets/images/ci/pricing-bg.svg`, igual
que se hizo con `assets/images/roa/skyline.svg`. Hay que quitar a mano el rect
opaco `#1E1E1E` que Figma incluye en el export, como ya se hizo con el skyline.

**Iconos.** Van a `assets/images/icons/ci/`, con `thinksme_ci_icons()` y sus
choices inyectadas en runtime vía `acf/load_field/name=...`, clonando
`thinksme_roa_icons()` / `thinksme_roa_icon_choices()`. Agregar un icono es un
SVG más una línea en esa función, sin re-sync de `acf-json/`.

**Fotos.** Las imágenes del bento de `ci-why` se descargan de Figma vía
`download_assets` y se commitean en `assets/images/ci/`. Cada una es también un
campo ACF image, así que el cliente puede reemplazarlas; las del repo son el
fallback de fidelidad, mismo criterio que el resto del tema.

### JavaScript

`assets/js/ci-tools.js` — único archivo JS nuevo. Solo mueve `data-active`,
`aria-selected` y `aria-hidden` entre tabs y paneles. Toda la geometría vive en
`.ci-tab*` en `src/base.css`.

Sin JS, el primer tab queda abierto y la sección se lee como el diseño — mismo
contrato de degradación que `roa-block.js`.

Los tabs son `<button role="tab">` dentro de un `role="tablist"`, con
`aria-controls` apuntando a paneles `role="tabpanel"`.

El enqueue se gatea con `thinksme_current_template()`, **nunca**
`is_page_template()` — el segundo lee el post meta `_wp_page_template` y falla
silenciosamente cuando WP sirve el template por slug sin que el atributo esté
puesto.

### Estilos

Clases `.ci-*` en `src/base.css` para lo que Tailwind no expresa bien: el bento
grid de `ci-why`, la pestaña "MOST POPULAR" del pricing, y los estados de tab. El
resto es utilidades Tailwind con los tokens existentes.

Si aparece un token nuevo (color, radio, spacing), se define en **los dos**
lados: custom property en `src/base.css` y `theme.extend` en
`src/tailwind.config.js`. Un typo en la escala de spacing falla en silencio como
utilidad sin estilo, no como error de build.

### Publicación en WordPress

Asignar `_wp_page_template = page-company-incorporation-local.php` al post 338.
No hay WP-CLI en `$PATH`; se usa el phar que trae Local más el socket de MySQL
del sitio:

```
PHP=~/Library/Application\ Support/Local/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin/php
SOCK=~/Library/Application\ Support/Local/run/3B7PzzVVr/mysql/mysqld.sock
"$PHP" -d mysqli.default_socket="$SOCK" \
  /Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar \
  --path=/Users/davidrestrepo/Desktop/studio/thinks-me/app/public \
  post meta update 338 _wp_page_template page-company-incorporation-local.php
```

Después de agregar `acf-json/group_thinksme_ci.json`, el grupo hay que
sincronizarlo una vez en wp-admin (ACF > Field Groups > Sync) para que el cliente
lo vea.

## Fuera de alcance

- Lógica real de las tres free tools (validación ACRA, dataset SSIC, cálculo de
  impuestos). El contenido queda lorem; se define en una fase posterior.
- Cualquier cambio a `front-page.php`, `page-contact-us.php` o
  `page-registered-office-address.php`.
- Cambios a `logos-slider.php`, `testimonials.php`, `faq.php` o `cta.php`. Si
  alguna de las cinco secciones nuevas pareciera necesitar tocarlos, es señal de
  que el partial nuevo debe absorber la diferencia.
- Menús, header, footer y chrome global (`get_theme_mod()`).
- Traducción o i18n más allá de lo que ya hace el tema.

## Testing / verificación

- `php -l` en cada archivo PHP nuevo, con el binario de Local (no hay PHP en
  `$PATH`).
- `npm run build` desde la raíz del tema, y confirmar que las clases nuevas
  entraron al CSS compilado.
- Sitio corriendo en Local: comparar cada sección contra su screenshot de Figma,
  una por una.
- Editar contenido como lo haría el cliente — cambiar un precio, vaciar un
  feature, reemplazar una foto del bento, cambiar el copy de un tab — y
  confirmar que el layout aguanta y que los campos vacíos se saltan.
- Tabs: verificar navegación por teclado y estados ARIA, y que con JS
  deshabilitado el primer panel sigue visible.
- Responsive en mobile, tablet y desktop. El Figma es desktop-only, así que los
  breakpoints intermedios son criterio propio, como en el resto del tema.
