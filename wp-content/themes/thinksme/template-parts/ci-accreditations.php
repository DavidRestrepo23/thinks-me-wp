<?php
/**
 * PSG Grant — the accreditation strip under the hero: two marks centred between two
 * rules.
 * Figma: node 127:895, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Deliberately not template-parts/logos-slider.php. That section is a marquee over
 * the `client_logo` CPT and exists because the list is variable and client-owned;
 * this is two fixed marks — the Xero Certified Advisor badge and the IMDA
 * pre-approved-solution one — which are the two credentials the whole page rests on.
 * Turning them into a two-slide carousel would move a claim rather than state it, and
 * putting them in the CPT would let a client logo land between them.
 *
 * So they are per-set defaults (`accreditations` in thinksme_ci_defaults()) and theme
 * files, not ACF fields — the same call the Business Loan hero's partner banks and
 * the Property Cashout figure discs made. A page whose set names none renders
 * nothing, so this part is inert on the other ten ci-* pages.
 *
 * The rules are borders on two flex children rather than the two `<line>` nodes Figma
 * draws, and they are `aria-hidden` siblings of the marks rather than a pseudo-element
 * on the row: at 436px each they are the section's only content besides the logos, and
 * a border that shrinks with the row keeps the marks centred at every width. Below sm
 * they are dropped — there the strip is the two marks, and 20px of rule either side
 * reads as an artefact.
 */

$d = thinksme_ci_defaults( 'accreditations' );

if ( empty( $d['logos'] ) ) {
	return;
}
?>
<section id="ci-accreditations" class="w-full px-lg lg:px-3xl py-lg lg:py-xl">
	<div class="flex items-center gap-lg lg:gap-[40px]">
		<span class="hidden sm:block grow border-t border-border-faint" aria-hidden="true"></span>

		<div class="flex flex-wrap items-end justify-center gap-lg lg:gap-[48px] mx-auto sm:mx-0">
			<?php foreach ( $d['logos'] as $logo ) : ?>
				<img
					src="<?php echo esc_url( thinksme_ci_image_url( $logo['file'] ) ); ?>"
					alt="<?php echo esc_attr( $logo['name'] ); ?>"
					class="<?php echo esc_attr( $logo['class'] ); ?> w-auto max-w-full object-contain"
				>
			<?php endforeach; ?>
		</div>

		<span class="hidden sm:block grow border-t border-border-faint" aria-hidden="true"></span>
	</div>
</section>
