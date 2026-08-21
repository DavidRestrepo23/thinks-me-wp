<?php
/**
 * Template Name: PSG Grant
 *
 * PSG Grant page, Figma frame 127:512 ("Desktop PSG Grant", file "Untitled",
 * CgqSxvxd3aQeQSkLPhc48q's sibling — vzdpOnH1U36oXcFcugiyE5, the same file the
 * first eight pages came from). Like the Accounting, Corporate Tax, GST, Property
 * Cashout, Business Loan, Remittance and Mortgage frames this one *is* an
 * assembled page, so the section order below is the design's own, read top to
 * bottom off the canvas:
 *
 *   ci-hero (127:514 + the photo group + the badge 127:527) → ci-accreditations
 *   (127:895) → ci-grid "benefits" (127:537) → ci-grid "features" (127:574 +
 *   127:934) → ci-criteria "criteria" (127:580 + 127:618) → ci-requirements
 *   (127:627) → ci-criteria "invoicenow" (127:901 + 127:622) → ci-grid
 *   "credentials" (127:821) → testimonials → the page's editor content if any →
 *   faq → cta.
 *
 * Nine of those eleven are sections an earlier page already had, which is the point
 * of this family: `ci-hero`, `ci-grid` (three instances — the navy benefits band,
 * the eight feature cards and the four credential cards), `ci-requirements` (the
 * pinned progress rail, same markup, same CSS, same assets/js/progress-rail.js,
 * even the same scattered-blocks artwork the Property Cashout rail draws),
 * `testimonials`, `faq` and `cta`. None of them was copied; each is called with
 * this page's values from inc/ci-content.php.
 *
 * Two parts are new and inert everywhere else: `ci-criteria`, the green-ticked list
 * beside a photograph, called twice with a different `instance` the way `ci-stack`
 * and `ci-grid` are; and `ci-accreditations`, the two credential marks between
 * rules under the hero.
 *
 * Nine sections the other pages have are missing from this frame and so are simply
 * not called: the pricing cards, the free-tools tabs, the two routes, the
 * what's-included cards, the figures band, the calculator, the "why" bento, the
 * card carousel — and the logo marquee, because this page's credentials are the
 * two-mark strip above rather than a carousel of client logos.
 *
 * `faq.php` is shared with every other page but shows this page's own six questions
 * rather than the site-wide `faq_item` CPT, because the frame writes six of its own.
 * That override lives in faq.php and is driven by the `faq` key in this page's
 * content set — see the note there. Its hat and heading come from the same key, one
 * word off the site-wide line.
 *
 * Auto-used for the page with slug "psg-grant"; also selectable as a page template
 * so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "PSG Grant Page Content" ACF
 * group (acf-json/group_thinksme_psg.json), which is located on this page template
 * — so the template has to be selected in Page Attributes for the fields to appear;
 * matching the slug alone doesn't trigger the group.
 *
 * The editor content renders between the last designed section and the FAQ when it
 * isn't empty. The design has no slot for it, but silently dropping whatever the
 * client typed into the editor would be worse. No <h1> with it: the hero above
 * already carries the page's heading.
 */

get_header();
?>

<main id="main-content" class="sections-spaced">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/ci-hero' );
		get_template_part( 'template-parts/ci-accreditations' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'benefits' ) );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'features' ) );
		get_template_part( 'template-parts/ci-criteria', null, array( 'instance' => 'criteria' ) );
		get_template_part( 'template-parts/ci-requirements' );
		get_template_part( 'template-parts/ci-criteria', null, array( 'instance' => 'invoicenow' ) );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'credentials' ) );
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
