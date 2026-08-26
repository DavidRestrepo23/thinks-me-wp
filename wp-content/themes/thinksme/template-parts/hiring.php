<?php
/**
 * "We're Hiring" — copy on the left, team photo with the yellow bulb badge on
 * the right.
 * Figma: node 62:100, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Contact page): hiring_hat_text, hiring_title (line breaks in the field
 * are kept, so the client controls where the headline wraps), hiring_text,
 * hiring_image, hiring_button_text, hiring_button_link. The design has no
 * button — leaving the button text empty (the default) renders none.
 *
 * The photo falls back to the Figma export so the section is complete before the
 * client uploads their own. See src/base.css for the one Figma detail dropped
 * here (the double-exposed photo bleeding above its frame).
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$image     = thinksme_field( 'hiring_image' );
$image_url = ! empty( $image['url'] ) ? $image['url'] : get_template_directory_uri() . '/assets/images/contact/hiring-1.jpg';
$image_alt = ! empty( $image['alt'] ) ? $image['alt'] : '';

$button_text = thinksme_field( 'hiring_button_text', false, '' );
$button_link = thinksme_field( 'hiring_button_link', false, '' );
?>
<section id="hiring" class="flex flex-col lg:flex-row lg:items-center gap-xl lg:gap-lg w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col justify-center gap-xl lg:gap-[48px] w-full lg:w-[704px] shrink-0">
		<div class="flex flex-col justify-center gap-md w-full">
			<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md self-start inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( thinksme_field( 'hiring_hat_text', false, 'Enjoy!' ) ); ?>
			</span>

			<h2 class="font-medium text-2xl lg:text-3xl leading-tight text-text-primary">
				<?php echo nl2br( esc_html( thinksme_field( 'hiring_title', false, "We’re Hiring!\nJoin Our Team Today!" ) ) ); ?>
			</h2>
		</div>

		<p class="font-normal text-sm text-text-heading-dark leading-loose max-w-[519px]">
			<?php echo esc_html( thinksme_field( 'hiring_text', false, 'At Think SME, we bring the best to the table and our team is the living proof of that. We constantly look for talents who share the same passion for business, for numbers, and for creativity.' ) ); ?>
		</p>

		<?php if ( $button_text ) : ?>
			<a href="<?php echo esc_url( $button_link ? $button_link : '#' ); ?>" class="btn-split inline-flex items-center self-start">
				<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
					<?php echo esc_html( $button_text ); ?>
				</span>
				<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
				</span>
			</a>
		<?php endif; ?>
	</div>

	<?php // The badge hangs off the frame's bottom-left corner, so the wrapper pads that side to keep it inside the section. ?>
	<div class="relative w-full lg:flex-1 pl-[32px] pb-[32px] lg:pl-[40px]">
		<div class="hiring-photo aspect-[515/391] w-full">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" class="w-full h-full object-cover">
		</div>
		<img
			src="<?php echo esc_url( "$icons_uri/hiring-bulb.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="absolute left-0 bottom-0 w-[84px] lg:w-[125px] pointer-events-none"
		>
	</div>
</section>
