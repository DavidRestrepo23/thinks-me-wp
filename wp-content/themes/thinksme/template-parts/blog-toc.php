<?php
/**
 * Blog post body: a sticky rail-style table of contents beside the article,
 * an optional embedded-video block, and a share row. Figma: nodes 134:133
 * (rail + TOC), 134:144 (article sections), 134:151 (video block), 134:169
 * (divider) and 134:170 (share row) — all one column on the "Desktop Blog
 * Post" frame (134:113), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Unlike legal-toc.php (one-off legal copy, hardcoded per page), this is a
 * genuine `post` post type: the article is real content the client writes and
 * edits in the block editor. So the sections are parsed straight out of
 * get_the_content() by splitting on <h2> — nothing hardcoded — which is what
 * lets the TOC and its anchors regenerate automatically as the client edits
 * the post. Confirmed against post 567: the design frame mocks six sections,
 * but the live post content actually has seven (a "Pre-Registration Readiness
 * Checklist" section was added after the frame was drawn) — parsing the real
 * content is what makes that show up correctly rather than silently dropping
 * a section or needing this file edited by hand.
 *
 * The video block (Figma 134:151) has no WordPress-native slot the way the
 * hero photo has post-thumbnails, so it is one optional ACF field,
 * `blog_video_url` (group_thinksme_blog_post.json). It renders after the
 * *second* parsed section, matching where Figma draws it on this post, and is
 * skipped entirely when the field is empty — not shown as a dead thumbnail —
 * the same skip-when-empty convention ci-why.php's card 3 and the free-tools
 * button on ci-tools.php use. The design's own stock photo/play-button assets
 * render regardless of whether the URL is set, the same "shows the design
 * before real content exists" fallback testimonials.php and the header nav
 * use — only the click-through is gated on the field.
 *
 * The rail's yellow fill (`[data-toc-fill]`) is positioned by
 * assets/js/toc-scrollspy.js reading the active link's own offsetTop/
 * offsetHeight, not by percentage math against the section count — see that
 * file's header comment. The first link starts `data-active="true"` and the
 * fill starts sized to it in inline style below, so the design's static look
 * (screenshot: first item bold black, rest at 40% opacity) holds before JS
 * runs.
 */

$content = apply_filters( 'the_content', get_the_content() );

preg_match_all( '/<h2[^>]*>(.*?)<\/h2>/is', $content, $matches, PREG_OFFSET_CAPTURE );

$sections = array();
$count    = count( $matches[0] );

for ( $i = 0; $i < $count; $i++ ) {
	$title = wp_strip_all_tags( $matches[1][ $i ][0] );
	$start = $matches[0][ $i ][1] + strlen( $matches[0][ $i ][0] );
	$end   = ( $i + 1 < $count ) ? $matches[0][ $i + 1 ][1] : strlen( $content );

	$sections[] = array(
		'id'    => sanitize_title( $title ),
		'title' => $title,
		'body'  => trim( substr( $content, $start, $end - $start ) ),
	);
}

if ( ! $sections ) {
	return;
}

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$video_url  = thinksme_field( 'blog_video_url' );
$video_icon = get_template_directory_uri() . '/assets/images/icons/blog-play-button.svg';
$video_bg   = get_template_directory_uri() . '/assets/images/blog/video-bg.jpg';

