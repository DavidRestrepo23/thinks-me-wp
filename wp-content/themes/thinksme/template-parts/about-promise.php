<?php
/**
 * About Us — a full-width yellow banner: eyebrow, heading, subtitle and one
 * button over a faint dot-grid texture.
 * Figma: node 157:542, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * About-only: none of the other pages draw a standalone promo banner shaped
 * like this — `ci-signup.php` (Remittance) is centred too, but has two buttons
 * and two flanking photographs rather than one button on a textured panel.
 *
 * ACF (About Us page): about_promise_hat, _heading, _text, _button_text,
 * _button_link. The dot-grid texture is a theme file, not a client field.
 */

$d = thinksme_ci_defaults( 'promise' );

if ( ! $d ) {
	return;
}

$icons_uri = get_template_directory_uri() . '/assets/images/about';

$button_text = thinksme_field( 'about_promise_button_text', false, $d['button_text'] );
$button_link = thinksme_field( 'about_promise_button_link', false, $d['button_link'] );
?>
<section id="about-promise" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="relative bg-brand-yellow overflow-hidden rounded-[40px] lg:rounded-[80px] px-lg lg:px-3xl py-2xl lg:py-[80px]">
		<img
			src="<?php echo esc_url( "$icons_uri/promise-bg.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none"
		>

		<div class="relative flex flex-col items-center gap-xl lg:gap-2xl text-center max-w-[745px] mx-auto">
			<div class="flex flex-col items-center gap-lg">
				<span class="bg-white border border-brand-yellow-border rounded-pill px-md py-[7px] inline-flex items-center justify-center w-max text-xs font-bold text-text-navy">
					<?php echo esc_html( thinksme_field( 'about_promise_hat', false, $d['hat'] ) ); ?>
				</span>

				<h2 class="font-medium text-2xl lg:text-[52px] leading-tight text-text-primary">
					<?php echo esc_html( thinksme_field( 'about_promise_heading', false, $d['heading'] ) ); ?>
				</h2>
			</div>

			<p class="font-normal text-sm leading-loose text-text-primary">
				<?php echo esc_html( thinksme_field( 'about_promise_text', false, $d['text'] ) ); ?>
			</p>

			<?php if ( $button_text ) : ?>
				<a href="<?php echo esc_url( $button_link ); ?>" class="btn-split inline-flex items-center">
					<span class="bg-white rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
						<?php echo esc_html( $button_text ); ?>
					</span>
					<span class="bg-white rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/arrow-up-right-dark.svg' ); ?>" alt="" class="size-[24px]">
					</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
