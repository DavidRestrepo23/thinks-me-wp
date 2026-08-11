<?php
/**
 * Template Name: Company Incorporation Local
 *
 * Company Incorporation Local page. Sections in the order the client asked for
 * (Figma file "Untitled", vzdpOnH1U36oXcFcugiyE5): the page hero, the
 * certifications logo marquee the homepage and ROA also run under their heroes,
 * the pricing packages (85:1351), the free-tools tabs (85:1665), the four
 * what's-included cards (85:1703), the two routes to getting started (85:1757),
 * the "Why Local Founders Choose Think SME" bento (85:1830), the Google Reviews
 * slider, then the page's editor content if any, the shared FAQ, and the final
 * CTA the rest of the site ends on.
 *
 * The five designed sections are separate frames on the Figma canvas, not an
 * assembled page, so Figma defines no sequence — this order is the client's.
 *
 * `logos-slider.php` is passed the same `certifications` logo_group it gets in
 * the same slot on the homepage and on ROA, so all three stay in step and none
 * shows the client logos that belong to the other group.
 *
 * `testimonials.php`, `faq.php` and `cta.php` are shared verbatim with the
 * homepage and ROA — same `testimonial` / `faq_item` CPTs, same Swiper loop and
 * autoplay, same native <details> accordion. Only their hat and heading differ,
 * and they read those from `testimonials_*` / `faq_*` / `cta_*`, field names
 * this page's ACF group repeats exactly as ROA's does. That is what keeps the
 * template parts themselves page-agnostic.
 *
 * Auto-used for the page with slug "company-incorporation-local"; also
 * selectable as a page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Company Incorporation Local
 * Page Content" ACF group (acf-json/group_thinksme_ci.json), which is located on
 * this page template — so the template has to be selected in Page Attributes for
 * the fields to appear; matching the slug alone doesn't trigger the group.
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
