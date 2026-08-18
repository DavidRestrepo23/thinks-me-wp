<?php
/**
 * The one calculator in the theme, drawn twice:
 *
 *   'tax'  — "Estimate Your Corporate Tax" (108:4603): two exemption schemes, one
 *            chargeable-income field, a tax figure and what the exemption saved.
 *   'loan' — "Estimate Your Loan Repayments" (119:1825): an amount, a tenure and a
 *            flat rate, a monthly instalment and the total repayable.
 *
 * File "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * `mode` is a per-page default, not a client field: it is which frame the design
 * draws, the same arrangement ci-pricing.php's `layout` has. Everything outside the
 * fields — the panel, its yellow tab, the heading block, the result block, the
 * disclaimer — is shared, which is why this is one part with two field sets rather
 * than a second near-copy.
 *
 * ACF (Corporate Tax page): ci_calculator_hat_text, ci_calculator_heading,
 * ci_calculator_text, ci_calculator_title, ci_calculator_panel_text,
 * ci_calculator_placeholder, ci_calculator_button_text / _link,
 * ci_calculator_result_label, ci_calculator_saved_label,
 * ci_calculator_disclaimer, and per scheme (1..2) ci_calculator_mode_N_label /
 * _text. The Business Loan page has the same section fields plus its three field
 * labels — ci_calculator_amount_label, _tenure_label, _rate_label — and no schemes.
 * The design's copy lives in thinksme_ci_defaults( 'calculator' ); a page whose set
 * has no such section renders nothing, so this part is inert on the other five ci-*
 * pages.
 *
 * Unlike template-parts/ci-tools.php, this tool actually computes. That is the
 * difference between the two sections rather than an inconsistency: ci-tools ships
 * three inputs Figma wrote no behaviour for, where this frame draws a calculator,
 * names the rate and names both exemption schemes — a Calculate button that only
 * navigated away would be a broken promise on the label.
 *
 * The loan arithmetic is the flat-rate method the frame names on its own
 * disclaimer: interest is charged on the original amount for the whole tenure, so
 * total = amount + amount x rate x years and the instalment is that over the
 * months. Deliberately not an amortising schedule — the panel says "flat rate", and
 * computing a reducing-balance figure under that label would be answering a
 * different question than the one on screen.
 *
 * The tax arithmetic is IRAS's own shape and lives in the defaults, not in the script:
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

$mode = isset( $d['mode'] ) ? $d['mode'] : 'tax';

// The exemption schemes belong to the tax frame; the loan one draws no radios at
// all, so an empty list is the normal state there rather than a section with
// nothing to compute.
$modes = array();

if ( 'tax' === $mode ) {
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
}

$title        = thinksme_field( 'ci_calculator_title', false, $d['title'] );
$panel_text   = thinksme_field( 'ci_calculator_panel_text', false, $d['panel_text'] );
$placeholder  = thinksme_field( 'ci_calculator_placeholder', false, $d['placeholder'] );
$button_text  = thinksme_field( 'ci_calculator_button_text', false, $d['button_text'] );
$button_link  = thinksme_field( 'ci_calculator_button_link', false, $d['button_link'] );
$result_label = thinksme_field( 'ci_calculator_result_label', false, $d['result_label'] );
$saved_label  = thinksme_field( 'ci_calculator_saved_label', false, $d['saved_label'] );
$disclaimer   = thinksme_field( 'ci_calculator_disclaimer', false, $d['disclaimer'] );
$active       = $modes ? array_key_first( $modes ) : 0;
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

	<?php // The tax frame's rate is fixed (IRAS's 17%) and rides on the form; the loan frame's is a field the visitor types, so there it is only the value the input starts on. ?>
	<form
		class="ci-calc"
		action="<?php echo esc_url( $button_link ); ?>"
		method="get"
		data-mode="<?php echo esc_attr( $mode ); ?>"
		data-rate="<?php echo esc_attr( $d['rate'] ); ?>"
		data-currency="<?php echo esc_attr( $d['currency'] ); ?>"
		<?php // The tenure select's caret is the theme's glyph rather than the platform's, handed to CSS the way faq.php hands over its +/- icons. ?>
		style="--ci-calc-caret: url('<?php echo esc_url( "$icons_uri/caret-down.svg" ); ?>');"
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

		<?php if ( $modes ) : ?>
			<?php // Native radios rather than scripted buttons: they carry their own keyboard behaviour and their own state, so the choice survives the script never loading and travels with the form when it doesn't. Each carries its own exemption bands — see the note at the top about why those live here. ?>
			<fieldset class="ci-calc__modes">
				<legend class="sr-only"><?php echo esc_html( $title ? $title : $d['title'] ); ?></legend>

				<?php foreach ( $modes as $n => $scheme ) : ?>
					<label class="ci-calc__mode">
						<input
							class="sr-only"
							type="radio"
							name="scheme"
							value="<?php echo esc_attr( $n ); ?>"
							data-bands="<?php echo esc_attr( wp_json_encode( $scheme['bands'] ) ); ?>"
							<?php checked( $n, $active ); ?>
						>
						<span class="ci-calc__mode-label"><?php echo esc_html( $scheme['label'] ); ?></span>

						<?php if ( $scheme['text'] ) : ?>
							<span class="ci-calc__mode-text"><?php echo esc_html( $scheme['text'] ); ?></span>
						<?php endif; ?>
					</label>
				<?php endforeach; ?>
			</fieldset>
		<?php endif; ?>

		<div class="flex flex-col gap-lg w-full">
			<?php if ( 'loan' === $mode ) : ?>
				<?php
				$amount_label = thinksme_field( 'ci_calculator_amount_label', false, $d['amount_label'] );
				$tenure_label = thinksme_field( 'ci_calculator_tenure_label', false, $d['tenure_label'] );
				$rate_label   = thinksme_field( 'ci_calculator_rate_label', false, $d['rate_label'] );
				?>
				<?php // Figma draws the amount and the tenure on one row and the rate on its own (119:1836); on a phone all three are their own row. ?>
				<div class="ci-calc__grid">
					<p class="ci-calc__field">
						<label class="ci-calc__label" for="ci-calc-income"><?php echo esc_html( $amount_label ); ?></label>
						<?php // inputmode rather than type="number": the spinner is meaningless on a currency amount, and a text field lets someone paste "1,250,000" without the browser discarding it. ?>
						<input
							class="ci-calc__input"
							type="text"
							id="ci-calc-income"
							name="amount"
							inputmode="decimal"
							autocomplete="off"
							placeholder="<?php echo esc_attr( $placeholder ); ?>"
						>
					</p>

					<p class="ci-calc__field">
						<label class="ci-calc__label" for="ci-calc-tenure"><?php echo esc_html( $tenure_label ); ?></label>
						<?php // A native <select>: it carries its own keyboard behaviour, travels with the form when the script never loads, and is what Figma draws (a closed control with a caret). ?>
						<select class="ci-calc__input ci-calc__select" id="ci-calc-tenure" name="tenure">
							<?php foreach ( $d['tenures'] as $years ) : ?>
								<option value="<?php echo esc_attr( $years ); ?>" <?php selected( $years, $d['tenure'] ); ?>>
									<?php
									/* translators: %s: number of years. */
									echo esc_html( sprintf( _n( '%s Year', '%s Years', (int) $years, 'thinksme' ), number_format_i18n( $years ) ) );
									?>
								</option>
							<?php endforeach; ?>
						</select>
					</p>
				</div>

				<p class="ci-calc__field">
					<label class="ci-calc__label" for="ci-calc-rate"><?php echo esc_html( $rate_label ); ?></label>
					<input
						class="ci-calc__input"
						type="text"
						id="ci-calc-rate"
						name="rate"
						inputmode="decimal"
						autocomplete="off"
						value="<?php echo esc_attr( $d['rate'] ); ?>"
					>
				</p>
			<?php else : ?>
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
			<?php endif; ?>

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
