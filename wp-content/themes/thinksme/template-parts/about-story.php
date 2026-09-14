<?php
/**
 * About Us — a navy checklist card beside the "Our Story" heading and copy.
 * Figma: node 157:314, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * About-only: this shape (an icon, a title and a four-item checklist on a navy
 * card with a faint dot-grid texture) doesn't match any existing ci-* part, so
 * it gets its own rather than growing a shared one for a single page.
 *
 * ACF (About Us page): about_story_title, about_story_item_1.._4,
 * about_story_hat, about_story_heading, about_story_text_1.._4. Paragraph 3
 * renders bold — a position in the design (157:512 sets only that line in
 * bold), not a per-paragraph field.
 *
 * The card's icon and its background texture are theme files, not client
 * fields: they are decoration, not content the client is expected to replace.
 */

$d = thinksme_ci_defaults( 'story' );

if ( ! $d ) {
	return;
}

$icons_uri = get_template_directory_uri() . '/assets/images/about';

$items = array();

foreach ( $d['items'] as $n => $default_item ) {
	$n    = $n + 1;
	$text = thinksme_field( "about_story_item_{$n}", false, $default_item );

	if ( '' === trim( $text ) ) {
		continue;
	}

	$items[] = $text;
}
?>
<section id="about-story" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col lg:flex-row items-center gap-2xl lg:gap-[96px] w-full">
		<?php // Figma draws this card 600px tall with the icon pinned to the top and the copy block sitting on the bottom padding (157:467 at y=40, 157:478 at y=280 running to 560 of 600). That air between them is the composition, not a gap that should collapse — hence a min-height plus justify-between rather than the content's own height. Below lg the card sizes to its content, where a 600px box would just be dead space on a phone. ?>
		<div class="relative flex flex-col bg-surface-dark overflow-hidden rounded-[32px] w-full lg:w-[560px] lg:shrink-0 lg:min-h-[600px] px-lg py-2xl lg:p-[40px]">
			<?php
			// Figma's block texture (157:317): a 1228.54x322.33 box placed at -244.67,0
			// inside the 560x600 card and mirrored vertically, so it fills the card's top
			// half and runs off both edges. Those are percentages of the card here so it
			// holds while the card flexes. It carries no opacity: the artwork is already
			// tone-on-tone (#132F53 blocks on the card's own #183458), which is what makes
			// it read as texture rather than as a second graphic.
			?>
			<img
				src="<?php echo esc_url( "$icons_uri/story-bg.svg" ); ?>"
				alt=""
				aria-hidden="true"
				class="absolute left-[-43.69%] top-0 w-[219.38%] h-[53.72%] max-w-none -scale-y-100 pointer-events-none select-none"
			>

			<div class="relative flex flex-col gap-2xl lg:gap-xl lg:grow lg:justify-between">
				<span class="bg-brand-yellow rounded-pill inline-flex items-center justify-center size-[72px] shrink-0">
					<img src="<?php echo esc_url( thinksme_ci_image_url( $d['icon'] ) ); ?>" alt="" class="size-[48px]">
				</span>

				<div class="flex flex-col gap-xl">
					<h3 class="font-medium text-xl leading-snug text-text-on-dark">
						<?php echo esc_html( thinksme_field( 'about_story_title', false, $d['title'] ) ); ?>
					</h3>

					<?php if ( $items ) : ?>
						<ul class="flex flex-col gap-lg">
							<?php foreach ( $items as $item ) : ?>
								<li class="flex items-center gap-md">
									<span class="border border-brand-yellow-border rounded-md inline-flex items-center justify-center shrink-0 size-[24px]">
										<img src="<?php echo esc_url( "$icons_uri/story-check.svg" ); ?>" alt="" class="size-[12px]">
									</span>
									<span class="font-normal text-sm leading-loose text-text-on-dark"><?php echo esc_html( $item ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="flex flex-col gap-2xl lg:gap-3xl w-full">
			<div class="flex flex-col gap-lg">
				<span class="bg-brand-yellow-soft/35 border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center w-max text-xs font-medium text-text-primary">
					<?php echo esc_html( thinksme_field( 'about_story_hat', false, $d['hat'] ) ); ?>
				</span>

				<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-text-primary">
					<?php echo esc_html( thinksme_field( 'about_story_heading', false, $d['heading'] ) ); ?>
				</h2>
			</div>

			<div class="flex flex-col gap-md max-w-[519px]">
				<?php foreach ( array( 1, 2, 3, 4 ) as $n ) : ?>
					<?php $text = thinksme_field( "about_story_text_{$n}", false, $d[ "text_{$n}" ] ); ?>
					<?php if ( $text ) : ?>
						<p class="<?php echo 3 === $n ? 'font-bold' : 'font-normal'; ?> text-sm leading-loose text-text-primary">
							<?php echo esc_html( $text ); ?>
						</p>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
