<?php
/**
 * Design-fidelity copy and images for the six pages built out of the ci-* parts.
 *
 * `page-company-incorporation-local.php`,
 * `page-company-incorporation-foreign.php`, `page-corporate-secretary.php`,
 * `page-accounting-bookkeeping.php`, `page-corporate-tax.php` and
 * `page-gst-registration.php` are the same page: the same sections in (nearly) the
 * same order, the same markup, the same ACF field *names*. Figma draws them as six
 * frames (85:1351.., 101:846, 102:1969, 102:3127, 108:4497 and 114:5287) whose
 * differences are the words, the photographs and which sections they include. So
 * the six pages share one set of
 * template parts (`template-parts/ci-*.php`) and this file is where they stop
 * being the same: every default those parts fall back to is looked up here, keyed
 * by whichever of the six templates WordPress resolved.
 *
 * Sections a page doesn't have simply have no set here, and the parts for them
 * return early — which is how the same list of parts serves six different
 * frames without a flag per section.
 *
 * The alternative, another copy of all the template parts, would have duplicated
 * ~1,100 lines of identical markup and made every layout fix a six-file job.
 * The alternative on the other side, leaving the copy in the database only,
 * would have kept it out of git and let a cleared field fall back to another
 * page's text.
 *
 * These are fallbacks, not content: anything the client types into the ACF
 * group wins. They exist so both pages look like the design before a single
 * field has been filled in, which is the same convention `testimonials.php`,
 * `faq.php` and `hero.php` follow for their CPT-driven content. Note the
 * consequence, documented for the client in the ACF instructions: because
 * `thinksme_field()` treats '' as "use the default", a field that ships with
 * copy cannot be emptied from the editor — clearing it brings the design text
 * back.
 *
 * Images are theme files rather than media-library uploads for the same reason:
 * they are part of the design, they belong in git, and ACF's free image field
 * has no default_value to carry them. A client upload still wins — see the
 * `$is_stock` branches in ci-hero.php and ci-includes.php.
 *
 * @package Thinksme
 */

/**
 * Which of the six pages is being rendered.
 *
 * Keys off thinksme_current_template() rather than is_page_template() for the
 * reason documented on that function: all four templates are also named for
 * their page slug, so WP serves them to pages that never had the Page
 * Attributes template set, and is_page_template() reads exactly that missing
 * post meta.
 *
 * 'local' is the fallback rather than an "unknown" set: the parts are only ever
 * loaded by one of these templates, and a set that renders is a better failure
 * than a section of blanks.
 *
 * @return string 'local', 'foreign', 'secretary', 'accounting', 'tax' or 'gst'.
 */
function thinksme_ci_content_set() {
	$sets = array(
		'page-company-incorporation-foreign.php' => 'foreign',
		'page-corporate-secretary.php'           => 'secretary',
		'page-accounting-bookkeeping.php'        => 'accounting',
		'page-corporate-tax.php'                 => 'tax',
		'page-gst-registration.php'              => 'gst',
	);

	$template = thinksme_current_template();

	return isset( $sets[ $template ] ) ? $sets[ $template ] : 'local';
}

/**
 * Resolve a design image shipped with the theme to its URL.
 *
 * @param string $file Path under assets/images/, e.g. 'cif/hero-image.png'.
 * @return string
 */
function thinksme_ci_image_url( $file ) {
	return get_template_directory_uri() . '/assets/images/' . ltrim( $file, '/' );
}

/**
 * The design's copy and images for one section of the current page.
 *
 * @param string $section One of hero, stats, pricing, tools, includes,
 *                        why_slider, ways, why, grid, requirements, calculator,
 *                        penalties, cta. Not every page defines every section —
 *                        `why_slider` is on Corporate Secretary and Corporate
 *                        Tax, `grid` and `requirements` on Accounting &
 *                        Bookkeeping and Corporate Tax, `stats`, `calculator`
 *                        and `penalties` on Corporate Tax alone, and
 *                        `tools`/`ways`/`includes`/`why` are absent from that
 *                        one — and each part renders nothing when its section
 *                        comes back empty.
 * @return array Empty array for an unknown section, so a caller that asks for
 *               one it never defined gets ordinary "missing key" notices at
 *               worst rather than a fatal.
 */
function thinksme_ci_defaults( $section ) {
	static $content = null;

	if ( null === $content ) {
		$content = thinksme_ci_content();
	}

	$set = thinksme_ci_content_set();

	return isset( $content[ $set ][ $section ] ) ? $content[ $set ][ $section ] : array();
}

/**
 * All six content sets, built once per request.
 *
 * Kept as one function rather than six so the pages' fields stay visibly
 * parallel — a key present on one side and missing on another is obvious
 * here and invisible if they live apart.
 *
 * @return array
 */
