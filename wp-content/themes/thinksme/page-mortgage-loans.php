<?php
/**
 * Template Name: Mortgage Loans
 *
 * Mortgage Loans page, Figma frame 124:3265 ("Desktop mortgage-loans", file
 * "Untitled", vzdpOnH1U36oXcFcugiyE5). An assembled frame, so the section order
 * below is the design's own, read top to bottom off the canvas:
 *
 *   ci-hero (124:3267 + 124:3523) → logos-slider (`certifications`, with this
 *   frame's own caption under it, 124:3605) → ci-stats (124:3281) → ci-grid "types"
 *   (124:3326) → ci-ways (124:3537) → ci-grid "why" (124:3399) → ci-grid "decision"
 *   (124:3372) → testimonials → the page's editor content if any → faq → cta.
 *
 * **This is the first page in the family that adds no new template part at all.**
 * Every section on it is one an earlier page already draws; what it needed were six
 * optional bits inside those sections, each rendered only where a set names it:
 *
 *   - `ci-hero`: the promotional pill above the buttons (`ci_hero_promo_text`,
 *     124:3274), and a second brush stroke with its own vector
 *     (`underline_2_file`) — this frame's two strokes are different lengths, and
 *     scaling one to the other's width changes its weight.
 *   - `logos-slider`: the small print under the marquee, read from this page's set
 *     the way faq.php reads its own question list, so the homepage's two calls are
 *     untouched.
 *   - `ci-ways`: a fourth check (the features loop now runs over whatever the set
 *     names) and `ci_ways_plan_note`, the line about which packages qualify.
 *   - `ci-grid`: `heading_class` / `hat_class` for the instance that sits on a navy
 *     panel, and an optional `value` between a card's title and its copy for the two
 *     rate cards.
 *
 * `ci-grid` is called three times, which is what that part is for: "types" is the
 * four centred cards, "why" the navy panel, "decision" the fixed-or-floating pair.
 * Read the drawing, not the heading — all three are the same component.
 *
 * Six sections the other pages have are missing from this frame and so are not
 * called: the pricing table, the free-tools tabs, the calculator, the
 * what's-included cards, the "why" bento and the pinned rail.
 *
 * `faq.php` and `cta.php` are the site-wide ones here, as on the Remittance page:
 * this frame draws the homepage's own six questions and its closing photograph, so
 * the set names neither a `faq` list nor a `cta` image.
 *
 * Auto-used for the page with slug "mortgage-loans" (page 557); also selectable as
 * a page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Mortgage Loans Page Content"
 * ACF group (acf-json/group_thinksme_ml.json), which is located on this page
 * template — so the template has to be selected in Page Attributes for the fields
 * to appear; matching the slug alone doesn't trigger the group.
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
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'certifications' ) );
		get_template_part( 'template-parts/ci-stats' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'types' ) );
		get_template_part( 'template-parts/ci-ways' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'why' ) );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'decision' ) );
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
