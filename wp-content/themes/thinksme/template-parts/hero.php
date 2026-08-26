<?php
/**
 * Hero — headline, buttons, and the fanned photo carousel underneath.
 * Figma: node 30:2342, file "Untitled" (vzdpOnH1U36oXcFcugiyE5). That frame
 * also contains the site header; only the hero is implemented here.
 *
 * ACF (Home page): hero_hat_text, hero_title (basic HTML allowed — <strong>
 * carries the bold half of the headline), hero_subtitle, hero_button_1_text /
 * _link, hero_button_2_text / _link.
 *
 * Photos come from the `hero_image` CPT (client adds/edits/reorders in
 * wp-admin); the five from Figma ship as a fallback.
 *
 * The carousel is a Swiper whose five visible slots reproduce Figma's exact
 * card sizes, rotations and vertical offsets. Like the other sliders in this
 * theme the styling is *positional*: assets/js/hero-slider.js stamps data-pos
 * (0 = centre) and src/base.css maps that to the geometry, so the fan keeps its
 * shape while the photos cycle through it. Loop + autoplay.
 *
 * Not carried over from the frame: the two small badges Figma draws on top of
 * the 1st and 3rd cards (a story-style icon and a client logo bubble). They're
 * bound to specific photos, and with photos rotating through fixed slots a
 * position-based badge would land on the wrong image.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$photos_uri = get_template_directory_uri() . '/assets/images/hero';

$hero_images = new WP_Query(
	array(
		'post_type'      => 'hero_image',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

$photos = array();

if ( $hero_images->have_posts() ) {
	while ( $hero_images->have_posts() ) :
		$hero_images->the_post();
		if ( has_post_thumbnail() ) {
			$photos[] = array(
				'url' => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
				'alt' => get_the_title(),
			);
		}
	endwhile;
	wp_reset_postdata();
}

if ( ! $photos ) {
	// Ordered left-to-right as they sit in the Figma fan, so the middle one
	// starts centred (see initialSlide in assets/js/hero-slider.js).
	foreach ( array( 1, 3, 5, 4, 2 ) as $n ) {
		$photos[] = array(
			'url' => "$photos_uri/hero-$n.jpg",
			'alt' => '',
		);
	}
}

// Swiper's loop reorders slides rather than cloning them, so it needs more
// slides than the five on screen or an edge of the fan ends up empty. Repeat
// the set until there's slack; repeats are aria-hidden.
$slides       = array();
$unique_count = count( $photos );
while ( count( $slides ) < 10 ) {
	foreach ( $photos as $photo ) {
		$slides[] = $photo;
	}
}

$button_1_text = thinksme_field( 'hero_button_1_text', false, 'Speak to an Advisor' );
$button_1_link = thinksme_field( 'hero_button_1_link', false, '#' );
$button_2_text = thinksme_field( 'hero_button_2_text', false, '+65 6012 9642' );
$button_2_link = thinksme_field( 'hero_button_2_link', false, 'tel:+6560129642' );
?>
<section id="hero" class="flex flex-col items-center gap-xl lg:gap-[48px] w-full px-lg lg:px-[47px] py-xl lg:py-[40px]">
	<div class="flex flex-col items-center gap-md w-full max-w-[1093px]">
		<span class="hidden lg:inline-flex bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md items-center justify-center text-center text-xs font-medium text-text-primary">
			<?php echo esc_html( thinksme_field( 'hero_hat_text', false, "Singapore's All-in-One SME Partner · IMDA Pre-Approved Vendor" ) ); ?>
		</span>

		<div class="flex flex-col items-center justify-center gap-lg w-full">
			<div class="relative w-full">
				<?php // Brush stroke under the bold half of the headline. Desktop only: its placement is tied to the 80px type breaking across exactly two lines. ?>
				<img
					src="<?php echo esc_url( "$icons_uri/hero-underline.svg" ); ?>"
					alt=""
					aria-hidden="true"
					class="hidden lg:block absolute left-[21.6%] top-[88%] w-[67.3%] rotate-[1.64deg] pointer-events-none"
				>
				<h1 class="relative font-medium text-[40px] sm:text-[56px] lg:text-4xl text-center leading-tight tracking-hero text-text-primary">
					<?php echo wp_kses_post( thinksme_field( 'hero_title', false, 'Everything Your SME Needs to <strong>Start, Run &amp; Grow</strong>' ) ); ?>
				</h1>
			</div>

			<p class="font-normal text-md text-text-secondary text-center leading-relaxed tracking-tight max-w-[724px]">
				<?php echo esc_html( thinksme_field( 'hero_subtitle', false, 'Company incorporation in 24 hours. Accounting, Corporate Secretary, GST & tax compliance. Business loans from 60+ lenders. Property cashout. PSG Xero grant. One team, full support.' ) ); ?>
			</p>

			<div class="flex flex-col sm:flex-row sm:flex-wrap justify-center gap-lg items-stretch sm:items-start w-full max-w-[362px] sm:max-w-none">
				<a href="<?php echo esc_url( $button_1_link ); ?>" class="btn-split inline-flex items-center w-full sm:w-auto">
					<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex flex-1 sm:flex-none items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
						<?php echo esc_html( $button_1_text ); ?>
					</span>
					<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
					</span>
				</a>
				<a href="<?php echo esc_url( $button_2_link ); ?>" class="btn-outline border border-border-medium rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap w-full sm:w-auto">
					<?php echo esc_html( $button_2_text ); ?>
				</a>
			</div>
		</div>
	</div>

	<div class="swiper hero-slider w-full" data-autoplay-delay="3500">
		<div class="swiper-wrapper">
			<?php foreach ( $slides as $index => $photo ) : ?>
				<div class="swiper-slide hero-slide"<?php echo $index >= $unique_count ? ' aria-hidden="true"' : ''; ?>>
					<div class="hero-slide__card">
						<img src="<?php echo esc_url( $photo['url'] ); ?>" alt="<?php echo esc_attr( $photo['alt'] ); ?>" class="w-full h-full object-cover">
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
