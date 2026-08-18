<?php
/**
 * Remittance — "Sign Up With OFX And Start Saving On FX Transfers*.": a centred
 * headline and two buttons, with a photograph tilted into each margin.
 * Figma: nodes 123:2747 + 123:2748 + 123:2755 + 123:2759, file "Untitled"
 * (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Remittance page): ci_signup_heading, ci_signup_button_text / _link,
 * ci_signup_button_2_text / _link. The design's copy lives in
 * thinksme_ci_defaults( 'signup' ); a page whose set has no such section renders
 * nothing, so this part is inert on the other eight ci-* pages exactly as
 * ci-steps.php and ci-definition.php are.
 *
 * It is deliberately not `cta.php` under another name. That section is the one
 * every page closes on — heading and Google rating on one row above a full-width
 * photo band — and this frame draws both of them, the sign-up band here and the
 * shared CTA further down. Folding them together would mean one of the two losing
 * its shape.
 *
 * **The two photographs are decoration, not content**, so they are theme files
 * and `aria-hidden` rather than ACF image fields: they carry no information the
 * headline doesn't, and a client upload in either slot would land at a tilt
 * measured for a specific export. Figma rotates them on the canvas, so the
 * rotation is baked into the exports and each one is placed at its own share of
 * the section box (`.ci-signup__photo--left` / `--right` in src/base.css).
 *
 * Below lg they are dropped rather than stacked. They are the only two elements on
 * this page with nothing to say, and on a phone the band is the headline and its
 * buttons — a decorative photograph above them would push both below the fold.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$d = thinksme_ci_defaults( 'signup' );

if ( ! $d ) {
	return;
}

$heading = thinksme_field( 'ci_signup_heading', false, $d['heading'] );

if ( '' === trim( $heading ) ) {
	return;
}

$button_text   = thinksme_field( 'ci_signup_button_text', false, $d['button_text'] );
$button_link   = thinksme_field( 'ci_signup_button_link', false, $d['button_link'] );
$button_2_text = thinksme_field( 'ci_signup_button_2_text', false, $d['button_2_text'] );
$button_2_link = thinksme_field( 'ci_signup_button_2_link', false, $d['button_2_link'] );
?>
<section id="ci-signup" class="ci-signup relative w-full px-lg lg:px-3xl py-xl lg:py-[80px]">
	<?php if ( ! empty( $d['photo_left'] ) ) : ?>
		<img
			src="<?php echo esc_url( thinksme_ci_image_url( $d['photo_left'] ) ); ?>"
			alt=""
			aria-hidden="true"
			loading="lazy"
			class="ci-signup__photo ci-signup__photo--left"
		>
	<?php endif; ?>

	<?php if ( ! empty( $d['photo_right'] ) ) : ?>
		<img
			src="<?php echo esc_url( thinksme_ci_image_url( $d['photo_right'] ) ); ?>"
			alt=""
			aria-hidden="true"
			loading="lazy"
			class="ci-signup__photo ci-signup__photo--right"
		>
	<?php endif; ?>

	<div class="relative flex flex-col items-center gap-xl lg:gap-[48px] mx-auto max-w-[700px]">
		<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-center text-text-primary">
			<?php echo esc_html( $heading ); ?>
		</h2>

		<?php if ( $button_text || $button_2_text ) : ?>
			<?php // Full-width on a phone for the reason ci-hero.php's pair is: side by side they wrap to ragged widths, and a full-width target is easier to hit. ?>
			<div class="flex flex-col sm:flex-row sm:items-center gap-md sm:gap-lg w-full sm:w-auto">
				<?php if ( $button_text ) : ?>
					<a href="<?php echo esc_url( $button_link ); ?>" class="btn-split flex sm:inline-flex items-center w-full sm:w-auto">
						<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap grow sm:grow-0">
							<?php echo esc_html( $button_text ); ?>
						</span>
						<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
							<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
						</span>
					</a>
				<?php endif; ?>

				<?php if ( $button_2_text ) : ?>
					<a href="<?php echo esc_url( $button_2_link ); ?>" class="ci-hero__button-alt w-full sm:w-auto">
						<?php echo esc_html( $button_2_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
