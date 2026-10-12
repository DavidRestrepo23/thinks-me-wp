# ThinkSME — Pendientes de QA (para implementación)

> Generado a partir de `QA_Checklist_ThinkSME.xlsx`. Contiene **solo** los puntos en estado
> **Pendiente** o **En progreso** (los ya marcados como "Hecho" se omitieron a propósito).
> Úsalo como lista de trabajo: cada ítem indica la página exacta donde aplicar el cambio.

**Sitio local (dev):** http://thinksme-local.local/
**Tema:** `wp-content/themes/thinksme` (WordPress, Local by Flywheel)
**Nota:** varias páginas aún conservan slugs antiguos (`/psg-grant/`, `/mra-grant/`) aunque el
contenido debe pasar a la nomenclatura "EDGE" — no renombrar el slug/URL salvo que se indique,
solo el contenido visible, salvo que el ticket diga lo contrario.

---

## Home Page
**URL:** http://thinksme-local.local/

- [ ] Actualizar texto del hero: cambiar "PSG Xero Grant & Government Grants" por "EDGE Xero Grant". _(Contenido)_
- [ ] Sección de partners/bancos: evaluar carrusel tipo swipe manteniendo el mismo diseño (igual que la sección superior de certificaciones). _(Diseño/Funcional)_
- [ ] Persisten problemas de espaciado en varias secciones de la página — revisar de arriba hacia abajo. _(Diseño)_
- [ ] Botón de WhatsApp debe enlazar a **+65 8533 3997**. _(Funcional/Enlace)_
- [ ] Revisar espaciado en la sección que sigue justo después del hero (mismo tipo de problema reportado en otras secciones). _(Diseño)_

---

## About
**URL:** http://thinksme-local.local/about-us/

- [ ] Revisar copy: `"Your" Partner for Business Success` — uso de comillas en "Your" (confirmar si es intencional o error tipográfico). _(Contenido)_
- [ ] Verificar sección/CTA "Claim the EDGE Grant - Xero" (texto y funcionamiento). _(Contenido)_
- [ ] Verificar sección "Applying for EDGE Xero Grant" (texto y funcionamiento). _(Contenido)_
- [ ] Verificar sección "EDGE Grant Vendor" (texto y funcionamiento). _(Contenido)_

---

## Company Incorporation — Local & Foreigner
**URLs:**
- Local: http://thinksme-local.local/company-incorporation-local/
- Foreigner: http://thinksme-local.local/company-incorporation-foreign/

