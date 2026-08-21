<?php
/**
 * Blog index search + category filter row. Figma: node 136:391 (top half —
 * the search field and the pill strip), "Desktop Blog Index" frame (136:366),
 * file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Figma draws three example category pills ("Government Grant", "Corporate
 * Secretary", "Business Loan") that don't exist as real categories on this
 * site yet (only the default "Uncategorized" does) — those are placeholder
 * names for the mock, not real taxonomy to create. The pill strip is built
 * from get_categories() instead, so it lists whatever categories the client
 * actually uses and grows on its own as they're added; a category with no
 * posts is skipped rather than shown as a dead filter.
 *
 * Both controls are a single GET form/links to the blog index's own URL
 * (thinksme_blog_index_url()) reading `blog_s` and `blog_cat` — see
 * thinksme_blog_query_vars() in functions.php for why the search field is
 * `blog_s` rather than WordPress' own `s` (short version: `s` on a static
 * page's pretty-permalink URL gets parsed as a global search request and
 * 404s). Each category link carries the current search term forward, and the
 * search form carries the current category forward as a hidden field, so
 * combining both filters works.
 */

$vars           = thinksme_blog_query_vars();
$current_search = $vars['blog_s'];
$current_cat    = $vars['blog_cat'];

$index_url = thinksme_blog_index_url();
$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$categories = get_categories(
	array(
		'hide_empty' => true,
		'object_type' => 'post',
	)
);
?>
<section class="w-full px-lg lg:px-3xl pt-xl lg:pt-3xl">
	<div class="flex flex-col lg:flex-row gap-lg items-stretch lg:items-center w-full">
		<form method="get" action="<?php echo esc_url( $index_url ); ?>" class="flex-1 min-w-0">
			<?php if ( $current_cat ) : ?>
				<input type="hidden" name="blog_cat" value="<?php echo esc_attr( $current_cat ); ?>">
			<?php endif; ?>
			<label class="bg-surface-faint border border-border-faint rounded-sm flex items-center gap-md px-lg py-md w-full">
				<img src="<?php echo esc_url( "$icons_uri/ci/magnifying-glass.svg" ); ?>" alt="" aria-hidden="true" class="size-[24px] shrink-0">
				<input
					type="search"
					name="blog_s"
					value="<?php echo esc_attr( $current_search ); ?>"
					placeholder="<?php esc_attr_e( 'Search Posts', 'thinksme' ); ?>"
					class="bg-transparent border-0 outline-none w-full font-normal text-sm leading-loose text-text-primary placeholder:text-text-primary"
				>
			</label>
		</form>

		<div class="flex flex-wrap gap-sm items-center">
			<?php
			$pills   = array();
			$pills[] = array(
				'label'  => __( 'All Posts', 'thinksme' ),
				'active' => '' === $current_cat,
				'href'   => add_query_arg( array_filter( array( 'blog_s' => $current_search ) ), $index_url ),
			);

			foreach ( $categories as $category ) {
				$pills[] = array(
					'label'  => $category->name,
					'active' => $current_cat === $category->slug,
					'href'   => add_query_arg(
						array_filter( array( 'blog_s' => $current_search, 'blog_cat' => $category->slug ) ),
						$index_url
					),
				);
			}

			foreach ( $pills as $pill ) :
				$pill_class = $pill['active']
					? 'bg-brand-yellow'
					: 'bg-surface-panel border border-border-faint';
				?>
				<a
					href="<?php echo esc_url( $pill['href'] ); ?>"
					class="<?php echo esc_attr( $pill_class ); ?> rounded-pill inline-flex items-center justify-center px-lg py-sm font-medium text-sm leading-loose text-text-primary whitespace-nowrap"
				>
					<?php echo esc_html( $pill['label'] ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