function thinksme_ci_content() {
	$local = array(
		'hero'     => array(
			'hat'             => 'For Singapore Citizens & PRs · ACRA Registered Filing Agent',
			'title'           => 'Incorporate Your Singapore Company From S$888 All-In',
			'text'            => 'ACRA-filed, fully online, with your corporate secretary and registered address bundled in from day one — no separate vendors, no guesswork.',
			'button_text'     => 'Start Incorporation',
			'button_link'     => '/contact-us',
			'button_2_text'   => '+65 6012 9642',
			'button_2_link'   => 'tel:+6560129642',
			'image'           => 'ci/hero-image.png',
			// Figma's whole image group, which is bigger than the photo card
			// because the badge overhangs it — see the note in ci-hero.php.
			'image_box'       => 'aspect-[609/554]',
			// The supplied export carries the badge already, so the stock branch
			// draws nothing over it; a set whose asset is the photo card alone
			// names the SVG here. The badge's placement and the photo's own slot
			// in the group are percentages of `image_box`, so they belong to the
			// set too — the upload branch draws both from these.
			'badge'           => '',
			'badge_class'     => 'absolute left-0 top-[4.69%] w-[20.57%] pointer-events-none select-none',
			'photo_slot_class' => 'absolute left-[4.27%] top-[12.45%] w-[95.73%] h-[87.55%] overflow-hidden rounded-2xl',
			// Measured against this headline breaking across three lines at 72px;
			// it belongs to the copy, so it is a per-page default like the copy is.
			'underline_class' => 'hidden lg:block absolute left-[-0.3%] top-[197px] w-[92.6%] rotate-[1.83deg] pointer-events-none select-none',
		),
		'pricing'  => array(
			'hat'           => 'Transparent Pricing',
			'heading'       => 'Incorporation Packages for Local Founders',
			'text'          => 'Every package includes the S$315 ACRA government fee — no hidden charges.',
			'popular_label' => 'MOST POPULAR',
			'cards'         => array(
				1 => array(
					'icon'           => 'shield-check',
					'title'          => 'Essential',
					'text'           => 'Simple setup for solo local founders with straightforward structures.',
					'price_label'    => 'From',
					'price'          => 'S$888',
					'badge_1'        => 'One-time · all-in',
					'badge_2'        => 'S$315 ACRA fee included',
					'features_intro' => '',
					'features'       => array(
						'ACRA name reservation & registration',
						'Standard company constitution',
						'Corporate secretary — 12 months',
						'Registered address — 12 months',
						'Bank account opening assistance',
					),
				),
				2 => array(
					'icon'           => 'shield-check',
					'title'          => 'Standard',
					'text'           => 'Full first-year coverage. The most common choice for new SME owners.',
					'price_label'    => 'From',
					'price'          => 'S$1,288',
					'badge_1'        => 'First year · all-in',
					'badge_2'        => 'S$315 ACRA fee included',
					'features_intro' => 'Everything in Essential, plus:',
					'features'       => array(
						'Annual return filing (Year 1)',
						'Priority email & chat support',
						'Compliance deadline reminders',
					),
				),
				3 => array(
					'icon'           => 'shield-check',
					'title'          => 'Premium',
					'text'           => 'Complex structures, multiple shareholders, dedicated service.',
					'price_label'    => 'From',
					'price'          => 'S$1,888',
					'badge_1'        => 'First year · all-in',
					'badge_2'        => 'S$315 ACRA fee included',
					'features_intro' => 'Everything in Standard, plus',
					'features'       => array(
						'Up to 5 shareholders onboarded',
						'Share register & allotment setup',
						'Dedicated account manager',
					),
				),
			),
		),
		'tools'    => array(
			'hat'         => 'Free Tools',
			'heading'     => 'Plan Your Company Before You Register',
			'text'        => 'Three free tools to help you get incorporation-ready — no sign-up required.',
			// A CTA under the panel. Only the Corporate Secretary frame draws one
			// (102:2253); an empty label renders nothing.
			'button_text' => '',
			'button_link' => '',
			'tabs'        => array(
				1 => array(
					'label'       => 'Company Name Check',
					'title'       => 'Company Name Check',
					'text'        => 'Get a quick read on your proposed company name before you file with ACRA.',
					'placeholder' => 'e.g. Think SME Pte. Ltd.',
					// A glyph inside the input, for a field whose format is not
					// obvious from the placeholder alone (a date, say).
					'icon'        => '',
					'button_text' => 'Check Name',
					'disclaimer'  => 'This is a preliminary format check, not a live ACRA registry search. Final name availability and approval is confirmed when we submit your official BizFile+ application.',
				),
				2 => array(
					'label'       => 'Business Activity (SSIC)',
					'title'       => 'Business Activity (SSIC)',
					'text'        => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.',
					'placeholder' => 'e.g. lorem ipsum dolor',
					'icon'        => '',
					'button_text' => 'Search',
					'disclaimer'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
				),
				3 => array(
					'label'       => 'Tax Calculator',
					'title'       => 'Tax Calculator',
					'text'        => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.',
					'placeholder' => 'e.g. lorem ipsum dolor',
					'icon'        => '',
					'button_text' => 'Calculate',
					'disclaimer'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
				),
			),
		),
		'includes' => array(
			'heading'            => "What's Included in Every Incorporation",
			'image'              => 'ci/includes-photo.jpg',
			// A plain rounded card: the box is the card itself and it clips.
			'image_box'          => 'aspect-[576/486] overflow-hidden rounded-md lg:rounded-lg',
			// Figma's placement of that export inside the card — see ci-includes.php.
			'image_class'        => 'relative left-[-0.7%] top-[-42.8%] w-[101.7%] h-[149.6%] max-w-none object-cover',
			'image_upload_class' => 'w-full h-full object-cover',
			'cards'              => array(
				1 => array(
					'icon'  => 'folders',
					'title' => 'ACRA Filing & Registration',
					'text'  => 'Name reservation and BizFile+ submission by an ACRA.',
				),
				2 => array(
					'icon'  => 'user-check',
					'title' => 'Corporate Secretary — 12 Months',
					'text'  => 'Qualified company secretary appointed from day one.',
				),
				3 => array(
					'icon'  => 'buildings',
					'title' => 'Registered Address — 12 Months',
					'text'  => 'Compliant Singapore business address to keep private.',
				),
				4 => array(
					'icon'  => 'receipt',
					'title' => 'Transparent, All-In Pricing',
					'text'  => 'S$315 ACRA government fee included — no hidden charges.',
				),
			),
		),
		'ways'     => array(
			'hat'     => 'Quick Registration | Transparent Pricing | Reliable support',
			'heading' => 'Two Ways to Get Started',
			'text'    => 'Move fast on your own, or have a specialist walk you through it — either way, the same ACRA-registered filing and 12-month bundle applies.',
			'cards'   => array(
				1 => array(
					'icon'  => 'envelope-simple',
					'title' => 'Start DIY Online',
					'text'  => 'Complete it yourself in our digital portal',
				),
				2 => array(
					'icon'  => 'phone',
					'title' => 'Talk to a Specialist',
					'text'  => 'Free 30-minute consultation, no obligation',
				),
			),
			'plan'    => array(
				'badge'       => 'MOST POPULAR',
				'title'       => 'Standard Package',
				'text'        => 'Full first-year coverage. The most common choice for new SME owners.',
				'price'       => 'S$888',
				'badge_1'     => 'First year · all-in',
				'badge_2'     => 'S$315 ACRA fee included',
				'features'    => array(
					1 => 'ACRA name reservation & registration',
					2 => 'Corporate secretary & registered address — 12 months',
					3 => 'Annual return filing (Year 1) + priority support',
				),
				'button_text' => 'Get Started',
				'button_link' => '/contact-us',
			),
		),
		'why'      => array(
			'hat'     => 'Complete Corporate Services',
			'heading' => 'Why Local Founders Choose Think SME',
			'text'    => 'Most incorporation delays and compliance headaches trace back to a handful of avoidable mistakes — here’s how we close those gaps.',
			'cards'   => array(
				1 => array(
					'icon'  => 'package',
					'title' => 'Everything Bundled, No Hidden Fees',
					'text'  => 'ACRA fee, company secretary, and registered address are all included — no surprise line items later.',
					'image' => 'ci/why-bundled.jpg',
				),
				2 => array(
					'icon'  => 'lightning',
					'title' => 'Fast, ACRA-Registered Filing',
					'text'  => 'Most local incorporations are approved within 1–3 business days, checked before submission to avoid rejections.',
				),
				// Card 3 is the photo — it carries no copy at all, by design.
				3 => array(
					'image' => 'ci/why-desk.jpg',
				),
				4 => array(
					'icon'  => 'headset',
					'title' => 'A Person, Not a Chatbot',
					'text'  => 'You get a dedicated account manager who knows your company, not a rotating app-chat queue.',
				),
				5 => array(
					'title' => 'Financing Built In',
					'text'  => 'When you’re ready to raise a loan, you’re already talking to the same firm — with access to 60+ banks & lenders.',
					'image' => 'ci/why-financing.jpg',
				),
			),
		),
		'cta'      => array(
			'image' => 'cta/cta-photo.jpg',
		),
	);

	// The header block ci-includes.php grew for the Accounting & Bookkeeping frame.
	// Empty here, so this page's section is exactly what it was: a centred heading
	// over the photo and the cards.
	$local['includes']['hat']         = '';
	$local['includes']['text']        = '';
	$local['includes']['button_text'] = '';
	$local['includes']['button_link'] = '';

	// Card 3 of the bento is the wordless photo on this page. The fields exist so
	// the slot can carry copy where a design writes some — see ci-why.php.
	$local['why']['cards'][3]['icon']  = '';
	$local['why']['cards'][3]['title'] = '';
	$local['why']['cards'][3]['text']  = '';

	// The Foreign page, Figma frame 101:846 ("Desktop Company Incorporation
	// Foreign"). Sections whose copy the design leaves untouched — the free
	// tools, the two routes, the section hats — repeat the Local strings on
	// purpose: they are what the frame says, and pointing the two sets at one
	// shared value would silently re-link them the next time only one changes.
	$foreign = $local;

	$foreign['hero'] = array_merge(
		$local['hero'],
		array(
			'hat'   => 'For Foreign Founders · ACRA Registered Filing Agent',
			'title' => 'Incorporate Your Singapore Company From Anywhere',
			'text'  => 'Fully remote incorporation with nominee director, registered address, and Employment Pass support — no need to set foot in Singapore.',
			'image' => 'cif/hero-image.png',
		)
	);

	$foreign['pricing']['cards'][2] = array(
		'icon'           => 'shield-check',
		'title'          => 'Complete',
		'text'           => 'Nominee resident director included. Full remote setup.',
		'price_label'    => 'From',
		'price'          => 'S$2,888',
		'badge_1'        => 'First year',
		'badge_2'        => 'S$315 ACRA fee included',
		'features_intro' => 'Everything in Essential, plus:',
		'features'       => array(
			'Nominee resident director — 12 months',
			'KYC & due diligence screening',
			'Priority support incl. video onboarding call',
			'Dedicated account manager',
		),
	);

	$foreign['pricing']['cards'][3] = array(
		'icon'           => 'shield-check',
		'title'          => 'Premium',
		'text'           => 'EP visa support, complex multi-shareholder structures.',
		'price_label'    => 'From',
		'price'          => 'S$5,588',
		'badge_1'        => 'First year · all-in',
		'badge_2'        => 'S$645 govt fees included',
		'features_intro' => 'Everything in Complete, plus',
		'features'       => array(
			'Employment Pass application advisory',
			'EP eligibility pre-assessment (COMPASS check)',
			'Up to 5 shareholders onboarded',
			'Share register & allotment setup',
		),
	);

	$foreign['includes']['heading'] = "What's Included for Foreign Founders";
	// The two-layer arrangement: the subject breaks above the card's top edge, so
	// the asset is the whole 576x528 group with its own corners and transparency
	// (Figma "Frame 1171277395"). The box is that group, it must not clip, and the
	// image is contained rather than cropped — the same contract ci-hero.php's
	// stock branch has. An upload here replaces that composed export, so it is
	// drawn the same way instead of being cropped into the card.
	$foreign['includes']['image']              = 'cif/includes-photo.png';
	$foreign['includes']['image_box']          = 'aspect-[576/528]';
	$foreign['includes']['image_class']        = 'w-full h-full object-contain';
	$foreign['includes']['image_upload_class'] = 'w-full h-full object-contain';
	$foreign['includes']['cards'][2]           = array(
		'icon'  => 'user-check',
		'title' => 'Nominee Resident Director',
		'text'  => 'KYC-screened local director included from Complete onward.',
	);
	$foreign['includes']['cards'][3]   = array(
		'icon'  => 'buildings',
		'title' => 'Corporate Secretary & Address',
		'text'  => 'Qualified company secretary and registered address.',
	);
	$foreign['includes']['cards'][4]   = array(
		'icon'  => 'headset',
		'title' => '100% Remote — No Visit Required',
		'text'  => 'Company details, KYC, and payment completed online.',
	);

	$foreign['ways']['plan'] = array(
		'badge'       => 'MOST POPULAR',
		'title'       => 'Complete Package',
		'text'        => 'Nominee resident director included. Full remote setup.',
		'price'       => 'S$2,888',
		'badge_1'     => 'First year · nominee director included',
		'badge_2'     => 'S$315 ACRA fee included',
		'features'    => array(
			1 => 'Nominee resident director — 12 months',
			2 => 'KYC & due diligence screening',
			3 => 'Corporate secretary, registered address & dedicated account manager',
		),
		'button_text' => 'Get Started',
		'button_link' => '/contact-us',
	);

	$foreign['why']['text']     = 'Setting up from overseas comes with questions a local incorporation never raises — here’s how we handle them.';
	$foreign['why']['cards'][1] = array(
		'icon'  => 'shield-check',
		'title' => 'A Nominee Director You Can Trust',
		'text'  => 'KYC-screened and indemnity-governed — they hold no shares and no bank access, just the statutory residency requirement.',
		'image' => 'cif/why-nominee.jpg',
	);
	$foreign['why']['cards'][2] = array(
		'icon'  => 'star',
		'title' => 'Employment Pass Support Built In',
		'text'  => 'Our Premium package includes EP application advisory and a COMPASS eligibility pre-check before you commit.',
	);
	$foreign['why']['cards'][3] = array(
		'image' => 'cif/why-remote.jpg',
		'icon'  => '',
		'title' => '',
		'text'  => '',
	);
	$foreign['why']['cards'][4] = array(
		'icon'  => 'user-sound',
		'title' => 'A Person, Not a Chatbot',
		'text'  => 'You get a dedicated account manager who knows your structure, not a rotating app-chat queue across time zones.',
	);
	$foreign['why']['cards'][5] = array(
		'title' => 'Remote Corporate Account Opening',
		'text'  => 'Partner with local and international banks — no need to visit Singapore. Open your account remotely, or through a video call.',
		'image' => 'cif/why-bank.jpg',
	);

	$foreign['cta'] = array(
		'image' => 'cta/cta-photo-foreign.jpg',
	);

	// The Corporate Secretary page, Figma frame 102:1969 ("Desktop Corporate
	// Secretary"). Written out in full rather than as a diff on $local: this page
	// keeps almost none of the incorporation copy, and a hundred overrides read
	// worse than the thing itself. It is also the one page with a section the
	// other two don't have — `why_slider`, the five-card carousel at 102:2410.
	$secretary = array(
		'hero'       => array(
			'hat'             => 'ACRA Registered Filing Agent · Corporate Secretary',
			'title'           => 'Switch Your Corporate Secretary in Minutes',
			'text'            => 'Every Singapore company needs a qualified corporate secretary. Whether you’re appointing your first one or switching from another provider, we handle the ACRA transfer — no compliance gaps.',
			'button_text'     => 'Start Switching',
			'button_link'     => '/contact-us',
			'button_2_text'   => '+65 6012 9642',
			'button_2_link'   => 'tel:+6560129642',
			// Figma's whole hero group (770,121 to 1400,707): the badge overhangs
			// the photo card to the left, so the group is wider than the card.
			'image'           => 'cs/hero-image.png',
			'image_box'       => 'aspect-[630/586]',
			// Unlike the other two pages the asset here is the photo card only —
			// the badge is vector art and the export flattens it onto white, so it
			// is drawn back over the card from the SVG the upload branch uses.
			'badge'           => 'ci/hero-badge.svg',
			'badge_class'     => 'absolute left-0 top-[11.6%] w-[19.88%] pointer-events-none select-none',
			'photo_slot_class' => 'absolute left-[7.46%] top-0 w-[92.54%] h-full overflow-hidden rounded-[54px]',
			// This headline is three lines at 72px too, but in a 775px column
			// rather than 703, and the stroke sits higher than Local's.
			'underline_class' => 'hidden lg:block absolute left-[-0.2%] top-[139px] w-[86.5%] rotate-[1.77deg] pointer-events-none select-none',
		),
		'pricing'    => array(
			'hat'           => 'Transparent Pricing',
			'heading'       => 'Corporate Secretary Packages',
			'text'          => 'Whether you just need the transfer done, or want the full retainer handled for a year.',
			'popular_label' => 'MOST POPULAR',
			'cards'         => array(
				1 => array(
					'icon'           => 'shield-check',
					'title'          => 'Transfer Only',
					'text'           => 'Just need your secretary changed over with ACRA.',
					'price_label'    => 'From',
					'price'          => 'S$300',
					'badge_1'        => 'One-time',
					'badge_2'        => '',
					'features_intro' => '',
					'features'       => array(
						'ACRA change of secretary filing',
						'Handover documentation review',
						'Compliance history check',
					),
				),
				2 => array(
					'icon'           => 'shield-check',
					'title'          => 'First-Year Bundle',
					'text'           => 'Everything in Transfer Only, plus a full 12-month retainer.',
					'price_label'    => 'From',
					'price'          => 'S$700',
					'badge_1'        => 'First year',
					'badge_2'        => 'renews at S$700/year',
					'features_intro' => 'Everything in Transfer Only, plus:',
					'features'       => array(
						'Full 12-month corporate secretary retainer',
						'Annual return preparation (filing fee excl.)',
						'Minutes & resolutions management',
						'Deadline alerts & compliance calendar',
					),
				),
				// Figma prices this page with two packages, not three. The slot
				// stays in the set with an empty title so ci-pricing.php skips it
				// and the client can still fill a third card in without code.
				3 => array(
					'icon'           => 'shield-check',
					'title'          => '',
					'text'           => '',
					'price_label'    => '',
					'price'          => '',
					'badge_1'        => '',
					'badge_2'        => '',
					'features_intro' => '',
					'features'       => array(),
				),
			),
		),
		'tools'      => array(
			'hat'         => 'Free Tools',
			'heading'     => 'Check Your Compliance Position',
			'text'        => 'Two free tools to help you plan around your deadlines — no sign-up required.',
			'button_text' => 'Switch to Think SME',
			'button_link' => '/contact-us',
			'tabs'        => array(
				1 => array(
					'label'       => 'Compliance Deadline Calculator',
					'title'       => 'Compliance Deadline Calculator',
					'text'        => 'Enter your company’s financial year end (FYE) to see your key filing deadlines.',
					'placeholder' => '31/12/2026',
					'icon'        => 'calendar-dots',
					'button_text' => 'Calculate',
					'disclaimer'  => 'Estimate only, based on standard ACRA/IRAS timelines. Private companies may be exempt from holding an AGM subject to conditions. Your compliance calendar may vary — talk to us to confirm your exact obligations.',
				),
				// Figma writes copy for the first tool only, exactly as it does on
				// the other two pages, so the second defaults to placeholder text.
				2 => array(
					'label'       => 'Tax Calculator',
					'title'       => 'Tax Calculator',
					'text'        => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.',
					'placeholder' => 'e.g. lorem ipsum dolor',
					'icon'        => '',
					'button_text' => 'Calculate',
					'disclaimer'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
				),
				// Two tools here, three on the incorporation pages: same
				// arrangement as the empty pricing card above.
				3 => array(
					'label'       => '',
					'title'       => '',
					'text'        => '',
					'placeholder' => '',
					'icon'        => '',
					'button_text' => '',
					'disclaimer'  => '',
				),
			),
		),
		'includes'   => array(
			// This frame (102:2643) carries no heading — the four cards and the
			// photo are the whole section. Nor does it have the header block the
			// Accounting & Bookkeeping frame puts above them.
			'heading'            => '',
			'hat'                => '',
			'text'               => '',
			'button_text'        => '',
			'button_link'        => '',
			'image'              => 'cs/includes-photo.png',
			// Two-layer, like the Foreign page: the subject breaks above the
			// card's top edge, so the asset is Figma's whole 576x573 group with
			// its own corners and transparency, and it must not be clipped.
			'image_box'          => 'aspect-[576/573]',
			'image_class'        => 'w-full h-full object-contain',
			'image_upload_class' => 'w-full h-full object-contain',
			'cards'              => array(
				1 => array(
					'icon'  => 'shield-check',
					'title' => 'Minutes & Resolutions',
					'text'  => 'Board and shareholder resolutions drafted and filed.',
				),
				2 => array(
					'icon'  => 'folders',
					'title' => 'Statutory Compliance & ACRA Filings',
					'text'  => 'Annual returns, register maintenance.',
				),
				3 => array(
					'icon'  => 'calendar-dots',
					'title' => 'Deadline Alerts & Compliance Calendar',
					'text'  => 'Proactive reminders for ECI, annual returns.',
				),
				4 => array(
					'icon'  => 'buildings',
					'title' => 'Registered Office Liaison',
					'text'  => 'Your company’s official contact for ACRA.',
				),
			),
		),
		// The section the other two pages don't have. Its hat, heading and intro
		// are Figma's, duplication artefacts and all: the frame still says "Local
		// Founders" and "setting up from overseas", which is the client's copy to
		// correct, not the theme's to invent — the same call the Foreign page's
		// three leftover strings got.
		'why_slider' => array(
			'hat'     => 'Complete Corporate Services',
			'heading' => 'Why Local Founders Choose Think SME',
			'text'    => 'Setting up from overseas comes with questions a local incorporation never raises — here’s how we handle them.',
			'cards'   => array(
				1 => array(
					'title' => 'You’ve Just Incorporated',
					'text'  => 'Singapore law gives you 6 months from incorporation to appoint a secretary — a natural person ordinarily resident here. Miss that window and it’s treated as an offence under the Companies Act, not a paperwork slip.',
					'image' => 'cs/why-slide-1.jpg',
				),
				2 => array(
					'title' => 'Your Company Structure Is Changing',
					'text'  => 'Adding a director, bringing in a new shareholder, or adjusting share capital all need resolutions drafted correctly and filed with ACRA — so what’s on record actually matches what happened in your business.',
					'image' => 'cs/why-slide-2.jpg',
				),
				3 => array(
					'title' => 'Compliance Doesn’t Stop After Year One',
					'text'  => 'Annual returns, AGM obligations, and register updates recur every year. Falling behind risks late penalties, and repeated breaches can put your director status at risk.',
					'image' => 'cs/why-slide-3.jpg',
				),
				4 => array(
					'title' => 'You’re Applying for Financing or Raising Capital',
					'text'  => 'Lenders and investors both check your statutory registers and share structure before releasing funds — messy records can stall a deal. Our corporate secretary and financing teams work from the same file, not two separate vendors.',
					'image' => 'cs/why-slide-4.jpg',
				),
				5 => array(
					'title' => 'You’re Ready to Switch Providers',
					'text'  => 'Slow responses, unclear invoices, or a provider who’s stopped chasing your deadlines are all valid reasons to move. A clean handover keeps your compliance history intact through the transition.',
					'image' => 'cs/why-slide-5.jpg',
				),
			),
		),
		'ways'       => array(
			'hat'     => 'Quick Registration | Transparent Pricing | Reliable support',
			'heading' => 'Two Ways to Switch',
			'text'    => 'Move fast on your own, or have a specialist review your current compliance status first — either way, we handle the ACRA transfer.',
			'cards'   => array(
				1 => array(
					'icon'  => 'envelope-simple',
					'title' => 'Start Switching Online',
					'text'  => 'Submit your details in our digital portal',
				),
				2 => array(
					'icon'  => 'phone',
					'title' => 'Talk to a Specialist',
					'text'  => 'Free compliance review, no obligation',
				),
			),
			'plan'    => array(
				'badge'       => 'MOST POPULAR',
				'title'       => 'First-Year Bundle',
				'text'        => 'Everything in Transfer Only, plus a full 12-month retainer.',
				'price'       => 'S$700',
				'badge_1'     => 'First year',
				'badge_2'     => 'renews at S$700/year from Year 2',
				'features'    => array(
					1 => 'Full 12-month corporate secretary retainer',
					2 => 'Annual return preparation, minutes & resolutions',
					3 => 'Deadline alerts, compliance calendar & priority support',
				),
				'button_text' => 'Get Started',
				'button_link' => '/contact-us',
			),
		),
		'why'        => array(
			'hat'     => 'Complete Corporate Services',
			'heading' => 'Why SMEs Switch to Think SME',
			'text'    => 'Most switches happen for the same handful of reasons — here’s how we fix them.',
			'cards'   => array(
				1 => array(
					'icon'  => 'shield-check',
					'title' => 'Never Miss a Deadline Again',
					'text'  => 'Proactive alerts for ECI, annual returns, and AGM obligations — not a scramble the week they’re due.',
					'image' => 'cs/why-deadline.jpg',
				),
				2 => array(
					'icon'  => 'star',
					'title' => 'One Flat Fee, No Surprises',
					'text'  => 'S$700/year covers the full retainer — no per-filing charges or line items that show up later.',
				),
				// Card 3 is the photo — it carries no copy at all, by design.
				3 => array(
					'image' => 'cs/why-shopkeeper.jpg',
					'icon'  => '',
					'title' => '',
					'text'  => '',
				),
				4 => array(
					'icon'  => 'user-sound',
					'title' => 'A Person, Not a Portal',
					'text'  => 'You get a dedicated account manager who knows your company, not a ticket queue.',
				),
				5 => array(
					'title' => 'We Handle the ACRA Transfer',
					'text'  => 'You don’t file anything — we manage the change of secretary and review your compliance history during handover.',
					'image' => 'cs/why-handover.jpg',
				),
			),
		),
		'cta'        => array(
			'image' => 'cta/cta-photo-secretary.jpg',
		),
	);

	// The Accounting & Bookkeeping page, Figma frame 102:3127 ("Desktop Accounting
	// & Bookkeeping"). Written out in full for the same reason the Corporate
	// Secretary set is: it keeps none of the incorporation copy.
	//
	// This frame has no free-tools section and no two-ways section, so the set
	// defines neither `tools` nor `ways` and the page template doesn't call those
	// parts. It has two the others don't: `grid`, the icon-card grid drawn twice
	// (102:3146 and 102:3726), and `requirements`, the pinned four-step rail
	// (102:3515).
	$accounting = array(
		'hero'         => array(
			'hat'              => 'Xero Certified Advisor · ACRA Registered Filing Agent',
			'title'            => 'Accounting & Bookkeeping Built Around Your Business',
			'text'             => 'Bookkeeping, financial reporting, and tax support from a team with a banking background — priced by how your business actually runs, not a one-size-fits-all tier.',
			'button_text'      => 'Get My Quote',
			'button_link'      => '/contact-us',
			'button_2_text'    => '+65 6012 9642',
			'button_2_link'    => 'tel:+6560129642',
			// Figma's whole hero group (102:3844), 622x502: the badge overhangs the
			// photo card's top-left corner, so the group is taller than the card.
			// The export is flattened onto white, which is exactly what this page's
			// white ground is, so it ships as one composed JPEG — the card, the
			// cut-out that breaks above it and the badge, already assembled.
			'image'            => 'ab/hero-image.jpg',
			'image_box'        => 'aspect-[622/502]',
			'badge'            => '',
			'badge_class'      => 'absolute left-[14.95%] top-0 w-[20.14%] pointer-events-none select-none',
			'photo_slot_class' => 'absolute left-0 top-[8.96%] w-[93.73%] h-[91.04%] overflow-hidden rounded-2xl',
			// This headline breaks across four lines, and Figma strikes two of them
			// (vectors 102:3131 and 102:3132), so this set is the one that draws a
			// second stroke. Both are measured against those four lines at 72px.
			'underline_class'  => 'hidden lg:block absolute left-0 top-[57px] w-[63%] rotate-[1.8deg] pointer-events-none select-none',
			'underline_2_class' => 'hidden lg:block absolute left-0 top-[121px] w-[57.6%] rotate-[1.9deg] pointer-events-none select-none',
		),
		'pricing'      => array(
			'hat'           => 'Transparent Pricing',
			'heading'       => 'Two Ways to Work With Us',
			'text'          => 'Same core service, billed the way that fits how your business runs.',
			// The highlighted card is the monthly one, and this page calls that
			// flexible rather than popular.
			'popular_label' => 'MOST FLEXIBLE',
			'cards'         => array(
				1 => array(
					'icon'           => 'shield-check',
					'title'          => 'Yearly Accounting & Bookkeeping',
					'text'           => '',
					'price_label'    => 'From',
					'price'          => 'S$1,200',
					'badge_1'        => 'Per financial year',
					'badge_2'        => 'Best for lower transaction volumes',
					'features_intro' => '',
					'features'       => array(
						'Annual bookkeeping & reconciliation',
						'Annual management & financial report',
						'Assigned accountant',
						'Annual tax return filing support',
					),
					'button_text'    => 'Get My Quote',
				),
				2 => array(
					'icon'           => 'shield-check',
					'title'          => 'Monthly Accounting & Bookkeeping',
					'text'           => 'Billed monthly — a good fit for active, growing businesses.',
					'price_label'    => 'From',
					'price'          => 'S$500',
					'badge_1'        => 'Per month',
					'badge_2'        => 'Best for active, high-volume SMEs',
					'features_intro' => 'Monthly bookkeeping & reconciliation',
					'features'       => array(
						'Live monthly management & financial reports',
						'Dedicated accountant & relationship manager, priority response',
						'Ongoing tax and GST filing support',
					),
					'button_text'    => 'Get My Quote',
				),
				// The frame prices two packages, so the third card is empty and the
				// loop that already skips empty titles skips it.
				3 => array(
					'icon'           => '',
					'title'          => '',
					'text'           => '',
					'price_label'    => '',
					'price'          => '',
					'badge_1'        => '',
					'badge_2'        => '',
					'features_intro' => '',
					'features'       => array(),
					'button_text'    => '',
				),
			),
		),
		// "What Determines Your Exact Price" (102:4070). Same section as the other
		// pages' what's-included, with the header block this frame puts above it.
		'includes'     => array(
			'hat'                => 'How Pricing Works',
			'heading'            => 'What Determines Your Exact Price',
			'text'               => 'We don’t force every business into the same fixed tier. Your quote reflects the real work behind your books.',
			'button_text'        => 'Switch to Think SME',
			'button_link'        => '/contact-us',
			// Two-layer like the Foreign and Corporate Secretary pages: the binders
			// break above the card's top edge, so the asset is Figma's whole 576x573
			// group (102:4072) and it must not be clipped.
			'image'              => 'ab/includes-photo.jpg',
			'image_box'          => 'aspect-[576/573]',
			'image_class'        => 'w-full h-full object-contain',
			'image_upload_class' => 'w-full h-full object-contain',
			'cards'              => array(
				1 => array(
					'icon'  => 'buildings',
					'title' => 'Business Nature',
					'text'  => 'Holding companies and retailers have different accounting needs.',
				),
				2 => array(
					'icon'  => 'hand-coins',
					'title' => 'Transaction Value',
					'text'  => 'A few monthly invoices require less work.',
				),
				3 => array(
					'icon'  => 'calendar-dots',
					'title' => 'Frequency',
					'text'  => 'Update frequency affects the bookkeeping workload.',
				),
				4 => array(
					'icon'  => 'file-text',
					'title' => 'Revenue',
					'text'  => 'Higher revenue means more to reconcile, report, and file.',
				),
			),
		),
		// The icon-card grid, drawn twice. Each card's span and min-height is one
		// literal class string because Tailwind only generates classes it can find
		// in the source — see the note in template-parts/ci-grid.php.
		'grid'         => array(
			'switching' => array(
				'hat'           => '',
				'heading'       => 'Switching to Think SME, Without the Hassle',
				'align'         => 'center',
				// The section's own vertical padding, the same values every other
				// ci-* section uses.
				'section_class' => 'py-xl lg:py-[40px]',
				'cards'   => array(
					1 => array(
						'icon'  => 'arrows-left-right',
						'title' => 'Smooth Handover of Your Records',
						'text'  => 'We liaise directly with your current accountant to gather ledgers, statements, and reports, so nothing gets lost in the switch.',
						'class' => 'lg:col-span-2 lg:min-h-[390px]',
					),
					2 => array(
						'icon'  => 'users-three',
						'title' => 'Dedicated Accounting & Relationship Managers',
						'text'  => 'One accounting manager and one relationship manager oversee your handover and your account going forward, so you always know who to call.',
						'class' => 'lg:col-span-2 lg:min-h-[390px]',
					),
					3 => array(
						'icon'  => 'headset',
						'title' => 'Zero Disruption to Your Business',
						'text'  => 'Your bookkeeping and compliance carry on as normal while we manage the transition behind the scenes.',
						'class' => 'lg:col-span-2 lg:min-h-[390px]',
					),
					4 => array(
						'icon'  => 'tag',
						'title' => 'One Clear Price, No Hidden Fees',
						'text'  => 'A single straightforward package covers onboarding, migration, and ongoing accounting — nothing sprung on you later.',
						'class' => 'lg:col-span-3 lg:min-h-[342px]',
					),
					5 => array(
						'icon'  => 'file-magnifying-glass',
						'title' => 'Records Reviewed Before Takeover',
						'text'  => 'We review ledgers, statements, and outstanding filings, and flag any gaps before we take over.',
						'class' => 'lg:col-span-3 lg:min-h-[342px]',
					),
				),
			),
			'same_firm' => array(
				'hat'           => 'Why Think SME',
				'heading'       => 'The Same Firm for Compliance, Digital, and Financing',
				'align'         => 'left',
				// Figma leaves 160px between the requirements panel above and this
				// frame; two adjacent sections at the shared 40px give it 80. The
				// extra goes on the top only, so the CTA below keeps its own rhythm.
				'section_class' => 'pt-3xl pb-xl lg:pt-[120px] lg:pb-[40px]',
				'cards'   => array(
					1 => array(
						'icon'  => 'seal-check',
						'title' => 'Xero Certified Advisor',
						'text'  => 'Setup, migration, and ongoing support from a team actually certified on the platform.',
						'class' => 'lg:col-span-2 lg:min-h-[362px]',
					),
					2 => array(
						'icon'  => 'certificate',
						'title' => 'ACRA Registered Filing Agent',
						'text'  => 'The same firm that can also handle your incorporation, secretary, and compliance filings.',
						'class' => 'lg:col-span-2 lg:min-h-[362px]',
					),
					3 => array(
						'icon'  => 'file-text',
						'title' => 'IRAS Tax Filing Agent',
						'text'  => 'Authorised to prepare and file your corporate tax returns directly with IRAS on your behalf.',
						'class' => 'lg:col-span-2 lg:min-h-[362px]',
					),
					4 => array(
						'icon'  => 'hand-coins',
						'title' => 'PSG Grant Eligible',
						'text'  => 'As an IMDA Pre-Approved Vendor, your Xero setup may qualify for up to 50% PSG grant support.',
						'class' => 'lg:col-span-2 lg:min-h-[362px]',
					),
					// The middle cell of the second row is a photograph rather than a
					// card, so it carries no copy and no ACF fields — the layout is
					// what decides it, the same way ci-why.php's card shapes are.
					5 => array(
						'photo' => 'ab/same-firm-photo.jpg',
						'class' => 'lg:col-span-2 lg:min-h-[362px]',
					),
					6 => array(
						'icon'  => 'bank',
						'title' => 'Financing When You Need It',
						'text'  => 'Clean books make loan and financing applications smoother — through the same team, via our 60+ lender network.',
						'class' => 'lg:col-span-2 lg:min-h-[362px]',
					),
				),
			),
		),
		'requirements' => array(
			'hat'     => 'Know Your Obligations',
			'heading' => 'Accounting Requirements for Singapore Companies',
			'steps'   => array(
				1 => array(
					'icon'  => 'books',
					'title' => 'Record-Keeping',
					'text'  => 'Under Section 199 of the Companies Act, every company must maintain accounting records explaining its transactions and financial position, kept for at least 5 years.',
				),
				2 => array(
					'icon'  => 'receipt',
					'title' => 'GST Registration & Filing (optional)',
					'text'  => 'Mandatory once taxable turnover exceeds S$1 million. File quarterly via Form GST F5 at the current 9% rate.',
				),
				3 => array(
					'icon'  => 'calendar-check',
					'title' => 'ACRA Annual Return',
					'text'  => 'File within 7 months of your financial year end, or 5 months for listed companies. Late penalties can reach S$600.',
				),
				4 => array(
					'icon'  => 'file-text',
					'title' => 'Corporate Tax Filing',
					'text'  => 'File ECI within 3 months of your financial year end. File Form C-S, C-S Lite, or Form C. Tax is 17%, with partial exemption on the first S$200,000.',
				),
			),
		),
		'why'          => array(
			'hat'     => 'Trusted by Growing Businesses',
			'heading' => 'Why SMEs Come to Us for Accounting',
			// This frame has no intro paragraph between the heading and the bento.
			'text'    => '',
			'cards'   => array(
				1 => array(
					'icon'  => 'buildings',
					'title' => 'Newly Incorporated',
					'text'  => 'Getting accounting set up from day one avoids backlog and messy records when your first ECI or tax filing comes due.',
					'image' => 'ab/why-newly-incorporated.jpg',
				),
				2 => array(
					'icon'  => 'chart-line-up',
					'title' => 'Outgrowing Spreadsheets',
					'text'  => 'Once transaction volumes climb, manual tracking stops giving you an accurate read on cash flow and margins.',
				),
				// The one page whose card 3 carries copy over its photograph.
				3 => array(
					'icon'  => 'globe-hemisphere-west',
					'title' => 'Foreign-Owned, Singapore-Registered',
					'text'  => 'Operating from abroad doesn’t remove your obligation to keep locally compliant books and file locally — we handle it remotely.',
					'image' => 'ab/why-foreign-owned.jpg',
				),
				4 => array(
					'icon'  => 'arrows-left-right',
					'title' => 'Switching Providers',
					'text'  => 'Slow responses or unclear reporting from your current accountant are common reasons businesses move on.',
				),
				5 => array(
					'title' => 'Spending Too Much Time on Finance Admin',
					'text'  => 'If receipts, reconciliation, and deadlines eat hours every month, outsourcing usually costs less than doing it yourself.',
					'image' => 'ab/why-finance-admin.jpg',
				),
			),
		),
		'cta'          => array(
			'image' => 'ab/cta-photo.jpg',
		),
	);

	// The Corporate Tax page, Figma frame 108:4497 ("Desktop corporate-tax").
	// Written out in full like the two sets above: it keeps none of the
	// incorporation copy.
	//
	// Three sections are new here and inert everywhere else — `stats`, the navy
	// figures band; `calculator`, the one free tool on this page, which unlike
	// ci-tools.php actually computes; and `penalties`, the late-filing comparison.
	// Four the other frames have are missing from this one: the free-tools tabs,
	// the two routes, the what's-included cards and the bento.
	$tax = array(
		'hero'         => array(
			'hat'              => 'IRAS Tax Filing Agent · ACRA Registered Filing Agent',
			'title'            => 'Corporate Tax Filing Without the Deadline Stress',
			'text'             => 'Filing taxes as a corporation in Singapore means IRAS company tax computation and filing, handled end-to-end — the right form selected, every exemption applied, submitted well ahead of the 30 November deadline.',
			'button_text'      => 'Get My Quote',
			'button_link'      => '/contact-us',
			'button_2_text'    => '+65 6012 9642',
			'button_2_link'    => 'tel:+6560129642',
			// Figma's whole hero group (108:4819), 583x519: the badge overhangs the
			// photo card's top edge, so the group is taller than the card. The
			// export is flattened onto white, which is this page's own ground, so
			// it ships as one composed JPEG the way the Accounting one does.
			'image'            => 'ct/hero-image.jpg',
			'image_box'        => 'aspect-[583/519]',
			'badge'            => '',
			'badge_class'      => 'absolute left-[15.27%] top-0 w-[21.48%] pointer-events-none select-none',
			'photo_slot_class' => 'absolute left-0 top-[11.95%] w-full h-[88.05%] overflow-hidden rounded-2xl',
			// Measured against this headline breaking across three lines at 72px.
			'underline_class'  => 'hidden lg:block absolute left-0 top-[56px] w-[71.3%] rotate-[1.8deg] pointer-events-none select-none',
		),
		// The figures band under the logo marquee (108:4515). Four numbers, no
		// heading — the section is the numbers.
		'stats'        => array(
			'cards' => array(
				1 => array(
					'icon'  => 'percent',
					'value' => '17%',
					'label' => 'Flat Corporate Tax Rate',
				),
				2 => array(
					'icon'  => 'calendar-check',
					'value' => '30 Nov',
					'label' => 'Filing Deadline, Every Year',
				),
				3 => array(
					'icon'  => 'clock-countdown',
					'value' => '3 Months',
					'label' => 'ECI Deadline From FYE',
				),
				4 => array(
					'icon'  => 'receipt',
					'value' => 'S$850',
					'label' => 'Corporate Tax Computation & Filing, onwards',
				),
			),
		),
		'pricing'      => array(
			// This frame draws pricing as a two-column split (108:4833) rather than
			// the navy panel the other four pages use: the section's copy on the
			// left with its own checklist, one package card on the right. Same
			// cards, same fields — see the note in template-parts/ci-pricing.php.
			'layout'        => 'split',
			'hat'           => 'Transparent Pricing',
			'heading'       => 'IRAS Submission',
			'text'          => 'We don’t split tax computation and filing across two vendors. One team prepares your numbers, applies your exemptions, and submits directly to IRAS — our corporate tax filing services cover the full corporate tax filing cost in one transparent quote.',
			// The split layout's own checklist, beside the section copy. The panel
			// layout has no such list, which is why these are their own fields
			// rather than a card's features.
			'features'      => array(
				'Tax computation prepared from your financial statements',
				'Correct form determined — C-S Lite, C-S, or C',
				'SUTE or PTE exemption optimisation',
				'Direct submission via IRAS myTax Portal',
			),
			'popular_label' => 'MOST POPULAR',
			'cards'         => array(
				1 => array(
					// Unused in the split layout — that card draws no icon disc —
					// but kept so the card's shape matches the other sets'.
					'icon'           => 'file-text',
					'title'          => 'Corporate Tax Computation & Filing',
					'text'           => 'Bundled — not sold as two separate services.',
					'price_label'    => 'From',
					'price'          => 'S$850',
					'badge_1'        => 'Per Year',
					'badge_2'        => 'Assessment, onwards',
					'features_intro' => '',
					'features'       => array(
						'Tax computation & exemption optimisation',
						'Form C-S / C-S Lite / C preparation',
						'ECI filing within 3 months of FYE',
						'Submission via myTax Portal, ahead of 30 Nov',
					),
					'button_text'    => 'Get My Quote',
				),
				// The frame prices one package, so cards 2 and 3 are empty and the
				// loop that already skips empty titles skips them.
				2 => array(
					'icon'           => '',
					'title'          => '',
					'text'           => '',
					'price_label'    => '',
					'price'          => '',
					'badge_1'        => '',
					'badge_2'        => '',
					'features_intro' => '',
					'features'       => array(),
					'button_text'    => '',
				),
				3 => array(
					'icon'           => '',
					'title'          => '',
					'text'           => '',
					'price_label'    => '',
					'price'          => '',
					'badge_1'        => '',
					'badge_2'        => '',
					'features_intro' => '',
					'features'       => array(),
					'button_text'    => '',
				),
			),
		),
		// "What Every Corporate Tax Filing Needs" (108:4558), the icon-card grid
		// again — centred, four cards in a row rather than the 3+2 the Accounting
		// page draws, hence the grid_class override.
		'grid'         => array(
			'needs' => array(
				'hat'           => '',
				'heading'       => 'What Every Corporate Tax Filing Needs',
				'align'         => 'center',
				'section_class' => 'py-xl lg:py-[40px]',
				'grid_class'    => 'lg:grid-cols-4',
				'cards'         => array(
					1 => array(
						'icon'  => 'calculator',
						'title' => 'Accurate Tax Computation',
						'text'  => 'Your chargeable income calculated correctly from your financial statements, before any form is submitted.',
						'class' => 'lg:col-span-1 lg:min-h-[390px]',
					),
					2 => array(
						'icon'  => 'file-text',
						'title' => 'The Right Form',
						'text'  => 'Form C-S Lite, Form C-S, or Form C — selected based on your revenue and structure, not guessed.',
						'class' => 'lg:col-span-1 lg:min-h-[390px]',
					),
					3 => array(
						'icon'  => 'seal-check',
						'title' => 'Every Exemption Applied',
						'text'  => 'Start-Up Tax Exemption or Partial Tax Exemption checked and applied — never left on the table.',
						'class' => 'lg:col-span-1 lg:min-h-[390px]',
					),
					// Figma repeats card 3's title and copy in this slot with a
					// different icon. That is a duplication artefact in the design
					// file, not copy anyone wrote — it ships verbatim, the same call
					// the Foreign frame's leftover strings got, because inventing
					// replacement copy is the client's to make.
					4 => array(
						'icon'  => 'percent',
						'title' => 'Every Exemption Applied',
						'text'  => 'Start-Up Tax Exemption or Partial Tax Exemption checked and applied — never left on the table.',
						'class' => 'lg:col-span-1 lg:min-h-[390px]',
					),
				),
			),
		),
		// The one free tool on this page (108:4603) — and the one in the theme that
		// computes something. See template-parts/ci-calculator.php for why the
		// rates and bands are defaults here rather than constants in the script.
		'calculator'   => array(
			'hat'         => 'Free Tools',
			'heading'     => 'Estimate Your Corporate Tax',
			'text'        => 'Calculating corporation tax gets complicated once exemptions are involved see how the schemes affect your chargeable income. An estimate, not a filing.',
			'title'       => 'Corporate Tax Calculator',
			'panel_text'  => 'Estimate your tax based on the standard 17% rate and exemption schemes',
			'placeholder' => 'Estimated chargeable income (S$)',
			'button_text' => 'Calculate',
			'button_link' => '/contact-us',
			'currency'    => 'S$',
			'rate'        => 17,
			'result_label' => 'Estimated tax payable',
			'saved_label' => 'Exemption saved',
			'error_text'  => 'Enter your estimated chargeable income to see the figure.',
			'disclaimer'  => 'Indicative only, based on the current 17% rate and the exemption you selected — not tax advice, and not a filing.',
			// Each scheme is a list of [band, exempt %] applied to the bottom of
			// chargeable income, IRAS's own shape. SUTE is the first 3 YAs; PTE
			// applies from then on. Editable here because the rules change and a
			// number buried in JS is a number nobody finds.
			'modes'       => array(
				1 => array(
					'label' => 'New Company',
					'text'  => 'First 3 YAs (SUTE)',
					'bands' => array(
						array( 100000, 75 ),
						array( 100000, 50 ),
					),
				),
				2 => array(
					'label' => 'Established Company',
					'text'  => 'Partial Exemption (PTE)',
					'bands' => array(
						array( 10000, 75 ),
						array( 190000, 50 ),
					),
				),
			),
		),
		// "What Happens If You File Late" (108:4630): two figures either side of a
		// VS badge, then the three stages of escalation.
		'penalties'    => array(
			'hat'     => 'Why It’s Worth Doing Right',
			'heading' => 'What Happens If You File Late',
			'text'    => 'IRAS doesn’t wait. Late or non-filing escalates quickly, and directors remain responsible even with a tax agent engaged.',
			'badge'   => 'VS',
			'compare' => array(
				1 => array(
					'tone'  => 'danger',
					'value' => 'S$200–S$5,000',
					'text'  => 'Composition fine per offence, depending on compliance history',
				),
				2 => array(
					'tone'  => 'success',
					'value' => 'From S$850',
					'text'  => 'Filed correctly and on time, every Year of Assessment',
				),
			),
			'steps'   => array(
				1 => array(
					'title' => 'Estimated Assessment',
					'text'  => 'IRAS raises a Notice of Assessment based on prior years — often higher than your real liability, and payable within 1 month regardless.',
				),
				2 => array(
					'title' => 'Composition Fine',
					'text'  => 'A fine of S$200 to S$5,000 may apply — paying it doesn’t excuse you from still filing the outstanding return.',
				),
				3 => array(
					'title' => 'Court Summons',
					'text'  => 'Continued non-compliance can escalate to court action, with penalties up to twice the tax assessed for prolonged non-filing.',
				),
			),
		),
		'why_slider'   => array(
			'hat'     => 'Is This You?',
			'heading' => 'Why SMEs Come to Us for Corporate Tax',
			'text'    => '',
			'cards'   => array(
				1 => array(
					'title' => 'Newly Incorporated',
					'text'  => 'First-time ECI and Form C-S filings, small business tax filing done right from year one, plus checking whether you qualify for the Start-Up Tax Exemption.',
					'image' => 'ct/why-slide-1.jpg',
				),
				2 => array(
					'title' => 'Dormant or Loss-Making',
					'text'  => 'No revenue this year doesn’t mean no filing obligation — a nil tax return is still required by 30 November.',
					'image' => 'ct/why-slide-2.jpg',
				),
				3 => array(
					'title' => 'Switching Tax Agents',
					'text'  => 'We take over your filing history, confirm your next deadline, and prepare your return from where your last agent left off.',
					'image' => 'ct/why-slide-3.jpg',
				),
			),
		),
		// "The Same Firm for Compliance, Digital, and Financing" (108:4664). The
		// Accounting page draws that heading as a grid of cards; this frame draws it
		// as the pinned rail, so it is `requirements` here and not `grid`.
		'requirements' => array(
			'hat'     => 'Why Think SME',
			'heading' => 'The Same Firm for Compliance, Digital, and Financing',
			'steps'   => array(
				1 => array(
					'icon'  => 'file-text',
					'title' => 'IRAS Tax Filing Agent',
					'text'  => 'Authorised to prepare and submit your corporate tax filings directly with IRAS.',
				),
				2 => array(
					'icon'  => 'certificate',
					'title' => 'ACRA Registered Filing Agent',
					'text'  => 'The same firm that can also handle your incorporation, secretary, and compliance filings.',
				),
				3 => array(
					'icon'  => 'seal-check',
					'title' => 'Xero Certified Advisor',
					'text'  => 'Your tax computation runs off the same accurate books we keep for your accounting.',
				),
				4 => array(
					'icon'  => 'hand-coins',
					'title' => 'PSG Grant Eligible',
					'text'  => 'As an IMDA Pre-Approved Vendor, your Xero setup may qualify for up to 50% PSG grant support.',
				),
				5 => array(
					'icon'  => 'bank',
					'title' => 'Financing When You Need It',
					'text'  => 'Clean, tax-compliant financials make loan applications smoother — through the same team, via our 60+ lender network.',
				),
			),
		),
		'cta'          => array(
			'image' => 'ct/cta-photo.jpg',
		),
	);

	// The GST Registration page, Figma frame 114:5287 ("Desktop gst-registration").
	// Written out in full like the three sets above.
	//
	// It adds no section of its own: every frame on it is one the earlier pages
	// already draw. What it does add are three optional bits inside two of them —
	// the hero's promotional price line, the registration card's struck-through
	// usual price with its terms, and the pricing panel's closing small print — each
	// of which renders only where a set names it. Four sections the others have are
	// missing here: the free-tools tabs, the two routes, the figures band and the
	// pinned rail.
	$gst = array(
		'hero'       => array(
			'hat'              => 'IRAS Tax Filing Agent · ACRA Registered Filing Agent',
			'title'            => 'GST Registration & Filing Without the Guesswork',
			'text'             => 'Register for GST correctly the first time, then keep every quarterly return filed on time — bundled with the accounting and bookkeeping that makes it accurate.',
			// Figma 114:5296: the offer, the usual price struck through beside it, and
			// the terms under both. The design writes "U.P S$400 strikeoff" into that
			// second label — the word is an instruction to itself, honoured here as the
			// line-through in ci-hero.php rather than shipped as copy.
			'price_text'       => 'GST Registration from S$80',
			'price_strike'     => 'U.P S$400',
			'price_note'       => 'New customers only — terms apply',
			'button_text'      => 'Get My Quote',
			'button_link'      => '/contact-us',
			'button_2_text'    => '+65 6012 9642',
			'button_2_link'    => 'tel:+6560129642',
			// Figma's whole hero group (114:5740), 583x519: the badge overhangs the
			// photo card's top edge, so the group is taller than the card. The export
			// is flattened onto white, which is this page's own ground, so it ships as
			// one composed JPEG the way the Accounting and Corporate Tax ones do.
			'image'            => 'gst/hero-image.jpg',
			'image_box'        => 'aspect-[583/519]',
			'badge'            => '',
			'badge_class'      => 'absolute left-[15.27%] top-0 w-[21.48%] pointer-events-none select-none',
			'photo_slot_class' => 'absolute left-0 top-[11.95%] w-full h-[88.05%] overflow-hidden rounded-2xl',
			// This frame sets the headline a step smaller than the others (114:5294).
			'title_class'      => 'lg:text-[64px]',
			// Two strokes, as in Figma (114:5292 and 114:5293), measured against this
			// headline breaking across three lines at 64px.
			'underline_class'  => 'hidden lg:block absolute left-0 top-[71px] w-[88.7%] rotate-[0.79deg] pointer-events-none select-none',
			'underline_2_class' => 'hidden lg:block absolute left-0 top-[132px] w-[22.9%] rotate-[3.64deg] pointer-events-none select-none',
		),
		// "Do You Need to Register for GST?" (114:5311) — the icon-card grid again,
		// centred, three equal cards, so it names its own track the way the Corporate
		// Tax instance does. No hat over it: this frame draws the heading alone.
		'grid'       => array(
			'needs' => array(
				'hat'           => '',
				'heading'       => 'Do You Need to Register for GST?',
				'align'         => 'center',
				'section_class' => 'py-xl lg:py-[40px]',
				'grid_class'    => 'lg:grid-cols-3',
				'cards'         => array(
					1 => array(
						'icon'  => 'chart-line-up',
						'title' => 'Your Turnover Has Crossed or Is Approaching S$1M',
						'text'  => 'Once your taxable turnover exceeds S$1 million in a 12-month period — or you can see it coming, especially if you deal in imports, exports, or high-value goods — registration becomes compulsory. Getting ahead of it avoids backdated penalties.',
						'class' => 'lg:col-span-1 lg:min-h-[438px]',
					),
					2 => array(
						'icon'  => 'hand-heart',
						'title' => 'You’re Registering Voluntarily',
						'text'  => 'Registering early to claim input tax credits still means filing every period, even below the S$1M threshold.',
						'class' => 'lg:col-span-1 lg:min-h-[438px]',
					),
					3 => array(
						'icon'  => 'gauge',
						'title' => 'You’re Buying Property or Machinery',
						'text'  => 'Making a major purchase — commercial property, equipment, or renovation? Voluntary registration lets you claim back the GST paid as input tax, often a meaningful saving on a big-ticket purchase.',
						'class' => 'lg:col-span-1 lg:min-h-[438px]',
					),
				),
			),
		),
		'pricing'    => array(
			'hat'           => 'Transparent Pricing',
			'heading'       => 'Registration and Filing, Priced Separately',
			'text'          => 'A one-time registration fee, and an ongoing filing plan that bundles your books with your GST returns.',
			// The line of small print closing the panel (114:5502).
			'note'          => 'GST filing is bundled with accounting and bookkeeping, not sold as a standalone add-on because accurate filing depends on accurate books.',
			'popular_label' => 'MOST POPULAR',
			'cards'         => array(
				1 => array(
					'icon'           => 'shield-check',
					'title'          => 'GST Registration',
					'text'           => 'Billed monthly — a good fit for active, growing businesses.',
					'price_label'    => 'Promotional Price',
					'price'          => 'S$80',
					'price_strike'   => 'S$400',
					'price_note'     => 'One-time fee — new customers only',
					'badge_1'        => 'Eligibility checked before you pay',
					'badge_2'        => '',
					'features_intro' => '',
					'features'       => array(
						'Eligibility assessment (compulsory or voluntary)',
						'Preparation of IRAS application documents',
						'Full submission and follow-up with IRAS',
						'Guidance on your assigned filing cycle',
					),
					'button_text'    => 'Get My Quote',
				),
				// The highlighted slot, as on every panel: the second card.
				2 => array(
					'icon'           => 'shield-check',
					'title'          => 'Quarterly GST Filing & Compliance',
					'text'           => 'Bundled with monthly accounting and bookkeeping.',
					'price_label'    => 'From',
					'price'          => 'S$500',
					// This card draws both of its pills filled (114:5578), where every
					// other frame outlines the first one.
					'badge_1_style'  => 'soft',
					'badge_1'        => 'Per month, onwards',
					'badge_2'        => 'Includes accounting, bookkeeping & GST filing',
					'features_intro' => '',
					'features'       => array(
						'Monthly accounting & bookkeeping',
						'Input and output tax reviewed for accuracy',
						'Quarterly GST F5 preparation & IRAS submission',
						'Nil returns filed on schedule — never missed',
					),
					'button_text'    => 'Get My Quote',
				),
				// This frame prices two packages, so the third card is empty and the
				// loop that already skips empty titles skips it.
				3 => array(
					'icon'           => '',
					'title'          => '',
					'text'           => '',
					'price_label'    => '',
					'price'          => '',
					'badge_1'        => '',
					'badge_2'        => '',
					'features_intro' => '',
					'features'       => array(),
					'button_text'    => '',
				),
			),
		),
		// "The Cost of Missing a Filing" (114:5653) — the Corporate Tax comparison,
		// two figures either side of the VS badge. This frame draws no escalation
		// stages under it, so `steps` is empty and that half of the part is skipped.
		'penalties'  => array(
			'hat'     => 'Why It’s Worth Doing Right',
			'heading' => 'The Cost of Missing a Filing',
			'text'    => 'IRAS doesn’t send reminders. One missed nil return costs more than a full quarter of staying compliant.',
			'badge'   => 'VS',
			'compare' => array(
				1 => array(
					'tone'  => 'danger',
					'value' => 'S$200',
					'text'  => 'Per month, per missed or late return',
				),
				2 => array(
					'tone'  => 'success',
					'value' => 'From S$500',
					'text'  => 'Per month — books and GST filing handled, penalty-free',
				),
			),
			'steps'   => array(),
		),
		// "GST Rules Every Registered Business Should Know" (114:5777) — the
		// what's-included section with this frame's hat above it and no button.
		'includes'   => array(
			'hat'                => 'Know Your Obligations',
			'heading'            => 'GST Rules Every Registered Business Should Know',
			'text'               => '',
			'button_text'        => '',
			'button_link'        => '',
			// Two-layer like the Foreign, Corporate Secretary and Accounting pages:
			// the stamped paperwork breaks above the card's top edge, so the asset is
			// Figma's whole 576x573 group (114:5778) and it must not be clipped.
			'image'              => 'gst/includes-photo.jpg',
			'image_box'          => 'aspect-[576/573]',
			'image_class'        => 'w-full h-full object-contain',
			'image_upload_class' => 'w-full h-full object-contain',
			'cards'              => array(
				1 => array(
					'icon'  => 'calendar-check',
					'title' => 'Filing Deadline',
					'text'  => 'GST F5 is due by the last day of the month.',
				),
				2 => array(
					'icon'  => 'file-text',
					'title' => 'Nil Returns Are Mandatory',
					'text'  => 'Nil returns must still be filed. Late filings incur a S$200 penalty.',
				),
				3 => array(
					'icon'  => 'percent',
					'title' => 'Current GST Rate',
					'text'  => 'GST is charged at 9% on standard-rated supplies.',
				),
				// The design's sentence stops mid-thought on a semicolon. Shipped
				// verbatim, the same call the Foreign frame's leftover strings got:
				// finishing someone's sentence for them is the client's to do.
				4 => array(
					'icon'  => 'file-arrow-up',
					'title' => 'Correcting a Mistake',
					'text'  => 'Small errors can be corrected in your next F5;',
				),
			),
		),
		// "Why SMEs Come to Us for GST" (114:5754) — the card carousel, three cards
		// like the Corporate Tax one.
		'why_slider' => array(
			'hat'     => 'Is This You?',
			'heading' => 'Why SMEs Come to Us for GST',
			'text'    => '',
			'cards'   => array(
				1 => array(
					'title' => 'Newly Crossed S$1M Turnover',
					'text'  => 'Registration becomes compulsory within a set window once you cross the threshold — we handle the paperwork before the deadline pressure builds.',
					'image' => 'gst/why-slide-1.jpg',
				),
				2 => array(
					'title' => 'Filing Yourself Is Getting Risky',
					'text'  => 'One misclassified input tax claim or one missed nil return is where the penalties start — we review every return before IRAS sees it.',
					'image' => 'gst/why-slide-2.jpg',
				),
				3 => array(
					'title' => 'Switching From Another Provider',
					'text'  => 'We collect your past filings, confirm your next deadline, and take over your filing cycle — most handovers done within a week.',
					'image' => 'gst/why-slide-3.jpg',
				),
			),
		),
		// "The Same Firm for Compliance, Digital, and Financing" (114:5834). The
		// Corporate Tax frame draws that heading as the pinned rail; this one draws
		// it as the bento, so it is `why` here and not `requirements`. Card 3 carries
		// copy over its photograph, as on the Accounting page.
		'why'        => array(
			'hat'     => 'Why Think SME',
			'heading' => 'The Same Firm for Compliance, Digital, and Financing',
			'text'    => '',
			'cards'   => array(
				1 => array(
					'icon'  => 'file-text',
					'title' => 'IRAS Tax Filing Agent',
					'text'  => 'Authorised to prepare and submit your GST and tax filings directly with IRAS.',
					'image' => 'gst/why-iras-agent.jpg',
				),
				2 => array(
					'icon'  => 'certificate',
					'title' => 'ACRA Registered Filing Agent',
					'text'  => 'The same firm that can also handle your incorporation, secretary, and compliance filings.',
				),
				3 => array(
					'icon'  => 'seal-check',
					'title' => 'Xero Certified Advisor',
					'text'  => 'Your books and your GST filing run off the same accurate, real-time data.',
					'image' => 'gst/why-xero-advisor.jpg',
				),
				4 => array(
					'icon'  => 'hand-coins',
					'title' => 'PSG Grant Eligible',
					'text'  => 'As an IMDA Pre-Approved Vendor, your Xero setup may qualify for up to 50% PSG grant support.',
				),
				5 => array(
					'title' => 'Financing When You Need It',
					'text'  => 'Clean books make loan and financing applications smoother — through the same team, via our 60+ lender network.',
					'image' => 'gst/why-financing.jpg',
				),
			),
		),
		'cta'        => array(
			'image' => 'gst/cta-photo.jpg',
		),
	);

	return array(
		'local'      => $local,
		'foreign'    => $foreign,
		'secretary'  => $secretary,
		'accounting' => $accounting,
		'tax'        => $tax,
		'gst'        => $gst,
	);
}

/**
 * Point the shared final CTA at the current page's photo.
 *
 * `template-parts/cta.php` is shared verbatim by every page and has no business
 * knowing which one it is on, so it exposes its fallback photo as a filter
 * rather than growing a branch per template. The Foreign and Corporate Secretary
 * frames each end on their own photograph; the Local set names the site-wide one,
 * so it passes the incoming URL through unchanged in practice. Pages outside this
 * family never reach here — the filter only fires while one of these templates is
 * the one WP resolved. A client upload in `cta_image` still wins over all of it.
 *
 * @param string $url Default CTA photo URL.
 * @return string
 */
function thinksme_ci_cta_photo( $url ) {
	$cta = thinksme_ci_defaults( 'cta' );

	return empty( $cta['image'] ) ? $url : thinksme_ci_image_url( $cta['image'] );
}
add_filter( 'thinksme_cta_photo', 'thinksme_ci_cta_photo' );
