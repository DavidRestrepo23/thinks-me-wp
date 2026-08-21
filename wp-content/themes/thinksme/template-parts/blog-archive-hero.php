<?php
/**
 * Blog index hero — navy rounded panel, latest published post's photo,
 * title, excerpt and date. Figma: node 136:368 ("Content Block"), "Desktop
 * Blog Index" frame (136:366), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Always the single latest published post, independent of any search/category
 * filter applied to the grid below (page-thinksme-blogs.php) — the hero is
 * "what's new," not "the top result of your filter," the same way a
 * magazine's cover story doesn't change when you flip to a specific section.
 *
 * Renders nothing when the site has no published posts yet rather than
 * falling back to the design's own GST-post mock: unlike chrome (nav items,
 * footer columns) a fabricated headline reads as a real, clickable article
 * that doesn't exist.
 *
 * The small three-dot decoration at the panel's right edge (Figma 136:387) is
 * pure ornament with no client-editable content behind it, so it is a theme
 * asset (assets/images/icons/blog-hero-dots.svg) rather than anything
 * data-driven.
 */

$latest = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $latest->have_posts() ) {
	return;
}

$latest->the_post();
$icons_uri = get_template_directory_uri() . '/assets/images/icons';
?>
<section class="w-full px-lg lg:px-3xl pt-xl lg:pt-[64px]">
	<div class="relative bg-surface-dark rounded-2xl overflow-hidden flex flex-col justify-center gap-xl px-lg py-3xl lg:py-0 lg:px-[56px] lg:min-h-[660px]">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'full', array( 'class' => 'absolute inset-0 w-full h-full object-cover', 'alt' => '' ) ); ?>
		<?php endif; ?>

		<div class="relative z-10 flex flex-col items-start gap-xl max-w-[587px]">
			<a href="<?php the_permalink(); ?>" class="font-medium text-[32px] sm:text-[44px] lg:text-[56px] leading-none tracking-normal lg:tracking-[-0.56px] text-text-on-dark">
				<?php the_title(); ?>
			</a>

			<p class="font-normal text-sm leading-loose text-text-on-dark">
				<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '' ) ); ?>
			</p>

			<div class="flex items-center gap-md">
				<span class="bg-brand-yellow rounded-pill flex items-center justify-center size-[40px] shrink-0">
					<img src="<?php echo esc_url( "$icons_uri/ci/calendar-dots.svg" ); ?>" alt="" aria-hidden="true" class="size-[23px]">
				</span>
				<span class="font-normal text-sm leading-loose text-text-on-dark whitespace-nowrap">
					<?php echo esc_html( get_the_date() ); ?>
				</span>
			</div>
		</div>

		<img
			src="<?php echo esc_url( "$icons_uri/blog-hero-dots.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="hidden lg:block absolute right-[48px] top-1/2 -translate-y-1/2 w-[8px] h-[36px]"
		>
	</div>
</section>
<?php
wp_reset_postdata();
