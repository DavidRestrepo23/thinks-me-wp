<?php
/**
 * Accounting & Bookkeeping — "Accounting Requirements for Singapore Companies":
 * a navy panel with a hat, a heading and a four-step rail whose connectors fill
 * as the section is scrolled.
 * Figma: node 102:3515, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Accounting & Bookkeeping page): ci_requirements_hat_text,
 * ci_requirements_heading, and per step (1..4) ci_requirements_step_N_icon
 * (select, fed at runtime from thinksme_ci_icons()), _title, _text. The design's
 * copy lives in thinksme_ci_defaults( 'requirements' ); a page whose set has no
 * such section renders nothing, so this part is inert on the three Company
 * Incorporation pages.
 *
 * The rail is the same one template-parts/roa-why.php uses — same markup
 * (.rail*), same CSS, same assets/js/progress-rail.js, and Figma draws it to the
 * same geometry on both pages (64px icon, 126px connector). The section opts in
 * by carrying `data-progress-rail` and marking the block to pin with
 * `data-progress-pin`; the script finds it from those attributes, so nothing
 * here is named after this page.
 *
 * Scrolling is not hijacked. The pin is `position: sticky`, so the page keeps
 * moving under the visitor's own scrolling; progress reaches 1 on the last step
 * — "Corporate Tax Filing" — and the panel releases there. Nothing prevents a
 * default, calls scrollTo, or traps the wheel, which is what makes "the scroll
 * stays here until the steps complete" safe to ship: a visitor who wants past it
 * simply keeps scrolling.
 *
 * Pinning is an enhancement, not the layout: --rail-progress defaults to 1, so
 * without the script (or below lg, or under prefers-reduced-motion, or on a
 * viewport too short to hold the panel) this is a plain block with every
 * connector already filled.
 *
 * --rail-track is overridden here because the rail sits on navy: the shared
 * default is the light grey ROA's white section needs. --rail-connector is
 * overridden in base.css for a load-bearing reason: this frame stacks the
 * heading above the rail in one panel, and at Figma's 126px connectors that
 * panel is taller than a laptop viewport, which is exactly the case the script
 * refuses to pin. See the .ci-requirements rules.
 *
 * The cityscape behind the panel is the same exported SVG ci-pricing.php uses.
 */

$images_uri = get_template_directory_uri() . '/assets/images/ci';

$d = thinksme_ci_defaults( 'requirements' );

if ( ! $d ) {
	return;
}

$steps = array();

foreach ( $d['steps'] as $n => $default ) {
	$title = thinksme_field( "ci_requirements_step_{$n}_title", false, $default['title'] );

	if ( '' === trim( $title ) ) {
		continue;
	}

	$steps[] = array(
		'icon'  => thinksme_ci_icon_url( thinksme_field( "ci_requirements_step_{$n}_icon", false, $default['icon'] ) ),
		'title' => $title,
		'text'  => thinksme_field( "ci_requirements_step_{$n}_text", false, $default['text'] ),
	);
}

if ( ! $steps ) {
	return;
}

// One connector between each pair of steps — also the divisor the CSS uses to
// slice --rail-progress into per-connector fills, so it has to be the real count.
$lines = max( 1, count( $steps ) - 1 );
?>
<section
	id="ci-requirements"
	class="ci-requirements w-full px-lg lg:px-3xl py-xl lg:py-[20px]"
	data-progress-rail
	style="--rail-lines: <?php echo esc_attr( $lines ); ?>; --rail-track: #304969;"
>
	<div
		data-progress-pin
		<?php // py-xl rather than the py-3xl the other navy panels use, and the rail below is mt-xl for the same reason: every pixel here counts against fitting the viewport, which is what the pin depends on. ?>
		class="ci-requirements__panel relative overflow-hidden rounded-[40px] lg:rounded-[80px] bg-surface-dark px-lg lg:px-[56px] py-xl flex flex-col justify-center"
	>
		<img
			src="<?php echo esc_url( "$images_uri/pricing-bg.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="absolute inset-x-0 top-0 w-full pointer-events-none select-none"
		>

		<div class="relative flex flex-col items-center gap-md text-center max-w-[812px] mx-auto">
			<?php $hat = thinksme_field( 'ci_requirements_hat_text', false, $d['hat'] ); ?>
			<?php if ( $hat ) : ?>
				<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-md inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( $hat ); ?>
				</span>
			<?php endif; ?>

			<h2 class="font-medium text-2xl lg:text-3xl leading-[1.1] tracking-hero text-text-on-dark">
				<?php echo esc_html( thinksme_field( 'ci_requirements_heading', false, $d['heading'] ) ); ?>
			</h2>
		</div>

		<?php // `relative` so the rail paints above the cityscape behind it: the artwork is absolutely positioned and would otherwise cover the first steps, which is where it overlaps. ?>
		<ol class="rail relative w-full max-w-[718px] mx-auto mt-xl">
			<?php foreach ( $steps as $index => $step ) : ?>
				<li class="rail__step">
					<span class="rail__marker" aria-hidden="true">
						<span class="rail__icon">
							<img src="<?php echo esc_url( $step['icon'] ); ?>" alt="" class="size-[27.4px]">
						</span>

						<?php if ( $index < count( $steps ) - 1 ) : ?>
							<?php // --rail-step is this connector's slice of the overall progress; see .rail__line-fill in src/base.css. ?>
							<span class="rail__line" style="--rail-step: <?php echo esc_attr( $index ); ?>;">
								<span class="rail__line-fill"></span>
							</span>
						<?php endif; ?>
					</span>

					<?php // The colour is repeated on the heading itself: base.css sets a hard `color` on h1..h6, and a rule on the element beats a colour inherited from this wrapper. ?>
					<div class="rail__body text-text-on-dark">
						<h3 class="font-medium text-xl lg:text-[28px] leading-snug text-text-on-dark">
							<?php echo esc_html( $step['title'] ); ?>
						</h3>

						<?php if ( $step['text'] ) : ?>
							<p class="font-normal text-sm leading-loose">
								<?php echo esc_html( $step['text'] ); ?>
							</p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
