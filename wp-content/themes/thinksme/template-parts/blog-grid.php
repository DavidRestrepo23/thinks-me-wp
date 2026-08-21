<?php
/**
 * Blog index post grid + pagination. Figma: node 136:391 (the card grid,
 * "Desktop Blog Index" frame 136:366, file "Untitled",
 * vzdpOnH1U36oXcFcugiyE5) and node 136:806 (the numbered pager).
 *
 * Nine cards per page, three columns — a clean paginated grid, not a literal
 * copy of the Figma canvas. The mock actually scrolls through roughly sixteen
 * cards *plus* a second "hero-shaped" block partway down repeating the same
 * GST post as the top hero — that second block is a copy-paste leftover in
 * the design file (identical title/excerpt/date to 136:368), not a distinct
 * "featured slot mid-grid" pattern any real archive convention calls for, so
 * it isn't reproduced here. Cards are template-parts/blog-card.php, the same
 * partial single.php's Related Posts section uses.
 *
 * Filters come from thinksme_blog_query_vars() — the same read
 * template-parts/blog-filters.php uses, so the two files can never disagree
 * about what's currently selected. `category_name` and `s` are simply left
 * empty in the WP_Query args when unset, which WP_Query already treats as "no
 * filter", so no extra branching is needed for the unfiltered case. The
 * search value is read from `blog_s` (not WordPress' own `s` — see
 * thinksme_blog_query_vars() in functions.php) but still passed to WP_Query
 * as its `s` arg: WP_Query's own `s` param is just "the term to search for",
 * unrelated to the URL-routing conflict that made the *query var name* a
 * problem in the first place.
 *
 * Pagination is WP core's paginate_links() against this page's own URL
 * (thinksme_blog_index_url()) with `paged` as a query arg (not the rewrite
 * page-jump `/page/N/`, which only applies to WordPress' own main query — this
 * grid is a secondary WP_Query on a static page) and the current `blog_s`/
 * `blog_cat` carried through `add_args` so paging preserves the active filter.
 */

$vars = thinksme_blog_query_vars();

$query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 9,
		'paged'          => $vars['paged'],
		's'              => $vars['blog_s'],
		'category_name'  => $vars['blog_cat'],
	)
);
?>
<section class="w-full px-lg lg:px-3xl py-xl lg:py-3xl">
	<?php if ( $query->have_posts() ) : ?>
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-xl w-full">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part( 'template-parts/blog-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<?php
		$icons_uri = get_template_directory_uri() . '/assets/images/icons';
		$links     = paginate_links(
			array(
				'base'      => add_query_arg( 'paged', '%#%', thinksme_blog_index_url() ),
				'format'    => '',
				'current'   => $vars['paged'],
				'total'     => $query->max_num_pages,
				'add_args'  => array_filter(
					array(
						'blog_s'   => $vars['blog_s'],
						'blog_cat' => $vars['blog_cat'],
					)
				),
				'prev_text' => '<img src="' . esc_url( "$icons_uri/caret-right.svg" ) . '" alt="" class="size-[16px] rotate-180">',
				'next_text' => '<img src="' . esc_url( "$icons_uri/caret-right.svg" ) . '" alt="" class="size-[16px]">',
				'type'      => 'array',
			)
		);
		?>

		<?php if ( $links ) : ?>
			<nav class="blog-pager flex items-center justify-center gap-sm mt-3xl" aria-label="<?php esc_attr_e( 'Posts pagination', 'thinksme' ); ?>">
				<?php foreach ( $links as $link ) : ?>
					<?php echo wp_kses_post( $link ); ?>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<p class="font-normal text-md text-text-secondary text-center py-3xl">
			<?php esc_html_e( 'No posts found.', 'thinksme' ); ?>
		</p>
	<?php endif; ?>
</section>
