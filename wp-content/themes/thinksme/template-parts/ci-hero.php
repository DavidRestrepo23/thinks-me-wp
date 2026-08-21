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
 * unchanged. The Property Cashout frame adds two more of the same kind:
 * ci_hero_text_2, a second intro paragraph (951:9001 — that frame is the only one
 * whose hero runs to two), and three figure pills over the photograph
 * (ci_hero_pill_N_value / _label, 951:9009 / 951:9019 / 951:9035).
 *
 * The Remittance frame replaces the hat pill with a partner logo (`logo`,
 * `logo_class`), for the reason the note beside it gives. The Mortgage Loans frame
 * adds a promotional pill above the buttons (`ci_hero_promo_text`) and gives its
 * second brush stroke its own vector (`underline_2_file`), since its two strokes are
 * different lengths.
 *
 * The PSG Grant frame (127:514) adds two more per-set defaults, both because its
 * headline is a 40px *paragraph* rather than a display line: `title_size_class`,
 * which replaces the whole responsive ramp instead of only its desktop step, and
 * `badge_file`, since its badge is its own vector and its stock export already has
 * that badge composed in — so it names the file for the upload branch without
 * naming `badge` for the stock one.
 *
 * The pills' icons are whole 56px discs rather than glyphs, because Figma draws
 * the navy disc and the yellow mark inside it as one vector group — so the SVG
 * carries both and there is no disc in the markup to colour. Which disc goes
 * where is a per-page default, not a client field: it belongs to the figure.
 *
 * Above lg the pills are absolutely placed on the image box at Figma's own
 * offsets (their wrapper is `lg:contents`, so each pill positions against the box
 * rather than against the wrapper). Below it they are a wrapped row under the
 * photo: the design has no mobile frame for them, and they carry real figures, so
 * hiding them would drop content rather than simplify a layout.
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

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$d = thinksme_ci_defaults( 'hero' );

$photo     = thinksme_field( 'ci_hero_image' );
$is_stock  = empty( $photo['url'] );
$photo_url = ! $is_stock ? $photo['url'] : thinksme_ci_image_url( $d['image'] );
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

// The Property Cashout frame's three figures over the photo. Absent everywhere
// else, so an empty list renders nothing and the image box is unchanged.
$pills = array();

foreach ( ( isset( $d['pills'] ) ? $d['pills'] : array() ) as $n => $pill_default ) {
	$value = thinksme_field( "ci_hero_pill_{$n}_value", false, $pill_default['value'] );

	if ( '' === trim( $value ) ) {
		continue;
	}

	$pills[] = array(
		'icon'  => $pill_default['icon'],
		'class' => $pill_default['class'],
		'value' => $value,
		'label' => thinksme_field( "ci_hero_pill_{$n}_label", false, $pill_default['label'] ),
	);
}

// Every frame but the Remittance one strikes a whole line with the same long brush
// stroke. That one strikes a single word mid-line (123:2690), which is its own
// shorter, thicker vector rather than the shared one scaled down — scaling the long
// stroke to a word's width thins it to a pencil line.
$underline = isset( $d['underline_file'] ) ? $d['underline_file'] : 'ci/hero-underline.svg';
// The Mortgage frame's two strokes are different lengths — a short one over a word on
// the first line and a long one under the line below it — so the second slot takes
// its own file, falling back to whatever the first one uses.
$underline_2 = isset( $d['underline_2_file'] ) ? $d['underline_2_file'] : $underline;

