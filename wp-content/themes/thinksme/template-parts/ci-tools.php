<?php
/**
 * Company Incorporation, Local and Foreign — free tools: a heading over three tabs and one
 * navy panel.
 * Figma: node 85:1665, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Shared verbatim by page-company-incorporation-local.php and
 * page-company-incorporation-foreign.php: same markup, same ACF field names,
 * different words and photographs. Every default below comes from
 * thinksme_ci_defaults() in inc/ci-content.php, which is the one place the two
 * pages differ — change the design's copy there, not here.
 *
 * ACF (all three pages): ci_tools_hat_text, ci_tools_heading, ci_tools_text,
 * ci_tools_button_text / _link for the section's own CTA, and per tab (1..3)
 * ci_tools_tab_N_label, _title, _text, _placeholder, _button_text, _button_link,
 * _disclaimer. The glyph inside the input is not a field: it names the format the
 * tool wants, which belongs to the tool rather than to the copy, so it comes from
 * inc/ci-content.php only.
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
 *
 * Below lg the tab strip is replaced by a native <select> styled as Figma's
 * yellow caret pill (node 100:10); see the note above the markup.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$d        = thinksme_ci_defaults( 'tools' );
$defaults = $d['tabs'];

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
		'icon'        => $default['icon'],
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
		<?php $hat = thinksme_field( 'ci_tools_hat_text', false, $d['hat'] ); ?>
		<?php if ( $hat ) : ?>
			<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
				<?php echo esc_html( $hat ); ?>
			</span>
		<?php endif; ?>

		<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-primary">
			<?php echo esc_html( thinksme_field( 'ci_tools_heading', false, $d['heading'] ) ); ?>
		</h2>

		<?php $intro = thinksme_field( 'ci_tools_text', false, $d['text'] ); ?>
		<?php if ( $intro ) : ?>
			<p class="font-normal text-sm leading-relaxed text-text-secondary">
				<?php echo esc_html( $intro ); ?>
			</p>
		<?php endif; ?>
	</div>

	<?php $tools_label = thinksme_field( 'ci_tools_heading', false, 'Free tools' ); ?>

	<div class="ci-tools w-full">
		<?php // Below lg the strip is a dropdown instead (Figma node 100:10) — three labels this long can't be a row on a phone. A native <select> rather than a scripted listbox: it opens the platform's own picker, and it is the one control the design's caret pill can be built around without re-implementing keyboard and focus behaviour. Both controls are always in the DOM and CSS shows exactly one; `display: none` keeps the other out of the accessibility tree too, so the tabs are never announced twice. ?>
		<div class="ci-tools__select">
			<label class="sr-only" for="ci-tools-select"><?php echo esc_html( $tools_label ); ?></label>
			<?php // The value is the tab button's id, so ci-tools.js activates the tab it already knows how to activate rather than mapping indexes. ?>
			<select class="ci-tools__select-field" id="ci-tools-select">
				<?php foreach ( $tabs as $n => $tab ) : ?>
					<option value="ci-tools-tab-<?php echo esc_attr( $n ); ?>" <?php selected( $n, $active ); ?>>
						<?php echo esc_html( $tab['label'] ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="ci-tools__list" role="tablist" aria-label="<?php echo esc_attr( $tools_label ); ?>">
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
						<?php // The glyph is decorative: it labels the format the field wants (a date, on the deadline calculator), and the field's real label is the sr-only one above. It is a design default rather than a client field — the icon belongs to the tool, not to the copy. ?>
						<span class="ci-tools__field<?php echo $tab['icon'] ? ' ci-tools__field--has-icon' : ''; ?>">
							<?php if ( $tab['icon'] ) : ?>
								<img src="<?php echo esc_url( thinksme_ci_icon_url( $tab['icon'] ) ); ?>" alt="" aria-hidden="true" class="ci-tools__field-icon">
							<?php endif; ?>

							<input
								class="ci-tools__input"
								type="text"
								id="ci-tools-input-<?php echo esc_attr( $n ); ?>"
								name="q"
								placeholder="<?php echo esc_attr( $tab['placeholder'] ); ?>"
							>
						</span>

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

	<?php
	$section_button_text = thinksme_field( 'ci_tools_button_text', false, $d['button_text'] );
	$section_button_link = thinksme_field( 'ci_tools_button_link', false, $d['button_link'] ? $d['button_link'] : '/contact-us' );
	?>
	<?php // Figma's 102:2253, a lone centred button under the panel on the Corporate Secretary frame: having just shown someone their deadlines, the section asks for the switch. The other two frames draw no button here, so an empty label renders nothing. ?>
	<?php if ( $section_button_text ) : ?>
		<a href="<?php echo esc_url( $section_button_link ); ?>" class="btn-split inline-flex items-center">
			<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
				<?php echo esc_html( $section_button_text ); ?>
			</span>
			<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
				<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
			</span>
		</a>
	<?php endif; ?>
</section>
