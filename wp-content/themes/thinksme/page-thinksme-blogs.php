<?php
/**
 * Template Name: Blog Index
 *
 * Blog index — every published post, searchable and filterable by category.
 * Figma frame 136:366 ("Desktop Blog Index"), file "Untitled"
 * (vzdpOnH1U36oXcFcugiyE5). Section order, read off the canvas: `blog-archive-
 * hero` (136:368, the latest post) → `blog-filters` (136:391 top half —
 * search + category pills) → `blog-grid` (136:391 bottom half — the card grid
 * + pager). Header and footer are get_header()/get_footer() verbatim, the
 * same nodes (101:283, 101:729) every other page already renders.
 *
 * Auto-used for the page with slug "thinksme-blogs" (WP's page-{slug}.php
 * template hierarchy — the page already existed, published, with no template
 * assigned, before this file was written). Also selectable as a page template
 * ("Blog Index") so it keeps working if the page is ever renamed; assigning
 * it explicitly is still what makes it show up in Page Attributes, the same
 * ACF-needs-a-selected-template note every other page-*.php template in this
 * theme carries.
 *
 * Three template parts do the real work — template-parts/blog-archive-hero.php,
 * blog-filters.php and blog-grid.php — and are documented there rather than
 * here, the same split single.php's blog-hero.php/blog-toc.php/blog-related.php
 * already use. All render `post` post type content live from the database;
 * nothing about this page's content is hardcoded.
 */

get_header();
?>

<main id="main-content" class="sections-spaced">
	<?php
	get_template_part( 'template-parts/blog-archive-hero' );
	get_template_part( 'template-parts/blog-filters' );
	get_template_part( 'template-parts/blog-grid' );
	?>
</main>

<?php
get_footer();
