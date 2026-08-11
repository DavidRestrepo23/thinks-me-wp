<?php
/**
 * Registered Office Address — the plan: pricing card on the left, section
 * heading and copy on the right.
 * Figma: node 67:109, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Registered Office Address page): roa_plan_hat_text, roa_plan_heading,
 * roa_plan_text, roa_plan_card_title, roa_plan_card_text, roa_plan_price,
 * roa_plan_benefit_1..6, roa_plan_button_text / _link.
 *
 * Benefits are six flat fields rather than a Repeater — ACF free has no
 * Repeater (same reason offices-map.php uses office_1..4_*). Empty ones are
 * skipped, so the design's three render out of the box and the client can add
 * up to three more without the list ever showing a blank row.
 *
 * The column order is reversed below `lg`: Figma puts the price card first
 * reading left-to-right, but stacked on a phone the section heading has to come
 * before the thing it introduces.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$benefit_defaults = array(
	1 => 'A compliant Singapore registered office address',
	2 => 'Digital mailroom: scan and upload of all received mail',
	3 => 'Secure cloud storage with easy access anytime, anywhere',
	4 => '',
	5 => '',
	6 => '',
);

$benefits = array();
foreach ( $benefit_defaults as $n => $default ) {
	$benefit = thinksme_field( "roa_plan_benefit_{$n}", false, $default );

	if ( '' !== trim( $benefit ) ) {
		$benefits[] = $benefit;
	}
}

$price       = thinksme_field( 'roa_plan_price', false, 'S$238/year' );
$card_text   = thinksme_field( 'roa_plan_card_text', false, 'Give your company a professional Singapore registered address with 24/7 access to all incoming mail through your secure online dashboard.' );
$button_text = thinksme_field( 'roa_plan_button_text', false, 'Enquire Now' );
$button_link = thinksme_field( 'roa_plan_button_link', false, '#' );
?>
<section id="roa-plan" class="flex flex-col-reverse lg:flex-row lg:items-start gap-3xl lg:gap-[109px] w-full mt-xl md:mt-3xl px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col gap-xl w-full lg:w-[547px] lg:shrink-0 bg-surface-light rounded-lg p-xl">
		<h3 class="font-medium text-2xl leading-[1.04] text-text-primary">
			<?php echo esc_html( thinksme_field( 'roa_plan_card_title', false, 'Registered Address & Digital Mailroom' ) ); ?>
		</h3>

		<?php if ( $card_text ) : ?>
			<p class="font-normal text-sm leading-loose text-text-heading-dark">
				<?php echo esc_html( $card_text ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $price ) : ?>
			<p class="font-medium text-[48px] leading-tight text-text-primary">
				<?php echo esc_html( $price ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $benefits ) : ?>
			<hr class="border-0 border-t border-border-soft">

			<ul class="flex flex-col gap-md">
				<?php foreach ( $benefits as $benefit ) : ?>
					<li class="flex gap-md items-center">
						<span class="bg-accent-green rounded-[12.8px] size-[40px] inline-flex items-center justify-center shrink-0">
							<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[19.2px]">
						</span>
						<span class="font-normal text-sm text-text-secondary leading-relaxed">
							<?php echo esc_html( $benefit ); ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $button_text ) : ?>
			<hr class="border-0 border-t border-border-soft">

			<?php // The button spans the card in Figma: the label half grows, the arrow square stays 50x50. ?>
			<a href="<?php echo esc_url( $button_link ); ?>" class="btn-split flex items-center w-full">
				<span class="bg-brand-yellow rounded-sm h-[50px] px-lg grow inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
					<?php echo esc_html( $button_text ); ?>
				</span>
				<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
				</span>
			</a>
		<?php endif; ?>
	</div>

	<?php // Takes the width the price card leaves, so the section fills the viewport instead of stopping at Figma's 1280px frame. The paragraph keeps a reading measure anyway, same as hiring.php. ?>
	<div class="flex flex-col justify-center gap-xl lg:gap-[48px] w-full lg:flex-1 lg:min-w-0">
		<div class="flex flex-col items-start gap-md">
			<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( thinksme_field( 'roa_plan_hat_text', false, 'Quick Registration | Transparent Pricing | Reliable support' ) ); ?>
			</span>
			<h2 class="font-medium text-2xl lg:text-3xl leading-tight text-text-primary">
				<?php echo esc_html( thinksme_field( 'roa_plan_heading', false, 'A Real Registered Office Address. Zero Hassle.' ) ); ?>
			</h2>
		</div>

		<p class="font-normal text-sm leading-loose text-text-heading-dark max-w-[519px]">
			<?php echo esc_html( thinksme_field( 'roa_plan_text', false, 'Register your registered address with us and get a prestigious CBD address backed by full mail management. Compliance notices, regulatory letters, everything—scanned and waiting in your shared drive whenever you need to check. Full visibility, zero risk of missing anything important.' ) ); ?>
		</p>
	</div>
</section>
