# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project status

This repo is the WordPress theme for "Think SME" (business incorporation/advisory site in Singapore). Tailwind build, design tokens (`src/base.css`), theme scaffold and the homepage sections all exist; ACF field groups are created and committed under `acf-json/`. Homepage order lives in `front-page.php`: hero, logos (certifications), Three Ways card deck, logos (clients), Start/Run/Grow accordion, Google Reviews slider, FAQ, final CTA.

**There is no gallery section** — `template-parts/gallery.php`, its `gallery_image` CPT and `assets/js/gallery-slider.js` were removed. `header.php` carries both the document shell (doctype, `<head>`/`wp_head()`, opening `<body>` that `footer.php` closes) and the site header, rebuilt from the newer Figma file; it can never be deleted outright, since `get_header()` is what emits that shell.

Newer sections (`hero`, `cards-deck`, `testimonials`, `faq`, `cta`, `footer.php`) were built against a **newer Figma file** ("Untitled", `vzdpOnH1U36oXcFcugiyE5`) than the one `docs/superpowers/specs/2026-07-30-*` describes, so treat that spec as historical — most of what it specifies (the old hero/gallery/header/CTA) no longer exists in this form.

Besides the homepage there are three inner pages, `page-contact-us.php`, `page-registered-office-address.php` and `page-company-incorporation-local.php`. All follow the same pattern: a `Template Name:` header *and* a filename matching the page slug, with an ACF group located on `page_template == <that file>` — so WP picks the template either way, but **the template has to be selected in Page Attributes for the fields to appear** (ACF has no page-slug location rule; this is the mechanism, not a workaround). Each group repeats the Home group's `cta_*` **field names** under its own key prefix, which is what lets `cta.php` stay page-agnostic.

`page-contact-us.php` ("Contact us"), in Figma order `contact-form` (62:42) → `offices-map` (62:126) → the page's editor content if any → `hiring` (62:100) → the shared `cta`. Its group is `group_thinksme_contact` ("Contact Page Content"), keys prefixed `field_thinksme_contact_*`.

Contact page specifics:

- **The enquiry form is the theme's own**, not a plugin. Markup in `template-parts/contact-form.php`, everything else in `functions.php`: `thinksme_contact_fields()` is the single field list, `thinksme_handle_contact_submission()` runs on `template_redirect` (early enough to redirect after a successful send, so a refresh can't re-post), and `thinksme_contact_state()` carries errors/values/sent to the template. Delivery is `wp_mail()` to the Customizer's "Contact form recipient" (Site Details), falling back to `admin_email` — on a real host that needs an SMTP/API sender or it lands in spam. Spam guard is nonce + off-screen honeypot; no CAPTCHA.
- **Fields are pills whose label doubles as the placeholder** (`.contact-field` in `src/base.css`): a real `<label>` overlays the input and fades on focus/input, and the input's `placeholder` is a single space so `:placeholder-shown` stays a reliable "is empty" test. Figma has no filled state, so this is the reading of it that keeps labels accessible and the red asterisk its own colour.
- **`offices-map.php` is a live Google Maps embed** on the keyless `maps?q=…&output=embed` endpoint — no API key, no Cloud project. The four office cards are real links to Google Maps that `assets/js/contact-map.js` intercepts to re-point the iframe and move `data-active` (the selected palette follows the selection, like the positional card styling elsewhere in the theme); modifier/middle-click still open Maps, and with the script gone the section degrades to four links. Addresses come from flat `office_1..4_*` ACF fields — ACF free has no Repeater — and an office with an empty name *and* address is skipped. Below `lg` the cards stop overlapping the map and stack under it with their own border.

`page-registered-office-address.php` ("Registered Office Address"), in Figma order `roa-hero` (67:49) → the shared `logos-slider` (`certifications` group, same slot it has on the homepage) → `roa-plan` (67:109) → `roa-block` (67:211) → `roa-why` (68:564) → the shared `testimonials` → the page's editor content if any → the shared `faq` → the shared `cta`. Its group is `group_thinksme_roa`, keys prefixed `field_thinksme_roa_*`.

