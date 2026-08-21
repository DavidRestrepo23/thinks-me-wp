<?php
/**
 * Blog post footer: "Related Posts" heading, "View All Posts" button, three
 * latest-other-post cards. Figma: node 134:180, "Desktop Blog Post" frame
 * (134:113), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * A real WP_Query of the three latest other published posts, not the design's
 * own three sample titles — those are fine as a *fallback* for chrome the
 * client hasn't filled in yet (nav items, footer columns), but three cards
 * that look like real articles and go nowhere would be worse than an honest
 * empty state. So the section renders nothing when there are no other
 * published posts, which is the actual state of this site today (only this
 * post and the default "Hello world!" sample exist) — it will start showing
 * cards the moment a second real post is published, with no template change
 * needed.
 *
 * Cards are template-parts/blog-card.php, the same partial the blog index
 * grid (page-thinksme-blogs.php) uses — one card component, not two copies of
 * its markup.
 */

$related = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $related->have_posts() ) {
	return;
}

$icons_uri = get_template_directory_uri() . '/assets/images/icons';
?>
<section class="w-full px-lg lg:px-3xl py-xl lg:py-3xl">
	<div class="flex flex-col gap-3xl items-start">
		<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-lg w-full">
			<p class="font-medium text-[40px] lg:text-[64px] leading-none text-text-primary">
				<?php esc_html_e( 'Related Posts', 'thinksme' ); ?>
			</p>

			<a href="<?php echo esc_url( thinksme_blog_index_url() ); ?>" class="btn-split flex items-center w-full sm:w-auto">
				<span class="bg-brand-yellow rounded-sm h-[50px] px-lg inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap grow sm:grow-0">
					<?php esc_html_e( 'View All Posts', 'thinksme' ); ?>
				</span>
				<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
					<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
				</span>
			</a>
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-xl w-full">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				get_template_part( 'template-parts/blog-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
