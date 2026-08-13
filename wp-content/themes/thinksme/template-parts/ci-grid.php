<?php
/**
 * Accounting & Bookkeeping and Corporate Tax — a grid of icon cards under a
 * heading.
 * Figma: "Switching to Think SME, Without the Hassle" (102:3146), "The Same
 * Firm for Compliance, Digital, and Financing" (102:3726) and "What Every
 * Corporate Tax Filing Needs" (108:4558), file "Untitled"
 * (vzdpOnH1U36oXcFcugiyE5).
 *
 * Those frames are the same component drawn three times: a yellow icon disc, a
 * title and a paragraph on a #f8f8f8 card, laid out in a grid. They differ in
 * alignment (centred vs left), disc size, how many cards there are and how many
 * columns they sit in, and whether one of the cells is a photograph instead of a
 * card — all of which are values, not markup. So this part is called with an
 * `instance` argument rather than duplicated as ci-switching.php and
 * ci-samefirm.php, which is the mistake inc/ci-content.php's header describes for
 * the pages themselves.
 *
 * Usage:
 *   get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'switching' ) );
 *
 * The instance's design content is thinksme_ci_defaults( 'grid' )[ $instance ];
 * a page whose set has no such instance renders nothing, so the part is inert on
 * the three Company Incorporation pages exactly as ci-why-slider.php is.
 *
 * ACF (Accounting & Bookkeeping page): per instance ci_grid_{instance}_hat_text,
 * _heading, and per card (1..6) ci_grid_{instance}_card_N_icon (select, fed at
 * runtime from thinksme_ci_icons()), _title, _text. A card whose title is empty
 * is skipped, the same rule every other ci-* section uses — except the photo
 * cell, which has no title by definition and is keyed off the defaults instead.
 *
 * The grid is six columns wide on lg by default so both Accounting frames fit it
 * without a second layout: the same-firm cards are 405px (2 of 6) and the
 * switching frame's second row is 632px (3 of 6). Each card carries its own span
 * and height, so a row of three 390px cards above a row of two 342px ones is one
 * grid, not two. An instance whose row divides differently — the Corporate Tax
 * frame's four equal cards — names its own track in `grid_class`.
 *
 * That span and height arrive as one whole class string per card
 * (`lg:col-span-2 min-h-[390px]`) rather than numbers interpolated into a class
 * here, because Tailwind builds its stylesheet by scanning source files for
 * literal class names: a class assembled at runtime is never generated. The
 * defaults file is scanned like every other PHP file, so the literals there are
 * what make the utilities exist.
 *
 * The photo cell is Figma's two-layer arrangement again — a photograph cropped
 * into the cell plus a cut-out of it that breaks 22px above the cell's top edge.
 * It ships as one composed export (the export is flattened onto white and this
 * section's ground is white, so nothing is lost) drawn from the bottom of the
 * cell upward, which is what lets the extra height overhang instead of squashing
 * the row.
 */

$instance = isset( $args['instance'] ) ? $args['instance'] : '';
$grid     = thinksme_ci_defaults( 'grid' );

if ( ! $instance || ! isset( $grid[ $instance ] ) ) {
	return;
}

$d      = $grid[ $instance ];
$prefix = "ci_grid_{$instance}";

// Six columns is what the two Accounting & Bookkeeping frames need (cards of 2
// and 3 columns); the Corporate Tax frame draws four equal cards, which six
// columns cannot divide. So the track is a per-instance default too — one whole
// class string, for the same Tailwind reason the cards' spans are.
$grid_class = isset( $d['grid_class'] ) ? $d['grid_class'] : 'lg:grid-cols-6';

$cards = array();

foreach ( $d['cards'] as $n => $default ) {
	// The photo cell carries no copy at all, so it is the one cell the empty-title
	// rule can't decide: it comes from the design's layout, not the client's text.
	if ( ! empty( $default['photo'] ) ) {
		$cards[] = array(
			'photo' => thinksme_ci_image_url( $default['photo'] ),
			'class' => $default['class'],
		);
		continue;
	}

	$title = thinksme_field( "{$prefix}_card_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$cards[] = array(
		'icon'  => thinksme_ci_icon_url( thinksme_field( "{$prefix}_card_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		'text'  => thinksme_field( "{$prefix}_card_{$n}_text", false, $default['text'] ),
		'class' => $default['class'],
	);
}

if ( ! $cards ) {
	return;
}

$centred = 'center' === $d['align'];
?>
<?php // The section's own padding is a per-instance default: the two frames sit at different distances from what precedes them, and "The Same Firm" follows the requirements panel with 160px of air in Figma rather than the 80px two adjacent sections give each other. ?>
<section id="ci-grid-<?php echo esc_attr( $instance ); ?>" class="w-full px-lg lg:px-3xl <?php echo esc_attr( $d['section_class'] ); ?>">
	<div class="flex flex-col items-center gap-md text-center max-w-[1022px] mx-auto">
		<?php $hat = thinksme_field( "{$prefix}_hat_text", false, $d['hat'] ); ?>
		<?php if ( $hat ) : ?>
			<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-text-primary">
			<?php echo esc_html( thinksme_field( "{$prefix}_heading", false, $d['heading'] ) ); ?>
		</h2>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-2 <?php echo esc_attr( $grid_class ); ?> gap-md lg:gap-[15px] mt-xl lg:mt-3xl">
		<?php foreach ( $cards as $card ) : ?>
			<?php if ( isset( $card['photo'] ) ) : ?>
				<?php // From lg the photo is bottom-aligned in its cell so the composed export's extra height hangs above the row, the way Figma draws the cut-out breaking out of the cell. Stacked below lg there is no row to break out of, and taking the image out of the flow there would leave a zero-height cell with the photo lying over the card above it — so it stays in the flow at its own height. ?>
				<div class="relative <?php echo esc_attr( $card['class'] ); ?>">
					<img
						src="<?php echo esc_url( $card['photo'] ); ?>"
						alt=""
						class="w-full h-auto lg:absolute lg:inset-x-0 lg:bottom-0"
					>
				</div>
			<?php else : ?>
				<article class="bg-surface-faint rounded-lg p-lg lg:p-[32px] flex flex-col gap-md <?php echo $centred ? 'items-center text-center justify-center' : 'justify-between'; ?> <?php echo esc_attr( $card['class'] ); ?>">
					<span class="bg-brand-yellow rounded-pill inline-flex items-center justify-center shrink-0 <?php echo $centred ? 'size-[112px]' : 'size-[80px]'; ?>">
						<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="<?php echo $centred ? 'size-[48px]' : 'size-[40px]'; ?>">
					</span>

					<div class="flex flex-col gap-md <?php echo $centred ? 'items-center' : ''; ?>">
						<h3 class="font-medium <?php echo $centred ? 'text-xl' : 'text-lg'; ?> leading-snug text-text-heading-dark">
							<?php echo esc_html( $card['title'] ); ?>
						</h3>

						<?php if ( $card['text'] ) : ?>
							<p class="font-normal text-sm leading-loose text-text-secondary">
								<?php echo esc_html( $card['text'] ); ?>
							</p>
						<?php endif; ?>
					</div>
				</article>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</section>