Registered Office Address page specifics:

- **The hero photo is two layers**, as in Figma: the landscape shot inside a rounded card, plus a transparent cut-out of the same building on top, which is the only reason the tower can break above the card's top edge. Both are ACF image fields; the cut-out is optional. The Figma crop offsets (`top-[-47.93%]`, `h-[152.9%]`, …) are measured against the *exported* photo and are meaningless for another image, so uploading a custom photo switches the card to a plain `object-cover` — see `$is_stock` in `roa-hero.php`.
- **`roa-plan.php`'s benefits are six flat `roa_plan_benefit_N` fields**, empties skipped (ACF free has no Repeater — same reason as `office_1..4_*`). The columns are `flex-col-reverse` below `lg`: Figma reads price-card-first left-to-right, but stacked the section heading has to come before the thing it introduces.
- **`roa-block.php`'s service cards expand on click.** All four ship identical markup; `assets/js/roa-block.js` only moves `data-active` and `aria-expanded`, and every pixel of the open/closed geometry is `.roa-card*` in `src/base.css` — so with the script gone the card the template marked active stays open and the section still reads as the design. Widths animate as pure `flex-grow` (`3.15 ≈ 632/200.67`, open over collapsed); below `lg` the row becomes a stack and only the palette, title size and description change. **Every card is a `<button>`, even before the client writes its description** — gating on copy would ship a section with nothing to click, since Figma only writes copy for card 2. A card with copy is a disclosure and carries `aria-expanded`/`aria-controls`; one without is only a selection and carries neither, which is also what `roa-block.js` keys off. The card that starts open is the first one that has copy — card 2, so the initial state matches the design. Card icons come from the ACF `roa_card_N_icon` selects, whose choices are injected at runtime by `thinksme_roa_icon_choices()` from `thinksme_roa_icons()`, exactly like the megamenu icons: add an SVG to `assets/images/icons/roa/` plus one line in that function, nothing to re-sync.
- **`roa-why.php` pins itself and scrubs a progress rail.** `assets/js/roa-progress.js` gives the section a scroll runway (`100svh` + 0.5svh per connector) and writes one custom property, `--roa-progress` (0..1); `src/base.css` turns that into each connector's yellow fill via `clamp(0%, calc((var(--roa-progress) * var(--roa-lines) - var(--roa-step)) * 100%), 100%)`, where `--roa-lines` is set on the section by PHP and `--roa-step` per connector. **The pin is `position: sticky` — nothing hijacks the scroll** (no `preventDefault`, no `scrollTo`, no wheel handler), so progress hits 1 on the last step and the page simply carries on. `--roa-progress` defaults to 1, so the un-enhanced section (no JS, below `lg`, reduced motion, or a viewport too short to hold the rail — the script measures) is a plain block with every connector already full. Two gotchas the code is shaped around: the section's vertical padding is zeroed while pinned, because sticky travel is measured against the *content* box and leaving it in would stop the last connector short; and `.has-scroll-reveal #main-content > section.roa-why` drops the reveal `translateY`, since a transform on the sticky element's ancestor chain breaks `position: sticky`.
- **The Google Reviews slider and the FAQ are `testimonials.php` / `faq.php` verbatim** — same `testimonial` / `faq_item` CPTs, same Swiper loop/autoplay, same native `<details>` accordion. Only their hat and heading differ, and those come from `testimonials_*` / `faq_*`, which this page's ACF group repeats (heading defaults "Read Our Reviews" and the homepage's FAQ heading) exactly as it repeats `cta_*`. Swiper's CSS/JS is enqueued for this template too, along with `logos-slider.js` and `testimonials-slider.js`, all of which were previously homepage-only; the FAQ needs no JS.
- **The skyline behind the block heading is one exported SVG** (`assets/images/roa/skyline.svg`), not the ~150 separate vectors it is on the canvas. It carries its own navy rounded panel, so it sits over the section background seamlessly; the opaque `#1E1E1E` canvas rect Figma includes in that export was stripped by hand.

