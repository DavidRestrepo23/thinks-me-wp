<?php
/**
 * Business Loan — "Get Funded in 3 Simple Steps": a row of three cards, one of
 * which is open and carries a description.
 * Figma: node 119:1796, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Business Loan page): ci_steps_heading, and per card (1..3)
 * ci_steps_card_N_icon (select, fed at runtime from thinksme_ci_icons()), _title,
 * _text. The design's copy lives in thinksme_ci_defaults( 'steps' ); a page whose
 * set has no such section renders nothing, so this part is inert on the other
 * seven ci-* pages exactly as ci-definition.php and ci-types.php are.
 *
 * It is template-parts/roa-block.php's interaction on this frame's palette — the
 * open card widens, its title steps up and its description appears, while the
 * others fall back to a centred icon and title. So the two share
 * assets/js/expand-cards.js and differ only in CSS: this row sits on white with
 * pale cards and a navy open one, where ROA's sits on a navy panel with a yellow
 * open one. Every pixel of the open/closed geometry is `.ci-step*` in
 * src/base.css, which is what keeps the section readable as the design when the
 * script never loads: the card the template marked open is already open.
 *
 * Which card is open is **the first one with copy**, not a field. Figma writes a
 * description for the middle card only — the same situation roa-block.php is in —
 * and a "make this one open" checkbox would let the client open all three and
 * collapse the interaction. A card with copy is a disclosure and carries
 * aria-expanded/aria-controls; one without is only a selection and carries
 * neither, which is also what the script keys off.
 *
 * The pointer glyph Figma draws over the open card (119:1824) is not carried
 * over: it is the designer's own cursor illustrating the interaction, not chrome.
 */

$d = thinksme_ci_defaults( 'steps' );

if ( empty( $d['cards'] ) ) {
	return;
}

$cards = array();
$open  = 0;

foreach ( $d['cards'] as $n => $default ) {
	$title = thinksme_field( "ci_steps_card_{$n}_title", false, $default['title'] );
	$text  = thinksme_field( "ci_steps_card_{$n}_text", false, $default['text'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	if ( ! $open && '' !== trim( $text ) ) {
		$open = $n;
	}

	$cards[ $n ] = array(
		'icon'  => thinksme_ci_icon_url( thinksme_field( "ci_steps_card_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		'text'  => $text,
	);
}

if ( ! $cards ) {
	return;
}

// With no copy anywhere the first card opens rather than none: the row is a
// three-state control, and one with nothing selected reads as broken.
if ( ! $open ) {
	$open = array_key_first( $cards );
}
?>
<section id="ci-steps" class="flex flex-col items-center gap-xl lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<h2 class="font-medium text-2xl lg:text-3xl leading-tight tracking-hero text-center text-text-primary max-w-[895px]">
		<?php echo esc_html( thinksme_field( 'ci_steps_heading', false, $d['heading'] ) ); ?>
	</h2>

	<div class="ci-steps flex flex-col lg:flex-row gap-md lg:gap-[15px] w-full max-w-[1280px]" data-expand-cards>
		<?php
		foreach ( $cards as $n => $card ) :
			$expandable = '' !== trim( $card['text'] );
			$is_open    = $n === $open;
			$state      = $is_open ? 'true' : 'false';

			$attrs = ' class="ci-step" type="button" data-expand-card data-active="' . $state . '"';

			// Only a card with copy discloses anything; without it the button is a
			// selection, and aria-expanded would promise a panel that isn't there.
			if ( $expandable ) {
				$attrs .= ' aria-expanded="' . $state . '"';
				$attrs .= ' aria-controls="ci-step-text-' . esc_attr( $n ) . '"';
			}

			echo '<button' . $attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attributes are built from the fixed set above.
			?>
				<span class="ci-step__icon">
					<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="" class="size-[32px]">
				</span>

				<span class="ci-step__body">
					<span class="ci-step__title"><?php echo esc_html( $card['title'] ); ?></span>

					<?php if ( $expandable ) : ?>
						<span class="ci-step__text" id="ci-step-text-<?php echo esc_attr( $n ); ?>">
							<?php echo esc_html( $card['text'] ); ?>
						</span>
					<?php endif; ?>
				</span>
			</button>
		<?php endforeach; ?>
	</div>
</section>
