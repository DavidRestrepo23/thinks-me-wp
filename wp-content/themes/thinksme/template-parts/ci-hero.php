<?php
/**
 * Company Incorporation, Local and Foreign — page hero: hat, headline, intro, two buttons,
 * and the photo with its lightbulb badge on the right.
 * Figma: node 91:2069, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Shared verbatim by page-company-incorporation-local.php and
 * page-company-incorporation-foreign.php: same markup, same ACF field names,
 * different words and photographs. Every default below comes from
 * thinksme_ci_defaults() in inc/ci-content.php, which is the one place the two
 * pages differ — change the design's copy there, not here.
 *
 * ACF (both Company Incorporation pages): ci_hero_hat_text, ci_hero_title,
 * ci_hero_text, ci_hero_button_text / _link, ci_hero_button_2_text / _link,
 * ci_hero_image. The GST Registration frame adds a promotional price above the
 * buttons — ci_hero_price_text, ci_hero_price_strike, ci_hero_price_note — and
 * each of those renders only when it has content, so the frames without them are
 * unchanged.
 *
 * Two buttons, not one: a solid split button for the primary action and an
 * outlined one carrying the phone number. The phone is an ACF field on this page
 * rather than the Customizer's site-wide number — it is the label on a CTA here,
 * not site chrome, and the design pairs it with a specific action. If the client
 * wants one number everywhere, that is a content decision to make once, not two
 * fields to keep in sync silently.
 *
 * The brush stroke under the headline is desktop-only and sits at a fixed
 * offset, because Figma measures it against this headline breaking across
 * exactly three lines at 72px — the same constraint roa-hero.php documents. Edit
 * the headline to a different length and the stroke will underline the wrong
 * words, which is why it is hidden below `lg` where the type is smaller.
 *
 * The default image is the client-supplied export of Figma's whole image group
 * (91:2056): photo, rounded corners and lightbulb badge already composed, on
 * transparency. It is drawn as-is — no card, no clipping — because it carries its
 * own shape.
 *
 * A client upload is a plain photo, so it takes the other branch: it is placed in
 * the photo's own region of the group (the percentages below are Figma's) with a
 * rounded corner and object-cover, and the badge is drawn back over it from
 * assets/images/ci/hero-badge.svg. That file is the nine separate vectors Figma
 * draws (91:2059–91:2067) merged into one SVG, the same treatment the pricing
 * cityscape gets. Either way the section keeps its design; only the source of the
 * photo changes.
 *
 * The wrapper is Figma's whole image group rather than the photo card inside it,
 * because the badge deliberately overhangs the card's top-left corner and that
 * extra space is part of the composition. Its proportion (`image_box`), the
 * photo's own slot in it and the badge's placement are per-page defaults for the
 * same reason the photo itself is: the three frames draw the group at three
 * different sizes.
 *
 * The Corporate Secretary set is the one whose stock asset is the photo card
 * alone: its badge is vector art, Figma exports vectors flattened onto white, and
 * there is no rasteriser here to bake the SVG into the PNG without losing its
 * transparency. So that set names the badge SVG in `badge` and the stock branch
 * draws it over the card — the same file, at the same offsets, the upload branch
 * already uses.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$images_uri = get_template_directory_uri() . '/assets/images/ci';

$d = thinksme_ci_defaults( 'hero' );

$photo     = thinksme_field( 'ci_hero_image' );
$is_stock  = empty( $photo['url'] );
$photo_url = ! $is_stock ? $photo['url'] : thinksme_ci_image_url( $d['image'] );
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

$button_text   = thinksme_field( 'ci_hero_button_text', false, $d['button_text'] );
$button_link   = thinksme_field( 'ci_hero_button_link', false, $d['button_link'] );
$button_2_text = thinksme_field( 'ci_hero_button_2_text', false, $d['button_2_text'] );
$button_2_link = thinksme_field( 'ci_hero_button_2_link', false, $d['button_2_link'] );
?>
<section id="ci-hero" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col justify-center gap-xl w-full lg:basis-[703px] lg:min-w-0">
		<div class="flex flex-col items-start gap-md">
			<?php $hat = thinksme_field( 'ci_hero_hat_text', false, $d['hat'] ); ?>
			<?php if ( $hat ) : ?>
				<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill min-h-[32px] px-md py-[6px] inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $hat ); ?>
				</span>
			<?php endif; ?>

			<div class="relative w-full">
				<?php // Positioned as a share of the column so it holds while the column flexes; hidden below lg, where the smaller type rewraps and the stroke would land on the wrong line. The offset is measured against this page's headline, so it travels with the copy in inc/ci-content.php. ?>
				<img
					src="<?php echo esc_url( "$icons_uri/ci/hero-underline.svg" ); ?>"
					alt=""
					aria-hidden="true"
					class="<?php echo esc_attr( $d['underline_class'] ); ?>"
				>

				<?php // Figma strikes two lines of the Accounting & Bookkeeping headline rather than one (102:3131 and 102:3132), so a set can name a second stroke; the others leave it empty and draw none. ?>
				<?php if ( ! empty( $d['underline_2_class'] ) ) : ?>
					<img
						src="<?php echo esc_url( "$icons_uri/ci/hero-underline.svg" ); ?>"
						alt=""
						aria-hidden="true"
						class="<?php echo esc_attr( $d['underline_2_class'] ); ?>"
					>
				<?php endif; ?>
				<?php // 72px is what five of the six frames draw; the GST one sets 64px, so the desktop step is a per-page default with those 72px as the fallback. ?>
					<h1 class="relative font-medium text-[40px] sm:text-[56px] <?php echo esc_attr( empty( $d['title_class'] ) ? 'lg:text-[72px]' : $d['title_class'] ); ?> leading-none tracking-hero text-text-primary">
					<?php echo esc_html( thinksme_field( 'ci_hero_title', false, $d['title'] ) ); ?>
				</h1>
			</div>
		</div>

		<?php $intro = thinksme_field( 'ci_hero_text', false, $d['text'] ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm text-text-secondary leading-relaxed max-w-[578px]">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>

		<?php
		// The GST frame (114:5296) puts a promotional price above the buttons: the
		// offer, the usual price struck through beside it, and the terms under both.
		// Optional, and absent from every other frame, so a set that names none of
		// the three renders nothing here.
		$price_text   = thinksme_field( 'ci_hero_price_text', false, isset( $d['price_text'] ) ? $d['price_text'] : '' );
		$price_strike = thinksme_field( 'ci_hero_price_strike', false, isset( $d['price_strike'] ) ? $d['price_strike'] : '' );
		$price_note   = thinksme_field( 'ci_hero_price_note', false, isset( $d['price_note'] ) ? $d['price_note'] : '' );
		?>
		<?php if ( $price_text || $price_note ) : ?>
			<div class="flex flex-col gap-xs">
				<?php if ( $price_text || $price_strike ) : ?>
					<p class="flex flex-wrap items-center gap-xs">
						<?php if ( $price_text ) : ?>
							<span class="font-medium text-lg leading-relaxed text-text-navy"><?php echo esc_html( $price_text ); ?></span>
						<?php endif; ?>

						<?php // Figma writes the word "strikeoff" into this label as an instruction to itself; the instruction is honoured here as the line-through and left out of the copy. ?>
						<?php if ( $price_strike ) : ?>
							<span class="font-medium text-sm leading-normal text-text-faint line-through"><?php echo esc_html( $price_strike ); ?></span>
						<?php endif; ?>
					</p>
				<?php endif; ?>

				<?php if ( $price_note ) : ?>
					<p class="font-normal text-sm leading-relaxed text-text-secondary"><?php echo esc_html( $price_note ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $button_text || $button_2_text ) : ?>
			<?php // Both buttons span the column on a phone — side by side they wrap to ragged widths, and a full-width target is easier to hit. ?>
			<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-md sm:gap-lg">
				<?php if ( $button_text ) : ?>
					<a href="<?php echo esc_url( $button_link ); ?>" class="btn-split flex sm:inline-flex items-center w-full sm:w-auto">
						<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap grow sm:grow-0">
							<?php echo esc_html( $button_text ); ?>
						</span>
						<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
							<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
						</span>
					</a>
				<?php endif; ?>

				<?php if ( $button_2_text ) : ?>
					<a href="<?php echo esc_url( $button_2_link ); ?>" class="ci-hero__button-alt w-full sm:w-auto">
						<?php echo esc_html( $button_2_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="relative w-full max-w-[520px] mx-auto lg:max-w-none lg:mx-0 lg:basis-[609px] lg:min-w-0 <?php echo esc_attr( $d['image_box'] ); ?>">
		<?php if ( $is_stock ) : ?>
			<?php // The supplied export already carries the card and its corners, so it is drawn straight into the box. ?>
			<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" class="absolute inset-0 w-full h-full object-contain">

			<?php // Most sets' export has the badge composed into it already. The one whose asset is the photo card alone names the SVG here, because that page's badge is vector art and a PNG export of it comes back flattened onto white. ?>
			<?php if ( $d['badge'] ) : ?>
				<img
					src="<?php echo esc_url( thinksme_ci_image_url( $d['badge'] ) ); ?>"
					alt=""
					aria-hidden="true"
					class="<?php echo esc_attr( $d['badge_class'] ); ?>"
				>
			<?php endif; ?>
		<?php else : ?>
			<?php // A plain upload gets the photo's own slot inside the group — Figma's placement as percentages of the box, which differs per page and so comes from the defaults — and the badge painted back on top. ?>
			<div class="<?php echo esc_attr( $d['photo_slot_class'] ); ?>">
				<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" class="absolute inset-0 w-full h-full object-cover">
			</div>

			<img
				src="<?php echo esc_url( "$images_uri/hero-badge.svg" ); ?>"
				alt=""
				aria-hidden="true"
				class="<?php echo esc_attr( $d['badge_class'] ); ?>"
			>
		<?php endif; ?>
	</div>
</section>