`page-company-incorporation-local.php` ("Company Incorporation Local", page ID 338), in the order the client asked for: `ci-hero` → the shared `logos-slider` (`certifications` group, same slot as the other two pages) → `ci-pricing` (85:1351) → `ci-tools` (85:1665) → `ci-includes` (85:1697) → `ci-ways` (85:1757) → `ci-why` (85:1830) → the shared `testimonials` → the page's editor content if any → the shared `faq` → the shared `cta`. Its group is `group_thinksme_ci`, keys prefixed `field_thinksme_ci_*`. **The five designed sections are loose frames on the Figma canvas, not an assembled page** — Figma defines no sequence here, so the order is the client's and not something to "correct" against the file.

Company Incorporation Local page specifics:

- **The free-tools tabs are the only interactive part, and the tools themselves do nothing yet.** Figma draws *one* panel (`85:1679`), not three: the tab strip is three labels, and only "Company Name Check" has copy written for it — the same situation `roa-block.php` is in. So all three tabs ship the identical panel shape and tabs 2–3 default to lorem, by explicit client decision. No ACRA name validation, no SSIC dataset, no tax computation. The panel's input+button is a plain GET form pointing at `ci_tools_tab_N_button_link` so the controls aren't dead chrome; nothing reads the `q` parameter. `assets/js/ci-tools.js` only moves `data-active`/`aria-selected` and toggles `[hidden]`, with roving tabindex and arrow/Home/End keys; without it the first panel stays open.
- **`ci-pricing.php`'s middle card is highlighted by position, not by a field.** `data-featured` is set on the second slot, and `.ci-plan*` in `src/base.css` keys the blue fill, the white type, the "MOST POPULAR" tab and the solid button off it — the same positional-styling convention `cards-deck.php` and `testimonials.php` use. A per-card "featured" checkbox would let the client tick all three and flatten the section. The three cards stretch to equal height so the buttons line up regardless of how many features each carries.
- **The cityscape behind the pricing heading is one exported SVG** (`assets/images/ci/pricing-bg.svg`), not the 149 separate vectors it is on the canvas — same treatment as `roa/skyline.svg`, including stripping by hand the opaque `#1E1E1E` canvas rect Figma puts in the export. `ci-why`'s navy card carries its own crop of the same artwork (`ci/why-card-bg.svg`), which needed no such strip.
- **`ci-why.php`'s five bento cards are five different shapes, written out rather than looped.** Card 1 is a wide photo with the copy over a scrim, 2 is plain white, 3 is a photo with no copy at all, 4 is navy, 5 is pale yellow with the photo above the copy. Which slot is which shape is fixed by the layout, so each card only carries the fields its shape uses — card 3 has no title field because card 3 *is* the photo. Figma serves those photos as 3300px PNGs (22 MB for one); they are committed downscaled to ~2× display size as JPEG.
- **Headings on dark backgrounds need the colour repeated on the `h1`–`h6` itself.** `src/base.css` sets a hard `color` on the heading elements, and a rule on the element beats a colour inherited from a wrapper — so a `<div class="text-text-on-dark">` turns its `<p>` white but leaves the `<h3>` dark. This bit `ci-tools.php` and two `ci-why.php` cards during the build; the fix is an explicit `text-text-on-dark` on the heading.
- **`ci-hero.php` is node 91:2069.** Two buttons, not one — a solid split button plus an outlined one carrying the phone number, which is this page's own ACF field rather than the Customizer's site-wide number (it is a CTA label here, not site chrome; the two do not stay in sync). The brush stroke under the headline is desktop-only at a fixed offset, measured against the design's three lines at 72px — the same constraint `roa-hero.php` documents, so a much shorter headline will re-wrap and put the stroke under the wrong line. The lightbulb badge is Figma's nine separate vectors (91:2059–91:2067) merged into one SVG, the same treatment the pricing cityscape gets.
- **The hero's image wrapper is Figma's whole 609×554 group, not the 583×485 photo card.** The badge deliberately overhangs the card's top-left corner, so the group is bigger than the card; both children are placed as percentages of that box (card at 26,69; badge at 0,26). An earlier pass sized the wrapper to the card and used padding for the badge, which quietly ate 26px off the photo's width and left the badge floating beside the corner instead of over it. If a Figma group is larger than the thing it contains, that extra space is usually load-bearing.
- **`ci-includes.php` is node 85:1697, not 85:1703.** `85:1703` is only the card column; the section around it (`Group 2368`) also holds the centred heading `85:1698` and the photo column `85:1699`, so building from the inner node drops two thirds of the design. Worth remembering generally: a Figma node handed over as "the section" can be a child of it — check the parent before implementing, especially when a screenshot comes back much narrower than the page. Figma stacks two photo layers in `85:1699` (`85:1700` and `85:1702`); only the lower one is visible, the upper sits entirely behind it, and it is not carried over. The visible photo's crop offsets are Figma's placement of that specific export, so a client upload switches to a plain `object-cover` — the same `$is_stock` arrangement `roa-hero.php` uses.

