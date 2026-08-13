<?php
/**
 * Template Name: Corporate Secretary
 *
 * Corporate Secretary page. Figma frame 102:1969 ("Desktop Corporate Secretary",
 * file "Untitled", vzdpOnH1U36oXcFcugiyE5) — an assembled page like the Foreign
 * frame, so the order below is the frame's own, read top to bottom: hero, the
 * certifications logo marquee, pricing, the free-tools tabs, what's included, the
 * "why founders choose us" carousel, the two routes to getting started, the "why
 * SMEs switch" bento, Google Reviews, the FAQ and the final CTA.
 *
 * This is the two Company Incorporation pages again with different words and
 * photographs, so it renders the same `template-parts/ci-*.php` rather than a
 * third copy of them, and the pages diverge in one place: thinksme_ci_defaults()
 * in inc/ci-content.php, which keys off thinksme_current_template().
 *
 * It differs from those two in exactly one structural way: `ci-why-slider`, the
 * five-card carousel at Figma 102:2410, which the other frames don't draw. That
 * is a section of its own rather than a variant of `ci-why` — the bento is still
 * on this page too, further down, as "Why SMEs Switch to Think SME". The part
 * renders nothing when a page's content set has no `why_slider`, so it stays
 * harmless in the other two templates.
 *
 * Two smaller differences the design makes, both handled as defaults rather than
 * branches: this page prices two packages instead of three and ships two free
 * tools instead of three (empty slots are skipped), and its what's-included
 * section carries no heading.
 *
 * `logos-slider.php`, `testimonials.php`, `faq.php` and `cta.php` are shared with
 * the homepage and every inner page. They read `testimonials_*`, `faq_*` and
 * `cta_*`, field names this page's ACF group repeats exactly as the other pages'
 * groups do — that is what keeps those parts page-agnostic. The final CTA's
 * fallback photo comes through the `thinksme_cta_photo` filter, not a branch
 * inside `cta.php`.
 *
 * Auto-used for the page with slug "corporate-secretary"; also selectable as a
 * page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Corporate Secretary Page
 * Content" ACF group (acf-json/group_thinksme_cs.json), which is located on this
 * page template — so the template has to be selected in Page Attributes for the
 * fields to appear; matching the slug alone doesn't trigger the group.
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
		get_template_part( 'template-parts/ci-why-slider' );
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
