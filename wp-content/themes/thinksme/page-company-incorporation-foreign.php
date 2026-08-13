<?php
/**
 * Template Name: Company Incorporation Foreign
 *
 * Company Incorporation Foreign page. Figma frame 101:846 ("Desktop Company
 * Incorporation Foreign", file "Untitled", vzdpOnH1U36oXcFcugiyE5) — unlike the
 * five loose frames the Local page was built from, this one is an assembled
 * page, and its section order is the order below: hero, the certifications logo
 * marquee, pricing, the free-tools tabs, what's included, the two routes to
 * getting started, the "why" bento, Google Reviews, the FAQ and the final CTA.
 * It matches the order the client asked for on the Local page, which is why the
 * two templates read identically.
 *
 * This page is the Local page with different words and photographs — every
 * section is the same design. So it renders the same `template-parts/ci-*.php`
 * rather than a second copy of them, and the two pages diverge in exactly one
 * place: thinksme_ci_defaults() in inc/ci-content.php, which keys off
 * thinksme_current_template() to hand each part its own set of design
 * fallbacks. See the note at the top of that file for why the copy lives there
 * rather than in a duplicated set of template parts or in the database.
 *
 * `logos-slider.php`, `testimonials.php`, `faq.php` and `cta.php` are shared
 * with the homepage, ROA and the Local page. They read `testimonials_*`,
 * `faq_*` and `cta_*`, field names this page's ACF group repeats exactly as the
 * other pages' groups do — that is what keeps those parts page-agnostic. The
 * final CTA's fallback photo is the one thing this page overrides, through the
 * `thinksme_cta_photo` filter rather than a branch inside `cta.php`.
 *
 * Auto-used for the page with slug "company-incorporation-foreign"; also
 * selectable as a page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Company Incorporation
 * Foreign Page Content" ACF group (acf-json/group_thinksme_cif.json), which is
 * located on this page template — so the template has to be selected in Page
 * Attributes for the fields to appear; matching the slug alone doesn't trigger
 * the group. The group repeats the Local group's field *names* under its own key
 * prefix, which is what lets the shared template parts read either page.
 *
 * The editor content renders between the bento and the FAQ when it isn't empty.
 * The design has no slot for it, but silently dropping whatever the client typed
 * into the editor would be worse. No <h1> with it: the hero above already
 * carries the page's heading.
 */

get_header();
?>

<main id="main-content">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/ci-hero' );
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'certifications' ) );
		get_template_part( 'template-parts/ci-pricing' );
		get_template_part( 'template-parts/ci-tools' );
		get_template_part( 'template-parts/ci-includes' );
		get_template_part( 'template-parts/ci-ways' );
		get_template_part( 'template-parts/ci-why' );
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
