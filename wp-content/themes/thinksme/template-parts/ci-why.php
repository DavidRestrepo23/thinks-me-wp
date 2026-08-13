<?php
/**
 * Company Incorporation, Local and Foreign — why founders choose Think SME: a heading over a
 * five-card bento grid.
 * Figma: node 85:1830, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Shared verbatim by page-company-incorporation-local.php and
 * page-company-incorporation-foreign.php: same markup, same ACF field names,
 * different words and photographs. Every default below comes from
 * thinksme_ci_defaults() in inc/ci-content.php, which is the one place the two
 * pages differ — change the design's copy there, not here.
 *
 * ACF (both Company Incorporation pages): ci_why_hat_text, ci_why_heading,
 * ci_why_text, and per card the fields its variant actually uses —
 * ci_why_card_1_icon/_title/_text/_image, _card_2_icon/_title/_text,
 * _card_3_image plus the optional _card_3_icon/_title/_text, _card_4_icon/
 * _title/_text, _card_5_image/_title/_text.
 *
 * The five cards are five different shapes, not one card repeated: a wide photo
 * with the copy over it, a plain white one, a photo with no copy at all, a navy
 * one carrying the cityscape artwork, and a pale yellow one with the photo above
 * the copy. Each is written out rather than looped, because a loop would need a
 * variant flag per card and that flag would be the one thing in this section a
 * client could set wrong. Variant belongs to the slot, the same way the card
 * palettes in cards-deck.php and testimonials.php do.
 *
 * Card 3 is a photo that may or may not carry copy. On the three Company
 * Incorporation pages Figma leaves it wordless, so it renders as a decorative
 * image — `alt=""`, no heading. The Accounting & Bookkeeping frame (102:3891)
 * writes a title and a paragraph into that slot, so when the set supplies them
 * the card takes card 1's shape instead: same scrim, same icon disc, same copy
 * block over the photograph. Which of the two it is follows from whether there
 * is a title, so no page needs a flag for it.
 *
 * The exported photos are downscaled to roughly twice their display size and
 * re-encoded as JPEG — Figma serves them as 3300px PNGs (22 MB for one of the
 * three), which is not something to commit to a theme or ship to a phone.
 *
 * The artwork behind card 4 is the same vectorised cityscape used by
 * ci-pricing.php, exported separately at that card's crop. Unlike the pricing
 * export it has no opaque canvas rect to strip.
 */

$images_uri = get_template_directory_uri() . '/assets/images/ci';

$d = thinksme_ci_defaults( 'why' );

/**
 * Resolve an ACF image field to a url/alt pair, falling back to a theme file.
 *
 * The fallback keeps the section looking like the design before the client has
 * uploaded anything, the same way hero.php and testimonials.php fall back.
 */
$ci_why_image = function ( $field, $fallback_file ) {
	$image = thinksme_field( $field );

	if ( ! empty( $image['url'] ) ) {
		return array(
			'url' => $image['url'],
			'alt' => ! empty( $image['alt'] ) ? $image['alt'] : '',
		);
	}

	return array(
		'url' => thinksme_ci_image_url( $fallback_file ),
		'alt' => '',
	);
};

$card_1 = array(
	'icon'  => thinksme_ci_icon_url( thinksme_field( 'ci_why_card_1_icon', false, $d['cards'][1]['icon'] ) ),
	'title' => thinksme_field( 'ci_why_card_1_title', false, $d['cards'][1]['title'] ),
	'text'  => thinksme_field( 'ci_why_card_1_text', false, $d['cards'][1]['text'] ),
	'image' => $ci_why_image( 'ci_why_card_1_image', $d['cards'][1]['image'] ),
);

$card_2 = array(
	'icon'  => thinksme_ci_icon_url( thinksme_field( 'ci_why_card_2_icon', false, $d['cards'][2]['icon'] ) ),
	'title' => thinksme_field( 'ci_why_card_2_title', false, $d['cards'][2]['title'] ),
	'text'  => thinksme_field( 'ci_why_card_2_text', false, $d['cards'][2]['text'] ),
);

