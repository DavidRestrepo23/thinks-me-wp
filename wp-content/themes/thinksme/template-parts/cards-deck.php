<?php
/**
 * "Three Ways Think SME Serves Your Business" — rotating card deck.
 * Figma: node 22:1104, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Three fanned cards (Start / Run / Grow). The front card shows full content;
 * the two behind it peek out at the top, rotated. The deck auto-advances every
 * 4s and a click on a back card promotes it to the front — see
 * assets/js/cards-deck.js (behavior) and src/base.css (stack geometry).
 *
 * ACF (Home page): ways_hat_text, ways_heading, ways_subheading, and per card
 * (1..3): ways_card_N_badge, _title, _description, _button_text, _button_link,
 * _image. Card background/text palette and stack rotation are fixed per
 * position in code, so editing content can never break the layout.
 *
 * Card 3 (Grow) has no copy in Figma — its title/description ship empty and
 * are hidden until the client fills them in wp-admin. Both back cards in the
 * Figma frame are stale component instances (DM Sans, yellow pill button);
 * the canonical front-card spec (Charlevoix Pro, white split button) is used
 * for every card, since every card becomes the front card in rotation.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$images_uri = get_template_directory_uri() . '/assets/images/cards-deck';

// Fixed per stack position — not client-editable. Figma: #ffe064 / #183458 / #fffdc3.
$palettes = array(
	1 => array(
		'card'  => 'bg-card-start',
		'badge' => 'bg-brand-yellow-soft/35 border-brand-yellow-border text-text-primary',
		'title' => 'text-text-primary',
		'body'  => 'text-text-heading-dark',
	),
	2 => array(
		'card'  => 'bg-surface-dark',
		'badge' => 'bg-brand-yellow-soft/35 border-white/45 text-text-on-dark',
		'title' => 'text-text-on-dark',
		'body'  => 'text-text-on-dark/70',
	),
	3 => array(
		'card'  => 'bg-card-grow',
		'badge' => 'bg-brand-yellow-soft/35 border-brand-yellow-border text-text-primary',
		'title' => 'text-text-primary',
		'body'  => 'text-text-heading-dark',
	),
);

$defaults = array(
	1 => array(
		'badge'       => 'Start',
		'title'       => 'Set Up Your Singapore Company Right',
		'description' => 'From name reservation to Certificate of Incorporation — handled fully online, as fast as 24 hours. As an ACRA Registered Filing Agent, we file on your behalf with full accountability.',
		'image'       => "$images_uri/card-start.jpg",
	),
	2 => array(
		'badge'       => 'Run',
		'title'       => 'Keep Your Business Compliant, Year-Round',
		'description' => 'Accounting, GST, and corporate tax — handled on time, every time. Powered by Xero. IRAS-compliant. Zero penalties. Free up your time to focus on the business, not the paperwork.',
		'image'       => "$images_uri/card-run.jpg",
	),
	3 => array(
		'badge'       => 'Grow',
		'title'       => '',
		'description' => '',
		// Figma reuses the Run photo on this card; kept as-is so the deck looks
		// complete out of the box. Client replaces it in wp-admin.
		'image'       => "$images_uri/card-run.jpg",
	),
);

$cards = array();
foreach ( $defaults as $n => $default ) {
	$image = thinksme_field( "ways_card_{$n}_image" );

	$cards[ $n ] = array(
		'badge'       => thinksme_field( "ways_card_{$n}_badge", false, $default['badge'] ),
		'title'       => thinksme_field( "ways_card_{$n}_title", false, $default['title'] ),
		'description' => thinksme_field( "ways_card_{$n}_description", false, $default['description'] ),
		'button_text' => thinksme_field( "ways_card_{$n}_button_text", false, 'Get Free Consultation' ),
		'button_link' => thinksme_field( "ways_card_{$n}_button_link", false, '#' ),
		'image_url'   => ! empty( $image['url'] ) ? $image['url'] : $default['image'],
		'image_alt'   => ! empty( $image['alt'] ) ? $image['alt'] : '',
		'palette'     => $palettes[ $n ],
	);
}
?>
<section id="ways" class="flex flex-col items-center gap-xl md:gap-[64px] w-full mt-xl md:mt-3xl pb-3xl md:pb-[136px] px-lg">
	<div class="flex flex-col items-center gap-xl text-center w-full max-w-[843px]">
		<div class="flex flex-col items-center gap-lg">
			<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-lg inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( thinksme_field( 'ways_hat_text', false, 'Everything Under One Roof' ) ); ?>
			</span>
			<h2 class="font-medium text-2xl md:text-3xl text-text-primary leading-snug md:leading-[68px] tracking-tight">
				<?php echo esc_html( thinksme_field( 'ways_heading', false, 'Three Ways Think SME Serves Your Business' ) ); ?>
			</h2>
		</div>
		<p class="font-normal text-md text-text-secondary leading-relaxed tracking-tight max-w-[592px]">
			<?php echo esc_html( thinksme_field( 'ways_subheading', false, "Whether you're just starting out, managing compliance, or ready to grow — we have the expertise, credentials, and network to get it done." ) ); ?>
		</p>
	</div>

	<div class="cards-deck w-full max-w-[1062px]" data-autoplay-delay="4000">
		<div class="cards-deck__viewport">
			<?php foreach ( $cards as $n => $card ) : ?>
				<article
					class="cards-deck__card <?php echo esc_attr( $card['palette']['card'] ); ?> rounded-xl overflow-hidden"
					data-depth="<?php echo esc_attr( $n - 1 ); ?>"
				>
					<button type="button" class="cards-deck__promote" aria-label="<?php
						/* translators: %s: card badge, e.g. "Run". */
						echo esc_attr( sprintf( __( 'Show the %s card', 'thinksme' ), $card['badge'] ) );
					?>"></button>

					<div class="cards-deck__content relative flex flex-col items-start h-full p-lg md:pl-[56px] md:pt-[56px] md:pb-[56px]">
						<span class="border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium <?php echo esc_attr( $card['palette']['badge'] ); ?>">
							<?php echo esc_html( $card['badge'] ); ?>
						</span>

						<?php if ( $card['title'] ) : ?>
							<h3 class="mt-md font-medium text-2xl lg:text-3xl leading-tight max-w-[560px] <?php echo esc_attr( $card['palette']['title'] ); ?>">
								<?php echo esc_html( $card['title'] ); ?>
							</h3>
						<?php endif; ?>

						<?php if ( $card['description'] ) : ?>
							<p class="mt-lg md:mt-[48px] font-normal text-sm leading-loose max-w-[560px] mb-10 <?php echo esc_attr( $card['palette']['body'] ); ?>">
								<?php echo esc_html( $card['description'] ); ?>
							</p>
						<?php endif; ?>

						<?php if ( $card['button_text'] ) : ?>
							<!-- mt-auto pins the CTA to the bottom padding edge: with the design's
								 copy that lands it at exactly y=485 as in Figma, and a card whose
								 title/description the client hasn't filled in yet still looks
								 deliberate instead of leaving the button floating mid-card. -->
							<a href="<?php echo esc_url( $card['button_link'] ); ?>" class="cards-deck__cta btn-split mt-auto mb-[10px] inline-flex items-center">
								<span class="bg-surface-white rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
									<?php echo esc_html( $card['button_text'] ); ?>
								</span>
								<span class="bg-surface-white rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
									<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
								</span>
							</a>
						<?php endif; ?>

						<?php if ( $card['image_url'] ) : ?>
							<!-- Mobile (144:494 node I144:540;319:2407): static, in-flow, full card
								 width, below the button — no rotation, no overlap with the copy. -->
							<div class="cards-deck__media-mobile lg:hidden w-full aspect-[4/5] rounded-lg overflow-hidden mt-lg">
								<img src="<?php echo esc_url( $card['image_url'] ); ?>" alt="<?php echo esc_attr( $card['image_alt'] ); ?>" class="w-full h-full object-cover">
							</div>
							<!-- top/right are the *unrotated* box; the 2.86deg rotation grows the
								 bounding box by ~11x7px, landing it on Figma's 691x69 bbox. -->
							<div class="cards-deck__media hidden lg:block absolute top-[76px] right-[59px] w-[301px] h-[439px] rounded-lg overflow-hidden rotate-[2.86deg]">
								<img src="<?php echo esc_url( $card['image_url'] ); ?>" alt="<?php echo esc_attr( $card['image_alt'] ); ?>" class="w-full h-full object-cover">
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
