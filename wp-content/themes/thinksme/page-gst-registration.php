<?php
/**
 * Template Name: GST Registration
 *
 * GST Registration page, Figma frame 114:5287 ("Desktop gst-registration", file
 * "Untitled", vzdpOnH1U36oXcFcugiyE5). Like the Accounting & Bookkeeping and
 * Corporate Tax frames this one *is* an assembled page, so the section order
 * below is the design's own, read top to bottom off the canvas:
 *
 *   ci-hero (114:5289 + 114:5740) → logos-slider → ci-grid "needs" (114:5311) →
 *   ci-pricing (114:5344) → ci-penalties (114:5653) → ci-includes (114:5777) →
 *   ci-why-slider (114:5754) → ci-why (114:5834) → testimonials → the page's
 *   editor content if any → faq → cta.
 *
 * It is the sixth page built out of the same `template-parts/ci-*.php`; the copy
 * and photographs come from the `gst` set in inc/ci-content.php. It adds no
 * section of its own — every frame on it is one an earlier page already draws —
 * and it leaves out four the others have: the free-tools tabs, the two routes,
 * the figures band and the pinned rail, which are simply not called.
 *
 * Two sections it shares with earlier pages arrive by way of a value rather than
 * new markup, and both are worth reading carefully. "The Cost of Missing a
 * Filing" is `ci-penalties`, the Corporate Tax comparison, with no escalation
 * stages under it — the `steps` key is empty, so that half of the part is
 * skipped. And "The Same Firm for Compliance, Digital, and Financing" is the
 * heading the Corporate Tax frame draws as the pinned rail, but this frame draws
 * it as the five-card bento — so it is `ci-why` here, not `ci-requirements`. Read
 * the drawing, not the heading.
 *
 * Three optional bits inside two other sections are this frame's own, all of them
 * defaults rather than branches: the hero's promotional price line (114:5296),
 * the registration card's struck-through usual price with its terms (114:5518),
 * and the small print closing the pricing panel (114:5502). Each renders only
 * where a set names it, so the other five pages are unchanged.
 *
 * `logos-slider.php` is passed the same `certifications` logo_group it gets in
 * the same slot on the homepage, ROA and the other five ci-* pages, so they all
 * stay in step.
 *
 * `testimonials.php`, `faq.php` and `cta.php` are shared verbatim with every
 * other page — same CPTs, same Swiper loop and autoplay, same native <details>
 * accordion. Only their hat and heading differ, and they read those from
 * `testimonials_*` / `faq_*` / `cta_*`, field names this page's ACF group repeats
 * exactly as the others do. That is what keeps those parts page-agnostic. The
 * final CTA's fallback photo comes through the `thinksme_cta_photo` filter, not a
 * branch inside `cta.php`.
 *
 * Auto-used for the page with slug "gst-registration" (page 516); also selectable
 * as a page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "GST Registration Page
 * Content" ACF group (acf-json/group_thinksme_gst.json), which is located on this
 * page template — so the template has to be selected in Page Attributes for the
 * fields to appear; matching the slug alone doesn't trigger the group.
 *
 * `<main>` carries `sections-spaced`, as the Accounting & Bookkeeping page's does:
 * the sections' own padding alone reads as one continuous column on this frame,
 * so every section after the hero takes 80px more air from `lg` (40px below it).
 *
 * The editor content renders between the bento and the FAQ when it isn't empty.
 * The design has no slot for it, but silently dropping whatever the client typed
 * into the editor would be worse. No <h1> with it: the hero above already carries
 * the page's heading.
 */

get_header();
?>

<?php // `sections-spaced` adds 40px above every section after the first, 80px from lg — see the note on that class in src/base.css. A class on this page's <main> rather than margins inside the template parts, which are shared with five other pages, and rather than a body-class selector, because `page-template-*` only exists when the template was picked in Page Attributes (the trap thinksme_current_template() documents). ?>
<main id="main-content" class="sections-spaced">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/ci-hero' );
		get_template_part( 'template-parts/logos-slider', null, array( 'group' => 'certifications' ) );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'needs' ) );
		get_template_part( 'template-parts/ci-pricing' );
		get_template_part( 'template-parts/ci-penalties' );
		get_template_part( 'template-parts/ci-includes' );
		get_template_part( 'template-parts/ci-why-slider' );
		get_template_part( 'template-parts/ci-why' );
		get_template_part( 'template-parts/testimonials' );

		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="w-full px-lg lg:px-3xl py-xl">
				<div class="entry-content max-w-[720px]">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;

		get_template_part( 'template-parts/faq' );
		get_template_part( 'template-parts/cta' );

	endwhile;
	?>
</main>

<?php
get_footer();
