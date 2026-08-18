<?php
/**
 * Property Cashout — "Property Cashout vs Traditional Mortgage Refinancing": a
 * centred heading over a two-column comparison table.
 * Figma: nodes 951:9075 (the heading), 951:9076 (the circled mark) and 951:9077
 * (the table), file "Think SME- INTERNAL" (CgqSxvxd3aQeQSkLPhc48q).
 *
 * ACF (Property Cashout page): ci_compare_heading, ci_compare_col_1_label,
 * ci_compare_col_2_label and, per row (1..8), ci_compare_row_N_label, _text_1,
 * _text_2. The design's copy lives in thinksme_ci_defaults( 'compare' ); a page
 * whose set has no such section renders nothing, so this part is inert on the
 * other six ci-* pages.
 *
 * **The row label is one field, not two.** Figma prints the same eight labels down
 * both columns — "Purpose", "Cash Received", … — so storing them twice would be
 * eight extra fields whose only job is to be kept identical, and the first time
 * they drifted the table would stop reading as a comparison. Only the two
 * descriptions differ, which is what the design is actually saying.
 *
 * A row is skipped when its label is empty, so a shorter table is a matter of
 * clearing fields from the bottom rather than of editing this file.
 *
 * Layout is one CSS grid, not a <table>: below lg the two columns have to become
 * one stack per row and a table cannot reflow that way without losing its
 * semantics anyway. Each row is a `.ci-compare__row` that is `display: contents`
 * from lg — so its two cells become grid items of the table itself and line up
 * across rows — and a bordered card below it. The column headers only exist from
 * lg; below it each cell carries its own tag naming the column, because a header
 * eight rows above is no use on a phone.
 *
 * The badges say which side of the comparison a cell is on, so they are the
 * section's own art rather than fields: a green check on ours, an amber warning on
 * theirs. Both are aria-hidden — the column label already says it in words.
 *
 * The hand-drawn circle over the heading is desktop-only at a fixed offset, the
 * same constraint the hero's brush stroke documents: Figma measures it against
 * this heading breaking across three lines, so a much shorter one will re-wrap and
 * put the circle in the wrong place.
 */

$d = thinksme_ci_defaults( 'compare' );

if ( ! $d ) {
	return;
}

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$col_1 = thinksme_field( 'ci_compare_col_1_label', false, $d['col_1_label'] );
$col_2 = thinksme_field( 'ci_compare_col_2_label', false, $d['col_2_label'] );

$rows = array();

foreach ( $d['rows'] as $n => $default ) {
	$label = thinksme_field( "ci_compare_row_{$n}_label", false, $default['label'] );

	if ( '' === trim( $label ) ) {
		continue;
	}

	$rows[] = array(
		'label'  => $label,
		'text_1' => thinksme_field( "ci_compare_row_{$n}_text_1", false, $default['text_1'] ),
		'text_2' => thinksme_field( "ci_compare_row_{$n}_text_2", false, $default['text_2'] ),
	);
}

if ( ! $rows ) {
	return;
}
?>
<section id="ci-compare" class="flex flex-col items-center gap-xl lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="relative w-full max-w-[829px]">
		<?php // Positioned as a share of the heading block so it holds while the block flexes; hidden below lg, where the smaller type rewraps and the circle would land on the wrong word. ?>
		<img
			src="<?php echo esc_url( "$icons_uri/pc/compare-mark.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="hidden lg:block absolute left-[81.7%] top-[-12px] w-[12.8%] pointer-events-none select-none"
		>

		<h2 class="relative font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary text-center">
			<?php echo esc_html( thinksme_field( 'ci_compare_heading', false, $d['heading'] ) ); ?>
		</h2>
	</div>

	<div class="ci-compare w-full max-w-[1062px]">
		<div class="ci-compare__head" data-tone="ours"><?php echo esc_html( $col_1 ); ?></div>
		<div class="ci-compare__head" data-tone="theirs"><?php echo esc_html( $col_2 ); ?></div>

		<?php foreach ( $rows as $row ) : ?>
			<div class="ci-compare__row">
				<div class="ci-compare__cell" data-tone="ours">
					<span class="ci-compare__badge" data-tone="ours" aria-hidden="true">
						<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[16px]">
					</span>

					<div class="ci-compare__body">
						<span class="ci-compare__tag"><?php echo esc_html( $col_1 ); ?></span>
						<p class="font-bold text-xs leading-normal text-text-primary"><?php echo esc_html( $row['label'] ); ?></p>
						<?php if ( $row['text_1'] ) : ?>
							<p class="font-normal text-xs leading-loose text-text-secondary"><?php echo esc_html( $row['text_1'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<div class="ci-compare__cell" data-tone="theirs">
					<span class="ci-compare__badge" data-tone="theirs" aria-hidden="true">
						<img src="<?php echo esc_url( "$icons_uri/pc/warning.svg" ); ?>" alt="" class="size-[16px]">
					</span>

					<div class="ci-compare__body">
						<span class="ci-compare__tag"><?php echo esc_html( $col_2 ); ?></span>
						<p class="font-bold text-xs leading-normal text-text-primary"><?php echo esc_html( $row['label'] ); ?></p>
						<?php if ( $row['text_2'] ) : ?>
							<p class="font-normal text-xs leading-loose text-text-secondary"><?php echo esc_html( $row['text_2'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
