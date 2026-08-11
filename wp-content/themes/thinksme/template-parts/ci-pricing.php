<?php
/**
 * Company Incorporation Local — pricing: a navy panel with a centred heading
 * over three package cards.
 * Figma: node 85:1351, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Company Incorporation Local page): ci_pricing_hat_text,
 * ci_pricing_heading, ci_pricing_text, and per card (1..3)
 * ci_pricing_card_N_icon (select, fed at runtime from thinksme_ci_icons()),
 * _title, _text, _price_label, _price, _badge_1, _badge_2, _features_intro,
 * _feature_1..5, _button_text, _button_link.
 *
 * The middle card is the highlighted one — blue fill, white text, a "MOST
 * POPULAR" tab above it and the solid yellow split button, where the outer two
 * get a white card and an outlined button. That styling belongs to the *slot*,
 * not to the content, the same way cards-deck.php and testimonials.php colour
 * their cards by position. A `featured` checkbox per card would let the client
 * tick all three and flatten the section; position can't be got wrong.
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
 * The cityscape behind the heading is the Figma artwork exported as one SVG
 * rather than the ~150 separate vectors it is on the canvas — same treatment as
 * assets/images/roa/skyline.svg, down to stripping by hand the opaque #1E1E1E
 * canvas rect that Figma includes in the export. It carries its own navy rounded
 * panel, so it sits over the section background seamlessly.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$images_uri = get_template_directory_uri() . '/assets/images/ci';

$defaults = array(
	1 => array(
		'icon'           => 'shield-check',
		'title'          => 'Essential',
		'text'           => 'Simple setup for solo local founders with straightforward structures.',
		'price_label'    => 'From',
		'price'          => 'S$888',
		'badge_1'        => 'One-time · all-in',
		'badge_2'        => 'S$315 ACRA fee included',
		'features_intro' => '',
		'features'       => array(
			'ACRA name reservation & registration',
			'Standard company constitution',
			'Corporate secretary — 12 months',
			'Registered address — 12 months',
			'Bank account opening assistance',
		),
	),
	2 => array(
		'icon'           => 'shield-check',
		'title'          => 'Standard',
		'text'           => 'Full first-year coverage. The most common choice for new SME owners.',
		'price_label'    => 'From',
		'price'          => 'S$1,288',
		'badge_1'        => 'First year · all-in',
		'badge_2'        => 'S$315 ACRA fee included',
		'features_intro' => 'Everything in Essential, plus:',
		'features'       => array(
			'Annual return filing (Year 1)',
			'Priority email & chat support',
			'Compliance deadline reminders',
		),
	),
	3 => array(
		'icon'           => 'shield-check',
		'title'          => 'Premium',
		'text'           => 'Complex structures, multiple shareholders, dedicated service.',
		'price_label'    => 'From',
		'price'          => 'S$1,888',
		'badge_1'        => 'First year · all-in',
		'badge_2'        => 'S$315 ACRA fee included',
		'features_intro' => 'Everything in Standard, plus',
		'features'       => array(
			'Up to 5 shareholders onboarded',
			'Share register & allotment setup',
			'Dedicated account manager',
		),
	),
);

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

$popular_label = thinksme_field( 'ci_pricing_popular_label', false, 'MOST POPULAR' );
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
			<?php $hat = thinksme_field( 'ci_pricing_hat_text', false, 'Transparent Pricing' ); ?>
			<?php if ( $hat ) : ?>
				<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $hat ); ?>
				</span>
			<?php endif; ?>

			<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-on-dark">
				<?php echo esc_html( thinksme_field( 'ci_pricing_heading', false, 'Incorporation Packages for Local Founders' ) ); ?>
			</h2>

			<?php $intro = thinksme_field( 'ci_pricing_text', false, 'Every package includes the S$315 ACRA government fee — no hidden charges.' ); ?>
			<?php if ( $intro ) : ?>
				<p class="font-normal text-sm leading-relaxed text-text-on-dark">
					<?php echo esc_html( $intro ); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php // The columns stretch so all three cards are the height of the tallest and the buttons line up, rather than each card sizing to its own feature count. ?>
		<div class="ci-plans relative flex flex-col lg:flex-row lg:items-stretch gap-xl lg:gap-md mt-xl lg:mt-3xl">
			<?php
			foreach ( $cards as $n => $card ) :
				$is_featured = 2 === $n;
				?>
				<div class="ci-plan flex flex-col gap-md lg:flex-1 lg:min-w-0" data-featured="<?php echo $is_featured ? 'true' : 'false'; ?>">
					<?php if ( $is_featured && $popular_label ) : ?>
						<span class="ci-plan__tab"><?php echo esc_html( $popular_label ); ?></span>
					<?php endif; ?>

					<div class="ci-plan__card">
						<div class="flex flex-col gap-lg">
							<span class="bg-brand-yellow rounded-pill size-[56px] inline-flex items-center justify-center shrink-0">
								<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[24px]">
							</span>

							<div class="flex flex-col gap-xs">
								<h3 class="ci-plan__title font-medium text-[32px] leading-[1.2]">
									<?php echo esc_html( $card['title'] ); ?>
								</h3>

								<?php if ( $card['text'] ) : ?>
									<p class="ci-plan__text font-normal text-sm leading-loose opacity-70">
										<?php echo esc_html( $card['text'] ); ?>
									</p>
								<?php endif; ?>
							</div>
						</div>

						<div class="flex flex-col gap-md">
							<div class="flex flex-col gap-xs">
								<?php if ( $card['price_label'] ) : ?>
									<p class="ci-plan__title font-medium text-xs leading-loose uppercase">
										<?php echo esc_html( $card['price_label'] ); ?>
									</p>
								<?php endif; ?>

								<?php if ( $card['price'] ) : ?>
									<p class="ci-plan__title font-medium text-[48px] leading-[1.2]">
										<?php echo esc_html( $card['price'] ); ?>
									</p>
								<?php endif; ?>
							</div>

							<?php if ( $card['badge_1'] || $card['badge_2'] ) : ?>
								<div class="flex flex-wrap gap-xs items-center">
									<?php if ( $card['badge_1'] ) : ?>
										<span class="ci-plan__badge ci-plan__badge--outline"><?php echo esc_html( $card['badge_1'] ); ?></span>
									<?php endif; ?>
									<?php if ( $card['badge_2'] ) : ?>
										<span class="ci-plan__badge"><?php echo esc_html( $card['badge_2'] ); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( $card['features_intro'] ) : ?>
							<p class="ci-plan__title font-bold text-md leading-[1.2]">
								<?php echo esc_html( $card['features_intro'] ); ?>
							</p>
						<?php endif; ?>

						<?php // The rules between features are a border on each li after the first, not separator elements: an empty <li> would pad the list's announced item count for no visual gain. ?>
						<?php if ( $card['features'] ) : ?>
							<ul class="ci-plan__features">
								<?php foreach ( $card['features'] as $feature ) : ?>
									<li class="flex gap-md items-center">
										<span class="bg-accent-green rounded-[7.68px] size-[24px] inline-flex items-center justify-center shrink-0">
											<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[11.52px]">
										</span>
										<span class="ci-plan__title font-medium text-md leading-[1.2]">
											<?php echo esc_html( $feature ); ?>
										</span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>

					<?php if ( $card['button_text'] ) : ?>
						<?php if ( $is_featured ) : ?>
							<a href="<?php echo esc_url( $card['button_link'] ); ?>" class="btn-split flex items-center w-full">
								<span class="bg-brand-yellow rounded-sm h-[50px] px-lg grow inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
									<?php echo esc_html( $card['button_text'] ); ?>
								</span>
								<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
									<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
								</span>
							</a>
						<?php else : ?>
							<a href="<?php echo esc_url( $card['button_link'] ); ?>" class="ci-plan__button">
								<?php echo esc_html( $card['button_text'] ); ?>
							</a>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
