<?php
/**
 * "See Why Clients Recommend Think SME" — Google Reviews slider.
 * Figma: node 24:1395, file "Untitled" (vzdpOnH1U36oXcFcugiyE5); mobile from
 * the client-supplied screenshot (same layout, smaller card and arrows).
 *
 * Variable-length list → `testimonial` CPT (client adds/edits/deletes from the
 * admin sidebar). Post title = reviewer name, ACF: testimonial_text,
 * testimonial_rating, testimonial_service, testimonial_when,
 * testimonial_source. With no posts yet the five reviews from the Figma frame
 * render as a fallback, so the section looks like the design out of the box.
 *
 * Card palette and rotation are *positional*, not per-review: the centered
 * slide is the navy one with no tilt, its neighbours are white, and the outer
 * two are pale blue / cream — exactly as in Figma. assets/js/testimonials-slider.js
 * stamps data-pos (0 = centered, negative = left) on each slide and src/base.css
 * keys the palette off it, so the highlight travels with the carousel instead
 * of being baked into a given review.
 *
 * Avatar colour is derived from the reviewer name (crc32 over the Google
 * palette) rather than being another field for the client to fill in.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$reviews = array();

$testimonials = new WP_Query(
	array(
		'post_type'      => 'testimonial',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

if ( $testimonials->have_posts() ) {
	while ( $testimonials->have_posts() ) :
		$testimonials->the_post();
		$reviews[] = array(
			'name'    => get_the_title(),
			'text'    => thinksme_field( 'testimonial_text', get_the_ID() ),
			'rating'  => (int) thinksme_field( 'testimonial_rating', get_the_ID(), 5 ),
			'service' => thinksme_field( 'testimonial_service', get_the_ID() ),
			'when'    => thinksme_field( 'testimonial_when', get_the_ID() ),
			'source'  => thinksme_field( 'testimonial_source', get_the_ID(), 'via Google' ),
		);
	endwhile;
	wp_reset_postdata();
} else {
	// Figma's five reviews, ordered so the navy (centered) card is the middle one.
	$reviews = array(
		array(
			'name'    => 'Sandy Elan',
			'text'    => 'It has been a seamless, helpful and quick service from Think SME who helped opening multiple companies. Nick and Yee Lin are super helpful, courteous, customer friendly and quiet patient to answer all my questions & clarify all doubts. I highly recommend Think SME and i have already referred my other business contacts to work with them for their company incorporation as well as managing their corp secretary and annual filing work. Great Job Nick and Yee. Thanks for all your help',
			'rating'  => 5,
			'service' => 'Registered Address & Accounting',
			'when'    => '2 months ago',
			'source'  => 'via Google',
		),
		array(
			'name'    => 'Choon Seng Seow',
			'text'    => 'Think SME is a very professional company. Nick is very detailed and patience. He spend time to explain all the legal procedures a Company needs to adhere. During the whole meetup, he never rush through any grounds. Highly recommended if you are a new startup. Even not a new startup, you should consider Think SME.',
			'rating'  => 5,
			'service' => 'Business Loan & Property Cashout',
			'when'    => '7 months ago',
			'source'  => 'via Google',
		),
		array(
			'name'    => 'Joel Ang',
			'text'    => 'We\'ve been working with Think SME for our company\'s accounting and tax matters, and the experience has been excellent from day one. A special mention to Nick, who has been incredibly responsive, knowledgeable, and patient throughout the process. Whether it\'s tax filing, compliance matters, or answering our day-to-day accounting questions, he always provides clear and practical advice that gives us confidence that everything is being handled properly.',
			'rating'  => 5,
			'service' => 'Company Incorporation',
			'when'    => '3 months ago',
			'source'  => 'via Google',
		),
		array(
			'name'    => 'Ho Doris',
			'text'    => 'We were looking at refinancing of our loan and gotten the consulting services of Mr Thomas Wee from ThinkSME. Thomas is very experienced and knowledgeable and he helps us to get the best deal possible and guides us through the entire process smoothly. Thanks Thomas for the good job',
			'rating'  => 5,
			'service' => 'Business Loan & Property Cashout',
			'when'    => '7 months ago',
			'source'  => 'via Google',
		),
		array(
			'name'    => 'Cynthia Tan',
			'text'    => 'Evelyn is very helpful and help me with my company matters promptly. Highly recommend Think SME to all SMEs looking for accounting and financial advisory.',
			'rating'  => 5,
			'service' => 'GST Filing & PSG Xero',
			'when'    => '5 months ago',
			'source'  => 'via Google',
		),
	);
}

$reviews = array_values( array_filter( $reviews, function ( $review ) {
	return ! empty( $review['text'] );
} ) );

if ( ! $reviews ) {
	return;
}

// Swiper's loop reorders slides instead of cloning them, so it needs more
// slides than fit on screen at once (~3.3 at 1440px) or one edge of the
// carousel ends up empty. Repeat the set until there's enough slack; the
// repeats are aria-hidden so a screen reader hears each review once.
$slides       = array();
$unique_count = count( $reviews );
while ( count( $slides ) < 8 ) {
	foreach ( $reviews as $review ) {
		$slides[] = $review;
	}
}

// Google brand palette, same four colours the Figma avatars use.
$avatar_colors = array( '#4285f4', '#34a853', '#fbbc05', '#ea4335' );
?>
<section
	id="testimonials"
	class="testimonials flex flex-col items-center gap-xl lg:gap-[48px] w-full mt-xl md:mt-3xl pb-3xl lg:pb-[160px] overflow-hidden"
	style="
		--testimonials-quote-navy: url('<?php echo esc_url( "$icons_uri/quote-mark-navy.svg" ); ?>');
		--testimonials-quote-white: url('<?php echo esc_url( "$icons_uri/quote-mark-white.svg" ); ?>');
		--testimonials-star-gold: url('<?php echo esc_url( "$icons_uri/star-review-gold.svg" ); ?>');
		--testimonials-star-pale: url('<?php echo esc_url( "$icons_uri/star-review-pale.svg" ); ?>');
	"
>
	<div class="flex flex-col items-center gap-lg text-center px-lg">
		<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-lg inline-flex items-center justify-center text-xs font-medium text-text-primary">
			<?php echo esc_html( thinksme_field( 'testimonials_hat_text', false, 'Google Reviews' ) ); ?>
		</span>
		<h2 class="font-medium text-2xl lg:text-3xl leading-tight text-text-primary max-w-[749px]" style="letter-spacing: -0.01em;">
			<?php echo esc_html( thinksme_field( 'testimonials_heading', false, 'See Why Clients Recommend Think SME' ) ); ?>
		</h2>
	</div>

	<div class="swiper testimonials-slider w-full" data-autoplay-delay="5000">
		<div class="swiper-wrapper items-center">
			<?php foreach ( $slides as $index => $review ) : ?>
				<?php
				$is_repeat = $index >= $unique_count;
				$name      = $review['name'];
				$rating = max( 1, min( 5, (int) $review['rating'] ) );
				$avatar = $avatar_colors[ crc32( $name ) % count( $avatar_colors ) ];
				$meta   = trim( $review['when'] . ( $review['when'] && $review['source'] ? ' · ' : '' ) . $review['source'] );
				?>
				<div class="swiper-slide testimonials-slide"<?php echo $is_repeat ? ' aria-hidden="true"' : ''; ?>>
					<article class="testimonials-card flex flex-col justify-center gap-xl lg:gap-[40px] p-lg lg:p-xl w-[300px] lg:w-[405px]">
						<span class="testimonials-quote" aria-hidden="true"></span>

						<div class="flex flex-col items-start gap-lg">
							<div class="flex gap-[2px]" role="img" aria-label="<?php
								/* translators: %d: star rating out of five. */
								echo esc_attr( sprintf( __( '%d out of 5 stars', 'thinksme' ), $rating ) );
							?>">
								<?php for ( $i = 0; $i < $rating; $i++ ) : ?>
									<span class="testimonials-star"></span>
								<?php endfor; ?>
							</div>

							<p class="testimonials-text font-normal text-sm">
								<?php echo esc_html( $review['text'] ); ?>
							</p>

							<?php if ( $review['service'] ) : ?>
								<span class="testimonials-tag rounded-pill px-md py-[7px] text-[12px] font-bold">
									<?php echo esc_html( $review['service'] ); ?>
								</span>
							<?php endif; ?>
						</div>

						<div class="flex items-center gap-sm">
							<span class="rounded-pill shrink-0 size-[40px] inline-flex items-center justify-center text-xs font-medium text-text-on-dark" style="background-color: <?php echo esc_attr( $avatar ); ?>;">
								<?php echo esc_html( mb_strtoupper( mb_substr( $name, 0, 1 ) ) ); ?>
							</span>
							<div class="flex flex-col gap-[4px] min-w-0">
								<span class="testimonials-name text-xs font-bold leading-[17.5px]"><?php echo esc_html( $name ); ?></span>
								<?php if ( $meta ) : ?>
									<span class="testimonials-meta text-[12px] leading-[15px]"><?php echo esc_html( $meta ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					</article>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="flex items-center justify-center gap-md">
		<button type="button" class="testimonials-nav testimonials-nav--prev bg-brand-yellow rounded-pill inline-flex items-center justify-center size-[48px] lg:size-[112px]" aria-label="<?php esc_attr_e( 'Previous review', 'thinksme' ); ?>">
			<img src="<?php echo esc_url( "$icons_uri/arrow-right.svg" ); ?>" alt="" class="size-[24px] lg:size-[64px] rotate-180">
		</button>
		<button type="button" class="testimonials-nav testimonials-nav--next bg-brand-yellow rounded-pill inline-flex items-center justify-center size-[48px] lg:size-[112px]" aria-label="<?php esc_attr_e( 'Next review', 'thinksme' ); ?>">
			<img src="<?php echo esc_url( "$icons_uri/arrow-right.svg" ); ?>" alt="" class="size-[24px] lg:size-[64px]">
		</button>
	</div>
</section>
