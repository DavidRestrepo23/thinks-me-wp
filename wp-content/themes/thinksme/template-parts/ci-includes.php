<?php
/**
 * Company Incorporation Local — what's included in every incorporation: a
 * centred heading over a photo on the left and four cards on the right.
 * Figma: node 85:1697, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Company Incorporation Local page): ci_includes_heading,
 * ci_includes_image, and per card (1..4) ci_includes_card_N_icon (select, fed at
 * runtime from thinksme_ci_icons()), _title, _text.
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
 * The crop offsets below are Figma's, measured against that exported photo, so
 * they mean nothing for another image — uploading a custom one switches the card
 * to a plain object-cover. Same `$is_stock` arrangement as roa-hero.php.
 */

$images_uri = get_template_directory_uri() . '/assets/images/ci';

$photo     = thinksme_field( 'ci_includes_image' );
$is_stock  = empty( $photo['url'] );
$photo_url = ! $is_stock ? $photo['url'] : "$images_uri/includes-photo.jpg";
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

$defaults = array(
	1 => array(
		'icon'  => 'folders',
		'title' => 'ACRA Filing & Registration',
		'text'  => 'Name reservation and BizFile+ submission by an ACRA.',
	),
	2 => array(
		'icon'  => 'user-check',
		'title' => 'Corporate Secretary — 12 Months',
		'text'  => 'Qualified company secretary appointed from day one.',
	),
	3 => array(
		'icon'  => 'buildings',
		'title' => 'Registered Address — 12 Months',
		'text'  => 'Compliant Singapore business address to keep private.',
	),
	4 => array(
		'icon'  => 'receipt',
		'title' => 'Transparent, All-In Pricing',
		'text'  => 'S$315 ACRA government fee included — no hidden charges.',
	),
);

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
<section id="ci-includes" class="flex flex-col items-center gap-xl lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-center text-text-primary max-w-[624px]">
		<?php echo esc_html( thinksme_field( 'ci_includes_heading', false, "What's Included in Every Incorporation" ) ); ?>
	</h2>

	<?php // Figma's 576 / 624 columns, held at their own widths and pushed apart, rather than stretched: the photo is fixed-ratio and growing it into a 1920px viewport would tower over the cards beside it. ?>
	<div class="flex flex-col lg:flex-row lg:items-center lg:justify-center gap-xl lg:gap-xl xl:gap-[96px] w-full">
		<div class="w-full max-w-[520px] mx-auto lg:max-w-none lg:mx-0 lg:basis-[576px] lg:min-w-0 aspect-[576/486] overflow-hidden rounded-lg">
			<?php if ( $is_stock ) : ?>
				<?php // 101.7% / 149.6% and the offsets are Figma's placement of this export inside the card — see the note above about why they don't survive a different photo. ?>
				<img
					src="<?php echo esc_url( $photo_url ); ?>"
					alt="<?php echo esc_attr( $photo_alt ); ?>"
					class="relative left-[-0.7%] top-[-42.8%] w-[101.7%] h-[149.6%] max-w-none object-cover"
				>
			<?php else : ?>
				<img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" class="w-full h-full object-cover">
			<?php endif; ?>
		</div>

		<ul class="flex flex-col gap-md w-full lg:basis-[624px] lg:min-w-0">
			<?php foreach ( $cards as $card ) : ?>
				<?php // The icon sits against the first line below lg: at the design's width the copy is two lines and centring reads level, but wrapped to three or four it leaves the icon stranded mid-card. ?>
				<li class="flex gap-md lg:gap-lg items-start lg:items-center bg-surface-faint border border-border-faint rounded-lg p-lg lg:p-xl">
					<span class="bg-brand-yellow rounded-pill size-[56px] lg:size-[64px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[28px] lg:size-[32px]">
					</span>

					<span class="flex flex-col gap-xs min-w-0">
						<span class="font-medium text-lg lg:text-xl leading-[1.2] tracking-hero text-text-primary">
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
