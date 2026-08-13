<?php
/**
 * One pricing package card, for template-parts/ci-pricing.php.
 * Figma: the cards inside 85:1351 (the navy panel) and 108:4867 (the Corporate
 * Tax split), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Its own part rather than markup inside ci-pricing.php because that section is
 * drawn two ways — three cards on a navy panel, or one card beside the section's
 * copy on white — and the card is identical in both. Duplicating ~90 lines of it
 * into a second branch is exactly the mistake inc/ci-content.php's header
 * describes at the page level.
 *
 * Usage (ci-pricing.php is the only caller):
 *   get_template_part( 'template-parts/ci-plan', null, array(
 *       'card'          => $card,          // built by ci-pricing.php
 *       'featured'      => true,           // the highlighted slot
 *       'variant'       => 'panel',        // 'panel' | 'split'
 *       'popular_label' => 'MOST POPULAR',
 *   ) );
 *
 * The variant decides how "featured" looks, and both readings come from the
 * design: on the navy panel the highlighted card is filled blue with a tab above
 * it, and on white it is a faint card with the label as a pill inside its top
 * corner. Hence `data-featured` carries which of the two, not just whether —
 * every pixel of both is `.ci-plan*` in src/base.css.
 *
 * The icon disc is panel-only: the split frame draws no icon at all. Same for the
 * outlined button's palette — on the navy panel it is outlined in white because
 * it sits on navy, so on white it takes the dark modifier instead.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$card          = isset( $args['card'] ) ? $args['card'] : array();
$featured      = ! empty( $args['featured'] );
$variant       = isset( $args['variant'] ) ? $args['variant'] : 'panel';
$popular_label = isset( $args['popular_label'] ) ? $args['popular_label'] : '';

if ( empty( $card['title'] ) ) {
	return;
}

$is_panel = 'panel' === $variant;

if ( ! $featured ) {
	$featured_state = 'false';
} else {
	$featured_state = $is_panel ? 'true' : 'badge';
}

// The button is inside the card on the split (Figma draws it on the card's own
// grey, under a rule) and under it on the panel (where it sits on the navy, which
// is why the un-highlighted one is outlined in white). Same markup either way, so
// it is buffered once rather than written into both branches.
$button = '';

if ( $card['button_text'] ) {
	ob_start();

	if ( $featured ) :
		?>
		<a href="<?php echo esc_url( $card['button_link'] ); ?>" class="btn-split flex items-center w-full">
			<span class="bg-brand-yellow rounded-sm h-[50px] px-lg grow inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
				<?php echo esc_html( $card['button_text'] ); ?>
			</span>
			<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
				<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
			</span>
		</a>
		<?php
	else :
		?>
		<a href="<?php echo esc_url( $card['button_link'] ); ?>" class="ci-plan__button<?php echo $is_panel ? '' : ' ci-plan__button--dark'; ?>">
			<?php echo esc_html( $card['button_text'] ); ?>
		</a>
		<?php
	endif;

	$button = trim( ob_get_clean() );
}
?>
<div class="ci-plan flex flex-col gap-md lg:flex-1 lg:min-w-0" data-featured="<?php echo esc_attr( $featured_state ); ?>" data-variant="<?php echo esc_attr( $variant ); ?>">
	<?php if ( $is_panel && $featured && $popular_label ) : ?>
		<span class="ci-plan__tab"><?php echo esc_html( $popular_label ); ?></span>
	<?php endif; ?>

	<div class="ci-plan__card">
		<div class="flex flex-col gap-lg">
			<?php // The split frame's card leads with its label pill where the panel's leads with the icon disc. ?>
			<?php if ( ! $is_panel && $featured && $popular_label ) : ?>
				<span class="ci-plan__pill"><?php echo esc_html( $popular_label ); ?></span>
			<?php elseif ( $is_panel ) : ?>
				<span class="bg-brand-yellow rounded-pill size-[56px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[24px]">
				</span>
			<?php endif; ?>

			<div class="flex flex-col gap-xs">
				<h3 class="ci-plan__title font-medium text-[32px] leading-[1.2]">
					<?php echo esc_html( $card['title'] ); ?>
				</h3>

				<?php if ( $card['text'] ) : ?>
					<p class="ci-plan__text font-normal text-sm leading-loose opacity-70">
						<?php echo esc_html( $card['text'] ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<div class="flex flex-col gap-md">
			<div class="flex flex-col gap-xs">
				<?php if ( $card['price_label'] ) : ?>
					<p class="ci-plan__title font-medium text-xs leading-loose uppercase">
						<?php echo esc_html( $card['price_label'] ); ?>
					</p>
				<?php endif; ?>

				<?php if ( $card['price'] ) : ?>
					<?php // The GST frame prices its registration card at a promotional figure with the usual one struck through beside it (114:5518); every other card names a price alone, so the strike is optional and the row collapses to the figure without it. ?>
					<p class="flex flex-wrap items-baseline gap-xs">
						<span class="ci-plan__title font-medium text-[48px] leading-[1.2]">
							<?php echo esc_html( $card['price'] ); ?>
						</span>

						<?php if ( ! empty( $card['price_strike'] ) ) : ?>
							<span class="font-medium text-xl leading-[1.2] text-text-faint line-through">
								<?php echo esc_html( $card['price_strike'] ); ?>
							</span>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $card['price_note'] ) ) : ?>
				<p class="ci-plan__text font-normal text-sm leading-loose opacity-70">
					<?php echo esc_html( $card['price_note'] ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $card['badge_1'] || $card['badge_2'] ) : ?>
				<div class="flex flex-wrap gap-xs items-center">
					<?php // The first badge is the outlined pill in every frame but the GST one, whose highlighted card draws both of its pills filled (114:5578) — hence `badge_1_style`, a per-card default rather than a client field: it is which pill the design drew. ?>
					<?php if ( $card['badge_1'] ) : ?>
						<span class="ci-plan__badge<?php echo ( isset( $card['badge_1_style'] ) && 'soft' === $card['badge_1_style'] ) ? '' : ' ci-plan__badge--outline'; ?>"><?php echo esc_html( $card['badge_1'] ); ?></span>
					<?php endif; ?>
					<?php if ( $card['badge_2'] ) : ?>
						<span class="ci-plan__badge"><?php echo esc_html( $card['badge_2'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $card['features_intro'] ) : ?>
			<p class="ci-plan__title font-bold text-md leading-[1.2]">
				<?php echo esc_html( $card['features_intro'] ); ?>
			</p>
		<?php endif; ?>

		<?php // On the panel the rules between features are a border on each li after the first, not separator elements: an empty <li> would pad the list's announced item count for no visual gain. The split frame draws no rules between them but one above the list, which is the border on the <ul> — see .ci-plan[data-variant="split"] in base.css. The check badge is 40px there and 24px on the panel, both Figma's. ?>
		<?php if ( $card['features'] ) : ?>
			<ul class="ci-plan__features">
				<?php foreach ( $card['features'] as $feature ) : ?>
					<li class="flex gap-md items-center">
						<span class="bg-accent-green inline-flex items-center justify-center shrink-0 <?php echo $is_panel ? 'rounded-[7.68px] size-[24px]' : 'rounded-[12px] size-[40px]'; ?>">
							<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="<?php echo $is_panel ? 'size-[11.52px]' : 'size-[19.2px]'; ?>">
						</span>
						<span class="ci-plan__title font-medium text-md leading-[1.2]">
							<?php echo esc_html( $feature ); ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( ! $is_panel && $button ) : ?>
			<div class="ci-plan__cta">
				<?php echo $button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped values. ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $is_panel && $button ) : ?>
		<?php echo $button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped values. ?>
	<?php endif; ?>
</div>
