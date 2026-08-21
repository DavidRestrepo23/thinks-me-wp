<?php
/**
 * MRA Grant — the centred statement under the hero: one 40px paragraph with a
 * hand-drawn ring around the figure in it, and a single button under both.
 * Figma: nodes 130:1512 (the copy), 130:1513 (the ring) and 130:1514 (the button),
 * file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (MRA Grant page): ci_statement_text, ci_statement_button_text,
 * ci_statement_button_link. The design's copy lives in
 * thinksme_ci_defaults( 'statement' ); a page whose set has no such section
 * renders nothing, so this part is inert on the other eleven ci-* pages exactly
 * as ci-definition.php and ci-signup.php are.
 *
 * Deliberately not ci-definition.php, which is the other "what is this thing"
 * block in the family: that one is a pale panel with a heading, a worked example
 * and a photograph in a two-column grid (951:9052). This frame draws none of
 * those — no panel, no heading, no photo, no hat — just the sentence, centred on
 * the page at the size the section headings elsewhere use. Folding it into that
 * part would mean five more optional class strings and a branch that skips four
 * of its five blocks.
 *
 * Not cta.php either, for the reason ci-signup.php gives: this frame draws that
 * section too, at the bottom of the page.
 *
 * The ring is the section's own art rather than a field — it circles "70%",
 * which is the one figure the sentence turns on — and it is placed the way every
 * brush stroke in this theme is: absolutely, as a share of the copy's own box,
 * desktop-only. Figma measures it against this sentence breaking into five lines
 * at 40px in a 1039px column, so a much shorter or longer sentence will ring the
 * wrong words, which is why it is hidden below `lg` where the type is smaller
 * and rewraps. `aria-hidden`, because the figure it rings is already in the
 * sentence.
 */

$d = thinksme_ci_defaults( 'statement' );

if ( ! $d ) {
	return;
}

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$text        = thinksme_field( 'ci_statement_text', false, $d['text'] );
$button_text = thinksme_field( 'ci_statement_button_text', false, $d['button_text'] );
$button_link = thinksme_field( 'ci_statement_button_link', false, $d['button_link'] );

if ( '' === trim( $text ) && '' === trim( $button_text ) ) {
	return;
}
?>
<section id="ci-statement" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col items-center gap-xl lg:gap-[48px]">
		<?php if ( $text ) : ?>
			<?php // 1039px is Figma's own text box, and the ring's offsets below are shares of it. ?>
			<div class="relative w-full max-w-[1039px]">
				<img
					src="<?php echo esc_url( "$icons_uri/{$d['ring_file']}" ); ?>"
					alt=""
					aria-hidden="true"
					class="<?php echo esc_attr( $d['ring_class'] ); ?>"
				>

				<p class="relative font-medium text-xl sm:text-2xl leading-normal text-text-primary text-center">
					<?php echo esc_html( $text ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( $button_text ) : ?>
			<a href="<?php echo esc_url( $button_link ); ?>" class="btn-split flex sm:inline-flex items-center w-full sm:w-auto">
				<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary text-center grow sm:grow-0 sm:whitespace-nowrap">
					<?php echo esc_html( $button_text ); ?>
				</span>
				<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
				</span>
			</a>
		<?php endif; ?>
	</div>
</section>
