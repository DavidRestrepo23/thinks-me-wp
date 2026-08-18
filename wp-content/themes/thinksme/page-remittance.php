<?php
/**
 * Template Name: Remittance
 *
 * Remittance page, Figma frame 123:2687 ("Desktop Remittance", file "Untitled",
 * vzdpOnH1U36oXcFcugiyE5). The shortest frame in the family — four sections and
 * the footer — so its order is read straight off the canvas:
 *
 *   ci-hero (123:2689 + 123:2701) → ci-grid "why_ofx" (123:2715) → ci-signup
 *   (123:2747 + 123:2748 + the two flanking photographs) → the page's editor
 *   content if any → faq → cta.
 *
 * It is a partner page rather than a service page: the offer is OFX's rates
 * through Think SME. That is why the hero opens on OFX's logo where every other
 * frame draws a hat pill — a pill reading "OFX" above a headline reading "Save
 * with OFX" would say it twice — and the logo is a per-page default rather than a
 * client field, because it is the mark of the company the page is about.
 *
 * One section is new: `ci-signup`, the centred headline and buttons with a
 * photograph tilted into each margin. Deliberately not `cta.php` under another
 * name — this frame draws both, the sign-up band here and the shared CTA at the
 * bottom, so folding them together would cost one of the two its shape.
 *
 * The other two designed sections are values, not markup:
 *
 *   - `ci-hero` grew the `logo` slot described above.
 *   - `ci-grid` grew `panel_class` and `card_class`. "Why Use OFX?" is the
 *     Accounting page's centred grid — three cards, 112px icon discs — drawn
 *     inside a pale panel with white cards instead of on white with pale ones.
 *
 * Nine sections the other pages have are missing from this frame and so are not
 * called: the pricing table, the free-tools tabs, the figures band, the
 * calculator, the what's-included cards, the "why" bento, the card carousel, the
 * pinned rail and the logo marquee. **So is the Google Reviews slider** — this
 * frame draws none.
 *
 * `faq.php` and `cta.php` are the site-wide ones here, unlike on the Property
 * Cashout and Business Loan pages: this frame draws the homepage's own six
 * questions and the homepage's closing photograph, so the set names neither a
 * `faq` list nor a `cta` image and both parts fall back to what every other page
 * shows.
 *
 * Auto-used for the page with slug "remittance" (page 555); also selectable as a
 * page template so it keeps working if the page is renamed.
 *
 * Runs inside The Loop so thinksme_field()/get_field() with no explicit post ID
 * read this page's own fields. Those come from the "Remittance Page Content" ACF
 * group (acf-json/group_thinksme_rm.json), which is located on this page template
 * — so the template has to be selected in Page Attributes for the fields to
 * appear; matching the slug alone doesn't trigger the group.
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
		get_template_part( 'template-parts/ci-grid', null, array( 'instance' => 'why_ofx' ) );
		get_template_part( 'template-parts/ci-signup' );

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
