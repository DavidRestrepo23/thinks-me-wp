<?php
/**
 * Pricing, drawn two ways: a navy panel with a centred heading over the package
 * cards, or the section's copy beside a single card on white.
 * Figma: nodes 85:1351 (the panel, five of the six pages) and 108:4833 (the
 * split, Corporate Tax), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Shared by all six ci-* pages: same markup, same ACF field names, different
 * words and photographs. Every default below comes from thinksme_ci_defaults() in
 * inc/ci-content.php, which is the one place the pages differ — change the
 * design's copy there, not here.
 *
 * ACF (all six pages): ci_pricing_hat_text, ci_pricing_heading, ci_pricing_text,
 * ci_pricing_note (the GST frame's small print under the cards),
 * ci_pricing_popular_label, ci_pricing_feature_1..4 (the split layout's own
 * checklist beside the copy), and per card (1..3) ci_pricing_card_N_icon (select,
 * fed at runtime from thinksme_ci_icons()), _title, _text, _price_label, _price,
 * _price_strike, _price_note (the GST frame's promotional pricing), _badge_1,
 * _badge_2, _features_intro, _feature_1..5, _button_text, _button_link.
 *
 * `layout` is a per-page default, not a client field: it is which frame the design
 * draws, and a page whose set doesn't name one gets the panel every other page
 * has. The card itself is identical in both, so it lives in
 * template-parts/ci-plan.php and both branches render it.
 *
 * The middle card is the highlighted one — on the panel that means a blue fill,
 * white text, a "MOST POPULAR" tab above it and the solid yellow split button,
 * where the outer two get a white card and an outlined button. On the split it is
 * the first card, since that layout only draws one. That styling belongs to the
 * *slot*, not to the content, the same way cards-deck.php and testimonials.php
 * colour their cards by position. A `featured` checkbox per card would let the
 * client tick all three and flatten the section; position can't be got wrong.
 *
 * Features are five flat fields per card rather than a Repeater — ACF free has
 * no Repeater (same reason roa-plan.php uses roa_plan_benefit_N). Empty ones are
 * skipped, so Figma's 5/3/3 split renders as-is and the client can top each card
 * up to five without ever showing a blank row.
 *
 * `ci_pricing_card_N_features_intro` is the "Everything in Essential, plus:"
 * line. Figma leaves it off the first card, so an empty value renders nothing
 * rather than an empty heading.
 *
 * The cityscape behind the panel's heading is the Figma artwork exported as one
 * SVG rather than the ~150 separate vectors it is on the canvas — same treatment
 * as assets/images/roa/skyline.svg, down to stripping by hand the opaque #1E1E1E
 * canvas rect that Figma includes in the export. It carries its own navy rounded
 * panel, so it sits over the section background seamlessly. The split layout has
 * no such artwork: that frame is white.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$images_uri = get_template_directory_uri() . '/assets/images/ci';

$d        = thinksme_ci_defaults( 'pricing' );
$defaults = $d['cards'];
$layout   = isset( $d['layout'] ) ? $d['layout'] : 'panel';

$cards = array();

foreach ( $defaults as $n => $default ) {
	$title = thinksme_field( "ci_pricing_card_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$features = array();
	foreach ( range( 1, 5 ) as $f ) {
		$fallback = isset( $default['features'][ $f - 1 ] ) ? $default['features'][ $f - 1 ] : '';
		$feature  = thinksme_field( "ci_pricing_card_{$n}_feature_{$f}", false, $fallback );

		if ( '' !== trim( $feature ) ) {
			$features[] = $feature;
		}
	}

	$cards[ $n ] = array(
		'icon'           => thinksme_ci_icon_url( thinksme_field( "ci_pricing_card_{$n}_icon", false, $default['icon'] ) ),
		'title'          => $title,
		'text'           => thinksme_field( "ci_pricing_card_{$n}_text", false, $default['text'] ),
		'price_label'    => thinksme_field( "ci_pricing_card_{$n}_price_label", false, $default['price_label'] ),
		'price'          => thinksme_field( "ci_pricing_card_{$n}_price", false, $default['price'] ),
		// The GST frame's promotional pricing: the usual figure struck through beside
		// the price, and the terms under it. Absent from every other set.
		'price_strike'   => thinksme_field( "ci_pricing_card_{$n}_price_strike", false, isset( $default['price_strike'] ) ? $default['price_strike'] : '' ),
		'price_note'     => thinksme_field( "ci_pricing_card_{$n}_price_note", false, isset( $default['price_note'] ) ? $default['price_note'] : '' ),
		// Which pill the design drew, not a client choice — see template-parts/ci-plan.php.
		'badge_1_style'  => isset( $default['badge_1_style'] ) ? $default['badge_1_style'] : 'outline',
		'badge_1'        => thinksme_field( "ci_pricing_card_{$n}_badge_1", false, $default['badge_1'] ),
		'badge_2'        => thinksme_field( "ci_pricing_card_{$n}_badge_2", false, $default['badge_2'] ),
		'features_intro' => thinksme_field( "ci_pricing_card_{$n}_features_intro", false, $default['features_intro'] ),
		'features'       => $features,
		'button_text'    => thinksme_field( "ci_pricing_card_{$n}_button_text", false, 'Get Started' ),
		'button_link'    => thinksme_field( "ci_pricing_card_{$n}_button_link", false, '/contact-us' ),
	);
}

if ( ! $cards ) {
	return;
}

$hat           = thinksme_field( 'ci_pricing_hat_text', false, $d['hat'] );
$heading       = thinksme_field( 'ci_pricing_heading', false, $d['heading'] );
$intro         = thinksme_field( 'ci_pricing_text', false, $d['text'] );
$popular_label = thinksme_field( 'ci_pricing_popular_label', false, $d['popular_label'] );

// On the panel the highlighted slot is the middle card, as Figma draws it. On the
// split there is one card, so it is that one.
$featured_slot = 'panel' === $layout ? 2 : array_key_first( $cards );

// A section pricing one package is a card, not a rail — see .ci-plans--single in
// src/base.css. assets/js/ci-plans.js already leaves a lone card alone.
$plans_class = count( $cards ) < 2 ? 'ci-plans ci-plans--single' : 'ci-plans';

if ( 'split' === $layout ) :
	$features = array();

	foreach ( range( 1, 4 ) as $f ) {
		$fallback = isset( $d['features'][ $f - 1 ] ) ? $d['features'][ $f - 1 ] : '';
		$feature  = thinksme_field( "ci_pricing_feature_{$f}", false, $fallback );

		if ( '' !== trim( $feature ) ) {
			$features[] = $feature;
		}
	}
	?>
	<section id="ci-pricing" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
		<?php // Figma reads copy-then-card left to right; stacked, the same order still holds — the checklist introduces the package, so it comes before it. ?>
		<div class="flex flex-col lg:flex-row lg:items-start justify-between gap-xl lg:gap-[131px]">
			<div class="flex flex-col gap-xl w-full lg:basis-[602px] lg:min-w-0">
				<div class="flex flex-col items-start gap-lg">
					<?php if ( $hat ) : ?>
						<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
							<?php echo esc_html( $hat ); ?>
						</span>
					<?php endif; ?>

					<?php // Capped at Figma's own column width so the heading breaks where the design breaks it — the column itself is wider, because the paragraph below runs to 506px. ?>
					<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary max-w-[512px]">
						<?php echo esc_html( $heading ); ?>
					</h2>

					<?php if ( $intro ) : ?>
						<p class="font-normal text-sm leading-loose text-text-secondary max-w-[506px]">
							<?php echo esc_html( $intro ); ?>
						</p>
					<?php endif; ?>
				</div>

				<?php if ( $features ) : ?>
					<hr class="border-0 border-t border-border-faint w-full">

					<ul class="flex flex-col gap-md">
						<?php foreach ( $features as $feature ) : ?>
							<li class="flex gap-md items-center">
								<span class="bg-accent-green rounded-[12px] size-[40px] inline-flex items-center justify-center shrink-0">
									<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[19.2px]">
								</span>
								<span class="font-normal text-sm leading-loose text-text-secondary">
									<?php echo esc_html( $feature ); ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php // The same rail the panel layout uses, so a client who fills the empty cards gets a slider below lg here too rather than a stack this layout was never measured for. ?>
			<div class="<?php echo esc_attr( $plans_class ); ?> relative w-full lg:basis-[547px] lg:min-w-0">
				<div class="ci-plans__track flex gap-md lg:items-stretch">
					<?php foreach ( $cards as $n => $card ) : ?>
						<?php
						get_template_part(
							'template-parts/ci-plan',
							null,
							array(
								'card'          => $card,
								'featured'      => $n === $featured_slot,
								'variant'       => 'split',
								'popular_label' => $popular_label,
							)
						);
						?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return;
endif;
?>
<section id="ci-pricing" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="relative overflow-hidden rounded-[40px] lg:rounded-[80px] bg-surface-dark px-lg lg:px-[56px] pt-3xl pb-xl lg:pb-[56px]">
		<img
			src="<?php echo esc_url( "$images_uri/pricing-bg.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="absolute inset-x-0 top-0 w-full pointer-events-none select-none"
		>

		<div class="relative flex flex-col items-center gap-md text-center max-w-[765px] mx-auto">
			<?php if ( $hat ) : ?>
				<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $hat ); ?>
				</span>
			<?php endif; ?>

			<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-on-dark">
				<?php echo esc_html( $heading ); ?>
			</h2>

			<?php if ( $intro ) : ?>
				<p class="font-normal text-sm leading-relaxed text-text-on-dark">
					<?php echo esc_html( $intro ); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php // The columns stretch so all three cards are the height of the tallest and the buttons line up, rather than each card sizing to its own feature count. Below lg the same row is a slider: the extra track element is Swiper's required container/wrapper pair, and it does nothing on desktop — see assets/js/ci-plans.js and .ci-plans in base.css. ?>
		<div class="<?php echo esc_attr( $plans_class ); ?> relative mt-xl lg:mt-3xl">
			<div class="ci-plans__track flex gap-md lg:items-stretch">
				<?php foreach ( $cards as $n => $card ) : ?>
					<?php
					get_template_part(
						'template-parts/ci-plan',
						null,
						array(
							'card'          => $card,
							'featured'      => $n === $featured_slot,
							'variant'       => 'panel',
							'popular_label' => $popular_label,
						)
					);
					?>
				<?php endforeach; ?>
			</div>
		</div>

		<?php
		// The GST frame closes the panel with a line of small print under the cards
		// (114:5502), explaining why filing isn't sold on its own. Optional: the
		// other frames end at the cards.
		$note = thinksme_field( 'ci_pricing_note', false, isset( $d['note'] ) ? $d['note'] : '' );
		?>
		<?php if ( $note ) : ?>
			<p class="relative mt-xl lg:mt-3xl mx-auto max-w-[679px] text-center font-normal text-sm leading-relaxed text-text-on-dark">
				<?php echo esc_html( $note ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>
