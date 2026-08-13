<?php
/**
 * Template Name: Accounting & Bookkeeping
 *
 * Accounting & Bookkeeping page, Figma frame 102:3127 ("Desktop Accounting &
 * Bookkeeping", file "Untitled", vzdpOnH1U36oXcFcugiyE5). Unlike the three
 * Company Incorporation frames, this one *is* an assembled page, so the section
 * order below is the design's own, read top to bottom off the canvas:
 *
 *   ci-hero (102:3129 + 102:3844) → logos-slider → ci-grid "switching"
 *   (102:3146) → ci-pricing (102:3213) → ci-includes (102:4070) → ci-why
 *   (102:3858) → ci-requirements (102:3515) → ci-grid "same_firm" (102:3726) →
 *   testimonials → the page's editor content if any → faq → cta.
 *
 * It renders the same `template-parts/ci-*.php` the other three pages do; the
 * copy and photographs come from the `accounting` set in inc/ci-content.php.
 * Two sections are new here and inert elsewhere: `ci-grid`, called twice with
 * different instances, and `ci-requirements`, the scroll-pinned rail. Two the
 * others have are missing from this frame — the free-tools tabs and the two
 * routes — so they are simply not called.
 *
 * `logos-slider.php` is passed the same `certifications` logo_group it gets in
 * the same slot on the homepage, ROA and the three incorporation pages, so they
 * all stay in step.
 *
 * `testimonials.php`, `faq.php` and `cta.php` are shared verbatim with every
 * other page — same CPTs, same Swiper loop and autoplay, same native <details>
 * accordion. Only their hat and heading differ, and they read those from
 * `testimonials_*` / `faq_*` / `cta_*`, field names this page's ACF group
 * repeats exactly as the others do. That is what keeps those parts page-agnostic.
 *
 * Auto-used for the page with slug "accounting-bookkeeping" (page 512); also
 * selectable as a page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Accounting & Bookkeeping
 * Page Content" ACF group (acf-json/group_thinksme_ab.json), which is located on
 * this page template — so the template has to be selected in Page Attributes for
 * the fields to appear; matching the slug alone doesn't trigger the group.
 *
 * The editor content renders between the last designed section and the FAQ when
 * it isn't empty. The design has no slot for it, but silently dropping whatever
 * the client typed into the editor would be worse. No <h1> with it: the hero
 * above already carries the page's heading.
 */

get_header();
?>

<?php // `sections-spaced` adds 40px above every section after the first — see the note on that class in src/base.css. It is a class on this page's <main> rather than a rule per template part because those parts are shared with four other pages, and rather than a body-class selector because `page-template-*` only exists when the template was picked in Page Attributes, which is the trap thinksme_current_template() documents. ?>
<main id="main-content" class="sections-spaced">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/ci-hero' );
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'certifications' ) );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'switching' ) );
		get_template_part( 'template-parts/ci-pricing' );
		get_template_part( 'template-parts/ci-includes' );
		get_template_part( 'template-parts/ci-why' );
		get_template_part( 'template-parts/ci-requirements' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'same_firm' ) );
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
