<?php
/**
 * Registered Office Address — page hero: headline, intro, one button, and the
 * building photo on the right.
 * Figma: node 67:49, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Registered Office Address page): roa_hero_title, roa_hero_text,
 * roa_hero_button_text / _link, roa_hero_image, roa_hero_image_cutout.
 *
 * Two brush strokes sit under the first two lines of the headline, the same
 * treatment the homepage hero uses. Like there, they're desktop-only: their
 * placement is tied to the 72px type breaking across exactly three lines, and
 * at smaller type they'd strike through the wrong words.
 *
 * The photo is two layers, as in Figma: the landscape shot inside a rounded
 * card, and a transparent cut-out of the same building on top, which is what
 * lets the tower break above the card's top edge. The cut-out is optional — a
 * client who uploads only a photo gets a plain rounded card, which is why the
 * crop switches to a straight object-cover in that case (the Figma offsets are
 * measured against the exported photo and mean nothing for another image).
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$images_uri = get_template_directory_uri() . '/assets/images/roa';

$photo   = thinksme_field( 'roa_hero_image' );
$cutout  = thinksme_field( 'roa_hero_image_cutout' );
$is_stock = empty( $photo['url'] ) && empty( $cutout['url'] );

$photo_url  = ! empty( $photo['url'] ) ? $photo['url'] : "$images_uri/hero-building.jpg";
$photo_alt  = ! empty( $photo['alt'] ) ? $photo['alt'] : '';
$cutout_url = ! empty( $cutout['url'] ) ? $cutout['url'] : ( $is_stock ? "$images_uri/hero-foreground.png" : '' );

$button_text = thinksme_field( 'roa_hero_button_text', false, 'Enquire Now' );
$button_link = thinksme_field( 'roa_hero_button_link', false, '#' );
?>
<section id="roa-hero" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-xl lg:gap-[34px] w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="relative flex flex-col justify-center gap-xl w-full lg:w-[663px] lg:shrink-0">
		<div class="relative w-full">
			<?php // Brush strokes under lines 1 and 2 of the headline. Positions are the Figma offsets as a share of the 663px column, so they hold while the column flexes. ?>
			<img
				src="<?php echo esc_url( "$icons_uri/roa/hero-underline-1.svg" ); ?>"
				alt=""
				aria-hidden="true"
				class="hidden lg:block absolute left-[0.6%] top-[56px] w-[82.53%] rotate-[2.2deg] pointer-events-none"
			>
			<img
				src="<?php echo esc_url( "$icons_uri/roa/hero-underline-2.svg" ); ?>"
				alt=""
				aria-hidden="true"
				class="hidden lg:block absolute left-[0.6%] top-[129px] w-[73.81%] -rotate-[2.2deg] scale-x-[-1] pointer-events-none"
			>
			<h1 class="relative font-medium text-[40px] sm:text-[56px] lg:text-[72px] leading-tight tracking-hero text-text-primary">
				<?php echo esc_html( thinksme_field( 'roa_hero_title', false, 'Local Registered Office Address in Singapore' ) ); ?>
			</h1>
		</div>

		<p class="font-normal text-sm text-text-secondary leading-relaxed">
			<?php echo esc_html( thinksme_field( 'roa_hero_text', false, 'Meet ACRA requirements and present a trusted, credible business address.' ) ); ?>
		</p>

		<?php if ( $button_text ) : ?>
			<a href="<?php echo esc_url( $button_link ); ?>" class="btn-split inline-flex items-center self-start">
				<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
					<?php echo esc_html( $button_text ); ?>
				</span>
				<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
				</span>
			</a>
		<?php endif; ?>
	</div>

	<?php // Held at Figma's 583px and pushed to the right edge by the section's justify-between (the same shape cta.php uses) rather than stretched: this photo is all but square, so growing it into a 1920px viewport would make it taller than the screen. ?>
	<div class="relative w-full lg:w-[583px] lg:shrink-0 aspect-[583/586] overflow-hidden rounded-[54px]">
		<?php // 81.4% = Figma's 477px card in the 586px frame; the card is pinned to the bottom so the cut-out has room to rise above it. ?>
		<div class="absolute inset-x-0 bottom-0 h-[81.4%] rounded-2xl overflow-hidden">
			<?php if ( $is_stock ) : ?>
				<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" class="absolute left-0 top-[-47.93%] w-[103.65%] h-[152.9%] max-w-none object-cover">
			<?php else : ?>
				<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" class="absolute inset-0 w-full h-full object-cover">
			<?php endif; ?>
		</div>

		<?php if ( $cutout_url ) : ?>
			<img
				src="<?php echo esc_url( $cutout_url ); ?>"
				alt=""
				aria-hidden="true"
				class="absolute <?php echo $is_stock ? 'left-0 top-[-20.42%] w-[103.65%] h-[124.46%]' : 'inset-0 w-full h-full'; ?> max-w-none object-cover pointer-events-none"
			>
		<?php endif; ?>
	</div>
</section>
