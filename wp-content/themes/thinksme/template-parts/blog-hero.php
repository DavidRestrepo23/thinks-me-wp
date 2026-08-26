<?php
/**
 * Blog post hero — navy rounded panel, full-bleed featured photo, centred
 * headline, calendar-dots date pill. Figma: node 134:115 ("Content Block"),
 * "Desktop Blog Post" frame (134:113), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * The photo is the post's native Featured Image, not an ACF field — WordPress
 * already has that mechanism (add_theme_support('post-thumbnails') in
 * functions.php) and every other page in the theme that needs a client-editable
 * photo either uses an ACF image field or, here, the more idiomatic
 * post-thumbnail route for an actual `post` post type. Post 567 ("GST
 * Registration Timing…") already ships with attachment 569 as its thumbnail —
 * a pre-composited export (photo + the design's rgba(24,52,88,0.55) navy tint
 * + rounded corners already baked in, confirmed by opening the file), the same
 * "stock export already has everything composed" situation ci-hero.php
 * documents for several other pages. So it is drawn as a plain object-cover
 * fill with no second overlay on top — adding one would double-darken it.
 *
 * Without a featured image the panel still renders — plain navy, no photo —
 * rather than reaching for a generic stock photo: unlike the marketing pages'
 * hero images (which stand in for a specific product shot Figma always draws
 * something in), a blog post's photo is that post's own editorial content, and
 * a placeholder here would need to be swapped per-post anyway.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';
$has_photo = has_post_thumbnail();
?>
<section class="w-full px-lg lg:px-3xl pt-xl lg:pt-[64px]">
	<div class="relative bg-surface-dark rounded-2xl overflow-hidden flex flex-col items-center justify-center gap-xl px-lg py-3xl lg:min-h-[520px]">
		<?php if ( $has_photo ) : ?>
			<?php the_post_thumbnail( 'full', array( 'class' => 'absolute inset-0 w-full h-full object-cover', 'alt' => '' ) ); ?>
		<?php endif; ?>

		<div class="relative z-10 flex flex-col items-center justify-center gap-xl text-center">
			<h1 class="font-medium text-[40px] sm:text-[44px] lg:text-[56px] leading-none tracking-normal lg:tracking-[-0.56px] text-text-on-dark max-w-[861px]">
				<?php the_title(); ?>
			</h1>

			<div class="flex items-center gap-md">
				<span class="bg-brand-yellow rounded-pill flex items-center justify-center size-[40px] shrink-0">
					<img src="<?php echo esc_url( "$icons_uri/ci/calendar-dots.svg" ); ?>" alt="" aria-hidden="true" class="size-[23px]">
				</span>
				<span class="font-normal text-sm leading-loose text-text-on-dark whitespace-nowrap">
					<?php echo esc_html( get_the_date() ); ?>
				</span>
			</div>
		</div>
	</div>
</section>
