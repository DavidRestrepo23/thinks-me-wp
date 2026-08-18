<?php
/**
 * A centred header over columns, each a photograph above a card whose headline is
 * a figure. Two frames:
 *
 *   "Types of Property Eligible for Cashout in Singapore" (951:9448, file
 *   "Think SME- INTERNAL", CgqSxvxd3aQeQSkLPhc48q) — four columns, portrait
 *   photographs, no checklist.
 *   "Financing Options at a Glance" (119:2043, file "Untitled",
 *   vzdpOnH1U36oXcFcugiyE5) — three columns, landscape photographs, three checks
 *   under each description.
 *
 * The differences are all values (the track, the photograph's proportion, whether
 * there is a checklist), which is why this is one part rather than two.
 *
 * ACF (Property Cashout and Business Loan pages): ci_types_hat_text,
 * ci_types_heading, ci_types_text and, per card (1..4), ci_types_card_N_label,
 * _value, _text, _image, _feature_1..3 (the Business Loan frame's checklist —
 * absent from the Property Cashout one, where an empty list renders nothing). The design's copy lives in thinksme_ci_defaults( 'types' ); a page whose
 * set has no such section renders nothing, so this part is inert on the other six
 * ci-* pages.
 *
 * A card is skipped when its label is empty, so three property types is a matter
 * of clearing fields rather than of editing this file. The grid is
 * `lg:grid-cols-4` and the cards stretch, so the four detail panels line up
 * whether or not their descriptions run to the same number of lines — which they
 * don't in the design ("Ramp-up" and "Mixed retail-resi" both wrap).
 *
 * The photograph keeps Figma's 296x412 proportion through aspect-ratio rather than
 * a height, so it scales with the column instead of needing a mobile spec; a
 * client upload lands in exactly the same box. Below md the four columns become
 * two and then one, and nothing else about a card changes — at 296px wide it is
 * already close to its phone size.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$d = thinksme_ci_defaults( 'types' );

if ( ! $d ) {
	return;
}

// Four equal columns is what the Property Cashout frame draws; the Business Loan
// one draws three, which is a value rather than a second layout. One whole class
// string for the Tailwind reason inc/ci-content.php's header gives.
$grid_class = isset( $d['grid_class'] ) ? $d['grid_class'] : 'lg:grid-cols-4';

// Figma's own proportion for the photograph: 296x412 on one frame, 405x300 on the
// other. Held as aspect-ratio either way, so it scales with the column and a
// client upload lands in exactly the same box.
$image_box = isset( $d['image_box'] ) ? $d['image_box'] : 'aspect-[296/412]';

$cards = array();

foreach ( $d['cards'] as $n => $default ) {
	$label = thinksme_field( "ci_types_card_{$n}_label", false, $default['label'] );

	if ( '' === trim( $label ) ) {
		continue;
	}

	$upload = thinksme_field( "ci_types_card_{$n}_image" );

	// The Business Loan frame adds three checks under each description; the Property
	// Cashout one draws none, so an empty list renders nothing. Flat fields, empties
	// skipped — ACF free has no Repeater, the same reason ci-pricing.php's features
	// are flat.
	$features = array();

	foreach ( range( 1, 3 ) as $f ) {
		$fallback = isset( $default['features'][ $f ] ) ? $default['features'][ $f ] : '';
		$feature  = thinksme_field( "ci_types_card_{$n}_feature_{$f}", false, $fallback );

		if ( '' !== trim( $feature ) ) {
			$features[] = $feature;
		}
	}

	$cards[] = array(
		'label' => $label,
		'value' => thinksme_field( "ci_types_card_{$n}_value", false, $default['value'] ),
		'text'  => thinksme_field( "ci_types_card_{$n}_text", false, $default['text'] ),
		'image' => ! empty( $upload['url'] ) ? $upload['url'] : thinksme_ci_image_url( $default['image'] ),
		'alt'   => ! empty( $upload['alt'] ) ? $upload['alt'] : '',
		'features' => $features,
	);
}

if ( ! $cards ) {
	return;
}
?>
<section id="ci-types" class="flex flex-col items-center gap-xl lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col items-center gap-md text-center max-w-[1008px]">
		<?php $hat = thinksme_field( 'ci_types_hat_text', false, $d['hat'] ); ?>
		<?php if ( $hat ) : ?>
			<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary">
			<?php echo esc_html( thinksme_field( 'ci_types_heading', false, $d['heading'] ) ); ?>
		</h2>

		<?php $intro = thinksme_field( 'ci_types_text', false, $d['text'] ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm leading-relaxed text-text-secondary max-w-[671px]">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>
	</div>

	<div class="grid grid-cols-1 sm:grid-cols-2 <?php echo esc_attr( $grid_class ); ?> gap-xl w-full max-w-[1280px]">
		<?php foreach ( $cards as $card ) : ?>
			<div class="flex flex-col gap-xs">
				<div class="w-full <?php echo esc_attr( $image_box ); ?> overflow-hidden rounded-md">
					<img src="<?php echo esc_url( $card['image'] ); ?>" alt="<?php echo esc_attr( $card['alt'] ); ?>" loading="lazy" class="w-full h-full object-cover">
				</div>

				<?php // `grow` so the four panels share the row's height and their top edges stay level when one description wraps and another doesn't. ?>
				<div class="grow flex flex-col gap-md bg-surface-faint border border-border-faint rounded-md p-xl">
					<p class="font-medium text-sm leading-normal text-text-primary"><?php echo esc_html( $card['label'] ); ?></p>

					<?php if ( $card['value'] ) : ?>
						<p class="font-medium text-[32px] leading-tight tracking-hero text-text-primary"><?php echo esc_html( $card['value'] ); ?></p>
					<?php endif; ?>

					<?php if ( $card['text'] ) : ?>
						<p class="font-normal text-sm leading-loose text-text-secondary"><?php echo esc_html( $card['text'] ); ?></p>
					<?php endif; ?>

					<?php if ( $card['features'] ) : ?>
						<?php // The same green check badge every list in the theme uses, at the 32px Figma draws here rather than ci-pricing's 40px. `mt-auto` pins the list to the bottom of the panel, so three cards whose descriptions wrap differently still line their checks up. ?>
						<ul class="flex flex-col gap-md mt-auto">
							<?php foreach ( $card['features'] as $feature ) : ?>
								<li class="flex gap-md items-center">
									<span class="bg-accent-green rounded-[10px] size-[32px] inline-flex items-center justify-center shrink-0">
										<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[15.4px]">
									</span>
									<span class="font-normal text-sm leading-loose text-text-secondary">
										<?php echo esc_html( $feature ); ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
