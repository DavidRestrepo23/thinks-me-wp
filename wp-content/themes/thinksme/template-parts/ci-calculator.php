<?php
/**
 * Corporate Tax — "Estimate Your Corporate Tax": the one free tool on this page,
 * a navy panel with two exemption schemes, an income field and a result.
 * Figma: node 108:4603, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Corporate Tax page): ci_calculator_hat_text, ci_calculator_heading,
 * ci_calculator_text, ci_calculator_title, ci_calculator_panel_text,
 * ci_calculator_placeholder, ci_calculator_button_text / _link,
 * ci_calculator_result_label, ci_calculator_saved_label,
 * ci_calculator_disclaimer, and per scheme (1..2) ci_calculator_mode_N_label /
 * _text. The design's copy lives in thinksme_ci_defaults( 'calculator' ); a page
 * whose set has no such section renders nothing, so this part is inert on the
 * other four ci-* pages.
 *
 * Unlike template-parts/ci-tools.php, this tool actually computes. That is the
 * difference between the two sections rather than an inconsistency: ci-tools ships
 * three inputs Figma wrote no behaviour for, where this frame draws a calculator,
 * names the rate and names both exemption schemes — a Calculate button that only
 * navigated away would be a broken promise on the label.
 *
 * The arithmetic is IRAS's own shape and lives in the defaults, not in the script:
 * `rate` is the flat corporate rate, and each scheme is a list of [band, exempt %]
 * applied to the bottom of chargeable income (SUTE 75% of the first 100k then 50%
 * of the next 100k; PTE 75% of the first 10k then 50% of the next 190k). Those
 * numbers change with the Budget, and a number buried in a JS file is a number
 * nobody finds — assets/js/ci-calculator.js reads them off the markup.
 *
 * It is not a filing and not advice, which is what the disclaimer under the panel
 * says. Deliberately no rounding to cents: an estimate that reads S$8,415.00
 * claims a precision it doesn't have.
 *
 * Progressive enhancement, the same contract every other interactive section in
 * the theme has: this is a real GET form pointing at ci_calculator_button_link, so
 * with no JS the schemes are native radios and the button takes the visitor to the
 * contact page with what they typed. With the script, submitting computes in place
 * instead. Nothing server-side reads the query string today.
 *
 * The yellow strip peeking above the panel's top edge is Figma's 108:4627 — the
 * tab the tools panel has on the other pages, left in place here with no strip to
 * belong to. It is decoration, so it is an aria-hidden element rather than a
 * heading nobody can read.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$d = thinksme_ci_defaults( 'calculator' );

if ( ! $d ) {
	return;
}

$modes = array();

foreach ( $d['modes'] as $n => $default ) {
	$label = thinksme_field( "ci_calculator_mode_{$n}_label", false, $default['label'] );

	if ( '' === trim( $label ) ) {
		continue;
	}

	$modes[ $n ] = array(
		'label' => $label,
		'text'  => thinksme_field( "ci_calculator_mode_{$n}_text", false, $default['text'] ),
		'bands' => $default['bands'],
	);
}

if ( ! $modes ) {
	return;
}

$title        = thinksme_field( 'ci_calculator_title', false, $d['title'] );
$panel_text   = thinksme_field( 'ci_calculator_panel_text', false, $d['panel_text'] );
$placeholder  = thinksme_field( 'ci_calculator_placeholder', false, $d['placeholder'] );
$button_text  = thinksme_field( 'ci_calculator_button_text', false, $d['button_text'] );
$button_link  = thinksme_field( 'ci_calculator_button_link', false, $d['button_link'] );
$result_label = thinksme_field( 'ci_calculator_result_label', false, $d['result_label'] );
$saved_label  = thinksme_field( 'ci_calculator_saved_label', false, $d['saved_label'] );
$disclaimer   = thinksme_field( 'ci_calculator_disclaimer', false, $d['disclaimer'] );
$active       = array_key_first( $modes );
?>
<section id="ci-calculator" class="flex flex-col items-center gap-xl lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col items-center gap-md text-center max-w-[765px]">
		<?php $hat = thinksme_field( 'ci_calculator_hat_text', false, $d['hat'] ); ?>
		<?php if ( $hat ) : ?>
			<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary">
			<?php echo esc_html( thinksme_field( 'ci_calculator_heading', false, $d['heading'] ) ); ?>
		</h2>

		<?php $intro = thinksme_field( 'ci_calculator_text', false, $d['text'] ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm leading-loose text-text-secondary">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>
	</div>

	<form
		class="ci-calc"
		action="<?php echo esc_url( $button_link ); ?>"
		method="get"
		data-rate="<?php echo esc_attr( $d['rate'] ); ?>"
		data-currency="<?php echo esc_attr( $d['currency'] ); ?>"
	>
		<span class="ci-calc__tab" aria-hidden="true"></span>

		<div class="flex flex-col items-center gap-md text-center text-text-on-dark w-full">
			<?php // The colour is repeated on the heading itself: base.css sets a hard `color` on h1..h6, and a rule on the element beats a colour inherited from this wrapper. ?>
			<?php if ( $title ) : ?>
				<h3 class="font-medium text-[28px] leading-snug text-text-on-dark">
					<?php echo esc_html( $title ); ?>
				</h3>
			<?php endif; ?>

			<?php if ( $panel_text ) : ?>
				<p class="font-normal text-sm leading-loose">
					<?php echo esc_html( $panel_text ); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php // Native radios rather than scripted buttons: they carry their own keyboard behaviour and their own state, so the choice survives the script never loading and travels with the form when it doesn't. Each carries its own exemption bands — see the note at the top about why those live here. ?>
		<fieldset class="ci-calc__modes">
			<legend class="sr-only"><?php echo esc_html( $title ? $title : $d['title'] ); ?></legend>

			<?php foreach ( $modes as $n => $mode ) : ?>
				<label class="ci-calc__mode">
					<input
						class="sr-only"
						type="radio"
						name="scheme"
						value="<?php echo esc_attr( $n ); ?>"
						data-bands="<?php echo esc_attr( wp_json_encode( $mode['bands'] ) ); ?>"
						<?php checked( $n, $active ); ?>
					>
					<span class="ci-calc__mode-label"><?php echo esc_html( $mode['label'] ); ?></span>

					<?php if ( $mode['text'] ) : ?>
						<span class="ci-calc__mode-text"><?php echo esc_html( $mode['text'] ); ?></span>
					<?php endif; ?>
				</label>
			<?php endforeach; ?>
		</fieldset>

		<div class="flex flex-col gap-lg w-full">
			<label class="sr-only" for="ci-calc-income"><?php echo esc_html( $placeholder ); ?></label>
			<?php // inputmode rather than type="number": the spinner is meaningless on a currency amount, and a text field lets someone paste "1,250,000" without the browser discarding it. ?>
			<input
				class="ci-calc__input"
				type="text"
				id="ci-calc-income"
				name="income"
				inputmode="decimal"
				autocomplete="off"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
			>

			<?php if ( $button_text ) : ?>
				<button type="submit" class="btn-split flex items-center w-full">
					<span class="bg-brand-yellow rounded-sm h-[58px] px-lg grow inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
						<?php echo esc_html( $button_text ); ?>
					</span>
					<span class="bg-brand-yellow rounded-sm h-[58px] w-[50px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
					</span>
				</button>
			<?php endif; ?>
		</div>

		<?php // Both regions are announced when they change and neither is in the layout until it has something to say. The error's copy is a design default, not a field: it describes the control, not the offer. ?>
		<div class="ci-calc__result" role="status" aria-live="polite" hidden>
			<p class="ci-calc__result-label"><?php echo esc_html( $result_label ); ?></p>
			<p class="ci-calc__amount" data-calc-amount></p>
			<p class="ci-calc__saved">
				<?php echo esc_html( $saved_label ); ?>
				<span data-calc-saved></span>
			</p>
		</div>

		<p class="ci-calc__error" data-calc-error hidden>
			<?php echo esc_html( $d['error_text'] ); ?>
		</p>

		<?php if ( $disclaimer ) : ?>
			<p class="font-normal text-[12px] leading-loose text-center text-text-on-dark opacity-50 max-w-[493px]">
				<?php echo esc_html( $disclaimer ); ?>
			</p>
		<?php endif; ?>
	</form>
</section>
