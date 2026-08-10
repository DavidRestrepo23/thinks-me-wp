<?php
/**
 * Generic page template — used by every page that has no template of its own
 * (Contact Us has page-contact-us.php; the static front page has front-page.php).
 *
 * The design has no frame for a plain content page, so this renders the page
 * title, the editor content, and the final CTA the rest of the site ends on,
 * using the existing tokens and section padding rather than inventing a layout.
 * Rich text coming out of the editor is styled by `.entry-content` in
 * src/base.css — Tailwind's preflight strips headings and lists, so without
 * those rules the client's content renders flat.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. The CTA's cta_* fields come from the "Page
 * Content" ACF group (acf-json/group_thinksme_page_cta.json), which is located
 * on the default page template and explicitly excludes the front page — the
 * front page also uses the default template, so without that exclusion its
 * cta_* fields would appear twice, once from each group.
 *
 * Sections are direct children of #main-content so assets/js/scroll-reveal.js
 * picks them up like every other section on the site.
 */

get_header();
?>

<main id="main-content">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<section class="w-full px-lg lg:px-3xl pt-xl md:pt-3xl pb-xl">
			<div class="flex flex-col gap-lg max-w-[720px]">
				<h1 class="font-medium text-2xl lg:text-3xl leading-tight text-text-primary" style="letter-spacing: -0.01em;">
					<?php the_title(); ?>
				</h1>

				<?php if ( '' !== trim( get_the_content() ) ) : ?>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<?php
		get_template_part( 'template-parts/cta' );

	endwhile;
	?>
</main>

<?php
get_footer();
