<?php
/**
 * Company Incorporation Local — page hero: hat, headline, intro, two buttons,
 * and the photo with its lightbulb badge on the right.
 * Figma: node 91:2069, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Company Incorporation Local page): ci_hero_hat_text, ci_hero_title,
 * ci_hero_text, ci_hero_button_text / _link, ci_hero_button_2_text / _link,
 * ci_hero_image.
 *
 * Two buttons, not one: a solid split button for the primary action and an
 * outlined one carrying the phone number. The phone is an ACF field on this page
 * rather than the Customizer's site-wide number — it is the label on a CTA here,
 * not site chrome, and the design pairs it with a specific action. If the client
 * wants one number everywhere, that is a content decision to make once, not two
 * fields to keep in sync silently.
 *
 * The brush stroke under the headline is desktop-only and sits at a fixed
 * offset, because Figma measures it against this headline breaking across
 * exactly three lines at 72px — the same constraint roa-hero.php documents. Edit
 * the headline to a different length and the stroke will underline the wrong
 * words, which is why it is hidden below `lg` where the type is smaller.
 *
 * The default image is the client-supplied export of Figma's whole image group
 * (91:2056): photo, rounded corners and lightbulb badge already composed, on
 * transparency. It is drawn as-is — no card, no clipping — because it carries its
 * own shape.
 *
 * A client upload is a plain photo, so it takes the other branch: it is placed in
 * the photo's own region of the group (the percentages below are Figma's) with a
 * rounded corner and object-cover, and the badge is drawn back over it from
 * assets/images/ci/hero-badge.svg. That file is the nine separate vectors Figma
 * draws (91:2059–91:2067) merged into one SVG, the same treatment the pricing
 * cityscape gets. Either way the section keeps its design; only the source of the
 * photo changes.
 *
 * The wrapper is Figma's 609x554 group rather than the 583x485 photo card,
 * because the badge deliberately overhangs the card's top-left corner and that
 * extra space is part of the composition.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$images_uri = get_template_directory_uri() . '/assets/images/ci';

$photo     = thinksme_field( 'ci_hero_image' );
$is_stock  = empty( $photo['url'] );
$photo_url = ! $is_stock ? $photo['url'] : "$images_uri/hero-image.png";
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

$button_text   = thinksme_field( 'ci_hero_button_text', false, 'Start Incorporation' );
$button_link   = thinksme_field( 'ci_hero_button_link', false, '/contact-us' );
$button_2_text = thinksme_field( 'ci_hero_button_2_text', false, '+65 6012 9642' );
$button_2_link = thinksme_field( 'ci_hero_button_2_link', false, 'tel:+6560129642' );
?>
<section id="ci-hero" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col justify-center gap-xl w-full lg:basis-[703px] lg:min-w-0">
		<div class="flex flex-col items-start gap-md">
			<?php $hat = thinksme_field( 'ci_hero_hat_text', false, 'For Singapore Citizens & PRs · ACRA Registered Filing Agent' ); ?>
			<?php if ( $hat ) : ?>
				<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill min-h-[32px] px-md py-[6px] inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $hat ); ?>
				</span>
			<?php endif; ?>

			<div class="relative w-full">
				<?php // Positioned as a share of the 703px column so it holds while the column flexes; hidden below lg, where the smaller type rewraps and the stroke would land on the wrong line. ?>
				<img
					src="<?php echo esc_url( "$icons_uri/ci/hero-underline.svg" ); ?>"
					alt=""
					aria-hidden="true"
					class="hidden lg:block absolute left-[-0.3%] top-[197px] w-[92.6%] rotate-[1.83deg] pointer-events-none select-none"
				>
				<h1 class="relative font-medium text-[40px] sm:text-[56px] lg:text-[72px] leading-none tracking-hero text-text-primary">
					<?php echo esc_html( thinksme_field( 'ci_hero_title', false, 'Incorporate Your Singapore Company From S$888 All-In' ) ); ?>
				</h1>
			</div>
		</div>

		<?php $intro = thinksme_field( 'ci_hero_text', false, 'ACRA-filed, fully online, with your corporate secretary and registered address bundled in from day one — no separate vendors, no guesswork.' ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm text-text-secondary leading-relaxed max-w-[578px]">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $button_text || $button_2_text ) : ?>
			<?php // Both buttons span the column on a phone — side by side they wrap to ragged widths, and a full-width target is easier to hit. ?>
			<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-md sm:gap-lg">
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

	<div class="relative w-full max-w-[520px] mx-auto lg:max-w-none lg:mx-0 lg:basis-[609px] lg:min-w-0 aspect-[609/554]">
		<?php if ( $is_stock ) : ?>
			<?php // The supplied export already carries the card, its corners and the badge, so it is drawn straight into the box. ?>
			<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" class="absolute inset-0 w-full h-full object-contain">
		<?php else : ?>
			<?php // A plain upload gets the photo's own slot inside the group — Figma's 26,69 / 583x485 as percentages — and the badge painted back on top. ?>
			<div class="absolute left-[4.27%] top-[12.45%] w-[95.73%] h-[87.55%] overflow-hidden rounded-2xl">
				<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" class="absolute inset-0 w-full h-full object-cover">
			</div>

			<img
				src="<?php echo esc_url( "$images_uri/hero-badge.svg" ); ?>"
				alt=""
				aria-hidden="true"
				class="absolute left-0 top-[4.69%] w-[20.57%] pointer-events-none select-none"
			>
		<?php endif; ?>
	</div>
</section>
