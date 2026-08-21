<?php
/**
 * PSG Grant and MRA Grant — a column of green-ticked criteria beside a
 * photograph. One part rendering four Figma frames:
 *
 *   'criteria'   — "Am I Eligible, and How Do I Apply?" (127:580 + the photo group
 *                  127:618), copy on the left, photograph on the right, the list
 *                  under its own sub-heading ("PSG Grant Criteria").
 *   'invoicenow' — "InvoiceNow" (127:901 + the photo group 127:622), the same shape
 *                  mirrored: photograph on the left, copy on the right, an intro
 *                  paragraph instead of the sub-heading, and a row of partner marks
 *                  under the list.
 *
 *   'covers'     — "What covers in MRA Grant in Singapore?" (130:1520 + the photo
 *                  group 130:1515), photograph on the left, three ticked items and
 *                  a button under them, no hat and no sub-heading.
 *   'expect'     — "What You Can Expect" (130:1798 + 130:1793), the same shape with
 *                  an intro paragraph and four items.
 *
 * File "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Called with array( 'instance' => 'criteria' | 'invoicenow' | 'covers' | 'expect' )
 * and reads
 * thinksme_ci_defaults( 'criteria' )[ $instance ] — the same arrangement
 * template-parts/ci-grid.php and ci-stack.php use for their frames. The two differ
 * in their copy, their photograph, which side it takes and which of the two optional
 * blocks they carry; everything else is identical, which is why this is one part
 * called twice rather than two near-copies.
 *
 * ACF (PSG Grant and MRA Grant pages), per instance:
 * ci_criteria_<instance>_hat_text, _heading, _text, _list_title, _image,
 * _item_1..7 and — on the MRA frames — _button_text / _button_link. The design's copy lives in
 * thinksme_ci_defaults( 'criteria' ); a page whose set has no such section renders
 * nothing, so this part is inert on the other ten ci-* pages exactly as
 * ci-definition.php and ci-signup.php are.
 *
 * An item with no text is skipped — the same rule every other ci-* section uses — so
 * running three criteria instead of five is a matter of clearing fields.
 *
 * The tick is Figma's rounded square at a 40px box (127:589), the same badge
 * ci-eligibility.php draws one size down; it is the section's own art rather than a
 * field, because it says the item is a criterion rather than carrying content.
 *
 * `logos` is a per-set default and not an image field: those marks are the
 * authorities the claim rests on (IMDA, InvoiceNow, IRAS), the same reason the
 * Business Loan hero's partner banks and the PSG figure discs are theme files. A set
 * that names none renders no row.
 *
 * DOM order is always copy-then-photograph, and `photo` only moves the photograph
 * with `lg:order-*` — so below lg both instances read heading, copy, list, photo
 * regardless of which side the design puts the picture on, and neither needs a
 * mobile spec.
 *
 * The photographs are Figma's two-layer arrangement (a rounded card plus a cut-out
 * of the same shot breaking above its top edge), exported flattened onto white —
 * which is this section's own ground, so nothing is lost. A client upload is a plain
 * photo and lands in the same box; it is drawn `object-cover` there rather than
 * `object-contain`, since it carries no shape of its own.
 */

$instance = isset( $args['instance'] ) ? $args['instance'] : '';
$sets     = thinksme_ci_defaults( 'criteria' );

if ( ! $instance || ! isset( $sets[ $instance ] ) ) {
	return;
}

$d         = $sets[ $instance ];
$prefix    = "ci_criteria_{$instance}";
$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$items = array();

foreach ( $d['items'] as $n => $item_default ) {
	$item = thinksme_field( "{$prefix}_item_{$n}", false, $item_default );

	if ( '' !== trim( $item ) ) {
		$items[] = $item;
	}
}

$photo     = thinksme_field( "{$prefix}_image" );
$is_stock  = empty( $photo['url'] );
$photo_url = ! $is_stock ? $photo['url'] : thinksme_ci_image_url( $d['image'] );
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

$heading = thinksme_field( "{$prefix}_heading", false, $d['heading'] );
$logos   = isset( $d['logos'] ) ? $d['logos'] : array();

