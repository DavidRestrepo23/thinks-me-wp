<?php
/**
 * Corporate Tax — "What Happens If You File Late": two figures either side of a
 * VS badge, then the three stages of escalation.
 * Figma: node 108:4630, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Corporate Tax page): ci_penalties_hat_text, ci_penalties_heading,
 * ci_penalties_text, ci_penalties_badge, per side (1..2)
 * ci_penalties_compare_N_value / _text, and per stage (1..3)
 * ci_penalties_step_N_title / _text. The design's copy lives in
 * thinksme_ci_defaults( 'penalties' ); a page whose set has no such section
 * renders nothing, so this part is inert on the other four ci-* pages.
 *
 * Which side is red and which is green is the design's, not the client's: `tone`
 * is a default, not a field. The comparison only reads if the penalty is the
 * alarming one and the fee is the reassuring one, and a select that lets the
 * client paint the fee red would only ever be used by mistake.
 *
 * The VS badge is centred over the gap between the two panels from `lg` and sits
 * in the flow between them below it — stacked there is no gap to centre it in, and
 * an absolute badge over a stack lands on top of the panel above. It is in the DOM
 * between the two panels for exactly that reason, so the un-positioned reading is
 * the correct order.
 *
 * A side with an empty value and a stage with an empty title are skipped, the same
 * rule every other ci-* section uses. With one side left the comparison still
 * renders as a single panel rather than half a layout.
 */

$d = thinksme_ci_defaults( 'penalties' );

if ( ! $d ) {
	return;
}

$compare = array();

foreach ( $d['compare'] as $n => $default ) {
	$value = thinksme_field( "ci_penalties_compare_{$n}_value", false, $default['value'] );

	if ( '' === trim( $value ) ) {
		continue;
	}

	$compare[] = array(
		'tone'  => $default['tone'],
		'value' => $value,
		'text'  => thinksme_field( "ci_penalties_compare_{$n}_text", false, $default['text'] ),
	);
}

$steps = array();

foreach ( $d['steps'] as $n => $default ) {
	$title = thinksme_field( "ci_penalties_step_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$steps[] = array(
		'title' => $title,
		'text'  => thinksme_field( "ci_penalties_step_{$n}_text", false, $default['text'] ),
	);
}

if ( ! $compare && ! $steps ) {
	return;
}

$badge = thinksme_field( 'ci_penalties_badge', false, $d['badge'] );
?>
<section id="ci-penalties" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col items-center gap-md text-center max-w-[1022px] mx-auto">
		<?php $hat = thinksme_field( 'ci_penalties_hat_text', false, $d['hat'] ); ?>
		<?php if ( $hat ) : ?>
			<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary">
			<?php echo esc_html( thinksme_field( 'ci_penalties_heading', false, $d['heading'] ) ); ?>
		</h2>

		<?php $intro = thinksme_field( 'ci_penalties_text', false, $d['text'] ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm leading-loose text-text-secondary max-w-[526px]">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>
	</div>

	<?php if ( $compare ) : ?>
		<div class="relative flex flex-col lg:flex-row items-stretch gap-md lg:gap-xl mt-xl lg:mt-3xl">
			<?php foreach ( $compare as $index => $side ) : ?>
				<?php if ( 1 === $index && $badge ) : ?>
					<?php // Out of the flow only once there is a gap to sit in. ?>
					<span class="ci-penalties__badge" aria-hidden="true"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>

				<div class="ci-penalties__panel flex flex-col items-center justify-center gap-lg text-center rounded-[40px] p-xl lg:min-h-[250px] lg:flex-1 lg:min-w-0" data-tone="<?php echo esc_attr( $side['tone'] ); ?>">
					<p class="ci-penalties__value text-2xl lg:text-3xl leading-tight">
						<?php echo esc_html( $side['value'] ); ?>
					</p>

					<?php if ( $side['text'] ) : ?>
						<p class="font-normal text-sm leading-loose text-text-secondary max-w-[359px]">
							<?php echo esc_html( $side['text'] ); ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $steps ) : ?>
		<?php // Numbered in the markup, not by a counter: the number is content the client can read back in wp-admin, and CSS counters would renumber a stage the client meant to keep as "3". ?>
		<ol class="grid grid-cols-1 md:grid-cols-3 gap-md lg:gap-xl mt-md lg:mt-xl">
			<?php foreach ( $steps as $index => $step ) : ?>
				<li class="bg-surface-faint rounded-lg px-lg py-xl flex flex-col items-center justify-center gap-xl text-center md:min-h-[345px]">
					<span class="bg-surface-navy-deep rounded-pill size-[60px] inline-flex items-center justify-center shrink-0 font-medium text-xl leading-snug text-brand-yellow" aria-hidden="true">
						<?php echo esc_html( $index + 1 ); ?>
					</span>

					<h3 class="font-medium text-xl leading-snug text-text-heading-dark">
						<?php echo esc_html( $step['title'] ); ?>
					</h3>

					<?php if ( $step['text'] ) : ?>
						<p class="font-normal text-sm leading-loose text-text-secondary">
							<?php echo esc_html( $step['text'] ); ?>
						</p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	<?php endif; ?>
</section>
