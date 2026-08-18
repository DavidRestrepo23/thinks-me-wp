<?php
/**
 * Property Cashout — a photograph beside a column of overlapping cards whose last
 * one is navy and tilted. One part rendering two Figma frames:
 *
 *   'benefits' — "Key Benefits of Property Cashout for Singapore SMEs" (951:9557),
 *                photograph on the left, five cards on the right.
 *   'why'      — "Why Choose ThinkSME for Property Cashout in Singapore?"
 *                (951:9444 + 951:9490 + 951:9545 + 951:9627), the same shape
 *                mirrored: cards on the left, photograph on the right.
 *
 * File "Think SME- INTERNAL" (CgqSxvxd3aQeQSkLPhc48q).
 *
 * Called with array( 'instance' => 'benefits' | 'why' ) and reads
 * thinksme_ci_defaults( 'stack' )[ $instance ] — the same arrangement
 * template-parts/ci-grid.php uses for its two frames. The two differ in their
 * copy, their photograph and which side it takes; everything else is identical,
 * which is why this is one part called twice rather than two near-copies.
 *
 * ACF (Property Cashout page), per instance: ci_stack_<instance>_hat_text,
 * _heading, _image and, per card (1..5), ci_stack_<instance>_card_N_icon (select,
 * fed at runtime from thinksme_ci_icons()), _title, _text.
 *
 * The overlap and the tilt are `.ci-stack*` in src/base.css, not utilities: the
 * cards pull up over the one before them by 48px and each has to paint above its
 * predecessor, which is a z-index per slot rather than a class per card. **The
 * last card is styled by position, not by a field** — the same convention
 * cards-deck.php, testimonials.php and ci-pricing.php follow. A per-card "make
 * this one navy" checkbox would let the client tick all five and flatten the
 * section into a navy block.
 *
 * A card is skipped when its title is empty, and the navy treatment follows
 * whichever card ends up last — so four cards still close on the dark one rather
 * than leaving the section trailing off in grey.
 *
 * That navy card is also the row's **hover state**, tilt and all: any card takes the
 * fill and the -2.07deg while the pointer is on it (or something inside it holds
 * focus) and lifts over the card overlapping it, and the resting dark card
 * straightens up and hands both over — so exactly one card is ever dark and tilted,
 * and it follows the pointer. The 4px shift that goes with the tilt is part of the
 * transform rather than a margin, because a margin coming and going would reflow
 * the column instead of just turning the card. All of it is `.ci-stack__card:hover`
 * in src/base.css, inside `hover: hover` so a tap on a phone can't leave a card
 * stuck dark, and inside `min-width: 1024px` for the tilt, since below `lg` the
 * cards don't overlap and a rotated full-width card overflows the viewport.
 *
 * Below lg the row stacks (photograph first for 'benefits', last for 'why', which
 * is each frame's own reading order) and the cards lose both the overlap and the
 * tilt: pulled together in one column on a phone they read as a broken list, and a
 * rotated full-width card overflows the viewport.
 */

$instance = isset( $args['instance'] ) ? sanitize_key( $args['instance'] ) : '';
$sets     = thinksme_ci_defaults( 'stack' );

if ( ! $instance || empty( $sets[ $instance ] ) ) {
	return;
}

$d = $sets[ $instance ];

$cards = array();

foreach ( $d['cards'] as $n => $default ) {
	$title = thinksme_field( "ci_stack_{$instance}_card_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$cards[] = array(
		'icon'  => thinksme_ci_icon_url( thinksme_field( "ci_stack_{$instance}_card_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		'text'  => thinksme_field( "ci_stack_{$instance}_card_{$n}_text", false, $default['text'] ),
	);
}

if ( ! $cards ) {
	return;
}

$last = count( $cards ) - 1;

$photo     = thinksme_field( "ci_stack_{$instance}_image" );
$photo_url = ! empty( $photo['url'] ) ? $photo['url'] : thinksme_ci_image_url( $d['image'] );
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

// Figma puts the photograph left on one frame and right on the other. Below lg it
// leads on the frame that leads with it and trails on the one that doesn't.
$photo_first = 'left' === $d['photo'];
?>
<section id="ci-stack-<?php echo esc_attr( $instance ); ?>" class="flex flex-col items-center gap-xl lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col items-center gap-md text-center max-w-[920px]">
		<?php $hat = thinksme_field( "ci_stack_{$instance}_hat_text", false, $d['hat'] ); ?>
		<?php if ( $hat ) : ?>
			<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary">
			<?php echo esc_html( thinksme_field( "ci_stack_{$instance}_heading", false, $d['heading'] ) ); ?>
		</h2>
	</div>

	<div class="flex flex-col <?php echo $photo_first ? 'lg:flex-row' : 'lg:flex-row-reverse'; ?> lg:items-start gap-xl lg:gap-[107px] w-full max-w-[1296px]">
		<div class="w-full max-w-[547px] mx-auto lg:mx-0 lg:shrink-0 <?php echo $photo_first ? '' : 'order-last lg:order-none'; ?>">
			<div class="w-full <?php echo esc_attr( $d['image_box'] ); ?> overflow-hidden rounded-xl">
				<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" loading="lazy" class="w-full h-full object-cover">
			</div>
		</div>

		<ul class="ci-stack w-full lg:max-w-[624px] lg:min-w-0">
			<?php foreach ( $cards as $index => $card ) : ?>
				<?php // --ci-stack-index drives the z-index, so each card paints over the one it overlaps; data-tone marks the last slot, which is the design's navy card. ?>
				<li
					class="ci-stack__card"
					style="--ci-stack-index: <?php echo esc_attr( $index ); ?>;"
					<?php echo $index === $last ? 'data-tone="dark"' : ''; ?>
				>
					<span class="ci-stack__icon" aria-hidden="true">
						<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[32px]">
					</span>

					<div class="flex flex-col gap-xl min-w-0">
						<?php // The colour is repeated on the heading itself: base.css sets a hard `color` on h1..h6, and a rule on the element beats a colour inherited from the card. ?>
						<h3 class="font-medium text-xl leading-normal <?php echo $index === $last ? 'text-text-on-dark' : 'text-text-primary'; ?>">
							<?php echo esc_html( $card['title'] ); ?>
						</h3>

						<?php if ( $card['text'] ) : ?>
							<p class="font-normal text-sm leading-loose <?php echo $index === $last ? 'text-text-on-dark' : 'text-text-secondary'; ?>">
								<?php echo esc_html( $card['text'] ); ?>
							</p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