**`thinksme_field()` returns its `$default` when the stored value is `''`** (`functions.php`), which means a field that ships with copy cannot be blanked from the editor — clearing it brings the design text back. That is the theme's design-fidelity-fallback convention and it predates this page (`roa-plan.php`, `roa-block.php` and `offices-map.php` all rely on it), but it is worth knowing before promising a client that emptying a field removes a card. Only slots whose default is already `''` (e.g. pricing features 4–5 on cards 2 and 3) actually disappear when left empty. The ACF instructions on this page say so explicitly.

After editing anything under `acf-json/`, the field group has to be synced once in wp-admin (ACF > Field Groups > Sync) before the client sees it.

The WP install runs via Local by WP Engine, site `thinksme-local`, rooted at `/Users/davidrestrepo/Desktop/studio/thinks-me/app/public` — **which is also the git repo root**, so theme files are tracked under the `wp-content/themes/thinksme/` prefix rather than at the top level.

There is no `wp` on `$PATH`, but Local bundles WP-CLI and its own PHP, so the database *is* reachable from the shell — useful for assigning a page template or checking what a page actually renders, instead of guessing:

```
PHP=~/Library/Application\ Support/Local/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin/php
SOCK=~/Library/Application\ Support/Local/run/3B7PzzVVr/mysql/mysqld.sock
"$PHP" -d mysqli.default_socket="$SOCK" \
  /Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar \
  --path=/Users/davidrestrepo/Desktop/studio/thinks-me/app/public post list --post_type=page
```

The socket path contains the Local site id (`3B7PzzVVr`) and is read from that site's `conf/mysql/my.cnf`; without `-d mysqli.default_socket` WP-CLI fails with "Error establishing a database connection".

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
  template-parts/           hero, logos-slider, cards-deck, cards-stack, testimonials, faq, cta,
                            contact-form, offices-map, hiring,
                            roa-hero, roa-plan, roa-block, roa-why,
                            ci-hero, ci-pricing, ci-tools, ci-includes, ci-ways, ci-why
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
- **Per-page script enqueues gate on `thinksme_current_template()`, never `is_page_template()`.** The latter reads the `_wp_page_template` post meta, so it is only true when the template was chosen in Page Attributes — but the theme's page templates are *also* named for their page slug, so WP serves them to pages that never had the attribute set, and every per-page script then silently fails to load on a page that is plainly using that template. `thinksme_current_template()` records the basename WP actually resolved, via the `template_include` filter (which runs before `get_header()` fires `wp_enqueue_scripts`). The ACF groups still need the template selected — ACF has no page-slug location rule — but the JS no longer does.
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
