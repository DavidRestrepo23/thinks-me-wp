<?php
/**
 * Company Incorporation Local — free tools: a heading over three tabs and one
 * navy panel.
 * Figma: node 85:1665, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Company Incorporation Local page): ci_tools_hat_text, ci_tools_heading,
 * ci_tools_text, and per tab (1..3) ci_tools_tab_N_label, _title, _text,
 * _placeholder, _button_text, _button_link, _disclaimer.
 *
 * Figma draws ONE panel, not three — only the active tab's content exists on the
 * canvas, and only the first tab ("Company Name Check") has copy written for it.
 * That is the same situation roa-block.php is in, and it gets the same answer:
 * every tab ships the identical panel shape, and the copy Figma didn't write is
 * a field the client fills. Tabs 2 and 3 therefore default to placeholder text.
 *
 * The tools have no behaviour yet — no ACRA name validation, no SSIC lookup, no
 * tax computation. That was scoped out deliberately; this phase builds the
 * section and the tab switching only. So the panel's control is a plain GET form
 * pointing at ci_tools_tab_N_button_link (default the contact page), which keeps
 * the input and button from being dead chrome and gets replaced wholesale when
 * the real tools land. Nothing reads the `q` parameter today.
 *
 * Switching is assets/js/ci-tools.js, which only moves data-active /
 * aria-selected and toggles [hidden] on the panels; every pixel of the open and
 * closed states is `.ci-tab*` in src/base.css. With the script gone the first
 * tab's panel stays open and the section still reads as the design — the same
 * degradation contract roa-block.js has.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$defaults = array(
	1 => array(
		'label'       => 'Company Name Check',
		'title'       => 'Company Name Check',
		'text'        => 'Get a quick read on your proposed company name before you file with ACRA.',
		'placeholder' => 'e.g. Think SME Pte. Ltd.',
		'button_text' => 'Check Name',
		'disclaimer'  => 'This is a preliminary format check, not a live ACRA registry search. Final name availability and approval is confirmed when we submit your official BizFile+ application.',
	),
	2 => array(
		'label'       => 'Business Activity (SSIC)',
		'title'       => 'Business Activity (SSIC)',
		'text'        => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.',
		'placeholder' => 'e.g. lorem ipsum dolor',
		'button_text' => 'Search',
		'disclaimer'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
	),
	3 => array(
		'label'       => 'Tax Calculator',
		'title'       => 'Tax Calculator',
		'text'        => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.',
		'placeholder' => 'e.g. lorem ipsum dolor',
		'button_text' => 'Calculate',
		'disclaimer'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
	),
);

$tabs = array();

foreach ( $defaults as $n => $default ) {
	$label = thinksme_field( "ci_tools_tab_{$n}_label", false, $default['label'] );

	if ( '' === trim( $label ) ) {
		continue;
	}

	$tabs[ $n ] = array(
		'label'       => $label,
		'title'       => thinksme_field( "ci_tools_tab_{$n}_title", false, $default['title'] ),
		'text'        => thinksme_field( "ci_tools_tab_{$n}_text", false, $default['text'] ),
		'placeholder' => thinksme_field( "ci_tools_tab_{$n}_placeholder", false, $default['placeholder'] ),
		'button_text' => thinksme_field( "ci_tools_tab_{$n}_button_text", false, $default['button_text'] ),
		'button_link' => thinksme_field( "ci_tools_tab_{$n}_button_link", false, '/contact-us' ),
		'disclaimer'  => thinksme_field( "ci_tools_tab_{$n}_disclaimer", false, $default['disclaimer'] ),
	);
}

if ( ! $tabs ) {
	return;
}

$active = array_key_first( $tabs );
?>
<section id="ci-tools" class="flex flex-col items-center gap-xl lg:gap-3xl w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col items-center gap-md text-center max-w-[765px]">
		<?php $hat = thinksme_field( 'ci_tools_hat_text', false, 'Free Tools' ); ?>
		<?php if ( $hat ) : ?>
			<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary">
			<?php echo esc_html( thinksme_field( 'ci_tools_heading', false, 'Plan Your Company Before You Register' ) ); ?>
		</h2>

		<?php $intro = thinksme_field( 'ci_tools_text', false, 'Three free tools to help you get incorporation-ready — no sign-up required.' ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm leading-relaxed text-text-secondary">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>
	</div>

	<div class="ci-tools w-full">
		<?php // Below lg the strip wraps onto as many lines as it needs. It used to scroll horizontally to keep Figma's sitting-on-the-panel look, but on a phone that clipped the second and third tabs at the viewport edge with nothing to show they were there — a hidden tab beats a slightly different silhouette. ?>
		<div class="ci-tools__list" role="tablist" aria-label="<?php echo esc_attr( thinksme_field( 'ci_tools_heading', false, 'Free tools' ) ); ?>">
			<?php foreach ( $tabs as $n => $tab ) : ?>
				<button
					type="button"
					class="ci-tools__tab"
					role="tab"
					id="ci-tools-tab-<?php echo esc_attr( $n ); ?>"
					aria-controls="ci-tools-panel-<?php echo esc_attr( $n ); ?>"
					aria-selected="<?php echo $n === $active ? 'true' : 'false'; ?>"
					data-active="<?php echo $n === $active ? 'true' : 'false'; ?>"
					tabindex="<?php echo $n === $active ? '0' : '-1'; ?>"
				>
					<?php echo esc_html( $tab['label'] ); ?>
				</button>
			<?php endforeach; ?>
		</div>

		<?php foreach ( $tabs as $n => $tab ) : ?>
			<div
				class="ci-tools__panel"
				role="tabpanel"
				id="ci-tools-panel-<?php echo esc_attr( $n ); ?>"
				aria-labelledby="ci-tools-tab-<?php echo esc_attr( $n ); ?>"
				tabindex="0"
				<?php echo $n === $active ? '' : 'hidden'; ?>
			>
				<div class="flex flex-col items-center gap-md text-center text-text-on-dark w-full">
					<?php // The colour is repeated on the heading itself: base.css sets a hard `color` on h1..h6, and a rule on the element beats a colour inherited from this wrapper. ?>
					<?php if ( $tab['title'] ) : ?>
						<h3 class="font-medium text-[28px] leading-snug text-text-on-dark">
							<?php echo esc_html( $tab['title'] ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( $tab['text'] ) : ?>
						<p class="font-normal text-sm leading-loose">
							<?php echo esc_html( $tab['text'] ); ?>
						</p>
					<?php endif; ?>
				</div>

				<div class="flex flex-col items-center gap-lg w-full">
					<form class="flex flex-col sm:flex-row gap-xs items-stretch sm:items-center w-full" action="<?php echo esc_url( $tab['button_link'] ); ?>" method="get">
						<label class="sr-only" for="ci-tools-input-<?php echo esc_attr( $n ); ?>">
							<?php echo esc_html( $tab['title'] ? $tab['title'] : $tab['label'] ); ?>
						</label>
						<input
							class="ci-tools__input"
							type="text"
							id="ci-tools-input-<?php echo esc_attr( $n ); ?>"
							name="q"
							placeholder="<?php echo esc_attr( $tab['placeholder'] ); ?>"
						>

						<?php if ( $tab['button_text'] ) : ?>
							<button type="submit" class="btn-split flex items-center shrink-0">
								<span class="bg-brand-yellow rounded-sm h-[58px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap grow sm:grow-0">
									<?php echo esc_html( $tab['button_text'] ); ?>
								</span>
								<span class="bg-brand-yellow rounded-sm h-[58px] w-[50px] inline-flex items-center justify-center shrink-0">
									<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
								</span>
							</button>
						<?php endif; ?>
					</form>

					<?php if ( $tab['disclaimer'] ) : ?>
						<p class="font-normal text-[12px] leading-loose text-center text-text-on-dark opacity-50 max-w-[493px]">
							<?php echo esc_html( $tab['disclaimer'] ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
