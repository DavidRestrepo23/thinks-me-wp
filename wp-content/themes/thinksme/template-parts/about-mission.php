<?php
/**
 * About Us — "Why Think SME Exists": heading and copy beside a photograph, on
 * a pale rounded panel.
 * Figma: node 157:839, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * About-only: closest existing shape is ci-definition.php (Property Cashout),
 * but that part is built around a worked-example callout this frame doesn't
 * draw — a heading, two paragraphs, one bold closing line and a photograph is
 * simpler on its own than as a third branch of that part.
 *
 * The photograph ships as one composited JPEG rather than the two layers
 * Figma draws (a background photo plus a cut-out breaking over it, the same
 * two-layer arrangement documented on the other hero/editorial photos in this
 * theme) — the cut-out's alpha was composited by hand, so the committed asset
 * is already the final look.
 *
 * ACF (About Us page): about_mission_hat, _heading, _text_1, _text_2, _text_3,
 * _image. Paragraph 3 renders bold — a position in the design (157:846), not a
 * per-paragraph field.
 */

$d = thinksme_ci_defaults( 'mission' );

if ( ! $d ) {
	return;
}

$photo     = thinksme_field( 'about_mission_image' );
$photo_url = ! empty( $photo['url'] ) ? $photo['url'] : thinksme_ci_image_url( $d['image'] );
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';
?>
<section id="about-mission" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="bg-surface-panel rounded-[40px] lg:rounded-[80px] px-lg lg:px-3xl py-2xl lg:py-[96px]">
		<div class="flex flex-col-reverse lg:flex-row lg:items-center lg:justify-between gap-2xl w-full">
			<div class="flex flex-col gap-2xl lg:gap-3xl w-full lg:max-w-[624px]">
				<div class="flex flex-col gap-lg">
					<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center w-max text-xs font-medium text-text-primary">
						<?php echo esc_html( thinksme_field( 'about_mission_hat', false, $d['hat'] ) ); ?>
					</span>

					<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-text-primary">
						<?php echo esc_html( thinksme_field( 'about_mission_heading', false, $d['heading'] ) ); ?>
					</h2>
				</div>

				<div class="flex flex-col gap-md max-w-[519px]">
					<?php foreach ( array( 1, 2, 3 ) as $n ) : ?>
						<?php $text = thinksme_field( "about_mission_text_{$n}", false, $d[ "text_{$n}" ] ); ?>
						<?php if ( $text ) : ?>
							<p class="<?php echo 3 === $n ? 'font-bold' : 'font-normal'; ?> text-sm leading-loose text-text-primary">
								<?php echo esc_html( $text ); ?>
							</p>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>

			<?php // The photo's rounded corners are baked into the composited asset itself (matching the page's own background where they aren't), so no rounding wrapper is applied here — the same contract ci-hero.php's stock branch uses for its own composed exports. ?>
			<div class="w-full max-w-[650px] mx-auto lg:mx-0 lg:shrink-0 aspect-[650/490]">
				<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" loading="lazy" class="w-full h-full object-cover">
			</div>
		</div>
	</div>
</section>
