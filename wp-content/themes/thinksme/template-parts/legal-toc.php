<?php
/**
 * Legal-page body: a sticky numbered table of contents beside long-form
 * sections, each with its own anchor. Figma: node 130:2165, "Desktop Privacy
 * Policy" frame (130:2151), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * `sections` (get_template_part() args) is an ordered list of
 * `[ 'title' => ..., 'body' => '<p>...</p>' ]`; `body` is trusted markup
 * written by the theme, not user input, so it is echoed as-is like
 * the_content() is elsewhere. The anchor id is derived from the title via
 * sanitize_title() rather than stored per section, so the sidebar link and the
 * section heading can never drift out of sync.
 *
 * Content is hardcoded per legal page rather than modeled as 15+ ACF fields
 * (free-tier ACF has no Repeater — the same ceiling documented for
 * office_1..4_* and roa_plan_benefit_N): this is one-off legal copy, not
 * something the client edits piecemeal, and the pattern already used
 * throughout the ci-* family for design-artifact copy (typos, duplicated
 * paragraphs) that "ships verbatim" applies here too — see the note on
 * section 10 in page-privacy-policy-2.php, a genuine copy-paste duplicate in
 * the source design (identical body to section 9, under its own heading),
 * kept rather than silently corrected.
 *
 * The TOC's first item renders styled "active" (bold, navy) to match the
 * design's static screenshot; assets/js/toc-scrollspy.js upgrades that to a
 * real IntersectionObserver-driven scrollspy once JS runs, and both target the
 * same `data-active` attribute so there is no flash between the two states.
 */

$sections = isset( $args['sections'] ) ? $args['sections'] : array();

if ( ! $sections ) {
	return;
}

$items = array();
foreach ( $sections as $section ) {
	$items[] = array(
		'id'    => sanitize_title( $section['title'] ),
		'title' => $section['title'],
		'body'  => $section['body'],
	);
}
?>
<section class="w-full px-lg lg:px-3xl py-xl lg:py-3xl">
	<div class="flex flex-col lg:flex-row items-start gap-xl lg:gap-[80px]" data-toc-scrollspy>
		<nav
			class="w-full lg:w-[280px] shrink-0 lg:sticky lg:top-[100px] lg:self-start"
			aria-label="<?php esc_attr_e( 'Table of contents', 'thinksme' ); ?>"
		>
			<div class="legal-toc bg-surface-panel rounded-sm p-xl flex flex-col gap-[20px] text-xs">
				<?php foreach ( $items as $i => $item ) : ?>
					<a
						href="#<?php echo esc_attr( $item['id'] ); ?>"
						data-toc-link="<?php echo esc_attr( $item['id'] ); ?>"
						data-active="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						class="leading-[21px]"
					>
						<?php echo esc_html( ( $i + 1 ) . '. ' . $item['title'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</nav>

		<div class="flex flex-col gap-[48px] min-w-0 grow">
			<?php foreach ( $items as $item ) : ?>
				<div id="<?php echo esc_attr( $item['id'] ); ?>" data-toc-section class="flex flex-col gap-md scroll-mt-[100px]">
					<h2 class="font-medium text-[32px] leading-[1.2] text-text-primary">
						<?php echo esc_html( $item['title'] ); ?>
					</h2>
					<div class="entry-content">
						<?php echo $item['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted theme-authored markup, not user input. ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
