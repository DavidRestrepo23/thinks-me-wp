<?php
/**
 * "Questions Singapore Business Owners Ask Us First" — FAQ accordion + photo collage.
 * Figma: node 25:1776, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Variable-length list → `faq_item` CPT (client adds/edits/deletes/reorders from
 * the admin sidebar). Post title = question, ACF: answer (WYSIWYG). Section hat
 * and heading are ACF fields on the Home page (faq_hat_text, faq_heading).
 *
 * Open/close is native <details>/<summary> sharing a `name` attribute, which
 * makes the browser enforce single-open accordion behaviour with no JS
 * (Baseline since late 2024) — same approach as template-parts/cards-stack.php.
 * The +/- icon swap and the padding/radius change between states are plain CSS
 * keyed off `details[open]`, see src/base.css.
 *
 * With no posts yet the six questions from the Figma frame render as a preview.
 * Only the first one has an answer in the design; the rest ship without one and
 * open to just the question until the client fills them in.
 *
 * The photo collage keeps Figma's overlap by holding its 548x894 aspect ratio
 * and positioning every piece in percentages of that box, so it scales with the
 * column instead of needing a mobile spec. Below the desktop breakpoint the row
 * stacks and it lands under the accordion.
 *
 * The two columns share the row with flex-grow 54/46 (Figma's 648:548 split)
 * rather than fixed pixel widths, so nothing overflows between 1024px and
 * 1440px — the widths in the design only add up at exactly 1440.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$photos_uri = get_template_directory_uri() . '/assets/images/faq';

$faqs = new WP_Query(
	array(
		'post_type'      => 'faq_item',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

$items = array();

if ( $faqs->have_posts() ) {
	while ( $faqs->have_posts() ) :
		$faqs->the_post();
		$items[] = array(
			'question' => get_the_title(),
			'answer'   => thinksme_field( 'answer', get_the_ID() ),
		);
	endwhile;
	wp_reset_postdata();
} else {
	$items = array(
		array(
			'question' => 'How much does it cost to set up a company in Singapore?',
			'answer'   => "Think SME's all-in company setup package starts from S$888, which covers the ACRA government registration fee, company constitution preparation, registered office address, and first-year corporate secretarial service. The ACRA government fee alone is S$315 — our professional package bundles everything a new Singapore company needs from day one. Contact us for a full transparent quote tailored to your business structure.",
		),
		array( 'question' => 'When must my Singapore company register for GST?', 'answer' => '' ),
		array( 'question' => 'How fast can Think SME incorporate my Singapore company?', 'answer' => '' ),
		array( 'question' => 'What is the corporate tax rate in Singapore?', 'answer' => '' ),
		array( 'question' => 'My bank rejected my SME loan application — what can I do?', 'answer' => '' ),
		array( 'question' => 'What makes Think SME different from competitors?', 'answer' => '' ),
	);
}

if ( ! $items ) {
	return;
}
?>
<section
	id="faq"
	class="w-full mt-xl md:mt-3xl pb-3xl lg:pb-[160px] px-lg lg:px-3xl"
	style="
		--faq-icon-plus: url('<?php echo esc_url( "$icons_uri/faq-plus.svg" ); ?>');
		--faq-icon-minus: url('<?php echo esc_url( "$icons_uri/faq-minus.svg" ); ?>');
	"
>
	<div class="flex flex-col lg:flex-row lg:items-center gap-3xl lg:gap-[85px]">
		<div class="flex flex-col gap-xl w-full min-w-0 lg:flex-[54_1_0%]">
			<div class="flex flex-col items-start gap-md">
				<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-lg inline-flex items-center justify-center text-xs font-medium text-text-primary">
					<?php echo esc_html( thinksme_field( 'faq_hat_text', false, 'Common Questions' ) ); ?>
				</span>
				<h2 class="font-medium text-2xl lg:text-3xl leading-tight text-text-primary" style="letter-spacing: -0.01em;">
					<?php echo esc_html( thinksme_field( 'faq_heading', false, 'Questions Singapore Business Owners Ask Us First' ) ); ?>
				</h2>
			</div>

			<div class="flex flex-col gap-lg w-full lg:max-w-[597px]">
				<?php foreach ( $items as $index => $item ) : ?>
					<details class="faq-item" name="faq-accordion" <?php echo 0 === $index ? 'open' : ''; ?>>
						<summary class="faq-item__summary">
							<span class="faq-item__question text-sm font-bold text-text-primary">
								<?php echo esc_html( $item['question'] ); ?>
							</span>
							<span class="faq-item__icon" aria-hidden="true"></span>
						</summary>
						<?php if ( $item['answer'] ) : ?>
							<div class="faq-item__answer text-sm font-normal">
								<?php echo wp_kses_post( $item['answer'] ); ?>
							</div>
						<?php endif; ?>
					</details>
				<?php endforeach; ?>
			</div>
		</div>

		<?php
		// Percentages of the 548x894 collage box, straight from the Figma offsets:
		// photo 1 is 480x436 at (0,0), photo 2 is 465x439 at (83,455), and the
		// bulb badge is 125x124 at (227,399), overlapping both.
		?>
		<div class="faq-collage relative w-full max-w-[548px] mx-auto lg:mx-0 aspect-[548/894] lg:flex-[46_1_0%]" aria-hidden="true">
			<img
				src="<?php echo esc_url( "$photos_uri/faq-photo-1.jpg" ); ?>"
				alt=""
				loading="lazy"
				class="absolute top-0 left-0 w-[87.59%] h-[48.77%] rounded-[40px] lg:rounded-[60px] object-cover"
			>
			<img
				src="<?php echo esc_url( "$photos_uri/faq-photo-2.jpg" ); ?>"
				alt=""
				loading="lazy"
				class="absolute top-[50.89%] left-[15.15%] w-[84.85%] h-[49.11%] rounded-[40px] lg:rounded-[60px] object-cover"
			>
			<img
				src="<?php echo esc_url( "$icons_uri/faq-bulb.svg" ); ?>"
				alt=""
				loading="lazy"
				class="absolute top-[44.63%] left-[41.42%] w-[22.86%] h-[13.85%]"
			>
		</div>
	</div>
</section>
