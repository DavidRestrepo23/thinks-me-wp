<?php
/**
 * Single blog post ('post' post type). Figma frame 134:113 ("Desktop Blog
 * Post"), file "Untitled" (vzdpOnH1U36oXcFcugiyE5) — the first blog template
 * built in this theme; no blog templates existed before this (WP was falling
 * back to index.php for any single post).
 *
 * Section order, read off the canvas: `blog-hero` (134:115, photo + headline)
 * → `blog-toc` (134:133–134:170: the rail TOC, the article itself parsed from
 * the post's own content, the optional video block, the divider, the share
 * row) → `blog-related` (134:180, three latest other posts). Header and
 * footer are get_header()/get_footer() verbatim, the same nodes (101:283,
 * 101:729) every other page already renders.
 *
 * Verified against post 567, "GST Registration Timing for Singapore SMEs":
 * title, featured image and full body content already existed in the
 * database before this template was written — WP was just rendering them
 * through index.php with none of this page's layout. Nothing was populated
 * here; the template only needed writing.
 */

get_header();
?>

<main id="main-content" class="sections-spaced">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/blog-hero' );
		get_template_part( 'template-parts/blog-toc' );
		get_template_part( 'template-parts/blog-related' );

	endwhile;
	?>
</main>

<?php
get_footer();
