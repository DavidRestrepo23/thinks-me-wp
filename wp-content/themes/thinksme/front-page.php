<?php
/**
 * Homepage. Assembled from template-parts in Figma order. The header, hero and
 * gallery sections were removed, so the page now opens on the certifications
 * logo strip.
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit
 * post ID reads the ACF field group assigned to this page (Home) in the
 * WP admin — Settings > Reading > "A static page" must point here.
 */

get_header();
?>

<main id="main-content">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/hero' );
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'certifications' ) );
		get_template_part( 'template-parts/cards-deck' );
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'clients' ) );
		get_template_part( 'template-parts/cards-stack' );
		get_template_part( 'template-parts/testimonials' );
		get_template_part( 'template-parts/faq' );
		get_template_part( 'template-parts/cta' );

	endwhile;
	?>
</main>

<?php
get_footer();
