<?php
/**
 * Final CTA — "Need help?" headline, Google rating, contact button, and a
 * full-width photo band.
 * Figma: node 27:1983, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Home page): cta_title, cta_text, cta_google_rating, cta_button_text,
 * cta_button_link, cta_image. The image falls back to the photo exported from
 * Figma so the section is complete before the client uploads their own.
 *
 * The photo keeps Figma's 1280x561 proportion via aspect-ratio instead of a
 * fixed height, so it scales down with the viewport. In Figma the band is two
 * overlapping layers (a cut-out of the subject over the full frame); only the
 * full photo is needed here.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';
$image     = thinksme_field( 'cta_image' );
// The fallback is a filter so a page can supply its own photo without this part
// learning which page it is on — see thinksme_ci_cta_photo() in inc/ci-content.php.
$default_url = apply_filters( 'thinksme_cta_photo', get_template_directory_uri() . '/assets/images/cta/cta-photo.jpg' );
$image_url   = ! empty( $image['url'] ) ? $image['url'] : $default_url;
$image_alt = ! empty( $image['alt'] ) ? $image['alt'] : '';
$rating    = thinksme_field( 'cta_google_rating', false, '4.9' );
?>
<section id="cta" class="flex flex-col gap-xl lg:gap-2xl w-full mt-xl md:mt-3xl pb-3xl px-lg lg:px-3xl">
	<div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-xl w-full">
		<div class="flex flex-col gap-lg max-w-[500px]">
			<h2 class="font-medium text-2xl lg:text-3xl leading-tight text-text-primary" style="letter-spacing: -0.01em;">
				<?php echo esc_html( thinksme_field( 'cta_title', false, 'Need help?' ) ); ?>
			</h2>
			<p class="font-normal text-md text-text-secondary leading-normal tracking-wide">
				<?php echo esc_html( thinksme_field( 'cta_text', false, 'Reach out with your requirements, and our experts will be happy to assist you — no commitment, no pressure.' ) ); ?>
			</p>
		</div>

		<div class="flex flex-col gap-lg items-start lg:items-end shrink-0">
			<div class="flex gap-md items-center">
				<img src="<?php echo esc_url( "$icons_uri/logo-google.svg" ); ?>" alt="Google" class="w-[122px] h-[40px]">
				<div class="flex gap-xs items-center">
					<span class="font-medium text-xl text-text-primary leading-normal tracking-wide"><?php echo esc_html( $rating ); ?></span>
					<div class="flex gap-[4px]" role="img" aria-label="<?php
						/* translators: %s: Google rating, e.g. "4.9". */
						echo esc_attr( sprintf( __( '%s out of 5 on Google', 'thinksme' ), $rating ) );
					?>">
						<?php for ( $i = 0; $i < 5; $i++ ) : ?>
							<img src="<?php echo esc_url( "$icons_uri/star.svg" ); ?>" alt="" class="size-[24px]">
						<?php endfor; ?>
					</div>
				</div>
			</div>

			<a href="<?php echo esc_url( thinksme_field( 'cta_button_link', false, '#' ) ); ?>" class="btn-split inline-flex items-center">
				<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
					<?php echo esc_html( thinksme_field( 'cta_button_text', false, 'Contact Us' ) ); ?>
				</span>
				<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
				</span>
			</a>
		</div>
	</div>

	<div class="w-full rounded-xl overflow-hidden aspect-[1280/561]">
		<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" class="w-full h-full object-cover">
	</div>
</section>
