<?php
/**
 * Template Name: Corporate Tax
 *
 * Corporate Tax page, Figma frame 108:4497 ("Desktop corporate-tax", file
 * "Untitled", vzdpOnH1U36oXcFcugiyE5). Like the Accounting & Bookkeeping frame
 * this one *is* an assembled page, so the section order below is the design's own,
 * read top to bottom off the canvas:
 *
 *   ci-hero (108:4499 + 108:4819) → logos-slider → ci-stats (108:4515) → ci-grid
 *   "needs" (108:4558) → ci-pricing (108:4833) → ci-calculator (108:4603) →
 *   ci-penalties (108:4630) → ci-why-slider (108:4909) → ci-requirements
 *   (108:4664) → testimonials → the page's editor content if any → faq → cta.
 *
 * It renders the same `template-parts/ci-*.php` the other four pages do; the copy
 * and photographs come from the `tax` set in inc/ci-content.php. Three sections
 * are new here and inert elsewhere: `ci-stats`, the navy figures band;
 * `ci-calculator`, the one tool in the theme that computes something; and
 * `ci-penalties`, the late-filing comparison. Four the others have are missing
 * from this frame — the free-tools tabs, the two routes, the what's-included cards
 * and the "why" bento — so they are simply not called.
 *
 * Two sections this frame shares with earlier pages arrive by way of a value
 * rather than new markup. `ci-pricing` is drawn as a two-column split here instead
 * of the navy panel, which is the `layout` default in that set. And "The Same Firm
 * for Compliance, Digital, and Financing" is the heading the Accounting page puts
 * over `ci-grid`, but this frame draws it as the pinned rail — so it is
 * `ci-requirements` on this page, not a second grid instance. Read the design, not
 * the heading.
 *
 * `logos-slider.php` is passed the same `certifications` logo_group it gets in the
 * same slot on the homepage, ROA and the other four ci-* pages, so they all stay
 * in step.
 *
 * `testimonials.php`, `faq.php` and `cta.php` are shared verbatim with every other
 * page — same CPTs, same Swiper loop and autoplay, same native <details>
 * accordion. Only their hat and heading differ, and they read those from
 * `testimonials_*` / `faq_*` / `cta_*`, field names this page's ACF group repeats
 * exactly as the others do. That is what keeps those parts page-agnostic.
 *
 * Auto-used for the page with slug "corporate-tax" (page 514); also selectable as
 * a page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Corporate Tax Page Content"
 * ACF group (acf-json/group_thinksme_ct.json), which is located on this page
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

<main id="main-content">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/ci-hero' );
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'certifications' ) );
		get_template_part( 'template-parts/ci-stats' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'needs' ) );
		get_template_part( 'template-parts/ci-pricing' );
		get_template_part( 'template-parts/ci-calculator' );
		get_template_part( 'template-parts/ci-penalties' );
		get_template_part( 'template-parts/ci-why-slider' );
		get_template_part( 'template-parts/ci-requirements' );
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
