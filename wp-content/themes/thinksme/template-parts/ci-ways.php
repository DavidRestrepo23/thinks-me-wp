<?php
/**
 * Section copy and two route cards on the left, a summary card on the right.
 * Figma: node 85:1757 (Company Incorporation, Local and Foreign), 119:1932
 * (Business Loan) and 124:3537 (Mortgage Loans), file "Untitled"
 * (vzdpOnH1U36oXcFcugiyE5).
 *
 * Shared verbatim by page-company-incorporation-local.php and
 * page-company-incorporation-foreign.php: same markup, same ACF field names,
 * different words and photographs. Every default below comes from
 * thinksme_ci_defaults() in inc/ci-content.php, which is the one place the two
 * pages differ — change the design's copy there, not here.
 *
 * ACF (both Company Incorporation pages): ci_ways_hat_text, ci_ways_heading,
 * ci_ways_text; per route card (1..2) ci_ways_card_N_icon (select, fed at
 * runtime from thinksme_ci_icons()), _title, _text, _link; and for the package
 * card ci_ways_plan_badge, _title, _text, _price, _badge_1, _badge_2,
 * _feature_1..3, _button_text, _button_link.
 *
 * The two route cards are whole-card links — Figma puts an arrow glyph at the
 * far right of each, which is the affordance for the card, not a separate
 * control. Making the <a> the card rather than wrapping the arrow keeps the
 * click target the full card and leaves one link per card for a screen reader
 * instead of two.
 *
 * Features are flat fields, empties skipped, for the same reason ci-pricing.php and
 * roa-plan.php use flat fields: ACF free has no Repeater. How many there are is the
 * set's own business — three on the incorporation frames, four on the Mortgage one,
 * which also closes the card with `ci_ways_plan_note`.
 *
 * The package card repeats content that also lives in ci-pricing.php. They are
 * deliberately separate fields rather than one shared source: Figma prices the
 * Standard package at S$1,288 in the pricing table and S$888 here, so wiring
 * them together would silently "fix" a difference the design is making on
 * purpose. If the client wants them locked together, that is a content
 * decision, not a template one.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$d             = thinksme_ci_defaults( 'ways' );
$card_defaults = $d['cards'];

$cards = array();

foreach ( $card_defaults as $n => $default ) {
	$title = thinksme_field( "ci_ways_card_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$cards[] = array(
		'icon'  => thinksme_ci_icon_url( thinksme_field( "ci_ways_card_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		'text'  => thinksme_field( "ci_ways_card_{$n}_text", false, $default['text'] ),
		'link'  => thinksme_field( "ci_ways_card_{$n}_link", false, '/contact-us' ),
	);
}

// Four checks on the Mortgage frame against the incorporation frames' three, so the
// loop runs over whatever the set names rather than a fixed range.
$feature_defaults = $d['plan']['features'];

$features = array();

foreach ( $feature_defaults as $n => $default ) {
	$feature = thinksme_field( "ci_ways_plan_feature_{$n}", false, $default );

	if ( '' !== trim( $feature ) ) {
		$features[] = $feature;
	}
}

$plan_title       = thinksme_field( 'ci_ways_plan_title', false, $d['plan']['title'] );
$plan_badge       = thinksme_field( 'ci_ways_plan_badge', false, $d['plan']['badge'] );
$plan_text        = thinksme_field( 'ci_ways_plan_text', false, $d['plan']['text'] );
$plan_price       = thinksme_field( 'ci_ways_plan_price', false, $d['plan']['price'] );
$plan_badge_1     = thinksme_field( 'ci_ways_plan_badge_1', false, $d['plan']['badge_1'] );
$plan_badge_2     = thinksme_field( 'ci_ways_plan_badge_2', false, $d['plan']['badge_2'] );
$plan_button_text = thinksme_field( 'ci_ways_plan_button_text', false, $d['plan']['button_text'] );
$plan_button_link = thinksme_field( 'ci_ways_plan_button_link', false, $d['plan']['button_link'] );
?>
<section id="ci-ways" class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-xl lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col gap-xl lg:gap-3xl w-full lg:basis-[602px] lg:min-w-0">
		<div class="flex flex-col gap-xl">
			<div class="flex flex-col items-start gap-md">
				<?php $hat = thinksme_field( 'ci_ways_hat_text', false, $d['hat'] ); ?>
				<?php if ( $hat ) : ?>
					<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill min-h-[32px] px-md py-[6px] inline-flex items-center justify-center text-xs font-medium text-text-primary">
						<?php echo esc_html( $hat ); ?>
					</span>
				<?php endif; ?>

				<?php // Capped at the width Figma sets the heading in, which is what decides where it breaks: 512px on the incorporation and Business Loan frames, 602px on the Mortgage one. ?>
				<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-text-primary <?php echo esc_attr( isset( $d['heading_class'] ) ? $d['heading_class'] : 'max-w-[512px]' ); ?>">
					<?php echo esc_html( thinksme_field( 'ci_ways_heading', false, $d['heading'] ) ); ?>
				</h2>
			</div>

			<?php $intro = thinksme_field( 'ci_ways_text', false, $d['text'] ); ?>
			<?php if ( $intro ) : ?>
				<p class="font-normal text-sm leading-loose text-text-heading-dark">
					<?php echo esc_html( $intro ); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $cards ) : ?>
			<div class="flex flex-col gap-md w-full lg:max-w-[530px]">
				<?php foreach ( $cards as $card ) : ?>
					<?php // The arrow is dropped below sm: the whole card is the link, so it is pure affordance, and on a narrow card it squeezed the copy into a third and fourth line to hold a glyph nobody needs there. ?>
					<a href="<?php echo esc_url( $card['link'] ); ?>" class="ci-way flex items-start lg:items-center justify-between gap-md bg-surface-light rounded-lg p-lg lg:p-xl">
						<span class="flex gap-md lg:gap-lg items-start lg:items-center min-w-0">
							<span class="bg-brand-yellow rounded-pill size-[56px] lg:size-[64px] inline-flex items-center justify-center shrink-0">
								<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[28px] lg:size-[32px]">
							</span>

							<span class="flex flex-col gap-xs min-w-0">
								<span class="font-medium text-lg lg:text-xl leading-tight tracking-hero text-text-primary">
									<?php echo esc_html( $card['title'] ); ?>
								</span>

								<?php if ( $card['text'] ) : ?>
									<span class="font-normal text-sm leading-relaxed text-text-secondary">
										<?php echo esc_html( $card['text'] ); ?>
									</span>
								<?php endif; ?>
							</span>
						</span>

						<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="hidden sm:block size-[24px] shrink-0">
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $plan_title ) : ?>
		<div class="flex flex-col items-start gap-xl w-full lg:basis-[547px] lg:min-w-0 bg-surface-light rounded-lg p-lg lg:p-xl">
			<?php if ( $plan_badge ) : ?>
				<span class="bg-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $plan_badge ); ?>
				</span>
			<?php endif; ?>

			<h3 class="font-medium text-2xl leading-[1.04] text-text-primary">
				<?php echo esc_html( $plan_title ); ?>
			</h3>

			<?php if ( $plan_text ) : ?>
				<p class="font-normal text-sm leading-loose text-text-heading-dark">
					<?php echo esc_html( $plan_text ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $plan_price || $plan_badge_1 || $plan_badge_2 ) : ?>
				<div class="flex flex-col items-start gap-md">
					<?php if ( $plan_price ) : ?>
						<p class="font-medium text-[48px] leading-tight text-text-primary">
							<?php echo esc_html( $plan_price ); ?>
						</p>
					<?php endif; ?>

					<?php if ( $plan_badge_1 || $plan_badge_2 ) : ?>
						<div class="flex flex-col items-start gap-sm">
							<?php if ( $plan_badge_1 ) : ?>
								<span class="ci-plan__badge ci-plan__badge--outline"><?php echo esc_html( $plan_badge_1 ); ?></span>
							<?php endif; ?>
							<?php if ( $plan_badge_2 ) : ?>
								<span class="ci-plan__badge ci-plan__badge--outline"><?php echo esc_html( $plan_badge_2 ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $features ) : ?>
				<hr class="border-0 border-t border-border-soft w-full">

				<ul class="flex flex-col gap-md w-full">
					<?php foreach ( $features as $feature ) : ?>
						<li class="flex gap-md items-center">
							<span class="bg-accent-green rounded-[12.8px] size-[40px] inline-flex items-center justify-center shrink-0">
								<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[19.2px]">
							</span>
							<span class="font-normal text-sm leading-relaxed text-text-secondary">
								<?php echo esc_html( $feature ); ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php
			// The Mortgage frame closes the card with a line of small print under the checks
			// (124:3602) — which packages the rebate applies to. Optional, so the
			// incorporation frames end at their features.
			$plan_note = thinksme_field( 'ci_ways_plan_note', false, isset( $d['plan']['note'] ) ? $d['plan']['note'] : '' );
			?>
			<?php if ( $plan_note ) : ?>
				<hr class="border-0 border-t border-border-soft w-full">

				<p class="font-normal text-sm leading-loose text-text-secondary">
					<?php echo esc_html( $plan_note ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $plan_button_text ) : ?>
				<?php // The rule above the button is skipped when the small print already drew one. ?>
				<?php if ( ! $plan_note ) : ?>
					<hr class="border-0 border-t border-border-soft w-full">
				<?php endif; ?>

				<a href="<?php echo esc_url( $plan_button_link ); ?>" class="btn-split flex items-center w-full">
					<span class="bg-brand-yellow rounded-sm h-[50px] px-lg grow inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
						<?php echo esc_html( $plan_button_text ); ?>
					</span>
					<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
					</span>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