$card_3 = array(
	'image' => $ci_why_image( 'ci_why_card_3_image', $d['cards'][3]['image'] ),
	// Empty on the pages whose design leaves this card wordless — see the note above.
	'icon'  => thinksme_ci_icon_url( thinksme_field( 'ci_why_card_3_icon', false, $d['cards'][3]['icon'] ) ),
	'title' => thinksme_field( 'ci_why_card_3_title', false, $d['cards'][3]['title'] ),
	'text'  => thinksme_field( 'ci_why_card_3_text', false, $d['cards'][3]['text'] ),
);

$card_4 = array(
	'icon'  => thinksme_ci_icon_url( thinksme_field( 'ci_why_card_4_icon', false, $d['cards'][4]['icon'] ) ),
	'title' => thinksme_field( 'ci_why_card_4_title', false, $d['cards'][4]['title'] ),
	'text'  => thinksme_field( 'ci_why_card_4_text', false, $d['cards'][4]['text'] ),
);

$card_5 = array(
	'title' => thinksme_field( 'ci_why_card_5_title', false, $d['cards'][5]['title'] ),
	'text'  => thinksme_field( 'ci_why_card_5_text', false, $d['cards'][5]['text'] ),
	'image' => $ci_why_image( 'ci_why_card_5_image', $d['cards'][5]['image'] ),
);
?>
<section id="ci-why" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="bg-surface-panel rounded-[40px] lg:rounded-[80px] overflow-hidden px-lg lg:px-3xl py-3xl">
		<div class="flex flex-col items-center gap-xl text-center max-w-[1008px] mx-auto">
			<?php $hat = thinksme_field( 'ci_why_hat_text', false, $d['hat'] ); ?>
			<?php if ( $hat ) : ?>
				<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $hat ); ?>
				</span>
			<?php endif; ?>

			<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-text-primary">
				<?php echo esc_html( thinksme_field( 'ci_why_heading', false, $d['heading'] ) ); ?>
			</h2>

			<?php $intro = thinksme_field( 'ci_why_text', false, $d['text'] ); ?>
			<?php if ( $intro ) : ?>
				<p class="font-normal text-sm leading-loose text-text-heading-dark max-w-[650px]">
					<?php echo esc_html( $intro ); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php // One 3-column grid covers both Figma rows: the wide photo card spans two of them, and the second row's three cards take one each. ?>
		<?php // Stacked in one column the five cards are one list, so they all take the same 366px below md — Figma's own mobile frame (101:79) lets each height follow its content (330..422), which reads as five different components rather than one set. From md the grid takes over and heights go back to the row's. ?>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md lg:gap-lg lg:gap-x-[15px] mt-xl lg:mt-3xl">
			<article class="ci-why-card md:col-span-2 relative overflow-hidden rounded-lg h-[366px] md:h-auto md:min-h-[340px] lg:min-h-[398px] flex flex-col justify-between gap-xl px-lg lg:px-[48px] py-lg lg:py-xl">
				<img src="<?php echo esc_url( $card_1['image']['url'] ); ?>" alt="<?php echo esc_attr( $card_1['image']['alt'] ); ?>" class="absolute inset-0 size-full object-cover">
				<span class="ci-why-card__scrim" aria-hidden="true"></span>

				<span class="relative bg-brand-yellow rounded-pill size-[72px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( $card_1['icon'] ); ?>" alt="" class="size-[31px]">
				</span>

				<?php // The colour is repeated on the heading itself: base.css sets a hard `color` on h1..h6, and a rule on the element beats a colour inherited from this wrapper. ?>
				<?php // gap-md below lg so the copy fits the shared 366px without clipping — also what the other cards use between title and description. ?>
				<div class="relative flex flex-col gap-md lg:gap-lg text-text-on-dark max-w-[563px]">
					<h3 class="font-medium text-[28px] lg:text-2xl leading-snug text-text-on-dark"><?php echo esc_html( $card_1['title'] ); ?></h3>
					<?php if ( $card_1['text'] ) : ?>
						<p class="font-normal text-sm leading-loose"><?php echo esc_html( $card_1['text'] ); ?></p>
					<?php endif; ?>
				</div>
			</article>

			<article class="ci-why-card bg-surface-white rounded-lg h-[366px] md:h-auto lg:min-h-[398px] flex flex-col justify-between gap-xl p-lg lg:p-[40px]">
				<span class="bg-brand-yellow rounded-pill size-[72px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( $card_2['icon'] ); ?>" alt="" class="size-[31px]">
				</span>

				<div class="flex flex-col gap-md">
					<h3 class="font-medium text-xl lg:text-[32px] leading-snug text-text-heading-dark"><?php echo esc_html( $card_2['title'] ); ?></h3>
					<?php if ( $card_2['text'] ) : ?>
						<p class="font-normal text-sm leading-loose text-text-secondary"><?php echo esc_html( $card_2['text'] ); ?></p>
					<?php endif; ?>
				</div>
			</article>

			<?php if ( '' === trim( $card_3['title'] ) ) : ?>
				<?php // No copy in this page's design, so the card is decorative: alt="" and no heading. ?>
				<div class="ci-why-card rounded-lg overflow-hidden h-[366px] md:h-auto">
					<img src="<?php echo esc_url( $card_3['image']['url'] ); ?>" alt="<?php echo esc_attr( $card_3['image']['alt'] ); ?>" class="size-full object-cover">
				</div>
			<?php else : ?>
				<?php // With copy the slot takes card 1's shape — scrim, icon disc, title and paragraph over the photograph. ?>
				<article class="ci-why-card relative overflow-hidden rounded-lg h-[366px] md:h-auto flex flex-col justify-between gap-xl p-lg lg:p-[40px]">
					<img src="<?php echo esc_url( $card_3['image']['url'] ); ?>" alt="<?php echo esc_attr( $card_3['image']['alt'] ); ?>" class="absolute inset-0 size-full object-cover">
					<span class="ci-why-card__scrim" aria-hidden="true"></span>

					<span class="relative bg-brand-yellow rounded-pill size-[72px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( $card_3['icon'] ); ?>" alt="" class="size-[31px]">
					</span>

					<div class="relative flex flex-col gap-md text-text-on-dark">
						<h3 class="font-medium text-xl lg:text-[32px] leading-snug text-text-on-dark"><?php echo esc_html( $card_3['title'] ); ?></h3>
						<?php if ( $card_3['text'] ) : ?>
							<p class="font-normal text-sm leading-loose"><?php echo esc_html( $card_3['text'] ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endif; ?>

			<article class="ci-why-card relative overflow-hidden bg-surface-dark rounded-lg h-[366px] md:h-auto flex flex-col justify-between gap-xl p-lg lg:p-[40px]">
				<img
					src="<?php echo esc_url( "$images_uri/why-card-bg.svg" ); ?>"
					alt=""
					aria-hidden="true"
					class="absolute inset-x-0 bottom-0 w-full pointer-events-none select-none opacity-70"
				>

				<span class="relative bg-brand-yellow rounded-pill size-[72px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( $card_4['icon'] ); ?>" alt="" class="size-[31px]">
				</span>

				<div class="relative flex flex-col gap-md text-text-on-dark">
					<h3 class="font-medium text-xl lg:text-[32px] leading-snug text-text-on-dark"><?php echo esc_html( $card_4['title'] ); ?></h3>
					<?php if ( $card_4['text'] ) : ?>
						<p class="font-normal text-sm leading-loose"><?php echo esc_html( $card_4['text'] ); ?></p>
					<?php endif; ?>
				</div>
			</article>

			<article class="ci-why-card bg-card-grow rounded-lg overflow-hidden h-[366px] md:h-auto flex flex-col justify-between gap-xl p-md">
				<?php // This is the one card whose content is taller than the shared 366px, so its photo gives up the difference: min-h-0 lets the flex item shrink past its intrinsic height, and object-cover crops rather than squashes. From md the card is free again and the photo is back at its Figma 211px. ?>
				<img src="<?php echo esc_url( $card_5['image']['url'] ); ?>" alt="<?php echo esc_attr( $card_5['image']['alt'] ); ?>" class="w-full h-[211px] min-h-0 md:min-h-[211px] object-cover rounded-md">

				<div class="flex flex-col gap-md px-lg pb-lg">
					<h3 class="font-medium text-xl lg:text-[32px] leading-snug text-text-heading-dark"><?php echo esc_html( $card_5['title'] ); ?></h3>
					<?php if ( $card_5['text'] ) : ?>
						<p class="font-normal text-sm leading-loose text-text-secondary"><?php echo esc_html( $card_5['text'] ); ?></p>
					<?php endif; ?>
				</div>
			</article>
		</div>
	</div>
</section>
