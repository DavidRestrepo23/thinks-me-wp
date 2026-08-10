# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project status

This repo is the WordPress theme for "Think SME" (business incorporation/advisory site in Singapore). Tailwind build, design tokens (`src/base.css`), theme scaffold and the homepage sections all exist; ACF field groups are created and committed under `acf-json/`. Homepage order lives in `front-page.php`: hero, logos (certifications), Three Ways card deck, logos (clients), Start/Run/Grow accordion, Google Reviews slider, FAQ, final CTA.

**There is no gallery section** — `template-parts/gallery.php`, its `gallery_image` CPT and `assets/js/gallery-slider.js` were removed. `header.php` carries both the document shell (doctype, `<head>`/`wp_head()`, opening `<body>` that `footer.php` closes) and the site header, rebuilt from the newer Figma file; it can never be deleted outright, since `get_header()` is what emits that shell.

Newer sections (`hero`, `cards-deck`, `testimonials`, `faq`, `cta`, `footer.php`) were built against a **newer Figma file** ("Untitled", `vzdpOnH1U36oXcFcugiyE5`) than the one `docs/superpowers/specs/2026-07-30-*` describes, so treat that spec as historical — most of what it specifies (the old hero/gallery/header/CTA) no longer exists in this form.

Besides the homepage there is one inner page: `page-contact-us.php` ("Contact us"), in Figma order `contact-form` (62:42) → `offices-map` (62:126) → the page's editor content if any → `hiring` (62:100) → the shared `cta`. The file carries a `Template Name: Contact Us` header *and* is named for the `contact-us` slug, so WP picks it either way — but the ACF group `group_thinksme_contact` ("Contact Page Content") is located on `page_template == page-contact-us.php`, so the template must actually be selected in Page Attributes for its fields to show. That group repeats the Home group's `cta_*` **field names** under `field_thinksme_contact_*` keys, which is what lets `cta.php` stay page-agnostic; ACF has no page-slug location rule, so this is the mechanism, not a workaround.

Contact page specifics:

- **The enquiry form is the theme's own**, not a plugin. Markup in `template-parts/contact-form.php`, everything else in `functions.php`: `thinksme_contact_fields()` is the single field list, `thinksme_handle_contact_submission()` runs on `template_redirect` (early enough to redirect after a successful send, so a refresh can't re-post), and `thinksme_contact_state()` carries errors/values/sent to the template. Delivery is `wp_mail()` to the Customizer's "Contact form recipient" (Site Details), falling back to `admin_email` — on a real host that needs an SMTP/API sender or it lands in spam. Spam guard is nonce + off-screen honeypot; no CAPTCHA.
- **Fields are pills whose label doubles as the placeholder** (`.contact-field` in `src/base.css`): a real `<label>` overlays the input and fades on focus/input, and the input's `placeholder` is a single space so `:placeholder-shown` stays a reliable "is empty" test. Figma has no filled state, so this is the reading of it that keeps labels accessible and the red asterisk its own colour.
- **`offices-map.php` is a live Google Maps embed** on the keyless `maps?q=…&output=embed` endpoint — no API key, no Cloud project. The four office cards are real links to Google Maps that `assets/js/contact-map.js` intercepts to re-point the iframe and move `data-active` (the selected palette follows the selection, like the positional card styling elsewhere in the theme); modifier/middle-click still open Maps, and with the script gone the section degrades to four links. Addresses come from flat `office_1..4_*` ACF fields — ACF free has no Repeater — and an office with an empty name *and* address is skipped. Below `lg` the cards stop overlapping the map and stack under it with their own border.

After editing anything under `acf-json/`, the field group has to be synced once in wp-admin (ACF > Field Groups > Sync) before the client sees it.

This is a git repo independent from the WordPress install it lives in (`wp-content/themes/thinksme`). The WP install itself runs via Local by WP Engine, site `thinksme-local`, rooted at `/Users/davidrestrepo/Desktop/studio/thinks-me/app/public`.

## Specs