$socials = array(
	'facebook'  => array(
		'label' => __( 'Facebook', 'thinksme' ),
		'url'   => get_theme_mod( 'thinksme_facebook_url', '' ),
		'icon'  => "$icons_uri/social-facebook.svg",
		'size'  => 'size-[20px]',
	),
	'linkedin'  => array(
		'label' => __( 'LinkedIn', 'thinksme' ),
		'url'   => get_theme_mod( 'thinksme_linkedin_url', '' ),
		'icon'  => "$icons_uri/social-linkedin.svg",
		'size'  => 'size-[24px]',
	),
	'pinterest' => array(
		'label' => __( 'Pinterest', 'thinksme' ),
		'url'   => get_theme_mod( 'thinksme_pinterest_url', '' ),
		'icon'  => "$icons_uri/social-pinterest.svg",
		'size'  => 'size-[17px]',
	),
);
?>
<section class="w-full px-lg lg:px-3xl py-xl lg:py-3xl">
	<div class="flex flex-col lg:flex-row items-start gap-xl lg:gap-[64px]" data-toc-scrollspy>
		<nav
			class="w-full lg:w-[220px] shrink-0 lg:sticky lg:top-[120px] lg:self-start relative flex gap-md items-stretch"
			aria-label="<?php esc_attr_e( 'Table of contents', 'thinksme' ); ?>"
		>
			<div class="relative shrink-0 w-[4px] bg-surface-panel rounded-pill" data-toc-rail>
				<span
					class="absolute left-0 w-full bg-brand-yellow rounded-pill"
					data-toc-fill
					style="top:0;height:24px;"
				></span>
			</div>

			<div class="blog-toc flex flex-col gap-xl text-md leading-snug">
				<?php foreach ( $sections as $i => $section ) : ?>
					<a
						href="#<?php echo esc_attr( $section['id'] ); ?>"
						data-toc-link="<?php echo esc_attr( $section['id'] ); ?>"
						data-active="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						class="blog-toc__link"
					>
						<?php echo esc_html( $section['title'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</nav>

		<div class="flex flex-col gap-[48px] min-w-0 grow">
			<?php foreach ( $sections as $i => $section ) : ?>
				<div id="<?php echo esc_attr( $section['id'] ); ?>" data-toc-section class="flex flex-col gap-md scroll-mt-[120px]">
					<h2 class="font-medium text-2xl leading-[1.2] text-text-primary">
						<?php echo esc_html( $section['title'] ); ?>
					</h2>
					<div class="entry-content">
						<?php echo $section['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- apply_filters( 'the_content' ) already ran, same as the_content() elsewhere. ?>
					</div>
				</div>

				<?php if ( 1 === $i && $video_url ) : ?>
					<a
						href="<?php echo esc_url( $video_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="relative block w-full h-[320px] lg:h-[500px] rounded-[40px] lg:rounded-[64px] overflow-hidden"
						aria-label="<?php esc_attr_e( 'Watch the featured video', 'thinksme' ); ?>"
					>
						<img src="<?php echo esc_url( $video_bg ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover">
						<span class="absolute inset-0 flex items-center justify-center">
							<img src="<?php echo esc_url( $video_icon ); ?>" alt="" class="w-[86px] lg:w-[115px] h-auto">
						</span>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>

			<hr class="w-full border-t border-border-soft">

			<div class="flex items-center gap-md">
				<span class="font-semibold text-md uppercase text-text-primary" style="font-family: 'Roboto Condensed', sans-serif;">
					<?php esc_html_e( 'Share', 'thinksme' ); ?>
				</span>
				<div class="flex gap-md items-start">
					<?php foreach ( $socials as $social ) : ?>
						<?php
						$circle_class = 'border border-border-light rounded-pill flex items-center justify-center p-md';
						$icon         = '<img src="' . esc_url( $social['icon'] ) . '" alt="" class="' . esc_attr( $social['size'] ) . '">';
						?>
						<?php if ( $social['url'] ) : ?>
							<a
								href="<?php echo esc_url( $social['url'] ); ?>"
								class="<?php echo esc_attr( $circle_class ); ?>"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php
									/* translators: %s: social network name. */
									echo esc_attr( sprintf( __( 'Share on %s', 'thinksme' ), $social['label'] ) );
								?>"
							>
								<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts. ?>
							</a>
						<?php else : ?>
							<span class="<?php echo esc_attr( $circle_class ); ?>" aria-hidden="true">
								<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts. ?>
							</span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
