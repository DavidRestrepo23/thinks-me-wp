<?php
/**
 * "Questions Singapore Business Owners Ask Us First" — FAQ accordion + photo collage.
 * Figma: node 25:1776, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Variable-length list → `faq_item` CPT (client adds/edits/deletes/reorders from
 * the admin sidebar). Post title = question, ACF: answer (WYSIWYG). Section hat
 * and heading are ACF fields on the Home page (faq_hat_text, faq_heading).
 *
 * One page overrides that list. Every frame up to the Property Cashout one asks
 * the same site-wide questions, so the CPT was the whole content model; that frame
 * (951:9844) writes six of its own, about property cashout and nothing else, which
 * belong to the page rather than to the site. So a page whose ci-* set names a
 * `faq` list wins over the CPT — its own flat fields (faq_item_N_question /
 * _answer, 1..8, empties skipped) and, behind them, the design's questions from
 * inc/ci-content.php. Pages whose set names none — every other one — read the CPT
 * exactly as before, and thinksme_ci_defaults() returns array() outside the ci-*
 * templates, so the homepage and the contact page are untouched too.
 *
 * A set that writes its own questions can name its own `hat` and `heading` beside
 * them (the PSG Grant frame titles the section "Questions Business Owners Ask Us
 * First", one word short of the site-wide line). Both fall back to the site-wide
 * strings, so a set that names neither — every other one — is unchanged.
 *
 * Deliberately not a `faq_group` taxonomy on the CPT: that would put this page's
 * questions in the same admin list as the site-wide ones and make "which page does
 * this appear on" a thing the client has to get right on every future FAQ. Fields
 * on the page are where per-page copy already lives in this theme.
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
 * The photo is a single ACF image field on the Home page (faq_photo) — empty
 * falls back to the design's own photo. The bulb badge stays fixed decorative
 * art, not client-editable.
 *
 * The photo box keeps Figma's 548x894 aspect ratio, with the bulb badge
 * positioned as a percentage of that box. The Mobile Homepage frame
 * (144:494, node 144:916) draws no photo under the FAQ list at all, so the
 * collage is `hidden` below `lg` rather than stacking under the accordion.
 *
 * The two columns share the row with flex-grow 54/46 (Figma's 648:548 split)
 * rather than fixed pixel widths, so nothing overflows between 1024px and
 * 1440px — the widths in the design only add up at exactly 1440.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$photos_uri = get_template_directory_uri() . '/assets/images/faq';

$photo = thinksme_field( 'faq_photo' );

$photo_url = ! empty( $photo['url'] ) ? $photo['url'] : "$photos_uri/faq-photo-1.jpg";
$photo_alt = ! empty( $photo['alt'] ) ? $photo['alt'] : '';

$items = array();

// A ci-* page that writes its own questions. thinksme_ci_defaults() only exists
// for those templates and returns array() elsewhere, so this is a no-op on every
// other page and the CPT below stays the site-wide list it has always been.
$page_faq = function_exists( 'thinksme_ci_defaults' ) ? thinksme_ci_defaults( 'faq' ) : array();

if ( ! empty( $page_faq['items'] ) ) {
	foreach ( $page_faq['items'] as $n => $default ) {
		$question = thinksme_field( "faq_item_{$n}_question", false, $default['question'] );

		if ( '' === trim( $question ) ) {
			continue;
		}

		$items[] = array(
			'question' => $question,
			'answer'   => thinksme_field( "faq_item_{$n}_answer", false, $default['answer'] ),
		);
	}
}

if ( ! $items ) {
	$faqs = new WP_Query(
		array(
			'post_type'      => 'faq_item',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order date',
			'order'          => 'ASC',
		)
	);

	if ( $faqs->have_posts() ) {
		while ( $faqs->have_posts() ) :
			$faqs->the_post();
			$items[] = array(
				'question' => get_the_title(),
				'answer'   => thinksme_field( 'answer', get_the_ID() ),
			);
		endwhile;
		wp_reset_postdata();
	}
}

if ( ! $items ) {
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
					<?php echo esc_html( thinksme_field( 'faq_hat_text', false, empty( $page_faq['hat'] ) ? 'Common Questions' : $page_faq['hat'] ) ); ?>
				</span>
				<h2 class="font-medium text-2xl lg:text-3xl leading-tight text-text-primary" style="letter-spacing: -0.01em;">
					<?php echo esc_html( thinksme_field( 'faq_heading', false, empty( $page_faq['heading'] ) ? 'Questions Singapore Business Owners Ask Us First' : $page_faq['heading'] ) ); ?>
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

		<div class="faq-collage hidden lg:block relative w-full max-w-[548px] mx-auto lg:mx-0 aspect-[548/894] lg:flex-[46_1_0%]" aria-hidden="true">
			<img
				src="<?php echo esc_url( $photo_url ); ?>"
				alt="<?php echo esc_attr( $photo_alt ); ?>"
				loading="lazy"
				class="absolute inset-0 w-full h-full rounded-[40px] lg:rounded-[60px] object-cover"
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