Aplican a **ambas** páginas:
- [ ] Integrar API de ACRA (data.gov.sg) para información de entidades corporativas. Referencias: [ACRA Information on Corporate Entities](https://data.gov.sg/collections/2/view), [API overview](https://guide.data.gov.sg/developer-guide/api-overview). _(Funcional)_
- [ ] Actualizar contenido de la herramienta SSIC:
  - Heading: `Business Activity (SSIC)`
  - Subtitle: `Describe your business in a few words and we'll suggest matching SSIC codes.`
  - Placeholder: `e.g. Software development, Café, Online retail`
  - Botón: `Search Code`
  - Disclaimer: `This is a guide to help you shortlist SSIC codes, not an official ACRA classification. We'll confirm your primary and secondary activities when we prepare your BizFile+ application.`
  _(Contenido)_
- [ ] Agregar Calculadora de Corporate Tax debajo de esta sección (mismo componente que está en http://thinksme-local.local/corporate-tax/). _(Funcional)_

Específico de **Company Incorporation - Local**:
- [ ] Corregir enlace del CTA principal a: `admin.thinksme.sg/requests/addeditrequest?requesttype=ADD_NEW_COMPANY&requestid=`. _(Enlace)_

Específico de **Company Incorporation - Foreigner**:
- [ ] Corregir enlace del CTA principal a: `admin.thinksme.sg/requests/addeditrequest?requesttype=ADD_NEW_COMPANY&category=FOREIGN`. _(Enlace)_

---

## Corporate Secretary
**URL:** http://thinksme-local.local/corporate-secretary/

- [ ] Corregir enlace del CTA a: `admin.thinksme.sg/requests/addeditrequest?requesttype=TAKEOVER_COMPANY_SECRETARY`. _(Enlace)_
- [ ] Corregir cálculo de "Annual Return Filing due date": debe mostrar **7 meses** desde la fecha ingresada. Ejemplo: input `31.12.26` → debe mostrar `31.07.27`. _(Funcional — revisar lógica de fecha, probablemente en JS/PHP del formulario/calculadora)_
- [ ] Agregar Calculadora de Corporate Tax debajo de esta sección (mismo componente que http://thinksme-local.local/corporate-tax/). _(Funcional)_

---

## Registered Office Address
**URL:** http://thinksme-local.local/registered-office-address/

- [ ] Agregar contenido (actualmente falta):
  - **Professional Business Address:** "A credible Singapore address for your company's registration, letterheads and official correspondence."
  - **Secure File Access:** "Your documents are scanned in our cloud storage driver, organised and kept safe, ready whenever you need them."
  - **Transparent Fees:** "Simple, upfront pricing with everything included, so there are no surprises at renewal."
  _(Contenido)_

---

## Finance & Loan (sección / mega menú)
No tiene landing page propia confirmada — es un grupo del mega menú que agrupa Business Loan, Property Cashout, Mortgage Loans y Remittance.

- [ ] Mover la sección completa de Finance & Loan (Business Loans, Property Cashout, Mortgage Loans, Remittance) para que quede bajo el menú "Grow", con sus descripciones correspondientes. _(Estructura/Arquitectura de información — revisar config del mega menú en el tema)_

### Business Loan
**URL:** http://thinksme-local.local/business-loan/

- [ ] Usar el mismo slider de logos de bancos que está en la home page. _(Diseño)_
- [ ] Formatear el monto del préstamo con separador de miles (coma): ej. `100,000` en vez de `100000`. _(Funcional — formateo de input numérico)_
- [ ] Mismo fix de separador de miles en la Calculadora de Corporate Tax. _(Funcional)_

### Property Cashout
**URL:** http://thinksme-local.local/property-cashout/

- [ ] Corregir texto a: "Unlock up to 90% of your Property Value". _(Contenido)_
- [ ] Usar el mismo slider/estilo "grisado" (greyed-out) del home; agregar los logos de Property Cashout encima de los existentes; usar el set de logos nuevo para todos. _(Diseño)_

### Mortgage Loan
**URL:** http://thinksme-local.local/mortgage-loans/

- [ ] Cambiar logos actuales por logos de bancos. _(Contenido)_
- [ ] Usar el mismo slider/estilo "grisado" del home; agregar estos logos encima de los existentes. _(Diseño)_
- [ ] Mover el bloque "Cash rebate" para que quede debajo de "Compare My Rate", en vez de encima del slider de logos. _(Diseño)_

---

## Grants (sección / mega menú)
No tiene landing page propia confirmada — agrupa las páginas de grants bajo el mega menú.

- [ ] Mover la sección Grants para que quede bajo el menú "Grow". _(Estructura/Arquitectura de información)_
- [ ] Renombrar/actualizar grant → **EDGE Grant: Go Digital with Xero** — "Get up to 50% support to adopt Xero and automate your accounting." _(Contenido)_
- [ ] Renombrar/actualizar grant → **EDGE Grant: Expand Overseas** — "Get up to S$100,000 funding for overseas market set-up, hiring, office rental and marketing." _(Contenido)_

### PSG Grant → EDGE Xero Grant
**URL actual:** http://thinksme-local.local/psg-grant/ _(el slug sigue siendo `psg-grant`; solo cambia el contenido visible)_

- [ ] Actualizar texto principal de la página a: "SMEs are eligible for up to 50% business grant support for the adoption of **All-in-One Xero Digital Accounting Software**, a Pre-Approved Solution under the IMDA SMEs Go Digital programme. As a vendor of pre-approved EDGE Xero solutions, you are eligible for substantial cost savings through our offerings, with the setup, training, and ongoing support handled by our own Xero Certified Advisor team." _(Contenido)_
- [ ] Cambiar texto del botón principal a: "Talk to Our Experts". _(Contenido)_
- [ ] Colocar el logo de Xero encima del botón "Talk to Our Experts". _(Diseño)_
- [ ] **Bloqueado / pendiente de asset:** falta el nuevo logomark de IMDA (el cliente lo envía vía Upwork, con guía de tamaño adjunta) — no implementar hasta recibir el archivo. _(Pendiente/Asset)_
- [ ] Reemplazar todo el contenido restante heredado de "PSG grant" por el contenido/nomenclatura "EDGE". _(Contenido)_

### MRA Grant → EDGE Grant - Expand Overseas
**URL actual:** http://thinksme-local.local/mra-grant/ _(el slug sigue siendo `mra-grant`; solo cambia el contenido visible)_

- [ ] Reemplazar la descripción principal por: "To help local SMEs get a foothold in overseas markets, the EDGE Internationalisation (MRA) Grant was set up to provide funds to take the first steps. The grant will cover up to 70% of eligible costs (capped at S$100,000) for each company in each new market." _(Contenido)_
- [ ] Reemplazar el botón "View full list of supportable activities" por "Talk to Our Experts". _(Contenido)_
- [ ] Reemplazar la sección de actividades cubiertas con el heading "What is Covered in EDGE Internationalisation (MRA) grant?" y los siguientes bullets agrupados:
  - **Overseas Market Promotion:** Overseas fair participation costs · Professional overseas marketing service costs
  - **Overseas Business Development:** Professional business development service costs · Office rental (12 months) · Basic salary support for overseas business development staff deployment (12 months)
    - Nota al pie: _For SMEs, support is up to 70% if a Singaporean or Singapore Permanent Resident is deployed, and up to 50% if a foreigner is deployed. For non-SMEs, support is up to 50% if a Singaporean or Singapore Permanent Resident is deployed, and up to 30% if a foreigner is deployed._
  - **Overseas Market Set-up:** Entity Incorporation · Drafting of Agreements · Import/Export License · Intellectual Property · Tax Advisory · Trade Credit Insurance
  _(Contenido)_

---

## Footer
**Aplica a:** todas las páginas (componente global del tema)

- [ ] Actualizar nombres de los enlaces del footer a: "EDGE Xero Grant" y "EDGE Grant - Expand Overseas". _(Contenido)_

---

## Start-up Tools
**Ubicación exacta por confirmar** — no se encontró una página dedicada en el sitio; es probable que viva dentro de Company Incorporation - Local (http://thinksme-local.local/company-incorporation-local/) o como un bloque/sección propia del tema.

- [ ] Hipervincular la herramienta "Company Name Check" a la sección de Company Incorporation - Local. _(Enlace)_

---

## Sitio completo / Todas las páginas

- [ ] No hay botón de WhatsApp en ninguna parte del sitio — agregarlo (reportado tanto en home como de forma general en todo el sitio). _(Funcional)_
- [ ] Estos 3 elementos deben hipervincularse a sus páginas respectivas — **revisar detalle exacto en el historial de chat con el cliente**, el documento original no especifica cuáles son. _(Enlace — pendiente de precisar alcance)_
- [ ] Estas 3 pestañas (tabs) no funcionan según lo acordado — **revisar detalle exacto en el historial de chat con el cliente**, el documento original no especifica cuáles son. _(Funcional/Bug — pendiente de precisar alcance)_
- [ ] **En progreso:** el hipervínculo de "Contact Us" ya dirige correctamente a la subpágina Contact Us (http://thinksme-local.local/contact-us/) en toda la navegación — **falta confirmar/implementar la integración con el formulario de contacto de Peppercloud**. _(Enlace/Funcional)_

---

## Mobile (vista responsive)
**Aplica a:** todo el sitio, en viewport mobile

- [ ] Quitar el fondo pixelado en vista mobile — cambiar a un solo color de fondo estático (no imagen/textura). _(Diseño/Mobile)_
- [ ] Estandarizar el rating de Google Reviews a **4.9** en todo el sitio (desktop y mobile). _(Contenido)_
- [ ] En mobile, permitir hacer click para cambiar de pestaña (tabs), igual que en desktop, en vez de usar una lista desplegable (dropdown). _(Funcional/Mobile)_

---

## Resumen

| Página / Sección | Puntos |
|---|---|
| Home Page | 5 |
| About | 4 |
| Company Incorporation (Local & Foreigner) | 5 |
| Corporate Secretary | 3 |
| Registered Office Address | 1 |
| Finance & Loan (landing + Business Loan + Property Cashout + Mortgage Loan) | 9 |
| Grants (landing + EDGE Xero Grant + EDGE Grant - Expand Overseas) | 11 |
| Footer | 1 |
| Start-up Tools | 1 |
| Sitio completo | 5 (incluye 1 "En progreso") |
| Mobile | 3 |
| **Total** | **47 Pendientes + 1 En progreso = 48** |

> Coincide con los 48 puntos en estado Pendiente/En progreso del `QA_Checklist_ThinkSME.xlsx`.
> El bullet de WhatsApp en "Sitio completo" junta 2 filas del Excel (puntos #48 y #56, que son el
> mismo hallazgo reportado dos veces en el documento original).
