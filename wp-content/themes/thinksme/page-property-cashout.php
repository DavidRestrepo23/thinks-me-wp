<?php
/**
 * Template Name: Property Cashout
 *
 * Property Cashout page, Figma frame 951:8993 ("Desktop Property Cashout", file
 * "Think SME- INTERNAL", CgqSxvxd3aQeQSkLPhc48q) — the first page built from the
 * newer Figma file rather than "Untitled". Like the Accounting, Corporate Tax and
 * GST frames this one *is* an assembled page, so the section order below is the
 * design's own, read top to bottom off the canvas:
 *
 *   ci-hero (951:8995 + 951:9005 + the three figure pills) → logos-slider →
 *   ci-definition (951:9052) → ci-compare (951:9075 + 951:9077) →
 *   ci-requirements (951:9220) → ci-types (951:9448) → ci-stack "benefits"
 *   (951:9557) → ci-eligibility (951:9631) → ci-stack "why" (951:9444…951:9627) →
 *   the page's editor content if any → faq → cta.
 *
 * Only four of those are sections an earlier page already had. `ci-hero` and `cta`
 * are unchanged apart from their copy and photographs; `logos-slider` is passed the
 * new `lenders` logo_group rather than the `certifications` one the other six pages
 * put in that slot, because this frame shows the banks and financiers the page's
 * offer depends on rather than Think SME's own accreditations; and
 * `ci-requirements` is the pinned progress rail from the Corporate Tax page, same
 * markup, same CSS, same assets/js/progress-rail.js — the client asked
 * specifically that "How Does Property Cashout Work in Singapore?" keep behaving
 * the way that one does, and it does because it *is* that one.
 *
 * The other five are new and inert everywhere else: `ci-definition`, the pale
 * panel with the worked example; `ci-compare`, the eight-row table against standard
 * refinancing; `ci-types`, the four LTV columns; `ci-stack`, the overlapping-card
 * frame, called twice with a different `instance`; and `ci-eligibility`, the navy
 * panel whose two tabs reuse the free-tools strip's script.
 *
 * Seven sections the other pages have are missing from this frame and so are simply
 * not called: the pricing cards, the free-tools tabs, the two routes, the
 * what's-included cards, the figures band, the calculator and the "why" bento. So
 * is the Google Reviews slider — this frame draws no testimonials, and adding one
 * would be adding a section the design doesn't have.
 *
 * `faq.php` is shared with every other page but shows this page's own six questions
 * rather than the site-wide `faq_item` CPT, because the frame writes six of its own.
 * That override lives in faq.php and is driven by the `faq` key in this page's
 * content set — see the note there.
 *
 * Auto-used for the page with slug "property-cashout" (page 537); also selectable
 * as a page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Property Cashout Page Content"
 * ACF group (acf-json/group_thinksme_pc.json), which is located on this page
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
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'lenders' ) );
		get_template_part( 'template-parts/ci-definition' );
		get_template_part( 'template-parts/ci-compare' );
		get_template_part( 'template-parts/ci-requirements' );
		get_template_part( 'template-parts/ci-types' );
		get_template_part( 'template-parts/ci-stack', null, array( 'instance' => 'benefits' ) );
		get_template_part( 'template-parts/ci-eligibility' );
		get_template_part( 'template-parts/ci-stack', null, array( 'instance' => 'why' ) );

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
