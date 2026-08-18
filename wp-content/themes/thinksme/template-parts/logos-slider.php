<?php
/**
 * Homepage logos carousel, sourced from the client_logo CPT. Called twice
 * from front-page.php (once for certifications, once for clients) with a
 * different $args['group'] each time — logo_group taxonomy term slug — so
 * the two carousels show independent, non-overlapping logo sets instead of
 * both looping every logo. Scrolls via Swiper as a continuous marquee
 * (loop + autoplay with near-zero delay, see assets/js/logos-slider.js).
 * Loop/autoplay tuning is admin-configurable from
 * Appearance > Customize > Client Logos Slider (see functions.php,
 * thinksme_customize_register()), so the client can adjust speed without
 * touching code.
 *
 * The script is not this part's alone: the Business Loan hero's partner-bank strip
 * (template-parts/ci-hero.php, Figma 119:1737) is the same marquee at a different
 * size, so it carries the same `logos-swiper` class and drives its differences
 * through the data-* attributes below — `data-slides-desktop="auto"` for slides
 * sized to their own logo and `data-space-desktop` for that frame's tighter gap.
 */

$autoplay        = (bool) get_theme_mod( 'thinksme_logos_autoplay', true );
$autoplay_delay  = absint( get_theme_mod( 'thinksme_logos_autoplay_speed', 3000 ) );
$pause_on_hover  = (bool) get_theme_mod( 'thinksme_logos_pause_on_hover', true );
$slides_desktop  = absint( get_theme_mod( 'thinksme_logos_slides_desktop', 5 ) );

$group = isset( $args['group'] ) ? sanitize_key( $args['group'] ) : '';

$query_args = array(
	'post_type'      => 'client_logo',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
);

if ( $group ) {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => 'logo_group',
			'field'    => 'slug',
			'terms'    => $group,
		),
	);
}

$client_logos = new WP_Query( $query_args );

// wp_unique_id(): this template part is reused twice on the homepage, so a
// hardcoded id would collide.
$section_id = wp_unique_id( 'logos-clients-' );
?>
<?php
// The Mortgage Loans frame writes a line of small print under the marquee (124:3614)
// — which packages its rebate applies to. It comes from that page's set rather than
// from an argument, the same way faq.php reads its own question list, so the
// homepage's two calls stay exactly what they were.
$logos    = function_exists( 'thinksme_ci_defaults' ) ? thinksme_ci_defaults( 'logos' ) : array();
$caption  = thinksme_field( 'logos_caption', false, isset( $logos['caption'] ) ? $logos['caption'] : '' );
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="w-full py-2xl">
	<?php if ( $client_logos->have_posts() ) : ?>
		<div
			class="swiper logos-swiper w-full"
			data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
			data-autoplay-delay="<?php echo esc_attr( $autoplay_delay ); ?>"
			data-pause-on-hover="<?php echo $pause_on_hover ? 'true' : 'false'; ?>"
			data-slides-desktop="<?php echo esc_attr( max( 1, $slides_desktop ) ); ?>"
		>
			<div class="swiper-wrapper items-center">
				<?php
				while ( $client_logos->have_posts() ) :
					$client_logos->the_post();
					?>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="swiper-slide flex items-center justify-center h-[40px]">
							<?php the_post_thumbnail( 'medium', array( 'class' => 'max-h-full max-w-full w-auto h-auto object-contain', 'alt' => get_the_title() ) ); ?>
						</div>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( $caption ) : ?>
		<p class="mt-lg mx-auto max-w-[525px] px-lg text-center font-normal text-sm leading-loose text-text-secondary">
			<?php echo esc_html( $caption ); ?>
		</p>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</section>