Design/planning docs live in `docs/superpowers/specs/YYYY-MM-DD-<slug>.md`, one per phase of work. The current spec is `2026-07-30-homepage-theme-design.md`, scoped to **Homepage only** — other pages from the Figma file are deferred to later phases following the same pattern. Read the relevant spec before making architectural decisions; it defines scope boundaries (what's explicitly out of scope for the phase) as well as the content model.

## Planned architecture (per current spec)

The theme is custom-built starting from `_s` (Underscores) — no page builder, minimal bloat. Theme slug: `thinksme`.

**Styling**: Tailwind CSS compiled via npm (`npm run dev` for watch, `npm run build` for production). Tailwind theme config (colors/spacing/typography) is mapped from the Figma file's styles/variables, not invented ad hoc.

**File layout** (matches the spec; `index.php` is an unlisted addition — WP won't recognize a theme without it):
```
thinksme/
  style.css                theme header (WP requires this; carries no CSS rules — see functions.php note)
  functions.php             setup, menu locations, enqueue, CPT registration, ACF json save/load hooks, thinksme_field() helper
  index.php                 fallback template (required by WP; out of scope beyond that)
  front-page.php            homepage — loops template-parts in Figma order, inside The Loop (see below)
  header.php
  footer.php
  template-parts/           hero, logos-slider, cards-deck, cards-stack, testimonials, faq, cta
  acf-json/                 ACF field group exports (empty until the field group is created in wp-admin)
  assets/
    css/                    Tailwind build output (gitignored-worthy but not yet ignored — ask before changing)
    js/
    images/
      icons/                small SVGs pulled from Figma via the MCP asset URLs (arrow-up-right, check, logo-google, star)
      fonts/                Charlevoix Pro .otf files (client-provided, not in Figma)
  src/
    tailwind.config.js       theme.extend mirrors the CSS custom properties in base.css — keep both in sync
    base.css                 @font-face + :root design tokens, imported at the top of input.css
    input.css                @import base.css, then @tailwind base/components/utilities
  package.json
```

**Content model** (ACF free tier — no Options Pages, since those are PRO-only):
- Fixed-quantity content per page (hero copy, the 3 overview cards, the 3 Three Ways cards, section hats/headings, CTA) → ACF field group assigned directly to the "Home" page.
- Variable-length lists the client edits like normal posts (add/edit/delete from the WP admin sidebar, no code or layout access) → Custom Post Types: `testimonial` (labelled "Google Reviews" in wp-admin), `faq_item`, `hero_image` ("Hero Photos"), `client_logo`.
- ACF field groups are exported to JSON (`acf-json/`) and committed to git, so they're reproducible across environments instead of relying on manual export/import.

**Assets**: images/icons/vectors are exported from Figma (via the Figma MCP `download_assets` tool), optimized, and placed in `assets/images/`. Purely decorative, static vector art (e.g. the constellation-style background in the Overview section) is exported as static SVG rather than recreated in code — evaluate case by case.

**Responsive**: the provided Figma design is desktop-only. Build mobile-first with Tailwind and use reasonable judgment for tablet/mobile breakpoints where the Figma has no explicit spec.

## Implementation conventions

- **`thinksme_field( $selector, $post_id = false, $default = '' )`** (in `functions.php`) wraps `get_field()` so templates don't fatal before ACF is installed/configured, and normalizes empty values to a default. Always use it instead of calling `get_field()` directly in templates.
- **`front-page.php` runs the whole section list inside the standard Loop** (`while ( have_posts() ) : the_post(); ...`). That's deliberate, not boilerplate: with no post ID, `get_field()`/`thinksme_field()` read whatever page is the current post — which is the static front page, exactly where the Home ACF field group is assigned. Don't move the `get_template_part()` calls outside the Loop.
- **Nav menus, not hardcoded links.** Header nav and the 3 footer columns are `wp_nav_menu()` against registered locations (`primary`, `footer-1`, `footer-2`, `footer-3`), so the client manages them from Appearance > Menus rather than editing PHP. `wp_nav_menu()` only lets you class the `<ul>`, not each `<li>/<a>` — per-item styling (header item padding, the caret on parents, the megamenu panel, the mobile panel) lives as plain CSS in `src/base.css`. The mobile burger is `assets/js/header-nav.js`, which only flips `data-nav-open` on the header.
- **The header megamenu is `Thinksme_Nav_Walker`** (`inc/class-thinksme-nav-walker.php`, Figma node 63:294) — the theme's only Walker, used because the panel markup is something `<ul class="sub-menu">` can't express. **Menu depth is the layout**: a 2nd-level item *with* children becomes a column (its title is the heading, its children are the cards); a 2nd-level item *without* children becomes a single card in its own column cell, so a two-level menu renders a row of cards instead of breaking. Card icons come from the ACF `menu_icon` select on menu items (`group_thinksme_menu_item`, located on `nav_menu_item`), whose choices are injected at runtime by `thinksme_menu_icon_choices()` from `thinksme_menu_icons()` — add an SVG to `assets/images/icons/megamenu/` plus one line in that function and the field updates itself, with nothing to re-sync in `acf-json/`. The card's second line is the menu item's **native Description field**, which the client has to switch on once under Screen Options.
- **The megamenu panel is anchored to `.site-header`, never to the `<li>`.** It spans the viewport minus 8px per side, so a `position: relative` on the parent `<li>` collapses it to the width of the nav item — that's why only nested parents (`.megamenu__item`, `.sub-menu` items) get a positioning context. It opens on `:hover`/`:focus-within`, no JS. The blurred backdrop over the page is `.megamenu::before`: `position: absolute` with `height: 100vh` so it starts below the header and can't cover it, and it sits on the outer `.megamenu` rather than `.megamenu__grid` because the grid clips its own rounded corners and would clip the backdrop with them.
- **`logos-slider.php` renders all homepage logos** (certifications + clients) from the `client_logo` CPT as one Swiper marquee. There used to be a separate fixed-ACF row for certifications (`logos-bar.php`); that was removed and merged into this CPT-driven slider so the client can edit both from wp-admin without touching code.
- **Accordions are native `<details>`/`<summary>` with a shared `name`**, not JS. `faq.php` and `cards-stack.php` both rely on the browser's single-open enforcement (Baseline since late 2024); `src/base.css` only resets the marker and swaps the +/- icon off `[open]`. Don't reach for a JS accordion here. `cards-stack.php` nests this twice: `overview-accordion` picks the Start/Run/Grow row, and inside each row `overview-services-N` picks which of the three service cards is expanded (double width, image cropped to 412px, description + benefits revealed below; the other two stay full-height and image-only). All three services carry the same ACF fields — there is no "primary" card. A service with no description and no benefits renders no content block, and every "expanded" CSS rule is gated on `:has(> .overview-service-content)`, so such a card is inert rather than opening into an empty column; the card that starts expanded is the row's first service that has copy — all three rows currently open service 1. The column geometry (746px tall = 412px image + 8px gap + 326px min-height glass block) was measured off the Figma PNG exports at 1:1; an earlier 694px in this file came from the older design node and was wrong. The card's arrow-circle link lives inside its `<summary>`, so clicking it both navigates and toggles; that's invisible in practice, but a service left on the placeholder `#` link will expand and jump to the top instead of navigating.
- **Positional styling in the two "card" sections.** In `cards-deck.php` (Three Ways), `testimonials.php` (Google Reviews) and `hero.php` (photo fan) the card palette and tilt belong to the *slot*, not the content: a small JS file stamps `data-depth` / `data-pos` on each card and `src/base.css` keys the colours and rotation off that attribute. So the navy highlight always sits in the middle/front as the carousel moves, and the client can reorder or replace content without inheriting a colour.
- **Site-wide chrome (footer tagline, social links, WhatsApp number, header phone) uses `get_theme_mod()`**, not ACF-on-Home fields — those aren't Home-page content, they're global and would leak into every future page if scoped to one page's field group. Their Customizer controls live in `thinksme_customize_register()` under the "Site Details" section; if you add another `get_theme_mod()` call, register a control for it there too or the client can never change it.
- **Design-fidelity fallbacks.** Sections whose content comes from CPTs/menus render the Figma content as a fallback when nothing is set yet (`testimonials.php`, `faq.php`, `hero.php`'s photos, the header nav, the three `footer.php` link columns). This keeps the site looking like the design pre-content-entry; the fallback is plain text, never a link to a page that doesn't exist.

## Key constraint

The client must be able to edit content (text, images, list items) but must never be able to break the layout or structure. This drives the CPT/ACF split above — anything the client edits is either an ACF field on a fixed layout or a CPT post with a fixed template, never raw structural control.

## Commands

- `npm install` — install Tailwind
- `npm run dev` — Tailwind CLI watch build, `src/input.css` → `assets/css/style.css`
- `npm run build` — Tailwind CLI production build (minified)

Tailwind config lives at `src/tailwind.config.js` (not the repo root) — `content` globs scan `./**/*.php` from the repo root, so npm scripts must run from the theme root. Design tokens (colors, font sizes, radii, spacing) are defined twice by necessity: as CSS custom properties in `src/base.css` (imported at the top of `src/input.css`, ahead of the `@tailwind` directives) and mirrored in `theme.extend` in the Tailwind config so utility classes (`bg-brand-yellow`, `text-text-secondary`, `gap-lg`, etc.) match. When a token changes, update both files — the spacing scale in particular uses t-shirt-size keys (`xs`..`4xl`) that shadow Tailwind's default numeric spacing scale, so a typo there fails silently as an unstyled utility rather than a build error.

No PHP CLI is on `$PATH` in this environment — Local by WP Engine bundles its own, found at a path like `~/Library/Application Support/Local/lightning-services/php-<version>/bin/darwin-arm64/bin/php`. Use that binary for `php -l` syntax checks.

## Verification approach (per spec)

- Run the site in Local and compare the rendered homepage against Figma screenshots, section by section.
- Test content editing the way the client would (add a Google review, add a FAQ, add a client logo, change a Three Ways card) and confirm layout doesn't break.
- Check responsive behavior at mobile/tablet/desktop breakpoints.