$button_text   = thinksme_field( 'ci_hero_button_text', false, $d['button_text'] );
$button_link   = thinksme_field( 'ci_hero_button_link', false, $d['button_link'] );
$button_2_text = thinksme_field( 'ci_hero_button_2_text', false, $d['button_2_text'] );
$button_2_link = thinksme_field( 'ci_hero_button_2_link', false, $d['button_2_link'] );
?>
<section id="ci-hero" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<?php // The two columns' widths are per-page defaults: the six older frames draw the copy at 703px beside a 609px image group, the Property Cashout one at 619 beside 583. That is not cosmetic — the column is what decides where the headline breaks, and the brush stroke and the figure pills are both placed against that break. ?>
	<div class="flex flex-col justify-center gap-xl w-full <?php echo esc_attr( empty( $d['body_class'] ) ? 'lg:basis-[703px]' : $d['body_class'] ); ?> lg:min-w-0">
		<div class="flex flex-col items-start gap-md">
			<?php
			// The Remittance frame opens on a partner's logo instead of the pill every
			// other frame draws (123:2692): the page is about that partner's rates, and a
			// pill reading "OFX" beside a headline reading "Save with OFX" would say it
			// twice. It is a per-page default rather than a field — it is the mark of the
			// company the page is about, not copy — and a set that names one gets the logo
			// in place of its hat, not as well as.
			$logo = isset( $d['logo'] ) ? $d['logo'] : '';
			?>
			<?php if ( $logo ) : ?>
				<img
					src="<?php echo esc_url( thinksme_ci_image_url( $logo ) ); ?>"
					alt="<?php echo esc_attr( isset( $d['logo_alt'] ) ? $d['logo_alt'] : '' ); ?>"
					class="<?php echo esc_attr( $d['logo_class'] ); ?>"
				>
			<?php else : ?>
				<?php $hat = thinksme_field( 'ci_hero_hat_text', false, $d['hat'] ); ?>
				<?php if ( $hat ) : ?>
					<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill min-h-[32px] px-md py-[6px] inline-flex items-center justify-center text-xs font-medium text-text-primary">
						<?php echo esc_html( $hat ); ?>
					</span>
				<?php endif; ?>
			<?php endif; ?>

			<div class="relative w-full">
				<?php // Positioned as a share of the column so it holds while the column flexes; hidden below lg, where the smaller type rewraps and the stroke would land on the wrong line. The offset is measured against this page's headline, so it travels with the copy in inc/ci-content.php. ?>
				<img
					src="<?php echo esc_url( "$icons_uri/$underline" ); ?>"
					alt=""
					aria-hidden="true"
					class="<?php echo esc_attr( $d['underline_class'] ); ?>"
				>

				<?php // Figma strikes two lines of the Accounting & Bookkeeping headline rather than one (102:3131 and 102:3132), so a set can name a second stroke; the others leave it empty and draw none. ?>
				<?php if ( ! empty( $d['underline_2_class'] ) ) : ?>
					<img
						src="<?php echo esc_url( "$icons_uri/$underline_2" ); ?>"
						alt=""
						aria-hidden="true"
						class="<?php echo esc_attr( $d['underline_2_class'] ); ?>"
					>
				<?php endif; ?>
				<?php
				// 72px is what five of the six frames draw; the GST one sets 64px, so the desktop
				// step is a per-page default with those 72px as the fallback.
				//
				// The PSG Grant frame needs the *whole* ramp instead of its last step: its
				// headline is a 40px paragraph (127:519), which is what the shared ramp already
				// sets on a phone — so `lg:text-[40px]` alone would leave the tablet band larger
				// than the desktop one. A set that names `title_size_class` replaces the ramp;
				// every other one keeps it and only names the step.
				$title_size_class = isset( $d['title_size_class'] )
					? $d['title_size_class']
					: 'text-[40px] sm:text-[56px] ' . ( empty( $d['title_class'] ) ? 'lg:text-[72px]' : $d['title_class'] );
				?>
					<h1 class="relative font-medium <?php echo esc_attr( $title_size_class ); ?> leading-none tracking-hero text-text-primary">
					<?php echo esc_html( thinksme_field( 'ci_hero_title', false, $d['title'] ) ); ?>
				</h1>
			</div>
		</div>

		<?php
		$intro   = thinksme_field( 'ci_hero_text', false, $d['text'] );
		$intro_2 = thinksme_field( 'ci_hero_text_2', false, isset( $d['text_2'] ) ? $d['text_2'] : '' );
		?>
		<?php if ( $intro || $intro_2 ) : ?>
			<div class="flex flex-col gap-md max-w-[578px]">
				<?php if ( $intro ) : ?>
					<p class="font-normal text-sm text-text-secondary leading-relaxed">
						<?php echo esc_html( $intro ); ?>
					</p>
				<?php endif; ?>

				<?php // Only the Property Cashout frame writes a second paragraph here; every other set leaves it empty and this renders nothing. ?>
				<?php if ( $intro_2 ) : ?>
					<p class="font-normal text-sm text-text-secondary leading-relaxed">
						<?php echo esc_html( $intro_2 ); ?>
					</p>
				<?php endif; ?>
			</div>
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

		<?php
		// The Mortgage frame puts a promotional line in a wide pill above the buttons
		// (124:3274) — an offer, not a heading, and not the GST frame's struck-through
		// price either. Optional, so every other frame renders nothing here.
		$promo = thinksme_field( 'ci_hero_promo_text', false, isset( $d['promo_text'] ) ? $d['promo_text'] : '' );
		?>
		<?php if ( $promo ) : ?>
			<p class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill w-full sm:w-auto sm:max-w-[436px] px-md py-[9px] text-center font-medium text-md leading-relaxed text-text-navy">
				<?php echo esc_html( $promo ); ?>
			</p>
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

	<?php // The column and the photo's own box are two elements, not one, so the figure pills can be a sibling of the photo rather than a child of it. Inside it they were in normal flow under an absolutely positioned <img>, and a positioned element paints above in-flow content regardless of DOM order — the same rule ci-requirements.php's cityscape ran into. The column carries `relative` for the pills to position against above lg; the inner box keeps the aspect ratio the badge and photo-slot percentages are measured from, so the other six sets are unchanged. ?>
	<div class="relative w-full max-w-[520px] mx-auto lg:max-w-none lg:mx-0 <?php echo esc_attr( empty( $d['image_col_class'] ) ? 'lg:basis-[609px]' : $d['image_col_class'] ); ?> lg:min-w-0">
		<div class="relative w-full <?php echo esc_attr( $d['image_box'] ); ?>">
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

			<?php
			// Gated on the placement class, not just on the upload: a set whose frame draws no
			// badge (Property Cashout) names neither, and an unclassed <img> would land in the
			// flow at its natural size.
			//
			// The file is a per-set default too, because the PSG Grant frame's badge (127:527)
			// is its own vector rather than the incorporation one — and that set composes the
			// badge into its stock export, so it names `badge_file` for this branch without
			// naming `badge` for the other.
			$badge_file = empty( $d['badge_file'] ) ? 'ci/hero-badge.svg' : $d['badge_file'];
			?>
			<?php if ( ! empty( $d['badge_class'] ) ) : ?>
				<img
					src="<?php echo esc_url( thinksme_ci_image_url( $badge_file ) ); ?>"
					alt=""
					aria-hidden="true"
					class="<?php echo esc_attr( $d['badge_class'] ); ?>"
				>
			<?php endif; ?>
		<?php endif; ?>
		</div>

		<?php
		// The Business Loan frame closes the image column with a row of partner-bank
		// logos under a small label (119:1737 + 119:1750). Figma draws it as a masked
		// marquee — 505px of logos inside a 439px frame, so the last wordmark is clipped
		// there — but five logos are a row, not a carousel, and a marquee of five would
		// scroll a claim rather than state it. Theme files and not a client field: they
		// are the evidence for the hat's "19 core partner banks", the same reason the
		// figure pills' discs are.
		$banks = isset( $d['banks'] ) ? $d['banks'] : array();
	?>
	<?php if ( ! empty( $banks['logos'] ) ) : ?>
		<?php // The label sits beside the strip rather than above it, as Figma draws it, and shrinks away below sm where the row needs the width more than the caption needs the line. ?>
		<div class="flex items-center gap-lg mt-xl lg:mt-lg">
			<?php if ( ! empty( $banks['label'] ) ) : ?>
				<span class="shrink-0 font-normal text-[12px] leading-loose tracking-widest uppercase text-text-secondary whitespace-nowrap">
					<?php echo esc_html( $banks['label'] ); ?>
				</span>
			<?php endif; ?>

			<?php
			// It is the theme's logo marquee, not a static row: Figma masks a 505px row
			// into a 439px window with a fade at each end, which is a strip that moves.
			// So it carries `logos-swiper` and assets/js/logos-slider.js drives it — the
			// same loop-and-autoplay contract every other carousel in the theme has.
			//
			// The logos are rendered three times because Swiper wants roughly twice the
			// visible count before it will loop, and at `slidesPerView: 'auto'` a 495px
			// window shows about six of them — five, or even ten, still trips the warning
			// and drops the loop. Same floor ci-why-slider.js clones around. Only the
			// first pass carries names; the copies are aria-hidden, so the row still
			// announces five banks once.
			?>
			<div
				class="swiper logos-swiper ci-hero__banks min-w-0 grow"
				data-autoplay="true"
				data-autoplay-delay="4000"
				data-pause-on-hover="true"
				data-slides-desktop="auto"
				data-space-desktop="37"
			>
				<div class="swiper-wrapper items-center">
					<?php foreach ( array( false, true, true ) as $is_copy ) : ?>
						<?php foreach ( $banks['logos'] as $bank ) : ?>
							<div class="swiper-slide !w-auto flex items-center justify-center h-[40px]" <?php echo $is_copy ? 'aria-hidden="true"' : ''; ?>>
								<img
									src="<?php echo esc_url( thinksme_ci_image_url( $bank['file'] ) ); ?>"
									alt="<?php echo $is_copy ? '' : esc_attr( $bank['name'] ); ?>"
									loading="lazy"
									class="<?php echo esc_attr( $bank['class'] ); ?> w-auto max-w-full object-contain"
								>
							</div>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $pills ) : ?>
			<?php // `lg:contents` so each pill positions against the image box above lg, and the wrapper is a wrapped row under the photo below it. ?>
			<div class="flex flex-wrap gap-md mt-lg lg:contents">
				<?php foreach ( $pills as $pill ) : ?>
					<div class="<?php echo esc_attr( $pill['class'] ); ?> flex items-center gap-md w-max lg:mt-0 bg-surface-navy-rich rounded-pill pl-md pr-lg py-md">
						<img src="<?php echo esc_url( "$icons_uri/pc/{$pill['icon']}" ); ?>" alt="" aria-hidden="true" class="size-[56px] shrink-0">
						<div class="flex flex-col">
							<span class="font-medium text-xl leading-normal text-text-on-dark whitespace-nowrap">
								<?php echo esc_html( $pill['value'] ); ?>
							</span>
							<?php if ( $pill['label'] ) : ?>
								<span class="font-normal text-[12px] leading-loose tracking-widest uppercase text-text-on-dark/70 whitespace-nowrap">
									<?php echo esc_html( $pill['label'] ); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
