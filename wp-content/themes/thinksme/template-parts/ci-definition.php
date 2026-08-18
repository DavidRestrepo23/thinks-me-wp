<?php
/**
 * Property Cashout — "What is Property Cashout in Singapore?": a pale panel with
 * the heading and intro side by side above a worked example and a photograph.
 * Figma: node 951:9052, file "Think SME- INTERNAL" (CgqSxvxd3aQeQSkLPhc48q).
 *
 * ACF (Property Cashout page): ci_definition_hat_text, ci_definition_heading,
 * ci_definition_text, ci_definition_example_heading, ci_definition_example_text,
 * ci_definition_formula, ci_definition_image. The design's copy lives in
 * thinksme_ci_defaults( 'definition' ); a page whose set has no such section
 * renders nothing, so this part is inert on the other six ci-* pages.
 *
 * Three of those fields keep inline markup where every other ci-* field is
 * plain text, because the design bolds runs *inside* sentences — the lead-in
 * ("Property Cashout Singapore"), the three figures in the worked example, and
 * the word "Formula:". They are rendered through wpautop() + wp_kses() with a
 * short allow-list (p/strong/em/b/i/br/a), which also turns a blank line in the
 * textarea into a second paragraph. That is what lets the intro be Figma's two
 * paragraphs from one field. Everything else on the page stays esc_html().
 *
 * The four blocks are placed rather than stacked: the heading and the intro share
 * the top row, and the worked example and the photograph share the bottom one,
 * both bottom-aligned — which is how the design lands the example's callout level
 * with the photo's lower edge. DOM order is the reading order (heading, intro,
 * example, photo), so below lg the grid simply collapses into that.
 *
 * The photograph is Figma's two-layer arrangement again (a rounded card plus a
 * cut-out of the same shot breaking above its top edge), exported flattened. Here
 * it is flattened onto #fbfbfb rather than white, because Figma flattens onto
 * whatever actually sits behind the node and behind this one is the panel — so
 * the export is pixel-exact against `--color-surface-panel` and would show a seam
 * on any other ground. A client upload is a plain photograph, so it takes the
 * card alone.
 */

$d = thinksme_ci_defaults( 'definition' );

if ( ! $d ) {
	return;
}

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$photo     = thinksme_field( 'ci_definition_image' );
$is_stock  = empty( $photo['url'] );
$photo_url = ! $is_stock ? $photo['url'] : thinksme_ci_image_url( $d['image'] );
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

$text            = thinksme_field( 'ci_definition_text', false, $d['text'] );
$example_heading = thinksme_field( 'ci_definition_example_heading', false, $d['example_heading'] );
$example_text    = thinksme_field( 'ci_definition_example_text', false, $d['example_text'] );
$formula         = thinksme_field( 'ci_definition_formula', false, $d['formula'] );

// The runs the design actually uses, plus the two an editor reaches for next.
// `p` has to be here even though no field contains one: wpautop() is what adds
// them, and kses strips any tag the list doesn't name — including the paragraphs
// the blank line was supposed to produce.
$inline_tags = array(
	'p'      => array(),
	'strong' => array(),
	'b'      => array(),
	'em'     => array(),
	'i'      => array(),
	'br'     => array(),
	'a'      => array(
		'href'   => true,
		'title'  => true,
		'target' => true,
		'rel'    => true,
	),
);
?>
<section id="ci-definition" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="bg-surface-panel rounded-[40px] lg:rounded-[80px] px-lg lg:px-[56px] py-2xl lg:py-[96px]">
		<?php // Two columns in Figma's own 582:650 proportion with its 48px gutter, and two rows: the second is `1fr` so both of its cells can sit on the panel's baseline. ?>
		<div class="flex flex-col gap-xl lg:grid lg:grid-cols-[582fr_650fr] lg:grid-rows-[auto_1fr] lg:gap-x-[48px] lg:gap-y-xl">
			<div class="flex flex-col items-start gap-md lg:col-start-1 lg:row-start-1 lg:self-start">
				<?php $hat = thinksme_field( 'ci_definition_hat_text', false, $d['hat'] ); ?>
				<?php if ( $hat ) : ?>
					<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
						<?php echo esc_html( $hat ); ?>
					</span>
				<?php endif; ?>

				<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary">
					<?php echo esc_html( thinksme_field( 'ci_definition_heading', false, $d['heading'] ) ); ?>
				</h2>
			</div>

			<?php if ( $text ) : ?>
				<div class="entry-copy font-normal text-sm leading-loose text-text-secondary lg:col-start-2 lg:row-start-1 lg:self-start">
					<?php echo wp_kses( wpautop( $text ), $inline_tags ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $example_heading || $example_text || $formula ) : ?>
				<div class="flex flex-col gap-lg max-w-[547px] lg:col-start-1 lg:row-start-2 lg:self-end">
					<?php if ( $example_heading ) : ?>
						<h3 class="font-medium text-lg leading-normal text-text-primary">
							<?php echo esc_html( $example_heading ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( $example_text ) : ?>
						<div class="entry-copy font-normal text-sm leading-loose text-text-secondary">
							<?php echo wp_kses( wpautop( $example_text ), $inline_tags ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $formula ) : ?>
						<?php // The pale-yellow callout (951:9067): a 24px info glyph beside the sum. Decorative — the sentence names itself "Formula:" — so it is aria-hidden and the icon is a default rather than a field. ?>
						<div class="flex items-start gap-md bg-surface-yellow-pale rounded-sm p-md">
							<img src="<?php echo esc_url( "$icons_uri/pc/info.svg" ); ?>" alt="" aria-hidden="true" class="size-[24px] shrink-0 mt-[1px]">
							<div class="entry-copy font-normal text-sm leading-loose text-text-primary">
								<?php echo wp_kses( wpautop( $formula ), $inline_tags ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="w-full max-w-[650px] mx-auto lg:mx-0 lg:col-start-2 lg:row-start-2 lg:self-end">
				<div class="relative w-full <?php echo esc_attr( $d['image_box'] ); ?>">
					<?php if ( $is_stock ) : ?>
						<?php // The export carries the card, its corners and the cut-out above them, so it is drawn straight into the box. ?>
						<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" loading="lazy" class="absolute inset-0 w-full h-full object-contain">
					<?php else : ?>
						<?php // An upload is a plain photograph: it gets the card's own slot in the box (Figma's 650x430 at y=60) and nothing overhangs it. ?>
						<div class="absolute left-0 top-[12.24%] w-full h-[87.76%] overflow-hidden rounded-xl">
							<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
