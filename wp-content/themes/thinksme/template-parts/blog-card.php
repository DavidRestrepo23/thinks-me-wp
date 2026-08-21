<?php
/**
 * One blog post card: thumbnail, title, calendar-dots date, "Read Post"
 * button. Figma: "Company Incorporation Card" nodes repeated throughout
 * 134:180 (single post's Related Posts) and 136:391 (the blog index grid) —
 * same component both places, so it is one file rather than duplicated
 * markup. Must be called from inside The Loop (have_posts()/the_post()); it
 * reads the current post via template tags, the same convention every other
 * part in this theme that renders inside a query loop follows.
 *
 * A card without its own featured image gets a plain panel rather than one of
 * the design's stock photos — see the note in template-parts/blog-related.php
 * on why a generic photo is not substituted for real post content.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';
?>
<div class="bg-surface-panel border border-border-faint rounded-lg flex flex-col gap-xs items-start p-xs">
	<a href="<?php the_permalink(); ?>" class="h-[240px] rounded-lg overflow-hidden relative w-full block bg-surface-white">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', array( 'class' => 'absolute inset-0 w-full h-full object-cover' ) ); ?>
		<?php endif; ?>
	</a>

	<div class="flex flex-col gap-md items-start pt-lg px-lg pb-md w-full">
		<a href="<?php the_permalink(); ?>" class="font-medium text-xl leading-snug text-text-primary">
			<?php the_title(); ?>
		</a>
		<div class="flex items-center gap-md">
			<span class="bg-brand-yellow rounded-pill flex items-center justify-center size-[40px] shrink-0">
				<img src="<?php echo esc_url( "$icons_uri/ci/calendar-dots.svg" ); ?>" alt="" aria-hidden="true" class="size-[23px]">
			</span>
			<span class="font-normal text-sm leading-loose text-text-primary whitespace-nowrap">
				<?php echo esc_html( get_the_date() ); ?>
			</span>
		</div>
	</div>

	<div class="flex flex-col items-start px-lg pb-lg w-full">
		<a href="<?php the_permalink(); ?>" class="border border-border-medium rounded-sm h-[50px] w-full inline-flex items-center justify-center text-sm font-medium text-text-primary">
			<?php esc_html_e( 'Read Post', 'thinksme' ); ?>
		</a>
	</div>
</div>
