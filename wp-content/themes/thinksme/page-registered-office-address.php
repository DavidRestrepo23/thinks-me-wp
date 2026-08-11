<?php
/**
 * Template Name: Registered Office Address
 *
 * Registered Office Address page. Sections in Figma order (file "Untitled",
 * vzdpOnH1U36oXcFcugiyE5): hero (67:49), the certifications logo marquee the
 * homepage also runs under its hero, the plan — pricing card plus section copy
 * (67:109), the navy services block with expanding cards (67:211), the
 * scroll-driven "Why Your Business Needs…" progress rail (68:564), the Google
 * Reviews slider the homepage also uses, then the page's editor content if any,
 * the shared FAQ, and the final CTA the rest of the site ends on.
 *
 * `logos-slider.php` is passed the same `certifications` logo_group it gets in
 * the same slot on the homepage, so the two stay in step and neither shows the
 * client logos that belong to the other group.
 *
 * `testimonials.php` and `faq.php` are shared verbatim with the homepage — same
 * `testimonial` / `faq_item` CPTs, same Swiper loop and autoplay, same native
 * <details> accordion. Only their hat and heading differ, and they read those
 * from `testimonials_*` / `faq_*`, field names this page's ACF group repeats
 * exactly as it repeats the `cta_*` names. That is what keeps the template
 * parts themselves page-agnostic.
 *
 * Auto-used for the page with slug "registered-office-address"; also selectable
 * as a page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Registered Office Address
 * Page Content" ACF group (acf-json/group_thinksme_roa.json), which is located
 * on this page template — so the template has to be selected in Page Attributes
 * for the fields to appear; matching the slug alone doesn't trigger the group.
 *
 * The editor content renders between the services block and the CTA when it
 * isn't empty. The design has no slot for it, but silently dropping whatever
 * the client typed into the editor would be worse. No <h1> with it: the hero
 * above already carries the page's heading.
 */

get_header();
?>

<main id="main-content">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/roa-hero' );
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'certifications' ) );
		get_template_part( 'template-parts/roa-plan' );
		get_template_part( 'template-parts/roa-block' );
		get_template_part( 'template-parts/roa-why' );
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
