<?php
/**
 * Property Cashout — "Eligibility Requirements for Property Cashout in
 * Singapore": a navy panel with two tabs over a white card holding a checklist
 * and a photograph.
 * Figma: node 951:9631, file "Think SME- INTERNAL" (CgqSxvxd3aQeQSkLPhc48q).
 *
 * ACF (Property Cashout page): ci_eligibility_hat_text, ci_eligibility_heading,
 * ci_eligibility_text, ci_eligibility_image and, per tab (1..2),
 * ci_eligibility_tab_N_label, _title and _item_1..7. The design's copy lives in
 * thinksme_ci_defaults( 'eligibility' ); a page whose set has no such section
 * renders nothing, so this part is inert on the other six ci-* pages.
 *
 * **Figma draws only the first tab's panel**, exactly as it draws only the first of
 * ci-tools.php's three — the second tab is a label on the canvas and nothing else.
 * So both tabs ship the identical panel shape and tab 2's seven items default to
 * empty: its panel renders its title and the photograph until the client fills
 * them in from wp-admin. Unlike the free-tools tabs, which default to lorem, there
 * is no placeholder here — a list of documents a client will be held to is their
 * copy to write, not the theme's to invent.
 *
 * Switching is assets/js/tabs.js, shared with ci-tools.php: it only moves
 * `data-active` / `aria-selected` and toggles [hidden], and it finds this group
 * from the `data-tabs` / `data-tab` / `data-tab-panel` attributes rather than from
 * these class names. Every pixel of the open and closed states is `.ci-elig*` in
 * src/base.css, so with the script gone the first tab's panel stays open and the
 * section still reads as the design.
 *
 * There is no <select> fallback here, unlike the free-tools strip: two labels this
 * length still fit as a row on a phone, where three didn't.
 *
 * An empty tab is skipped — a label is what makes a tab exist — and a lone
 * surviving tab still renders its panel, which tabs.js leaves alone rather than
 * letting a click close the only thing on screen.
 *
 * The photograph is Figma's two-layer arrangement (a rounded card plus a cut-out
 * breaking above and to the right of it), exported flattened onto white. White is
 * correct here even though the section is navy: the layers sit inside the white
 * panel, not on the navy one, and stay within its bounds.
 *
 * The scattered-blocks artwork behind the panel is the same exported SVG the
 * process rail on this page uses (assets/images/pc/panel-bg.svg).
 */

$d = thinksme_ci_defaults( 'eligibility' );

if ( ! $d ) {
	return;
}

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$images_uri = get_template_directory_uri() . '/assets/images/pc';

$tabs = array();

foreach ( $d['tabs'] as $n => $default ) {
	$label = thinksme_field( "ci_eligibility_tab_{$n}_label", false, $default['label'] );

	if ( '' === trim( $label ) ) {
		continue;
	}

	$items = array();

	foreach ( $default['items'] as $i => $item_default ) {
		$item = thinksme_field( "ci_eligibility_tab_{$n}_item_{$i}", false, $item_default );

		if ( '' !== trim( $item ) ) {
			$items[] = $item;
		}
	}

	$tabs[ $n ] = array(
		'label' => $label,
		'title' => thinksme_field( "ci_eligibility_tab_{$n}_title", false, $default['title'] ),
		'items' => $items,
	);
}

if ( ! $tabs ) {
	return;
}

$active = array_key_first( $tabs );

$photo     = thinksme_field( 'ci_eligibility_image' );
$photo_url = ! empty( $photo['url'] ) ? $photo['url'] : thinksme_ci_image_url( $d['image'] );
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';
?>
<section id="ci-eligibility" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="relative overflow-hidden rounded-[40px] lg:rounded-[80px] bg-surface-dark px-lg lg:px-[40px] py-2xl lg:py-3xl">
		<img
			src="<?php echo esc_url( "$images_uri/panel-bg.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="absolute inset-x-0 top-0 w-full pointer-events-none select-none"
		>

		<?php // `relative` so the panel paints above the artwork behind it: that <img> is absolutely positioned and would otherwise cover the header. ?>
		<div class="relative flex flex-col items-center gap-md text-center max-w-[1046px] mx-auto">
			<?php $hat = thinksme_field( 'ci_eligibility_hat_text', false, $d['hat'] ); ?>
			<?php if ( $hat ) : ?>
				<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $hat ); ?>
				</span>
			<?php endif; ?>

			<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-on-dark">
				<?php echo esc_html( thinksme_field( 'ci_eligibility_heading', false, $d['heading'] ) ); ?>
			</h2>

			<?php $intro = thinksme_field( 'ci_eligibility_text', false, $d['text'] ); ?>
			<?php if ( $intro ) : ?>
				<p class="font-normal text-sm leading-relaxed text-text-on-dark max-w-[750px]">
					<?php echo esc_html( $intro ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="ci-elig relative mt-xl lg:mt-[64px]" data-tabs>
			<div class="ci-elig__list" role="tablist" aria-label="<?php echo esc_attr( thinksme_field( 'ci_eligibility_heading', false, $d['heading'] ) ); ?>">
				<?php foreach ( $tabs as $n => $tab ) : ?>
					<button
						type="button"
						class="ci-elig__tab"
						role="tab"
						data-tab
						id="ci-elig-tab-<?php echo esc_attr( $n ); ?>"
						aria-controls="ci-elig-panel-<?php echo esc_attr( $n ); ?>"
						aria-selected="<?php echo $n === $active ? 'true' : 'false'; ?>"
						data-active="<?php echo $n === $active ? 'true' : 'false'; ?>"
						tabindex="<?php echo $n === $active ? '0' : '-1'; ?>"
					>
						<?php echo esc_html( $tab['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<?php foreach ( $tabs as $n => $tab ) : ?>
				<div
					class="ci-elig__panel"
					role="tabpanel"
					data-tab-panel
					id="ci-elig-panel-<?php echo esc_attr( $n ); ?>"
					aria-labelledby="ci-elig-tab-<?php echo esc_attr( $n ); ?>"
					tabindex="0"
					<?php echo $n === $active ? '' : 'hidden'; ?>
				>
					<?php if ( $tab['title'] ) : ?>
						<h3 class="font-medium text-xl leading-normal text-text-primary text-center">
							<?php echo esc_html( $tab['title'] ); ?>
						</h3>
					<?php endif; ?>

					<div class="flex flex-col gap-xl lg:grid lg:grid-cols-[421fr_442fr] lg:gap-x-[72px] lg:items-center w-full">
						<?php if ( $tab['items'] ) : ?>
							<ul class="flex flex-col gap-xl">
								<?php foreach ( $tab['items'] as $item ) : ?>
									<li class="flex items-start gap-md">
										<?php // Figma's rounded square at a 10px radius, the same badge the comparison table draws — not the pill the ROA plan card uses. ?>
										<span class="bg-accent-green rounded-[10px] size-[32px] inline-flex items-center justify-center shrink-0" aria-hidden="true">
											<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[16px]">
										</span>
										<span class="font-normal text-sm leading-loose text-text-secondary">
											<?php echo esc_html( $item ); ?>
										</span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php // Explicitly column 2: with an empty checklist (tab 2 until the client fills it in) the photograph would otherwise become the grid's first item and slide over to the left. ?>
						<div class="w-full max-w-[442px] mx-auto lg:mx-0 lg:col-start-2 <?php echo esc_attr( $d['image_box'] ); ?>">
							<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" loading="lazy" class="w-full h-full object-contain">
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
