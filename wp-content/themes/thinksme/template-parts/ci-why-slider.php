<?php
/**
 * Corporate Secretary — why founders choose Think SME, as a carousel: a centred
 * heading over five cards that scroll sideways, with a prev/next pair under them.
 * Figma: nodes 102:2254 (heading), 102:2410 (the cards) and 102:2260 (the arrows),
 * file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * This is the one section the Corporate Secretary page has that the two Company
 * Incorporation pages don't. It is *not* a replacement for `ci-why.php` — that
 * bento is still on this page further down, as "Why SMEs Switch to Think SME".
 * The two sit in the same family and read the same way, which is why this part
 * takes its copy from thinksme_ci_defaults( 'why_slider' ) like every other
 * ci-* part, and renders nothing at all when a page has no such set. So dropping
 * it into the other two templates is harmless rather than a decision.
 *
 * ACF (Corporate Secretary page): ci_why_slider_hat_text, ci_why_slider_heading,
 * ci_why_slider_text, and per card (1..5) ci_why_slider_card_N_title, _text,
 * _image. A card with an empty title is skipped, so the client can run four or
 * six of them without touching the layout.
 *
 * Figma draws the row 2703px wide inside a 1440px frame — it is a carousel on the
 * canvas, not a grid, and the arrow pair under it is drawn as two overlapping
 * Icon Buttons. Three layers, the same arrangement ci-pricing.php's mobile slider
 * uses and for the same reason: the markup is a plain flex row, src/base.css
 * turns it into a scroll-snap rail, and assets/js/ci-why-slider.js swaps that
 * rail for Swiper. Each layer degrades to the one under it — with no JS the
 * section is still swipeable, it just loses the arrows, which are hidden until
 * the script has something to drive.
 *
 * Swiper's `swiper`/`swiper-wrapper`/`swiper-slide` classes are stamped on by
 * that script rather than written here, because `.swiper`'s `overflow: hidden`
 * would otherwise apply with nothing driving it.
 *
 * Each card is a photo above a copy block, not copy over the photo: the scrim in
 * the design darkens the photo's own lower edge (Figma's navy gradient at 80%),
 * which is decoration, so it is a CSS pseudo-element rather than a second image.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$d = thinksme_ci_defaults( 'why_slider' );

if ( empty( $d['cards'] ) ) {
	return;
}

$cards = array();

foreach ( $d['cards'] as $n => $default ) {
	$title = thinksme_field( "ci_why_slider_card_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$image = thinksme_field( "ci_why_slider_card_{$n}_image" );

	$cards[] = array(
		'title'     => $title,
		'text'      => thinksme_field( "ci_why_slider_card_{$n}_text", false, $default['text'] ),
		'image'     => ! empty( $image['url'] ) ? $image['url'] : thinksme_ci_image_url( $default['image'] ),
		'image_alt' => ! empty( $image['alt'] ) ? $image['alt'] : '',
	);
}

if ( ! $cards ) {
	return;
}
?>
<section id="ci-why-slider" class="w-full py-xl lg:py-[40px] overflow-hidden">
	<div class="flex flex-col items-center gap-md text-center max-w-[1008px] mx-auto px-lg lg:px-3xl">
		<?php $hat = thinksme_field( 'ci_why_slider_hat_text', false, $d['hat'] ); ?>
		<?php if ( $hat ) : ?>
			<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary">
			<?php echo esc_html( thinksme_field( 'ci_why_slider_heading', false, $d['heading'] ) ); ?>
		</h2>

		<?php $intro = thinksme_field( 'ci_why_slider_text', false, $d['text'] ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm leading-loose text-text-heading-dark max-w-[650px]">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>
	</div>

	<div class="ci-why-slider mt-xl lg:mt-3xl" data-count="<?php echo esc_attr( count( $cards ) ); ?>">
		<div class="ci-why-slider__track flex items-stretch">
			<?php foreach ( $cards as $card ) : ?>
				<article class="ci-why-slide flex flex-col gap-xs">
					<div class="ci-why-slide__photo">
						<img src="<?php echo esc_url( $card['image'] ); ?>" alt="<?php echo esc_attr( $card['image_alt'] ); ?>" loading="lazy" class="size-full object-cover">
					</div>

					<div class="ci-why-slide__body flex flex-col gap-lg grow">
						<h3 class="font-medium text-xl lg:text-[32px] leading-snug text-text-primary">
							<?php echo esc_html( $card['title'] ); ?>
						</h3>

						<?php if ( $card['text'] ) : ?>
							<p class="font-normal text-sm leading-loose text-text-primary">
								<?php echo esc_html( $card['text'] ); ?>
							</p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>

	<?php // Hidden until ci-why-slider.js has a Swiper to drive them: without it the rail is scrolled by dragging, and two buttons that do nothing are worse than none. ?>
	<div class="ci-why-slider__nav flex items-center justify-center gap-md mt-xl px-lg" hidden>
		<button type="button" class="ci-why-slider__button ci-why-slider__button--prev bg-brand-yellow rounded-pill inline-flex items-center justify-center size-[48px] lg:size-[112px]" aria-label="<?php esc_attr_e( 'Previous card', 'thinksme' ); ?>">
			<img src="<?php echo esc_url( "$icons_uri/arrow-right.svg" ); ?>" alt="" class="size-[24px] lg:size-[64px] rotate-180">
		</button>
		<button type="button" class="ci-why-slider__button ci-why-slider__button--next bg-brand-yellow rounded-pill inline-flex items-center justify-center size-[48px] lg:size-[112px]" aria-label="<?php esc_attr_e( 'Next card', 'thinksme' ); ?>">
			<img src="<?php echo esc_url( "$icons_uri/arrow-right.svg" ); ?>" alt="" class="size-[24px] lg:size-[64px]">
		</button>
	</div>
</section>
