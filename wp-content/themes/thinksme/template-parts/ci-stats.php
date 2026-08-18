<?php
/**
 * Corporate Tax — the figures band under the logo marquee: four numbers on one
 * navy pill.
 * Figma: node 108:4515, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Corporate Tax and Business Loan pages): per figure (1..4)
 * ci_stats_card_N_icon (select, fed at runtime from thinksme_ci_icons()), _value,
 * _label, plus ci_stats_note. The design's copy lives in
 * thinksme_ci_defaults( 'stats' ); a page whose set has no such section renders
 * nothing, so this part is inert on the other four ci-* pages exactly as
 * ci-requirements.php and ci-why-slider.php are.
 *
 * A figure with an empty *value* is skipped — the value is what the cell is, the
 * way a card's title is elsewhere in this family. So the client can run three
 * figures without touching the layout.
 *
 * The Business Loan frame closes the band with a footnote (119:1795): its headline
 * figure is a *flat* rate, and the line under the numbers is what says so. Optional
 * — the Corporate Tax band ends at the figures.
 *
 * There is no heading: the numbers are the section. Figma draws the band 1392px
 * wide inside the 1440px frame rather than at the 1280px content width every other
 * section uses, hence `px-lg` here instead of `lg:px-3xl` — the band is meant to
 * read as one wide strip, not as a panel inset like ci-pricing's.
 */

$d = thinksme_ci_defaults( 'stats' );

if ( empty( $d['cards'] ) ) {
	return;
}

$cards = array();

foreach ( $d['cards'] as $n => $default ) {
	$value = thinksme_field( "ci_stats_card_{$n}_value", false, $default['value'] );

	if ( '' === trim( $value ) ) {
		continue;
	}

	$cards[] = array(
		'icon'  => thinksme_ci_icon_url( thinksme_field( "ci_stats_card_{$n}_icon", false, $default['icon'] ) ),
		'value' => $value,
		'label' => thinksme_field( "ci_stats_card_{$n}_label", false, $default['label'] ),
	);
}

if ( ! $cards ) {
	return;
}
?>
<section id="ci-stats" class="w-full px-lg py-xl lg:py-[40px]">
	<div class="bg-surface-dark rounded-[40px] lg:rounded-[48px] px-lg lg:px-[56px] py-xl lg:py-[78px]">
		<?php // Two per row on a phone rather than four: the values are short, and one long column of four turns a band into a list. ?>
		<ul class="grid grid-cols-2 lg:grid-cols-4 gap-xl lg:gap-md">
			<?php foreach ( $cards as $card ) : ?>
				<li class="flex flex-col items-center gap-md text-center px-md lg:px-lg">
					<span class="bg-brand-yellow rounded-pill size-[56px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[32px]">
					</span>

					<p class="font-medium text-xl lg:text-[32px] leading-snug text-text-on-dark">
						<?php echo esc_html( $card['value'] ); ?>
					</p>

					<?php if ( $card['label'] ) : ?>
						<p class="font-normal text-xs lg:text-sm leading-loose text-text-on-dark">
							<?php echo esc_html( $card['label'] ); ?>
						</p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php $note = thinksme_field( 'ci_stats_note', false, isset( $d['note'] ) ? $d['note'] : '' ); ?>
		<?php if ( $note ) : ?>
			<?php // 12px and 80% opacity, as Figma draws it: it is the small print on the figure above it, not a second line of copy. ?>
			<p class="mt-xl lg:mt-[32px] mx-auto max-w-[567px] text-center font-normal text-[12px] leading-loose text-text-on-dark opacity-80">
				<?php echo esc_html( $note ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>
