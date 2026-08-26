<?php
/**
 * Legal-page hero — navy rounded panel, one headline, decorative badge.
 * Figma: node 130:2153, "Desktop Privacy Policy" frame (130:2151), file
 * "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Deliberately its own part rather than a ci-hero.php call: this frame draws no
 * hat, no intro copy, no buttons and no photo — just a title on a navy panel — so
 * forcing it through ci-hero's branches would mean naming defaults for a dozen
 * things this page doesn't have. `title` is passed in via get_template_part()
 * args so a future legal page (Terms of Service, etc.) can reuse this verbatim.
 *
 * The badge is the same "Group 14" sunburst vector already shipped as
 * faq-bulb.svg / hiring-bulb.svg (confirmed identical path data, just exported
 * at a different scale here) — reused rather than re-downloaded, per the theme's
 * own convention of not shipping the same vector twice. It overhangs the panel's
 * top-right and bottom edges by Figma's own offsets (inset
 * -8.76%/-1.71%/-9.52%/78.58% against the 1298x251 panel resolves to a
 * 300x297 box at top:-22px, right:-22px), so the panel has no overflow-hidden —
 * clipping it would cut the badge. Desktop-only: the panel's height is fluid
 * below lg (the title wraps to more lines on a phone), and the badge's offsets
 * are only meaningful against Figma's fixed 251px panel.
 */

$title = isset( $args['title'] ) ? $args['title'] : __( 'ThinkSME Privacy Policy', 'thinksme' );
$icons_uri = get_template_directory_uri() . '/assets/images/icons';
?>
<section class="w-full px-lg lg:px-3xl pt-xl lg:pt-3xl">
	<div class="relative bg-surface-dark rounded-2xl flex flex-col justify-center gap-xs px-lg py-xl lg:px-[64px] lg:py-[20px] lg:min-h-[251px]">
		<h1 class="relative z-10 font-medium text-[40px] sm:text-[48px] lg:text-[72px] leading-[1.04] tracking-normal lg:tracking-[-0.72px] text-text-on-dark lg:max-w-[485px]">
			<?php echo esc_html( $title ); ?>
		</h1>

		<img
			src="<?php echo esc_url( "$icons_uri/faq-bulb.svg" ); ?>"
			alt=""
			aria-hidden="true"
			class="hidden lg:block absolute -top-[22px] -right-[22px] w-[300px] h-[297px] pointer-events-none"
		>
	</div>
</section>
