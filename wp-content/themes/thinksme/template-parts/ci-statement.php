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
 * brush stroke in this theme is, desktop-only — but anchored to the figure
 * (`ring_target`) rather than to the copy's box, so a rewritten sentence still
 * rings the right word. `aria-hidden`, because the figure it rings is already
 * in the sentence.
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
			<?php
			// The ring is anchored to the figure itself rather than placed as a share of the
			// text box: the copy changed once already (EDGE rename) and a box-relative offset
			// rang the wrong word. Splitting on the first occurrence keeps the figure inline,
			// so wrapping moves the ring with it.
			$ring_target = isset( $d['ring_target'] ) ? $d['ring_target'] : '';
			$ring_at     = $ring_target ? strpos( $text, $ring_target ) : false;
			?>
			<?php // 1039px is Figma's own text box. ?>
			<div class="w-full max-w-[1039px]">
				<p class="font-medium text-xl sm:text-2xl leading-normal text-text-primary text-center">
					<?php if ( false === $ring_at ) : ?>
						<?php echo esc_html( $text ); ?>
					<?php else : ?>
						<?php echo esc_html( substr( $text, 0, $ring_at ) ); ?><span class="relative inline-block whitespace-nowrap"><img
							src="<?php echo esc_url( "$icons_uri/{$d['ring_file']}" ); ?>"
							alt=""
							aria-hidden="true"
							class="<?php echo esc_attr( $d['ring_class'] ); ?>"
						><span class="relative"><?php echo esc_html( $ring_target ); ?></span></span><?php echo esc_html( substr( $text, $ring_at + strlen( $ring_target ) ) ); ?>
					<?php endif; ?>
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
