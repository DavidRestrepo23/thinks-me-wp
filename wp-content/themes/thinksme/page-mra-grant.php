<?php
/**
 * Template Name: MRA Grant
 *
 * MRA Grant page, Figma frame 130:1489 ("Desktop MRA Grant", file "Untitled",
 * vzdpOnH1U36oXcFcugiyE5 — the same file the PSG Grant frame and the first eight
 * pages came from). Like the Accounting, Corporate Tax, GST, Property Cashout,
 * Business Loan, Remittance, Mortgage and PSG frames this one *is* an assembled
 * page, so the section order below is the design's own, read top to bottom off the
 * canvas:
 *
 *   ci-hero (130:1491 + the photo group 130:1501 + the badge 130:1502) →
 *   ci-statement (130:1512 + 130:1513 + 130:1514) → ci-criteria "covers"
 *   (130:1520 + 130:1515) → ci-requirements (130:1694 + 130:1698) →
 *   ci-accreditations (130:1737) → ci-grid "solutions" (130:1744) → testimonials
 *   (130:1831) → ci-criteria "expect" (130:1798 + 130:1793) → the page's editor
 *   content if any → cta.
 *
 * Eight of those nine are sections an earlier page already had, which is the point
 * of this family: `ci-hero`, `ci-criteria` (twice, the way the PSG page calls it),
 * `ci-requirements` (the pinned progress rail, same markup, same CSS, same
 * assets/js/progress-rail.js, even the same scattered-blocks artwork the Property
 * Cashout and PSG rails draw), `ci-accreditations`, `ci-grid`, `testimonials` and
 * `cta`. None of them was copied; each is called with this page's values from
 * inc/ci-content.php.
 *
 * One part is new and inert everywhere else: `ci-statement`, the centred sentence
 * with the hand-drawn ring around "70%" and a button under it. It is deliberately
 * not `ci-definition` — that section is a pale panel with a heading, a worked
 * example and a photograph, and this frame draws none of those — and deliberately
 * not `cta`, since this frame draws that section too, at the bottom.
 *
 * `ci-criteria` grew one optional block for this frame and one only: both of its
 * instances here close on a button (130:1541, 130:1827) where the two PSG ones
 * close on the list or on a row of partner marks. Everything else that differs
 * between the four instances — copy, photograph, which side it takes, whether
 * there is an intro or a sub-heading — was already a value.
 *
 * Ten sections the other pages have are missing from this frame and so are simply
 * not called: the pricing cards, the free-tools tabs, the two routes, the
 * what's-included cards, the figures band, the calculator, the "why" bento, the
 * card carousel, the logo marquee — and the FAQ. That last one is the difference
 * worth noting against the PSG page: this frame writes no questions at all, so
 * `faq` is neither a set key here nor a call below. Adding the site-wide FAQ would
 * be adding a section the design doesn't have.
 *
 * Auto-used for the page with slug "mra-grant"; also selectable as a page template
 * so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "MRA Grant Page Content" ACF
 * group (acf-json/group_thinksme_mra.json), which is located on this page template
 * — so the template has to be selected in Page Attributes for the fields to appear;
 * matching the slug alone doesn't trigger the group.
 *
 * The editor content renders between the last designed section and the CTA when it
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
		get_template_part( 'template-parts/ci-statement' );
		get_template_part( 'template-parts/ci-criteria', null, array( 'instance' => 'covers' ) );
		get_template_part( 'template-parts/ci-requirements' );
		get_template_part( 'template-parts/ci-accreditations' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'solutions' ) );
		get_template_part( 'template-parts/testimonials' );
		get_template_part( 'template-parts/ci-criteria', null, array( 'instance' => 'expect' ) );

		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="w-full px-lg lg:px-3xl py-xl">
				<div class="entry-content max-w-[720px]">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;

		get_template_part( 'template-parts/cta' );

	endwhile;
	?>
</main>

<?php
get_footer();
