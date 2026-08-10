<?php
/**
 * Template Name: Contact Us
 *
 * Contact page. Sections in Figma order (file "Untitled",
 * vzdpOnH1U36oXcFcugiyE5): headline + enquiry form (62:42), offices over a live
 * Google Map (62:126), "We're Hiring" (62:100), then the final CTA the homepage
 * also ends on.
 *
 * Auto-used for the page with slug "contact-us"; also selectable as a page
 * template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields — the "Contact Page Content" ACF group
 * (acf-json/group_thinksme_contact.json) supplies them, and it is located on this
 * page template, so the template has to be selected in Page Attributes for the
 * fields to appear (matching the slug alone doesn't trigger the group).
 *
 * The page's editor content renders between the map and the hiring section when
 * it isn't empty — the design has no slot for it, but silently dropping whatever
 * the client typed into the editor would be worse. No <h1> with it: the form
 * section above already carries the page's heading.
 */

get_header();
?>

<main id="main-content">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/contact-form' );
		get_template_part( 'template-parts/offices-map' );

		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="w-full px-lg lg:px-3xl py-xl">
				<div class="entry-content max-w-[720px]">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;

		get_template_part( 'template-parts/hiring' );
		get_template_part( 'template-parts/cta' );

	endwhile;
	?>
</main>

<?php
get_footer();
