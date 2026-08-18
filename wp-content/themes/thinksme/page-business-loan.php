<?php
/**
 * Template Name: Business Loan
 *
 * Business Loan page, Figma frame 119:1711 ("Desktop Business Loan", file
 * "Untitled", vzdpOnH1U36oXcFcugiyE5) — back in the file the first six pages came
 * from, after the Property Cashout page took its frame from the newer one. The
 * fifth assembled frame, so the section order below is the design's own, read top
 * to bottom off the canvas:
 *
 *   ci-hero (119:1713 + 119:1723 + the partner-bank strip 119:1737) → ci-stats
 *   (119:1751) → ci-steps (119:1796) → ci-calculator (119:1825) → ci-grid
 *   "approval" (119:1871) → ci-ways (119:1932) → ci-stack "bankers" (119:1991 +
 *   119:1995 + 119:2179) → ci-stack "why" (119:2182) → ci-types (119:2043) →
 *   testimonials → the page's editor content if any → faq → cta.
 *
 * Only one section here is new: `ci-steps`, the three-card row whose middle card is
 * open. It is template-parts/roa-block.php's interaction on this frame's palette,
 * so the two share assets/js/expand-cards.js — what roa-block.js became — and differ
 * only in CSS.
 *
 * Every other section is one an earlier page already draws, reached through a value
 * rather than new markup — the mechanism inc/ci-content.php exists for:
 *
 *   - `ci-hero` grew an optional partner-bank strip under the photograph.
 *   - `ci-stats` grew the footnote this frame writes under the four figures: the
 *     headline rate is a *flat* rate, and the small print is what says so.
 *   - `ci-calculator` grew a second mode. The Corporate Tax panel computes tax from
 *     one field and an exemption scheme; this one computes a flat-rate instalment
 *     from an amount, a tenure and a rate. `mode` is a per-page default, like
 *     ci-pricing's `layout`.
 *   - `ci-grid` grew an intro line and a three-column track. "What Actually
 *     Determines Loan Approval" is five icon cards with a photograph in the fifth
 *     cell, which is the Accounting page's "Same Firm" grid, not a new section.
 *   - `ci-ways` is unchanged: this frame's "One Application, Matched Against 60+
 *     Lenders" is that section exactly — two contact cards beside a summary card —
 *     with the card's price and pills left empty, which the part already skips.
 *   - `ci-stack` is unchanged and called twice, as on Property Cashout.
 *   - `ci-types` grew a checklist per card and a three-column track. "Financing
 *     Options at a Glance" is the property-types row with loan ceilings in place of
 *     LTVs.
 *
 * Six sections the other pages have are missing from this frame and so are simply
 * not called: the pricing table, the free-tools tabs, the what's-included cards,
 * the "why" bento, the card carousel and the pinned rail. **So is the logo
 * marquee** — this frame's logos are the five partner banks in the hero, not the
 * full-width `logos-slider` strip, so that part is not called either.
 *
 * `faq.php` shows this page's own six questions rather than the site-wide
 * `faq_item` CPT, because the frame writes six of its own about business lending.
 * That override lives in faq.php and is driven by the `faq` key in this page's
 * content set — the same arrangement the Property Cashout page introduced.
 *
 * Auto-used for the page with slug "business-loan" (page 553); also selectable as a
 * page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Business Loan Page Content" ACF
 * group (acf-json/group_thinksme_bl.json), which is located on this page template —
 * so the template has to be selected in Page Attributes for the fields to appear;
 * matching the slug alone doesn't trigger the group.
 *
 * The editor content renders between the last designed section and the FAQ when it
 * isn't empty. The design has no slot for it, but silently dropping whatever the
 * client typed into the editor would be worse. No <h1> with it: the hero above
 * already carries the page's heading.
 */

get_header();
?>

<main id="main-content" class="sections-spaced">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/ci-hero' );
		get_template_part( 'template-parts/ci-stats' );
		get_template_part( 'template-parts/ci-steps' );
		get_template_part( 'template-parts/ci-calculator' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'approval' ) );
		get_template_part( 'template-parts/ci-ways' );
		get_template_part( 'template-parts/ci-stack', null, array( 'instance' => 'bankers' ) );
		get_template_part( 'template-parts/ci-stack', null, array( 'instance' => 'why' ) );
		get_template_part( 'template-parts/ci-types' );
		get_template_part( 'template-parts/testimonials' );

		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="w-full px-lg lg:px-3xl py-xl">
				<div class="entry-content max-w-[720px]">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;

		get_template_part( 'template-parts/faq' );
		get_template_part( 'template-parts/cta' );

	endwhile;
	?>
</main>

<?php
get_footer();
