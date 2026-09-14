<?php
/**
 * Template Name: About Us
 *
 * About Us page, Figma frame 157:280 ("Desktop About", file "Untitled",
 * vzdpOnH1U36oXcFcugiyE5), read top to bottom off the canvas:
 *
 *   ci-hero (157:289) → about-story (157:314) → ci-grid "drive" (157:513) →
 *   about-promise (157:542) → ci-grid "credentials" (157:702) → ci-stack
 *   "serve" (157:752) → about-mission (157:839) → ci-grid "explore" (157:852)
 *   → the page's editor content if any → the shared cta.
 *
 * Three sections are new — about-story.php (the navy checklist card beside
 * "Our Story"), about-promise.php (the yellow brand-promise banner) and
 * about-mission.php ("Why Think SME Exists") — because none of the existing
 * ci-* parts draw those shapes. Everything else is a value reached through
 * `thinksme_ci_defaults()` in inc/ci-content.php, the same mechanism the other
 * twelve pages built from ci-* parts use:
 *
 *   - `ci-hero` is unchanged, just this page's own copy and photo.
 *   - `ci-grid` is called three times: "What Drive Us" (three cards on a navy
 *     panel — the "Values" card writes three bold-led bullets instead of a
 *     plain paragraph, which is why ci-grid.php grew an optional `items` list
 *     per card), "Real Credentials, Not Just Claims" (a plain four-card
 *     centred grid, the same shape GST Registration's and Corporate Tax's own
 *     instances already draw) and "Explore How We Can Help" (four cards on a
 *     navy panel, each closing on its own "Learn More" link — the other
 *     reason ci-grid.php grew an optional per-card button).
 *   - `ci-stack` is unchanged: "Who We Serve" is the same overlapping-card
 *     column Property Cashout and Business Loan already draw, just with six
 *     cards instead of five and the photo on the opposite side.
 *
 * This page is not one of service pages the other eleven ci-* templates
 * are — it has no pricing, no free tools, no what's-included cards, no
 * pinned rail and no Google Reviews slider, so none of those parts are
 * called and this page needs no page-specific JavaScript at all: `ci-hero`,
 * `ci-grid` and `ci-stack` are all markup-and-CSS, the same as everywhere
 * else they're used.
 *
 * Page 635 already existed (published, slug "about-us", no template chosen)
 * with the generic `group_thinksme_page_cta` meta on it — the same trap pages
 * 559 and 562 set — so its `cta_text` and `cta_button_link` needed real values
 * rather than the placeholder ones; `cta_title` already read "Need help?",
 * which is this frame's own line, so it needed no change.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "About Us Page Content" ACF
 * group (acf-json/group_thinksme_about.json), which is located on this page
 * template — so the template has to be selected in Page Attributes for the
 * fields to appear; matching the slug alone doesn't trigger the group.
 *
 * The editor content renders between the last designed section and the CTA
 * when it isn't empty. The design has no slot for it, but silently dropping
 * whatever the client typed into the editor would be worse.
 */

get_header();
?>

<main id="main-content" class="sections-spaced">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/ci-hero' );
		get_template_part( 'template-parts/about-story' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'drive' ) );
		get_template_part( 'template-parts/about-promise' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'credentials' ) );
		get_template_part( 'template-parts/ci-stack', null, array( 'instance' => 'serve' ) );
		get_template_part( 'template-parts/about-mission' );
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'explore' ) );

		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="w-full px-lg lg:px-3xl py-xl">
				<div class="entry-content max-w-[720px]">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;

		get_template_part( 'template-parts/cta' );

	endwhile;
	?>
</main>

<?php
get_footer();
