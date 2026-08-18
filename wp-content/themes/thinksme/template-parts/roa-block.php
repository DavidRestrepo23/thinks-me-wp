<?php
/**
 * Registered Office Address — the navy "Content Block": a heading over four
 * service cards, one of which is expanded.
 * Figma: node 67:211, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Registered Office Address page): roa_block_heading, and per card (1..4)
 * roa_card_N_icon (select, fed at runtime from thinksme_roa_icons()),
 * roa_card_N_title, roa_card_N_text.
 *
 * Clicking a card expands it: the card widens, its title steps up to 28px and
 * its description appears, while the others fall back to icon + title. The
 * behaviour is assets/js/expand-cards.js (which only moves data-active) and the
 * geometry is src/base.css, the same split the rest of the theme's card
 * sections use. That script was this section's own until the Business Loan frame
 * drew the same interaction (template-parts/ci-steps.php); it is now driven by
 * the data-expand-cards / data-expand-card attributes below rather than by these
 * class names, which stay styling hooks.
 *
 * Every card is clickable, including ones the client hasn't written a
 * description for yet — otherwise, with Figma's content (copy on card 2 only)
 * the section would ship with nothing to click. A card with copy is a
 * disclosure and carries aria-expanded/aria-controls; one without is only a
 * selection, so it carries neither. The card that starts expanded is the first
 * one that has copy, which is card 2 in the design.
 *
 * The pointer glyph Figma draws over the expanded card is not carried over: it
 * is the designer's mouse cursor illustrating the interaction, not chrome.
 *
 * The skyline behind the heading is the Figma artwork exported as one SVG
 * rather than the ~150 separate vectors it is on the canvas. It carries its own
 * navy panel, which is why it can sit over the section background seamlessly.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$images_uri = get_template_directory_uri() . '/assets/images/roa';

$defaults = array(
	1 => array(
		'icon'  => 'buildings',
		'title' => 'Professional Business Address',
		'text'  => '',
	),
	2 => array(
		'icon'  => 'envelope-open',
		'title' => 'Digital Mailroom Service',
		'text'  => 'Receive and review your important correspondence digitally — fast, organised, and hassle-free.',
	),
	3 => array(
		'icon'  => 'folder-lock',
		'title' => 'Secure File Access',
		'text'  => '',
	),
	4 => array(
		'icon'  => 'receipt',
		'title' => 'Transparent Fees',
		'text'  => '',
	),
);

$cards  = array();
$active = 0;

foreach ( $defaults as $n => $default ) {
	$title = thinksme_field( "roa_card_{$n}_title", false, $default['title'] );
	$text  = thinksme_field( "roa_card_{$n}_text", false, $default['text'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	if ( ! $active && '' !== trim( $text ) ) {
		$active = $n;
	}

	$cards[ $n ] = array(
		'icon'  => thinksme_roa_icon_url( thinksme_field( "roa_card_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		'text'  => $text,
	);
}

if ( ! $cards ) {
	return;
}
?>
<section id="roa-services" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="relative overflow-hidden rounded-[40px] lg:rounded-[80px] bg-surface-dark px-lg lg:px-[56px] pt-3xl pb-xl lg:pb-[56px]">
		<img
			src="<?php echo esc_url( "$images_uri/skyline.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="absolute inset-x-0 top-0 w-full pointer-events-none select-none"
		>

		<h2 class="relative font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-center text-text-on-dark max-w-[903px] mx-auto">
			<?php echo esc_html( thinksme_field( 'roa_block_heading', false, 'Registered Office Address and Virtual Mailroom Services' ) ); ?>
		</h2>

		<div class="roa-cards relative flex flex-col lg:flex-row gap-[15px] mt-xl lg:mt-[56px]" data-expand-cards>
			<?php
			foreach ( $cards as $n => $card ) :
				$expandable = '' !== trim( $card['text'] );
				$is_active  = $n === $active;
				$state      = $is_active ? 'true' : 'false';

				$attrs = ' class="roa-card" type="button" data-expand-card data-active="' . $state . '"';

				// Only a card with copy actually discloses anything; without it the
				// button is a selection, and aria-expanded would promise a panel that
				// isn't there.
				if ( $expandable ) {
					$attrs .= ' aria-expanded="' . $state . '"';
					$attrs .= ' aria-controls="roa-card-text-' . esc_attr( $n ) . '"';
				}

				echo '<button' . $attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attributes are built from the fixed set above.
				?>
					<span class="roa-card__icon">
						<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[27.7px]">
					</span>

					<span class="roa-card__body">
						<span class="roa-card__title"><?php echo esc_html( $card['title'] ); ?></span>

						<?php if ( $expandable ) : ?>
							<span class="roa-card__text" id="roa-card-text-<?php echo esc_attr( $n ); ?>">
								<?php echo esc_html( $card['text'] ); ?>
							</span>
						<?php endif; ?>
					</span>
				</button>
			<?php endforeach; ?>
		</div>
	</div>
</section>
