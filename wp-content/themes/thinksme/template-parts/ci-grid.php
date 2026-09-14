<?php
/**
 * Accounting & Bookkeeping, Corporate Tax and Business Loan — a grid of icon
 * cards under a heading.
 * Figma: "Switching to Think SME, Without the Hassle" (102:3146), "The Same
 * Firm for Compliance, Digital, and Financing" (102:3726), "What Every
 * Corporate Tax Filing Needs" (108:4558) and "What Actually Determines Loan
 * Approval" (119:1871), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Those frames are the same component drawn four times: a yellow icon disc, a
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
 * ACF (Accounting & Bookkeeping, Corporate Tax and Business Loan pages): per
 * instance ci_grid_{instance}_hat_text, _heading, _text (the Business Loan
 * frame's intro line), and per card (1..6) ci_grid_{instance}_card_N_icon (select, fed at
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
 * A card can also carry a figure between its title and its copy (`value`, the
 * Mortgage frame's rates), and an instance on a navy panel names its own
 * `heading_class` / `hat_class` — a colour inherited from a wrapper loses to the
 * hard `color` base.css sets on h1..h6, so the heading has to carry it itself.
 *
 * The PSG Grant frame draws this component twice more and needed six further class
 * strings, every one of which defaults to what the instances before it drew:
 * `heading_size_class` (its benefits band sets 48px, between the shared 40/64
 * steps), `header_class` (that band's heading is measured in a 552px box, not
 * 1022), `card_pad_class` (its columns have no card behind them, so they have no
 * padding either), `disc_class` / `icon_class` (56/32 against the centred 112/48)
 * and `title_class` / `text_class` (28px white and white, against 24px and #505050).
 * Values, not a second layout — the same call every other knob here made.
 *
 * An instance can sit inside a panel rather than on the page: `panel_class` wraps
 * the whole thing (the Remittance frame's pale rounded block, 123:2715) and
 * `card_class` is what its cards are filled with, white there against #f8f8f8
 * everywhere else. Both are values, so the panel is not a second layout.
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

	// The About page's "Values" card writes three bold-led bullets instead of a
	// plain paragraph (157:538). ACF free has no Repeater, so this is three flat
	// bold/text pairs like every other fixed-length list in the theme; a card
	// whose default carries no `items` renders none, which is every other card.
	$items = array();
	foreach ( ( isset( $default['items'] ) ? $default['items'] : array() ) as $item_index => $item_default ) {
		$item_n    = $item_index + 1;
		$item_text = thinksme_field( "{$prefix}_card_{$n}_item_{$item_n}_text", false, $item_default['text'] );

		if ( '' === trim( $item_text ) ) {
			continue;
		}

		$items[] = array(
			'bold' => thinksme_field( "{$prefix}_card_{$n}_item_{$item_n}_bold", false, $item_default['bold'] ),
			'text' => $item_text,
		);
	}

	$cards[] = array(
		'icon'  => thinksme_ci_icon_url( thinksme_field( "{$prefix}_card_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		// The Mortgage frame's fixed-or-floating pair puts a rate between the card's
		// title and its copy (124:3388, 124:3397). Absent from every other instance, so
		// an empty value renders nothing.
		'value' => thinksme_field( "{$prefix}_card_{$n}_value", false, isset( $default['value'] ) ? $default['value'] : '' ),
		'text'  => thinksme_field( "{$prefix}_card_{$n}_text", false, $default['text'] ),
		'items' => $items,
		// The About page's "Explore How We Can Help" panel closes each card on its
		// own "Learn More" link (157:872 etc.) rather than one button for the whole
		// section — a value per card, absent from every other instance.
		'button_text' => thinksme_field( "{$prefix}_card_{$n}_button_text", false, isset( $default['button_text'] ) ? $default['button_text'] : '' ),
		'button_link' => thinksme_field( "{$prefix}_card_{$n}_button_link", false, isset( $default['button_link'] ) ? $default['button_link'] : '' ),
		'class' => $default['class'],
	);
}

if ( ! $cards ) {
	return;
}

$centred = 'center' === $d['align'];

// The Remittance frame draws this grid inside a pale panel with white cards
// (123:2715), where every other instance sits on white with #f8f8f8 cards. Both
// are one class string per instance, for the Tailwind reason the cards' spans are:
// a class assembled at runtime is never generated.
$panel_class = isset( $d['panel_class'] ) ? $d['panel_class'] : '';
$card_class  = isset( $d['card_class'] ) ? $d['card_class'] : 'bg-surface-faint';

// The card's own radius and padding, separate from its fill because the PSG Grant
// frame's benefits band (127:537) draws four columns *without* a card behind them —
// on that navy panel a card is a transparent column, and 32px of padding inside a
// fill nobody can see only narrows the copy. Every other instance takes the
// original pair.
$card_pad_class = isset( $d['card_pad_class'] ) ? $d['card_pad_class'] : 'rounded-lg p-lg lg:p-[32px]';

// On a navy panel the header block has to invert. base.css sets a hard colour on
// h1..h6, so the heading needs the class on the element itself — the note in
// template-parts/ci-tools.php's history. The hat's own fill changes too: the pale
// yellow pill disappears against navy, where Figma draws a white one.
$heading_class = isset( $d['heading_class'] ) ? $d['heading_class'] : 'text-text-primary';
$hat_class     = isset( $d['hat_class'] ) ? $d['hat_class'] : 'bg-brand-yellow-soft/35 border-brand-yellow-border text-text-primary';

// Four more per-instance class strings, all defaulting to what every instance drew
// before them, for the PSG Grant frame's two new bands. Its benefits panel
// (127:537) sets its heading at 48px between the 40px and 64px steps of the shared
// scale, and its columns carry a 56px disc, a 28px title and white copy where the
// centred instances draw 112px, 24px and #505050. Its features grid (127:534 +
// 127:934) is the shared centred card again but with 20px titles. Values, not a
// second layout — and one whole class string each, for the Tailwind reason the
// cards' spans are.
$heading_size_class = isset( $d['heading_size_class'] ) ? $d['heading_size_class'] : 'text-2xl lg:text-3xl';
$header_class       = isset( $d['header_class'] ) ? $d['header_class'] : 'max-w-[1022px]';
$disc_class         = isset( $d['disc_class'] ) ? $d['disc_class'] : ( $centred ? 'size-[112px]' : 'size-[80px]' );
$icon_class         = isset( $d['icon_class'] ) ? $d['icon_class'] : ( $centred ? 'size-[48px]' : 'size-[40px]' );
$title_class        = isset( $d['title_class'] ) ? $d['title_class'] : ( $centred ? 'text-xl text-text-heading-dark' : 'text-lg text-text-heading-dark' );
// How the card distributes its own height. The centred instances centre it, which is
// right when every card's copy runs to the same number of lines and wrong when one
// title wraps further than its neighbours' — there the discs stop lining up across
// the row, which is what the PSG Grant frame draws them doing (top-aligned, 127:539
// and 127:829). A value, so the instances that centre keep centring.
$card_justify_class = isset( $d['card_justify_class'] ) ? $d['card_justify_class'] : ( $centred ? 'justify-center' : 'justify-between' );
$text_class         = isset( $d['text_class'] ) ? $d['text_class'] : 'text-text-secondary';
// The air between a card's disc, its title and its copy. The About page's "What
// Drive Us" panel sets 32px (157:518) where every earlier instance draws 16px —
// its cards are a third of the panel wide and run to a paragraph or more, so the
// tighter rhythm reads as cramped there. A value, so the instances before it are
// untouched.
$card_gap_class     = isset( $d['card_gap_class'] ) ? $d['card_gap_class'] : 'gap-md';
// The gap between the title and the copy under it, which the About page's
// "Explore How We Can Help" cards set tighter than the gap above them (18px
// against 32px, 157:869 inside 157:868). Defaults to the card's own gap, so
// every instance that wants one rhythm throughout names only `card_gap_class`.
$card_text_gap_class = isset( $d['card_text_gap_class'] ) ? $d['card_text_gap_class'] : $card_gap_class;
// The track's own gutter. Figma draws 15px on the Accounting frames this part was
// built for and 32px on the About page's "Explore" panel (157:860) — and that is
// not cosmetic: it sets the card width, which is what decides whether each title
// wraps to the two lines the design draws.
$gap_class          = isset( $d['gap_class'] ) ? $d['gap_class'] : 'gap-md lg:gap-[15px]';
?>
<?php // The section's own padding is a per-instance default: the two frames sit at different distances from what precedes them, and "The Same Firm" follows the requirements panel with 160px of air in Figma rather than the 80px two adjacent sections give each other. ?>
<section id="ci-grid-<?php echo esc_attr( $instance ); ?>" class="w-full px-lg lg:px-3xl <?php echo esc_attr( $d['section_class'] ); ?>">
	<?php if ( $panel_class ) : ?>
	<div class="<?php echo esc_attr( $panel_class ); ?>">
	<?php endif; ?>

	<div class="flex flex-col items-center gap-md text-center <?php echo esc_attr( $header_class ); ?> mx-auto">
		<?php $hat = thinksme_field( "{$prefix}_hat_text", false, $d['hat'] ); ?>
		<?php if ( $hat ) : ?>
			<span class="<?php echo esc_attr( $hat_class ); ?> border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium <?php echo esc_attr( $heading_size_class ); ?> leading-tight tracking-hero <?php echo esc_attr( $heading_class ); ?>">
			<?php echo esc_html( thinksme_field( "{$prefix}_heading", false, $d['heading'] ) ); ?>
		</h2>

		<?php // The Business Loan frame writes a line under the heading (119:1876); the three Accounting and Corporate Tax instances write none, so an instance without one renders nothing rather than holding a gap open. ?>
		<?php $intro = thinksme_field( "{$prefix}_text", false, isset( $d['text'] ) ? $d['text'] : '' ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm leading-loose <?php echo 'text-text-primary' === $heading_class ? 'text-text-secondary' : 'text-text-on-dark'; ?> max-w-[931px]">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-2 <?php echo esc_attr( $grid_class ); ?> <?php echo esc_attr( $gap_class ); ?> mt-xl lg:mt-3xl">
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
				<article class="<?php echo esc_attr( $card_class ); ?> <?php echo esc_attr( $card_pad_class ); ?> flex flex-col <?php echo esc_attr( $card_gap_class ); ?> <?php echo $centred ? 'items-center text-center' : ''; ?> <?php echo esc_attr( $card_justify_class ); ?> <?php echo esc_attr( $card['class'] ); ?>">
					<span class="bg-brand-yellow rounded-pill inline-flex items-center justify-center shrink-0 <?php echo esc_attr( $disc_class ); ?>">
						<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="<?php echo esc_attr( $icon_class ); ?>">
					</span>

					<div class="flex flex-col <?php echo esc_attr( $card_text_gap_class ); ?> <?php echo $centred ? 'items-center' : ''; ?>">
						<h3 class="font-medium <?php echo esc_attr( $title_class ); ?> leading-snug">
							<?php echo esc_html( $card['title'] ); ?>
						</h3>

						<?php if ( ! empty( $card['value'] ) ) : ?>
							<p class="font-medium text-[32px] leading-tight tracking-hero text-text-primary">
								<?php echo esc_html( $card['value'] ); ?>
							</p>
						<?php endif; ?>

						<?php if ( $card['text'] ) : ?>
							<p class="font-normal text-sm leading-loose <?php echo esc_attr( $text_class ); ?>">
								<?php echo esc_html( $card['text'] ); ?>
							</p>
						<?php endif; ?>

						<?php if ( ! empty( $card['items'] ) ) : ?>
							<ul class="flex flex-col gap-lg <?php echo $centred ? 'text-center' : ''; ?>">
								<?php foreach ( $card['items'] as $item ) : ?>
									<li class="font-normal text-sm leading-loose <?php echo esc_attr( $text_class ); ?>">
										<?php if ( $item['bold'] ) : ?>
											<span class="font-bold"><?php echo esc_html( $item['bold'] ); ?></span>
										<?php endif; ?>
										<?php echo esc_html( $item['text'] ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>

					<?php // The About page's "Explore How We Can Help" panel closes each card on its own link; absent everywhere else, so this renders nothing on every other instance. ?>
					<?php if ( ! empty( $card['button_text'] ) ) : ?>
						<a href="<?php echo esc_url( $card['button_link'] ); ?>" class="btn-split inline-flex items-center mt-auto">
							<span class="bg-white rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
								<?php echo esc_html( $card['button_text'] ); ?>
							</span>
							<span class="bg-white rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/arrow-up-right-dark.svg' ); ?>" alt="" class="size-[24px]">
							</span>
						</a>
					<?php endif; ?>
				</article>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>

	<?php
	// The Mortgage frame repeats its rates disclaimer under the fixed-or-floating pair
	// (124:3604) — the same line the figures band above already carries. It ships as
	// the design writes it: the two sections are read minutes apart, and a rate note
	// under the rates is not a duplication artefact the way a stray heading is.
	$note = thinksme_field( "{$prefix}_note", false, isset( $d['note'] ) ? $d['note'] : '' );
	?>
	<?php if ( $note ) : ?>
		<p class="mt-lg lg:mt-xl mx-auto max-w-[722px] text-center font-normal text-xs leading-loose <?php echo 'text-text-primary' === $heading_class ? 'text-text-primary' : 'text-text-on-dark'; ?>">
			<?php echo esc_html( $note ); ?>
		</p>
	<?php endif; ?>

	<?php if ( $panel_class ) : ?>
	</div>
	<?php endif; ?>
</section>