// The MRA Grant frame closes both of its instances on a button (130:1541,
// 130:1827) where the two PSG ones close on the list or on the partner marks.
// Optional, so an instance that names none renders nothing here.
$button_text = thinksme_field( "{$prefix}_button_text", false, isset( $d['button_text'] ) ? $d['button_text'] : '' );
$button_link = thinksme_field( "{$prefix}_button_link", false, isset( $d['button_link'] ) ? $d['button_link'] : '' );
?>
<section id="ci-criteria-<?php echo esc_attr( $instance ); ?>" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col lg:grid lg:grid-cols-2 lg:items-center gap-2xl lg:gap-3xl">
		<div class="flex flex-col gap-xl lg:gap-[56px] <?php echo 'left' === $d['photo'] ? 'lg:order-2' : ''; ?>">
			<div class="flex flex-col items-start gap-md">
				<?php $hat = thinksme_field( "{$prefix}_hat_text", false, $d['hat'] ); ?>
				<?php if ( $hat ) : ?>
					<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
						<?php echo esc_html( $hat ); ?>
					</span>
				<?php endif; ?>

				<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-text-primary">
					<?php echo esc_html( $heading ); ?>
				</h2>

				<?php // The InvoiceNow frame explains what the service is under its heading (127:907); the criteria one goes straight to its list, so an instance without an intro renders nothing rather than holding a gap open. ?>
				<?php $intro = thinksme_field( "{$prefix}_text", false, isset( $d['text'] ) ? $d['text'] : '' ); ?>
				<?php if ( $intro ) : ?>
					<p class="font-normal text-sm leading-loose text-text-secondary max-w-[560px]">
						<?php echo esc_html( $intro ); ?>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( $items ) : ?>
				<div class="flex flex-col gap-lg lg:gap-[40px]">
					<?php // The criteria frame titles its list (127:586); the InvoiceNow one lets the heading above stand for it. ?>
					<?php $list_title = thinksme_field( "{$prefix}_list_title", false, isset( $d['list_title'] ) ? $d['list_title'] : '' ); ?>
					<?php if ( $list_title ) : ?>
						<h3 class="font-medium text-lg lg:text-xl leading-normal text-text-primary">
							<?php echo esc_html( $list_title ); ?>
						</h3>
					<?php endif; ?>

					<ul class="flex flex-col gap-md">
						<?php foreach ( $items as $item ) : ?>
							<li class="flex items-start gap-md">
								<span class="bg-accent-green rounded-[13px] size-[40px] inline-flex items-center justify-center shrink-0" aria-hidden="true">
									<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[19px]">
								</span>
								<span class="font-normal text-sm leading-loose text-text-secondary pt-[8px]">
									<?php echo esc_html( $item ); ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $logos ) : ?>
				<?php // A row, not a marquee: Figma draws three marks at their own widths with room to spare, and a carousel of three would move a claim rather than state it. ?>
				<div class="flex flex-wrap items-center gap-lg lg:gap-[40px]">
					<?php foreach ( $logos as $logo ) : ?>
						<img
							src="<?php echo esc_url( thinksme_ci_image_url( $logo['file'] ) ); ?>"
							alt="<?php echo esc_attr( $logo['name'] ); ?>"
							loading="lazy"
							class="<?php echo esc_attr( $logo['class'] ); ?> w-auto max-w-full object-contain"
						>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $button_text ) : ?>
				<?php // The same split button the hero draws, so it reads as the section's action rather than a second style of button. ?>
				<a href="<?php echo esc_url( $button_link ); ?>" class="btn-split flex sm:inline-flex sm:self-start items-center w-full sm:w-auto">
					<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap grow sm:grow-0">
						<?php echo esc_html( $button_text ); ?>
					</span>
					<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
					</span>
				</a>
			<?php endif; ?>
		</div>

		<?php // Explicitly the other column, so a set whose items are all cleared still leaves the photograph where the design puts it rather than letting it slide into column 1. ?>
		<div class="w-full max-w-[547px] mx-auto lg:mx-0 <?php echo 'left' === $d['photo'] ? 'lg:order-1 lg:col-start-1' : 'lg:col-start-2'; ?> <?php echo esc_attr( $d['image_box'] ); ?>">
			<img
				src="<?php echo esc_url( $photo_url ); ?>"
				alt="<?php echo esc_attr( $photo_alt ); ?>"
				loading="lazy"
				class="w-full h-full <?php echo $is_stock ? 'object-contain' : 'object-cover rounded-lg'; ?>"
			>
		</div>
	</div>
</section>
