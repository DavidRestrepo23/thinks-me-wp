<?php
/**
 * Company Incorporation, Local and Foreign — what's included in every incorporation: a
 * centred heading over a photo on the left and four cards on the right.
 * Figma: node 85:1697, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Shared verbatim by page-company-incorporation-local.php and
 * page-company-incorporation-foreign.php: same markup, same ACF field names,
 * different words and photographs. Every default below comes from
 * thinksme_ci_defaults() in inc/ci-content.php, which is the one place the two
 * pages differ — change the design's copy there, not here.
 *
 * ACF (both Company Incorporation pages): ci_includes_heading,
 * ci_includes_image, and per card (1..4) ci_includes_card_N_icon (select, fed at
 * runtime from thinksme_ci_icons()), _title, _text. The Accounting & Bookkeeping
 * page draws the same section with a fuller header — ci_includes_hat_text,
 * ci_includes_text and a centred ci_includes_button_text / _link under it
 * (Figma 102:4133) — and each of those renders only when it has content, so the
 * three Company Incorporation pages, whose defaults leave them empty, are
 * unchanged.
 *
 * The section is 85:1697, not 85:1703 — that node is only the card column, and
 * building from it alone drops the heading and the whole photo column.
 *
 * Figma stacks two photo layers here (85:1700 and 85:1702). Only the lower one
 * is actually visible: it occupies exactly the rounded card you see, and the
 * upper layer sits entirely behind it. It is not carried over — reproducing a
 * layer the design never shows would mean shipping a second 21 MB export for no
 * pixels.
 *
 * The crop offsets are Figma's, measured against that exported photo, so they
 * mean nothing for another image — uploading a custom one switches the card to a
 * plain object-cover. Same `$is_stock` arrangement as roa-hero.php.
 *
 * The two pages disagree about the *shape* of this slot, not just its photo, so
 * the box and the image's own classes come from inc/ci-content.php too:
 *
 * - Local is a plain rounded card. The box is the card (576x486), it clips, and
 *   the photo is cropped into it.
 * - Foreign is the two-layer arrangement ci-hero.php and roa-hero.php also use:
 *   the subject breaks above the card's top edge, so the design's asset is the
 *   whole 576x528 group with its corners and transparency already in it. The box
 *   is that group, it does not clip, and the image is drawn object-contain —
 *   cropping it into the card would cut off the very overhang it exists for.
 *
 * That is also why Foreign's upload branch is object-contain rather than
 * object-cover: on this page the field's content is a composed export, not a
 * bare photograph, and a client replacing it will be replacing that.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$d = thinksme_ci_defaults( 'includes' );

$photo       = thinksme_field( 'ci_includes_image' );
$is_stock    = empty( $photo['url'] );
$photo_url   = ! $is_stock ? $photo['url'] : thinksme_ci_image_url( $d['image'] );
$photo_class = $is_stock ? $d['image_class'] : $d['image_upload_class'];
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

$defaults = $d['cards'];

$cards = array();

foreach ( $defaults as $n => $default ) {
	$title = thinksme_field( "ci_includes_card_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$cards[] = array(
		'icon'  => thinksme_ci_icon_url( thinksme_field( "ci_includes_card_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		'text'  => thinksme_field( "ci_includes_card_{$n}_text", false, $default['text'] ),
	);
}

if ( ! $cards ) {
	return;
}
?>
<section id="ci-includes" class="flex flex-col items-center gap-[56px] lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<?php
	$hat         = thinksme_field( 'ci_includes_hat_text', false, $d['hat'] );
	$heading     = thinksme_field( 'ci_includes_heading', false, $d['heading'] );
	$intro       = thinksme_field( 'ci_includes_text', false, $d['text'] );
	$button_text = thinksme_field( 'ci_includes_button_text', false, $d['button_text'] );
	$button_link = thinksme_field( 'ci_includes_button_link', false, $d['button_link'] );
	?>
	<?php // The Corporate Secretary frame (102:2643) has no heading over this section — the cards and the photo are the whole of it — so an empty heading renders nothing rather than an empty <h2> holding the section's gap open. The hat, intro and button are the Accounting & Bookkeeping frame's, and go the same way on the pages that don't have them. ?>
	<?php if ( $hat || $heading || $intro || $button_text ) : ?>
		<div class="flex flex-col items-center gap-md text-center max-w-[753px]">
			<?php if ( $hat ) : ?>
				<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $hat ); ?>
				</span>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-text-primary max-w-[624px]">
					<?php echo esc_html( $heading ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( $intro ) : ?>
				<p class="font-normal text-sm leading-loose text-text-secondary max-w-[560px]">
					<?php echo esc_html( $intro ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $button_text ) : ?>
				<a href="<?php echo esc_url( $button_link ); ?>" class="btn-split inline-flex items-center mt-md">
					<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
						<?php echo esc_html( $button_text ); ?>
					</span>
					<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
					</span>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php // Figma's 576 / 624 columns, held at their own widths and pushed apart, rather than stretched: the photo is fixed-ratio and growing it into a 1920px viewport would tower over the cards beside it. ?>
	<?php // flex-col-reverse below lg: the mobile design (101:18) puts the photo last, under the cards, while desktop reads photo-first left-to-right — same arrangement roa-plan.php uses. ?>
	<div class="flex flex-col-reverse lg:flex-row lg:items-center lg:justify-between gap-[56px] lg:gap-xl xl:gap-[96px] w-full">
		<?php // Both the box and the image's own classes come from the defaults — the two pages draw this slot as different shapes; see the note at the top. ?>
		<div class="w-full max-w-[520px] mx-auto lg:max-w-none lg:mx-0 lg:basis-[576px] lg:min-w-0 <?php echo esc_attr( $d['image_box'] ); ?>">
			<img
				src="<?php echo esc_url( $photo_url ); ?>"
				alt="<?php echo esc_attr( $photo_alt ); ?>"
				class="<?php echo esc_attr( $photo_class ); ?>"
			>
		</div>

		<ul class="flex flex-col gap-md w-full lg:basis-[624px] lg:min-w-0">
			<?php foreach ( $cards as $card ) : ?>
				<?php // Below lg the icon sits above the copy (Figma 101:23), which also keeps a title that wraps to three lines from stranding it mid-card; from lg it moves beside the copy, vertically centred. ?>
				<li class="flex flex-col lg:flex-row gap-lg items-start lg:items-center bg-surface-faint border border-border-faint rounded-lg p-xl">
					<span class="bg-brand-yellow rounded-pill size-[64px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[32px]">
					</span>

					<span class="flex flex-col gap-xs min-w-0">
						<span class="font-medium text-xl leading-[1.2] tracking-hero text-text-primary">
							<?php echo esc_html( $card['title'] ); ?>
						</span>

						<?php if ( $card['text'] ) : ?>
							<span class="font-normal text-sm leading-relaxed text-text-secondary">
								<?php echo esc_html( $card['text'] ); ?>
							</span>
						<?php endif; ?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
