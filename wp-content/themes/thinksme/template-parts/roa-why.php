<?php
/**
 * Registered Office Address — "Why Your Business Needs…": copy on the left, a
 * four-step rail on the right whose connectors fill as the section is scrolled.
 * Figma: node 68:564, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Registered Office Address page): roa_why_hat_text, roa_why_heading,
 * roa_why_text, and per step (1..4) roa_why_step_N_icon (select, same runtime
 * icon list as the service cards), _title, _text.
 *
 * The section pins itself while you scroll past it: assets/js/roa-progress.js
 * gives the section a tall scroll runway and writes --roa-progress (0..1) as
 * you move through it, and the CSS turns that number into the height of each
 * connector's yellow fill. Progress reaches 1 on the last step — "No P.O. Boxes
 * Allowed" in the design — and the sticky child releases there, so the page
 * carries on scrolling normally.
 *
 * Pinning is an enhancement, not the layout: --roa-progress defaults to 1, so
 * with the script gone (or on a phone, or under prefers-reduced-motion, or on a
 * viewport too short to hold the rail) the section is a plain block with every
 * connector already filled. The script only pins when it has checked the
 * content fits, which is why the runway is set from JS rather than in the CSS.
 *
 * Figma freezes the first connector part-filled to show the interaction; that's
 * a state, not a style, so nothing here reproduces it statically.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$defaults = array(
	1 => array(
		'icon'  => 'envelope-simple',
		'title' => 'Receive All Official Mail',
		'text'  => 'A central place for all government notices, tax letters, and legal correspondence.',
	),
	2 => array(
		'icon'  => 'door-open',
		'title' => 'Accessible to the Public',
		'text'  => 'Open for at least 8 hours on business days, ensuring compliance with ACRA requirements.',
	),
	3 => array(
		'icon'  => 'map-pin',
		'title' => 'Singapore-Based Physical Address',
		'text'  => 'Shows your business has a real, legitimate presence in Singapore.',
	),
	4 => array(
		'icon'  => 'shield-check',
		'title' => 'No P.O. Boxes Allowed',
		'text'  => 'Ensures transparency and credibility with clients and authorities.',
	),
);

$steps = array();
foreach ( $defaults as $n => $default ) {
	$title = thinksme_field( "roa_why_step_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$steps[] = array(
		'icon'  => thinksme_roa_icon_url( thinksme_field( "roa_why_step_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		'text'  => thinksme_field( "roa_why_step_{$n}_text", false, $default['text'] ),
	);
}

if ( ! $steps ) {
	return;
}

// One connector between each pair of steps — also the divisor the CSS uses to
// slice --roa-progress into per-connector fills, so it has to be the real count.
$lines = max( 1, count( $steps ) - 1 );
?>
<section
	id="roa-why"
	class="roa-why w-full px-lg lg:px-3xl py-xl lg:py-[40px]"
	style="--roa-lines: <?php echo esc_attr( $lines ); ?>;"
>
	<div class="roa-why__pin flex flex-col lg:flex-row lg:items-start gap-3xl lg:gap-[144px] w-full">
		<div class="flex flex-col justify-center gap-xl lg:gap-[48px] w-full lg:w-[548px] lg:shrink-0">
			<div class="flex flex-col items-start justify-center gap-md w-full">
				<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( thinksme_field( 'roa_why_hat_text', false, 'Complete Corporate Services' ) ); ?>
				</span>
				<h2 class="font-medium text-2xl lg:text-3xl leading-[1.04] text-text-primary">
					<?php echo nl2br( esc_html( thinksme_field( 'roa_why_heading', false, "Why Your Business Needs\na Local Registered Office Address in Singapore" ) ) ); ?>
				</h2>
			</div>

			<p class="font-normal text-sm leading-loose text-text-heading-dark">
				<?php echo esc_html( thinksme_field( 'roa_why_text', false, 'When incorporating a company in Singapore, a local registered office address is a legal must — and it also helps your business appear professional and credible.' ) ); ?>
			</p>
		</div>

		<ol class="roa-why__list w-full lg:flex-1 lg:min-w-0">
			<?php foreach ( $steps as $index => $step ) : ?>
				<li class="roa-why__step">
					<span class="roa-why__rail" aria-hidden="true">
						<span class="roa-why__icon">
							<img src="<?php echo esc_url( $step['icon'] ); ?>" alt="" class="size-[27.4px]">
						</span>

						<?php if ( $index < count( $steps ) - 1 ) : ?>
							<?php // --roa-step is this connector's slice of the overall progress; see .roa-why__line-fill in src/base.css. ?>
							<span class="roa-why__line" style="--roa-step: <?php echo esc_attr( $index ); ?>;">
								<span class="roa-why__line-fill"></span>
							</span>
						<?php endif; ?>
					</span>

					<div class="roa-why__body">
						<h3 class="font-medium text-[28px] leading-[1.2] text-text-heading-dark">
							<?php echo esc_html( $step['title'] ); ?>
						</h3>

						<?php if ( $step['text'] ) : ?>
							<p class="font-normal text-sm leading-loose text-text-secondary">
								<?php echo esc_html( $step['text'] ); ?>
							</p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
