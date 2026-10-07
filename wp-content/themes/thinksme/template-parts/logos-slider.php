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
<?php
		// Amendment #19/#20/#21 — BOTH homepage strips run in `auto` width mode (each
		// slide sized to its own logo, fixed 96/40px gap) so the VISIBLE gap between
		// adjacent logos is equal for all of them, and every logo sits in an equal-height
		// box so none is crushed shorter than the others. Was equal-column, which gave
		// narrow logos big side-gaps and shrank wide wordmarks (e.g. ANEXT) to a smaller
		// height. logos-slider.js fills the loop with clones so the crawl stays seamless,
		// including at the loop seam.
		//   - $group 'certifications' (coloured) → .logos-swiper--lg, box 72 / img 60px.
		//   - other groups (gray client/bank logos) → .logos-swiper--sm, box 48 / img 40px
		//     (their source art is 40px tall, so it is kept crisp at native height).
		$is_coloured = ( 'certifications' === $group );
		$size_class  = $is_coloured ? 'logos-swiper--lg' : 'logos-swiper--sm';
		?>
		<div
			class="swiper logos-swiper <?php echo esc_attr( $size_class ); ?> w-full"
			data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
			data-autoplay-delay="<?php echo esc_attr( $autoplay_delay ); ?>"
			data-pause-on-hover="<?php echo $pause_on_hover ? 'true' : 'false'; ?>"
			data-slides-desktop="auto"
			data-space-desktop="96"
			data-space-mobile="40"
		>
			<div class="swiper-wrapper items-center">
				<?php
				while ( $client_logos->have_posts() ) :
					$client_logos->the_post();
					?>
					<?php if ( has_post_thumbnail() ) : ?>
						<?php
						// Amendment #21/#22 — GRAY strip only: size each logo for equal VISUAL
						// WEIGHT (equal area), not equal height. The gray logos have wildly
						// different aspect ratios (ANEXT BANK ~10:1 down to ORIX ~0.8:1); at one
						// fixed height a wordmark dwarfs a compact mark, and at one fixed width the
						// wordmark turns tiny. So derive each logo's height from its own aspect so
						// the areas match: h = sqrt(TARGET_AREA / aspect), clamped. Full-size file
						// is served so shrinking never blurs. The coloured strip is unchanged
						// (its art is uniform and uses the max-height nudges).
						$logo_style = '';
						// The Property Cashout lender logos joined the gray bank strip (client
						// QA: one set of bank logos everywhere, greyed out like the homepage's).
						// Their art is full colour, so the strip desaturates them in CSS and
						// these ones are also faded to the gray art's weight.
						$tint_class = ( ! $is_coloured && has_term( 'lenders', 'logo_group' ) ) ? ' logos-swiper__logo--tint' : '';
						$logo_size  = $is_coloured ? 'medium' : 'full';
						if ( ! $is_coloured ) {
							$full   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							$aspect = ( $full && ! empty( $full[2] ) ) ? $full[1] / $full[2] : 1;
							$h      = (int) round( sqrt( 5200 / max( 0.1, $aspect ) ) );
							$h      = max( 24, min( 44, $h ) ); // px, clamp the extremes
							$logo_style = 'height:' . $h . 'px;width:auto;';
						}
						?>
						<div class="swiper-slide flex items-center justify-center h-[40px]">
							<?php
							the_post_thumbnail(
								$logo_size,
								array( 'class' => 'max-h-full max-w-full w-auto h-auto object-contain' . $tint_class, 'alt' => get_the_title(), 'style' => $logo_style )
							);
							?>
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
