<?php
/**
 * Design-fidelity copy and images for the ten pages built out of the ci-* parts.
 *
 * `page-company-incorporation-local.php`,
 * `page-company-incorporation-foreign.php`, `page-corporate-secretary.php`,
 * `page-accounting-bookkeeping.php`, `page-corporate-tax.php`,
 * `page-gst-registration.php`, `page-property-cashout.php` and
 * `page-business-loan.php` are the same page: the same sections in (nearly) the
 * same order, the same markup, the same ACF field *names*. Figma draws them as
 * eight frames (85:1351.., 101:846, 102:1969, 102:3127, 108:4497, 114:5287,
 * 119:1711, and 951:8993 in the newer "Think SME- INTERNAL" file) whose
 * differences are the words, the photographs and which sections they
 * include. So the eight pages share one set of template parts
 * (`template-parts/ci-*.php`) and this file is where they stop being the same:
 * every default those parts fall back to is looked up here, keyed by whichever of
 * the seven templates WordPress resolved.
 *
 * Sections a page doesn't have simply have no set here, and the parts for them
 * return early — which is how the same list of parts serves seven different
 * frames without a flag per section.
 *
 * The alternative, another copy of all the template parts, would have duplicated
 * ~1,100 lines of identical markup and made every layout fix a seven-file job.
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
 * Which of the ten pages is being rendered.
 *
 * Keys off thinksme_current_template() rather than is_page_template() for the
 * reason documented on that function: all seven templates are also named for
 * their page slug, so WP serves them to pages that never had the Page
 * Attributes template set, and is_page_template() reads exactly that missing
 * post meta.
 *
 * 'local' is the fallback rather than an "unknown" set: the parts are only ever
 * loaded by one of these templates, and a set that renders is a better failure
 * than a section of blanks.
 *
 * @return string 'local', 'foreign', 'secretary', 'accounting', 'tax', 'gst',
 *                'cashout', 'loan', 'remittance', 'mortgage', 'psg' or 'mra'.
 */
function thinksme_ci_content_set() {
	$sets = array(
		'page-company-incorporation-foreign.php' => 'foreign',
		'page-corporate-secretary.php'           => 'secretary',
		'page-accounting-bookkeeping.php'        => 'accounting',
		'page-corporate-tax.php'                 => 'tax',
		'page-gst-registration.php'              => 'gst',
		'page-property-cashout.php'              => 'cashout',
		'page-business-loan.php'                 => 'loan',
		'page-remittance.php'                    => 'remittance',
		'page-mortgage-loans.php'                => 'mortgage',
		'page-psg-grant.php'                     => 'psg',
		'page-mra-grant.php'                     => 'mra',
		'page-about-us.php'                      => 'about',
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
 * All ten content sets, built once per request.
 *
 * Kept as one function rather than ten so the pages' fields stay visibly
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

	// The Property Cashout page, Figma frame 951:8993 ("Desktop Property Cashout",
	// file "Think SME- INTERNAL", CgqSxvxd3aQeQSkLPhc48q). The fourth assembled
	// frame, so the section order below is the canvas's own.
	//
	// This is the first set that is mostly *new* sections: `definition`, `compare`,
	// `types`, `stack` (two instances) and `eligibility` are drawn on this frame
	// alone and their parts return early everywhere else, the same way
	// `ci-why-slider.php` is inert on the pages whose set names no `why_slider`.
	// Only four sections are shared with the earlier frames — the hero, the logo
	// marquee, the pinned rail (`requirements`) and the final CTA — and the frame
	// draws no pricing, no free tools, no what's-included, no bento, no carousel
	// and no Google Reviews.
	//
	// It is also the first set to name its own FAQ questions. Every earlier page
	// takes them from the site-wide `faq_item` CPT; this frame writes six of its
	// own, so `faq.php` now prefers a set's list when there is one. See the note
	// there.
	$cashout = array(
		'hero'         => array(
			'hat'              => 'Property Cashout Singapore',
			'title'            => 'Unlock Up To 90% of Your Property Value',
			'text'             => 'Your property is one of Singapore’s most valuable assets — and it doesn’t have to sit idle. At ThinkSME, we help Singapore SME owners use property cashout to release equity from their private residential, commercial, or industrial property and convert it into working capital for your business.',
			// This frame is the only one whose hero runs to two paragraphs
			// (951:9000 and 951:9001), which is why the second one is its own
			// field rather than a line break inside the first.
			'text_2'           => 'Property cashout in Singapore is one of the most flexible and cost-effective financing solutions available to SME owners.',
			'button_text'      => 'Get Your Free Assessment',
			'button_link'      => '/contact-us',
			'button_2_text'    => '+65 6012 9642',
			'button_2_link'    => 'tel:+6560129642',
			// Figma's hero image group (951:9005), 583x586: the subject is cut out
			// over the photo card and breaks above its top edge, so the group is
			// taller than the 583x495 card. The export is flattened onto white,
			// which is this page's own ground, so nothing is lost — the same
			// shortcut the Accounting, Corporate Tax and GST sets take.
			'image'            => 'pc/hero-image.jpg',
			'image_box'        => 'aspect-[583/586]',
			// Figma's own two columns (619 for the copy, 583 for the image group)
			// rather than the 703/609 the six older frames draw. Load-bearing: the
			// copy column is what breaks the headline into three lines, and both
			// the brush stroke and the first figure pill are measured against that
			// break — at 703px the headline rewraps and the pill lands on it.
			'body_class'       => 'lg:basis-[619px]',
			'image_col_class'  => 'lg:basis-[583px]',
			'badge'            => '',
			'badge_class'      => '',
			'photo_slot_class' => 'absolute left-0 top-[15.53%] w-full h-[84.47%] overflow-hidden rounded-2xl',
			// Measured against this headline breaking across three lines at 72px:
			// Figma strikes the third line ("Property Value"), not the first.
			'underline_class'  => 'hidden lg:block absolute left-0 top-[214px] w-[81.8%] rotate-[2.4deg] pointer-events-none select-none',
			// Three figures floating over the photo (951:9009, 951:9019, 951:9035).
			// Their icons are whole 56px discs rather than glyphs — Figma draws the
			// disc fill and the yellow glyph as one vector group, so the SVG carries
			// both and there is no disc in the markup to colour. Placement is a
			// share of the image box, straight off the canvas.
			'pills'            => array(
				1 => array(
					'icon'  => 'pill-ltv.svg',
					'value' => '90%',
					'label' => 'MAX LTV AVAILABLE',
					'class' => 'lg:absolute lg:left-[-20.24%] lg:top-[13.14%]',
				),
				2 => array(
					'icon'  => 'pill-lenders.svg',
					'value' => '60+',
					'label' => 'BANKS & LENDERS',
					'class' => 'lg:absolute lg:left-[-6%] lg:top-[93.17%]',
				),
				3 => array(
					'icon'  => 'pill-approval.svg',
					'value' => '1-7',
					'label' => 'DAYS APPROVAL',
					'class' => 'lg:absolute lg:left-[68.27%] lg:top-[59.56%]',
				),
			),
		),
		// "What is Property Cashout in Singapore?" (951:9052): a pale panel with the
		// heading and intro side by side above a worked example and a photograph.
		'definition'   => array(
			'hat'             => 'Definition',
			'heading'         => 'What is Property Cashout in Singapore?',
			// Two paragraphs with a bold lead-in, so this one is rendered through
			// wpautop()/wp_kses() rather than esc_html() — see the note in
			// template-parts/ci-definition.php.
			'text'            => "<strong>Property Cashout Singapore</strong> allows you to unlock the equity in your property without selling it. Through cash-out refinancing, you can access additional funds while continuing to own your residential, commercial, or industrial property.\n\nIdeal for SME owners, property cashout provides lower interest rates, larger loan amounts, and flexible funding for business expansion, working capital, equipment purchases, or investment opportunities.",
			'example_heading' => 'Example Calculation',
			'example_text'    => 'If your commercial shophouse is valued at <strong>S$2,000,000</strong> and you have an outstanding mortgage of <strong>S$900,000</strong>, you may be able to access up to <strong>S$900,000 in additional cash</strong> through a property cashout refinancing — without selling the property.',
			'formula'         => '<strong>Formula:</strong> (S$2M × 90% LTV) − S$900K outstanding = S$900K cashout available',
			// Two-layer in Figma (951:9059) and exported flattened — onto #fbfbfb
			// here rather than white, because that is the panel this photo sits on
			// and Figma flattens onto whatever is actually behind the node.
			'image'           => 'pc/definition-photo.jpg',
			'image_box'       => 'aspect-[650/490]',
		),
		// "Property Cashout vs Traditional Mortgage Refinancing" (951:9075 +
		// 951:9077). The two columns repeat the same eight row labels, so the label
		// is one field per row and only the two descriptions differ — half the
		// fields of a table that spelled both sides out.
		'compare'      => array(
			'heading'     => 'Property Cashout vs Traditional Mortgage Refinancing',
			'col_1_label' => 'Property Cashout',
			'col_2_label' => 'Standard Refinancing',
			'rows'        => array(
				1 => array(
					'label'  => 'Purpose',
					'text_1' => 'Unlock your property’s equity to raise funds for business expansion, working capital, investments, or personal financial goals.',
					'text_2' => 'Refinance your existing mortgage to secure a lower interest rate or reduce monthly repayments.',
				),
				2 => array(
					'label'  => 'Cash Received',
					'text_1' => 'Receive additional cash by borrowing against your available property equity.',
					'text_2' => 'No additional cash received. Your existing mortgage is simply replaced with a new loan.',
				),
				3 => array(
					'label'  => 'Loan Amount',
					'text_1' => 'Borrow up to 90% of your property’s valuation (subject to eligibility and lender assessment).',
					'text_2' => 'Limited to your existing outstanding mortgage balance.',
				),
				4 => array(
					'label'  => 'Interest Rate',
					'text_1' => 'Competitive secured financing rates, typically around 1.5%–3.5% p.a., depending on lender and profile.',
					'text_2' => 'Focused on obtaining the lowest available mortgage interest rate for your existing loan.',
				),
				5 => array(
					'label'  => 'Loan Tenure',
					'text_1' => 'Up to 15 years for business financing (subject to lender’s terms).',
					'text_2' => 'Up to 30 years, depending on property type and borrower eligibility.',
				),
				6 => array(
					'label'  => 'Processing Time',
					'text_1' => 'Approval and disbursement can be completed in as fast as 1 week.',
					'text_2' => 'Typically 4–12 weeks, depending on valuation and bank processing timelines.',
				),
				7 => array(
					'label'  => 'Financing Options',
					'text_1' => 'Access to 60+ banks, financial institutions, digital banks, and private lenders to secure the most suitable financing package.',
					'text_2' => 'Usually limited to traditional local and international banks offering mortgage refinancing.',
				),
				8 => array(
					'label'  => 'Best For',
					'text_1' => 'Business owners and property investors who need additional capital while retaining property ownership.',
					'text_2' => 'Homeowners looking to reduce mortgage costs without accessing additional funds.',
				),
			),
		),
		// "How Does Property Cashout Work in Singapore?" (951:9220) — the pinned
		// progress rail, the section the client asked to keep working exactly as it
		// does on the Corporate Tax page. Two things this frame adds to it and the
		// other two rails leave empty: an intro under the heading, and a timing
		// label under each step ("DAY 1", "WITHIN 24 HRS", …).
		'requirements' => array(
			'hat'        => 'Process',
			'heading'    => 'How Does Property Cashout Work in Singapore?',
			'text'       => 'ThinkSME guides you through every step — from free consultation to cash in your account.',
			// The scattered-blocks artwork this frame puts behind its two navy
			// panels, in place of the cityscape the other rails draw.
			'background' => 'pc/panel-bg.svg',
			'rail_class' => 'max-w-[588px]',
			'steps'      => array(
				1 => array(
					'icon'  => 'house',
					'title' => 'Property Valuation',
					'text'  => 'A bank-appointed valuer determines your property’s market value and maximum loan amount.',
					'meta'  => 'DAY 1',
				),
				2 => array(
					'icon'  => 'calculator',
					'title' => 'Assess Equity',
					'text'  => 'We calculate: (Property Value × up to 90% LTV) minus outstanding balance = your available cashout.',
					'meta'  => 'WITHIN 24 HRS',
				),
				3 => array(
					'icon'  => 'magnifying-glass',
					'title' => 'Choose Lender',
					'text'  => 'ThinkSME compares 60+ banks and institutions to find your best rate, LTV, and terms.',
					'meta'  => '2 – 3 DAYS',
				),
				4 => array(
					'icon'  => 'file-arrow-up',
					'title' => 'Submit Docs',
					'text'  => 'Provide the property address and documents. ThinkSME prepares and submits your full application.',
					'meta'  => '1 – 2 DAYS',
				),
				5 => array(
					'icon'  => 'shield-check',
					'title' => 'Approval & Drawdown',
					'text'  => 'We provide approval-in-principle quickly. Full approval and disbursement within 2–7 working days.',
					'meta'  => '2 – 7 DAYS',
				),
			),
		),
		// "Types of Property Eligible for Cashout in Singapore" (951:9448): four
		// photo-over-card columns whose headline figure is the LTV.
		'types'        => array(
			'hat'     => 'Eligible Property Types',
			'heading' => 'Types of Property Eligible for Cashout in Singapore',
			'text'    => 'ThinkSME works with all major private property types in Singapore for equity withdrawal.',
			'cards'   => array(
				1 => array(
					'label' => 'Private Residential',
					'value' => 'Up to 85%',
					'text'  => 'Condominium, Landed, SOHO',
					'image' => 'pc/type-residential.jpg',
				),
				2 => array(
					'label' => 'Commercial Property',
					'value' => 'Up to 75%',
					'text'  => 'Shophouse, Retail, Office unit',
					'image' => 'pc/type-commercial.jpg',
				),
				3 => array(
					'label' => 'Industrial Property',
					'value' => 'Up to 75%',
					'text'  => 'B1/B2 Factory, Warehouse, Ramp-up',
					'image' => 'pc/type-industrial.jpg',
				),
				4 => array(
					'label' => 'Mixed-Use',
					'value' => 'Up to 80%',
					'text'  => 'Heritage shophouse, HDB shophouse, Mixed retail-resi',
					'image' => 'pc/type-mixed-use.jpg',
				),
			),
		),
		// Two frames of the same shape (951:9557 and 951:9444 + 951:9490 +
		// 951:9545 + 951:9627): a photograph beside a column of overlapping cards
		// whose last one is navy and tilted. `photo` says which side the photograph
		// takes; everything else is identical, which is why this is one part called
		// twice rather than two.
		'stack'        => array(
			'benefits' => array(
				'hat'       => 'Why Use Property Cashout',
				'heading'   => 'Key Benefits of Property Cashout for Singapore SMEs',
				'photo'     => 'left',
				'image'     => 'pc/benefits-photo.jpg',
				'image_box' => 'aspect-[547/528]',
				'cards'     => array(
					1 => array(
						'icon'  => 'trend-down',
						'title' => 'Lower Interest Rates (Bank Rates)',
						'text'  => 'Secured property loans carry rates from 1%+ p.a. vs 4–5% p.a. for unsecured SME loans — significantly reducing your cost of capital.',
					),
					2 => array(
						'icon'  => 'stack',
						'title' => 'Large Loan Quantum',
						'text'  => 'Access S$200,000 to S$10,000,000+ depending on property value — far exceeding unsecured business loan limits.',
					),
					3 => array(
						'icon'  => 'lock-key-open',
						'title' => 'No Restriction on Use of Funds',
						'text'  => 'Unlike government SME loans, cashout proceeds can be used for any business purpose — inventory, payroll, renovation, expansion, investment.',
					),
					4 => array(
						'icon'  => 'house',
						'title' => 'Retain Property Ownership',
						'text'  => 'You continue to own and benefit from your property’s appreciation — while putting its equity to productive use today.',
					),
					5 => array(
						'icon'  => 'calendar-dots',
						'title' => 'Flexible Repayment Tenures',
						'text'  => 'Loan tenures of up to 25–30 years mean manageable monthly repayments suited to your cash flow.',
					),
				),
			),
			'why'      => array(
				'hat'       => 'Why ThinkSME',
				'heading'   => 'Why Choose ThinkSME for Property Cashout in Singapore?',
				'photo'     => 'right',
				'image'     => 'pc/why-photo.jpg',
				'image_box' => 'aspect-[547/415]',
				'cards'     => array(
					1 => array(
						'icon'  => 'bank',
						'title' => 'Access to 60+ Banks & Financial Institutions',
						'text'  => 'Best rate guaranteed — we compare across the entire market so you never overpay.',
					),
					2 => array(
						'icon'  => 'user-focus',
						'title' => 'Dedicated Property Cashout Advisors',
						'text'  => 'Expert guidance tailored to your property type and business goals — not generic advice.',
					),
					3 => array(
						'icon'  => 'briefcase',
						'title' => 'Full In-House Corporate Services',
						'text'  => 'We handle your accounting, secretarial, and grants while securing your loan.',
					),
					4 => array(
						'icon'  => 'clock-countdown',
						'title' => 'Free Eligibility Assessment within 24 Hours',
						'text'  => 'Know your options before committing to anything — zero cost, zero obligation.',
					),
					5 => array(
						'icon'  => 'hand-coins',
						'title' => 'Grant Advisory Integration',
						'text'  => 'Maximise EDG, PSG & MRA grants alongside your cashout strategy.',
					),
				),
			),
		),
		// "Eligibility Requirements for Property Cashout in Singapore" (951:9631):
		// a navy panel with two tabs over a white card holding a checklist and a
		// photograph.
		//
		// Figma draws only the first tab's panel, exactly as it draws only the first
		// of ci-tools.php's three. Tab 2 therefore ships with the label and title
		// the design writes and **no items** — the client fills its seven fields
		// from wp-admin. Inventing a document list here would be inventing the
		// client's copy, so the panel renders its title and photograph until they
		// do.
		'eligibility'  => array(
			'hat'       => 'Am I Eligible?',
			'heading'   => 'Eligibility Requirements for Property Cashout in Singapore',
			'text'      => 'Here’s what you typically need. ThinkSME assesses your specific situation for free within 24 hours.',
			// Two-layer in Figma (951:9840 over 951:9843) and flattened onto white
			// — the white card, not the navy panel, is what sits behind it.
			'image'     => 'pc/eligibility-photo.jpg',
			'image_box' => 'aspect-[442/538]',
			'tabs'      => array(
				1 => array(
					'label' => 'General Eligibility Criteria',
					'title' => 'General Eligibility Criteria',
					'items' => array(
						1 => 'Property owner (individual or company-owned property accepted)',
						2 => 'Singapore Citizen, Permanent Resident, or Foreign National (terms vary)',
						3 => 'Property free from legal encumbrances or active disputes',
						4 => 'Minimum property value of S$500,000 (most lenders)',
						5 => 'Property must have sufficient equity above outstanding loans',
						6 => 'Personal name: comply with MAS TDSR cap of 55% of gross income',
						7 => 'Company name: TDSR does not apply — significantly more flexible',
					),
				),
				2 => array(
					'label' => 'Documents Typically Required',
					'title' => 'Documents Typically Required',
					'items' => array(
						1 => '',
						2 => '',
						3 => '',
						4 => '',
						5 => '',
						6 => '',
						7 => '',
					),
				),
			),
		),
		// The six questions this frame writes (951:9844). Only the first has an
		// answer on the canvas; the rest open to just the question until the client
		// fills them in, the same way faq.php's own CPT-less fallback does.
		'faq'          => array(
			'items' => array(
				1 => array(
					'question' => 'What is property cashout in Singapore?',
					'answer'   => 'Property cashout in Singapore is the process of borrowing against the equity of a property you already own. Instead of selling the property, you take out a new loan (or top-up an existing mortgage) secured against the property’s value and receive the difference in cash. This cash can be used for any purpose, including funding your business operations, expansion, or investment.',
				),
				2 => array(
					'question' => 'Can I use my HDB flat for cashout refinancing in Singapore?',
					'answer'   => '',
				),
				3 => array(
					'question' => 'How much can I borrow through property cashout in Singapore?',
					'answer'   => '',
				),
				4 => array(
					'question' => 'What is the interest rate for property cashout loans in Singapore?',
					'answer'   => '',
				),
				5 => array(
					'question' => 'How long does property cashout approval take in Singapore?',
					'answer'   => '',
				),
				6 => array(
					'question' => 'Can I use property cashout proceeds for my business?',
					'answer'   => '',
				),
			),
		),
		'cta'          => array(
			'image' => 'pc/cta-photo.jpg',
		),
	);

	// The Business Loan page, Figma frame 119:1711 ("Desktop Business Loan", file
	// "Untitled", vzdpOnH1U36oXcFcugiyE5). The fifth assembled frame, so the section
	// order below is the canvas's own, read top to bottom:
	//
	//   hero (119:1713 + 119:1723 + the partner-bank strip 119:1737) → stats
	//   (119:1751) → steps (119:1796) → calculator (119:1825) → grid "approval"
	//   (119:1871) → ways (119:1932) → stack "bankers" (119:1991 + 119:1995 +
	//   119:2179) → stack "why" (119:2182) → types (119:2043) → testimonials → faq
	//   (119:2144) → cta (119:2146).
	//
	// It adds exactly one new part, `ci-steps.php` — the three-card row whose middle
	// card is open — and reaches every other section through a value: the figures
	// band grew a footnote, `ci-grid` grew an intro paragraph and a three-column
	// track, `ci-types` grew a checklist per card, and `ci-calculator` grew a second
	// mode. The frame draws no pricing table, no free-tools tabs, no what's-included
	// cards, no bento, no card carousel, no pinned rail and no logo marquee, so those
	// sections simply have no key here.
	//
	// Like the Property Cashout set it writes its own FAQ questions rather than
	// taking the site-wide `faq_item` CPT — this frame's six are about business
	// lending and nothing else.
	$loan = array(
		'hero'        => array(
			'hat'              => '60+ Lenders · 19 Core Partner Banks · Ex-Banker Team',
			'title'            => 'Compare SME Business Loans With One Application',
			'text'             => 'One bank’s “no” isn’t the market’s answer. Our team — ex-bankers with 30+ years of combined experience — matches your application against 19 core partner banks and 60+ lenders overall, so you find the one whose criteria actually fit your business.',
			'button_text'      => 'Check My Eligibility',
			'button_link'      => '/contact-us',
			'button_2_text'    => '+65 6012 9642',
			'button_2_link'    => 'tel:+6560129642',
			// Figma's whole image group (119:1723), 642x586: the lightbulb badge
			// overhangs the photo card's top-left corner, so the group is wider and
			// taller than the 583x484 card. The export carries the badge already, hence
			// no `badge` here — see the note in template-parts/ci-hero.php.
			'image'            => 'bl/hero-image.jpg',
			'image_box'        => 'aspect-[642/586]',
			'image_col_class'  => 'lg:basis-[642px]',
			'badge'            => '',
			// Only the upload branch needs these: where the photo sits inside the group
			// and where the badge goes back on top of it.
			'badge_class'      => 'absolute left-[0.8%] top-[12.4%] w-[18.3%] pointer-events-none select-none',
			'photo_slot_class' => 'absolute left-[9%] top-[14.5%] w-[91%] h-[85.5%] overflow-hidden rounded-2xl',
			// Figma strikes two of this headline's four lines (119:1714 and 119:1715),
			// measured against it breaking at 72px in the 703px column.
			'underline_class'  => 'hidden lg:block absolute left-0 top-[131px] w-[67.6%] rotate-[2.27deg] pointer-events-none select-none',
			'underline_2_class' => 'hidden lg:block absolute left-0 top-[201px] w-[27.2%] -scale-y-100 rotate-[8.71deg] pointer-events-none select-none',
			// The five partner banks under the photograph (119:1737 + 119:1750). Figma
			// draws them as a masked marquee 505px wide inside a 439px frame, which is
			// why the Standard Chartered wordmark is clipped mid-letter there; only its
			// mark is carried over. Decoration for the claim the hat makes, so the
			// logos are theme files and not a client field.
			'banks'            => array(
				'label' => 'PARTNER BANKS',
				'logos' => array(
					array( 'file' => 'bl/banks/dbs.png', 'name' => 'DBS', 'class' => 'h-[26px] lg:h-[32px]' ),
					array( 'file' => 'bl/banks/citi.png', 'name' => 'Citi', 'class' => 'h-[24px] lg:h-[30px]' ),
					array( 'file' => 'bl/banks/ocbc.png', 'name' => 'OCBC', 'class' => 'h-[26px] lg:h-[32px]' ),
					array( 'file' => 'bl/banks/uob.png', 'name' => 'UOB', 'class' => 'h-[26px] lg:h-[32px]' ),
					array( 'file' => 'bl/banks/standard-chartered.png', 'name' => 'Standard Chartered', 'class' => 'h-[28px] lg:h-[34px]' ),
				),
			),
		),
		// The figures band (119:1751). Same four-figure navy pill the Corporate Tax
		// page draws, plus the footnote this frame writes under it (119:1795): the
		// headline rate is a flat rate, and saying so under the number is the point.
		'stats'       => array(
			'note'  => '*Flat rate example — your actual rate depends on the lender and your profile. Ask us for the Effective Interest Rate (EIR) equivalent, which reflects the true cost more accurately than a flat rate.',
			'cards' => array(
				1 => array(
					'icon'  => 'lightning',
					'value' => 'From 4.05%*',
					'label' => 'Flat Rate P.A.',
				),
				2 => array(
					'icon'  => 'calendar-dots',
					'value' => '2-4 Weeks',
					'label' => 'Standard Full Approval',
				),
				3 => array(
					'icon'  => 'clock-countdown',
					'value' => '24Hrs',
					'label' => 'Initial Eligibility Match',
				),
				4 => array(
					'icon'  => 'shield-check',
					'value' => '30+ Years',
					'label' => 'Combined Ex-Banker Experience',
				),
			),
		),
		// "Get Funded in 3 Simple Steps" (119:1796): three cards, the middle one open.
		// Figma writes copy for that one only and draws its own mouse cursor over it —
		// the same situation template-parts/roa-block.php is in, and the reason the
		// open card is "the first one with copy" rather than a field.
		'steps'       => array(
			'heading' => 'Get Funded in 3 Simple Steps',
			'cards'   => array(
				1 => array(
					'icon'  => 'file-arrow-up',
					'title' => 'Send Your Financial Documents',
					'text'  => '',
				),
				2 => array(
					'icon'  => 'chart-bar',
					'title' => 'Get a Free Comparison Report',
					'text'  => 'Our ex-banker team reviews your profile and shows which lenders actually fit, with real rate comparisons.',
				),
				3 => array(
					'icon'  => 'hand-coins',
					'title' => 'Get Your Funding',
					'text'  => '',
				),
			),
		),
		// "Estimate Your Loan Repayments" (119:1825). The same navy calculator panel
		// the Corporate Tax page draws, in its second mode: three inputs and a flat-rate
		// instalment instead of one input and an exemption. `mode` is a per-page
		// default because it is which frame the design draws — see the note in
		// template-parts/ci-calculator.php.
		'calculator'  => array(
			'mode'         => 'loan',
			'hat'          => 'Free Tools',
			'heading'      => 'Estimate Your Loan Repayments',
			'text'         => 'A quick indicative estimate based on flat rate pricing — not a loan offer.',
			'title'        => 'Business Loan Calculator',
			'panel_text'   => 'Enter your loan amount, tenure, and rate to estimate your monthly instalment.',
			'amount_label' => 'Loan amount (S$)',
			'placeholder'  => 'e.g. 200,000',
			'tenure_label' => 'Loan tenure (years)',
			// The tenures the frame's control offers. Figma draws the closed select
			// showing "3 Years" and no open state, so the list is the control's own
			// vocabulary — the EFS working capital ceiling is 5 years and the fixed
			// asset one 15, which is what the range spans.
			'tenures'      => array( 1, 2, 3, 4, 5, 7, 10, 15 ),
			'tenure'       => 3,
			'rate_label'   => 'Flat interest rate (% p.a.)',
			'rate'         => '4.05',
			'button_text'  => 'Calculate Repayment',
			'button_link'  => '/contact-us',
			'result_label' => 'Estimated monthly instalment',
			'saved_label'  => 'Total repayable over the tenure',
			'error_text'   => 'Enter a loan amount to see an estimate.',
			'currency'     => 'S$',
			'disclaimer'   => 'Based on flat rate methodology: interest is calculated on the original loan amount for the full tenure. This is illustrative only — your actual rate and approved amount depend on the lender and your company’s credit profile. Ask us for the Effective Interest Rate (EIR) equivalent for a true cost comparison.',
		),
		// "What Actually Determines Loan Approval" (119:1871): five icon cards in three
		// columns with a photograph in the fifth cell — the same shape the Accounting
		// page's "Same Firm" grid has, so it is a `ci-grid` instance rather than a part
		// of its own. Three equal columns, which six columns can't divide, hence the
		// track below.
		'grid'        => array(
			'approval' => array(
				'hat'           => 'Is This You?',
				'heading'       => 'What Actually Determines Loan Approval',
				'text'          => 'A rejection rarely means “no bank will lend to you” — it usually means one bank’s specific criteria didn’t match your profile.',
				'align'         => 'left',
				'grid_class'    => 'lg:grid-cols-3',
				'section_class' => 'py-xl lg:py-[40px]',
				'cards'         => array(
					1 => array(
						'icon'  => 'question',
						'title' => 'Your Bank Said No — Without Explaining Why',
						'text'  => 'Approval depends on your personal credit profile, which bank you go with, existing banking relationships, and even how you answer credit questions — factors that vary hugely between institutions.',
						'class' => 'lg:min-h-[404px]',
					),
					2 => array(
						'icon'  => 'seal-warning',
						'title' => 'You Don’t Want a “Black Mark”',
						'text'  => 'A rejected application can leave a mark in a bank’s internal records, making future applications to that same bank harder. Getting matched right the first time avoids this.',
						'class' => 'lg:min-h-[404px]',
					),
					3 => array(
						'icon'  => 'arrows-split',
						'title' => 'Different Banks, Wildly Different Answers',
						'text'  => 'You could get rejected by one major bank and approved for S$100,000 by another — because each institution’s risk appetite and required paperwork genuinely differ that much.',
						'class' => 'lg:min-h-[404px]',
					),
					4 => array(
						'icon'  => 'calendar-dots',
						'title' => 'Your Proposal Paperwork Isn’t Bank-Ready',
						'text'  => 'Weak financial documentation or a poorly prepared credit proposal is one of the most common reasons for rejection or a lower-than-expected loan amount.',
						'class' => 'lg:min-h-[378px]',
					),
					5 => array(
						'photo' => 'bl/grid-photo.jpg',
						// The photograph is cropped to the cell's own proportion at the design's
						// 1440px and drawn from its bottom edge, so it fills the cell rather than
						// reaching over the row above it — this frame's cards carry copy right down
						// to their bottom padding, and the grid's row gap is only 15px. The corners
						// are the cell's rather than baked into the JPEG.
						'class' => 'lg:min-h-[378px] overflow-hidden rounded-lg',
					),
					6 => array(
						'icon'  => 'binoculars',
						'title' => 'You Want an Ex-Banker’s Perspective',
						'text'  => 'Our team has 30+ years of combined banking experience — we know what banks are actually assessing before you submit, not after you’re rejected.',
						'class' => 'lg:min-h-[378px]',
					),
				),
			),
		),
		// "One Application, Matched Against 60+ Lenders" (119:1932): section copy and
		// two contact cards on the left, the free-assessment card on the right. That is
		// template-parts/ci-ways.php exactly, down to the arrow at the far right of
		// each card — this frame just prices nothing, so the card's price and pills are
		// empty and the part skips them.
		'ways'        => array(
			'hat'     => 'How It Works',
			'heading' => 'One Application, Matched Against 60+ Lenders',
			'text'    => 'We take your business profile once and match it against banks, digital lenders, and government-backed schemes — rather than you filling out the same form 20 times.',
			'cards'   => array(
				1 => array(
					'icon'  => 'envelope-simple',
					'title' => 'Check My Eligibility',
					'text'  => 'Free, non-obligatory assessment',
				),
				2 => array(
					'icon'  => 'phone',
					'title' => 'Talk to Our Financing Team',
					'text'  => 'Direct advisory, not a call centre',
				),
			),
			'plan'    => array(
				'badge'       => 'FREE ASSESSMENT',
				'title'       => 'No Cost to Find Out Where You Stand',
				'text'        => 'Our loan assessment and lender comparison are entirely free. If you choose to engage us to manage your application, that service is subject to a fee — we’ll walk you through it upfront before anything is charged.',
				'price'       => '',
				'badge_1'     => '',
				'badge_2'     => '',
				'features'    => array(
					1 => 'Free assessment and lender comparison, no obligation',
					2 => 'One profile matched across our full lender panel',
					3 => 'Clear engagement terms explained before you commit',
				),
				'button_text' => 'Get My Free Assessment',
				'button_link' => '/contact-us',
			),
		),
		// Two frames of the overlapping-card shape (119:1991 + 119:1995 + 119:2179, and
		// 119:2182). `photo` says which side the photograph takes: "Built by Bankers"
		// puts it right, "Why Choose Think SME" puts it left.
		'stack'       => array(
			'bankers' => array(
				'hat'       => 'Why Think SME',
				'heading'   => 'Built by Bankers, Not Just Brokers',
				'photo'     => 'right',
				'image'     => 'bl/bankers-photo.jpg',
				'image_box' => 'aspect-[1090/1075]',
				'cards'     => array(
					1 => array(
						'icon'  => 'chart-line-up',
						'title' => 'We Read Financials Like a Bank Does',
						'text'  => 'Our team’s banking background means we know how your numbers will actually be assessed, before you apply.',
					),
					2 => array(
						'icon'  => 'stack',
						'title' => 'Same Firm for Compliance and Financing',
						'text'  => 'Your accounts and structure are already clean because we likely handle your incorporation and bookkeeping too.',
					),
					3 => array(
						'icon'  => 'user-focus',
						'title' => 'A Person, Not a Portal',
						'text'  => 'Our financing team works your application personally — not an algorithm that stops responding after a rejection.',
					),
					4 => array(
						'icon'  => 'scales',
						'title' => 'Genuinely Independent',
						'text'  => 'We’re not tied to one bank — 60+ lenders means we recommend what fits you, not what pays us most.',
					),
					5 => array(
						'icon'  => 'shield-check',
						'title' => '',
						'text'  => '',
					),
				),
			),
			'why'     => array(
				'hat'       => 'Why Think SME',
				'heading'   => 'Why Choose Think SME for SME Business Loans in Singapore?',
				'photo'     => 'left',
				'image'     => 'bl/why-photo.jpg',
				'image_box' => 'aspect-[1094/1128]',
				'cards'     => array(
					1 => array(
						'icon'  => 'trend-down',
						'title' => 'Access to 60+ Banks & Financial Institutions',
						'text'  => 'Best rate guaranteed — we compare across our full panel, including 19 core partner banks, so you never overpay.',
					),
					2 => array(
						'icon'  => 'stack',
						'title' => 'Dedicated Business Loan Advisors',
						'text'  => 'Ex-bankers with 30+ years of combined experience — expert guidance tailored to your industry, not generic advice.',
					),
					3 => array(
						'icon'  => 'lock-key-open',
						'title' => 'Full In-House Corporate Services',
						'text'  => 'We handle your accounting, secretarial, and grants while securing your loan.',
					),
					4 => array(
						'icon'  => 'house',
						'title' => 'Free Eligibility Assessment Within 24 Hours',
						'text'  => 'Know your options before committing to anything — zero cost, zero obligation.',
					),
					5 => array(
						'icon'  => 'calendar-dots',
						'title' => 'Grant Advisory Integration',
						'text'  => 'Maximise EDG, PSG & MRA grants alongside your financing strategy.',
					),
				),
			),
		),
		// "Financing Options at a Glance" (119:2043): three photo-topped columns whose
		// headline is the ceiling. The Property Cashout page's `ci-types` in three
		// columns instead of four, with the checklist this frame adds under each
		// description.
		'types'       => array(
			'hat'        => 'Loan Types',
			'heading'    => 'Financing Options at a Glance',
			'text'       => '',
			'grid_class' => 'lg:grid-cols-3',
			'image_box'  => 'aspect-[405/300]',
			'cards'      => array(
				1 => array(
					'label'    => 'EFS Working Capital Loan',
					'value'    => 'Up to S$500,000',
					'text'     => 'Government risk-shared loan for day-to-day operating needs, up to 5-year repayment.',
					'image'    => 'bl/type-working-capital.jpg',
					'features' => array(
						1 => '30% local shareholding required',
						2 => 'Repayment up to 5 years',
						3 => 'Shared risk with Enterprise Singapore',
					),
				),
				2 => array(
					'label'    => 'EFS Fixed Asset Loan',
					'value'    => 'Up to S$30 million',
					'text'     => 'For equipment, machinery, or business premises, with tenure up to 15 years.',
					'image'    => 'bl/type-fixed-asset.jpg',
					'features' => array(
						1 => 'Covers property & equipment purchase',
						2 => 'Up to 15-year tenure',
						3 => 'Available through DBS, OCBC, UOB',
					),
				),
				3 => array(
					'label'    => 'Trade & Invoice Financing',
					'value'    => 'Up to S$10 million',
					'text'     => 'Unlocks cash tied up in receivables, inventory, or overseas trade cycles.',
					'image'    => 'bl/type-trade.jpg',
					'features' => array(
						1 => 'Inventory & factoring financing',
						2 => 'Overseas working capital',
						3 => 'Bank guarantees included',
					),
				),
				4 => array(
					'label'    => '',
					'value'    => '',
					'text'     => '',
					'image'    => '',
					'features' => array(),
				),
			),
		),
		// The six questions this frame writes (119:2144). Only the first has an answer
		// on the canvas; the rest open to just the question until the client fills them
		// in, exactly as on the Property Cashout page.
		'faq'         => array(
			'items' => array(
				1 => array(
					'question' => 'What is the Working Capital Loan (WCL)?',
					'answer'   => 'The SME Working Capital Loan is a government-assisted financing scheme under the Enterprise Financing Scheme (EFS-WCL), helping SMEs finance operational cash flow needs. Eligible businesses can access up to S$500,000, with risk shared 50/50 between the lender and Enterprise Singapore.',
				),
				2 => array(
					'question' => 'Why shouldn’t I just apply to banks myself?',
					'answer'   => '',
				),
				3 => array(
					'question' => 'Why would one bank reject me but another approve me?',
					'answer'   => '',
				),
				4 => array(
					'question' => 'Can I take a new loan if I already have a WCL?',
					'answer'   => '',
				),
				5 => array(
					'question' => 'How fast can I get funded?',
					'answer'   => '',
				),
				6 => array(
					'question' => 'How much do I have to pay you?',
					'answer'   => '',
				),
			),
		),
		'cta'         => array(
			'image' => 'bl/cta-photo.jpg',
		),
	);

	// The Remittance page, Figma frame 123:2687 ("Desktop Remittance", file
	// "Untitled", vzdpOnH1U36oXcFcugiyE5). The shortest frame in the family — four
	// sections and a footer — read off the canvas:
	//
	//   hero (123:2689 + 123:2701) → grid "why_ofx" (123:2715) → signup (123:2747 +
	//   123:2748 + the two flanking photographs) → faq → cta.
	//
	// It is a partner page rather than a service page: the offer is OFX's rates
	// through Think SME, which is why the hero opens on OFX's logo instead of a hat
	// pill and why the copy names the partner throughout.
	//
	// One new part, `ci-signup.php`. Everything else is a value: the hero's `logo`
	// slot and, on the grid, a pale panel with white cards (`panel_class` /
	// `card_class`). The frame draws no pricing, no free tools, no figures band, no
	// calculator, no what's-included, no bento, no carousel, no pinned rail, no logo
	// marquee and no Google Reviews, so none of those sections have a key here.
	//
	// Its FAQ is the **site-wide** one: unlike Property Cashout and Business Loan
	// this frame draws the same six questions the homepage does, so the set names no
	// `faq` list and faq.php falls back to the `faq_item` CPT. Its final CTA is the
	// site-wide one too, photograph included, so there is no `cta` key either.
	$remittance = array(
		'hero'   => array(
			// The hat is unused on this page — the logo below takes its place — but it
			// stays defined so the field has something to fall back to if the client
			// clears the logo out of the theme.
			'hat'              => 'Remittance',
			'logo'             => 'rm/ofx-logo.svg',
			'logo_alt'         => 'OFX',
			'logo_class'       => 'h-[48px] lg:h-[64px] w-auto',
			'title'            => 'Save with OFX and Think SME partner rates',
			'text'             => 'Whether your business is paying international suppliers, overseas staff or selling to a global customer base, understanding your foreign exchange expenses could mean savings for your business.',
			'button_text'      => 'Sign Up with OFX',
			'button_link'      => '/contact-us',
			'button_2_text'    => '+65 6012 9642',
			'button_2_link'    => 'tel:+6560129642',
			// Figma's whole image group (123:2701), 642x523: the lightbulb badge
			// overhangs the photo card's top-left corner, so the group is bigger than
			// the 537x403 card. The export carries the badge already, hence no `badge`.
			'image'            => 'rm/hero-image.jpg',
			'image_box'        => 'aspect-[642/523]',
			'image_col_class'  => 'lg:basis-[642px]',
			'badge'            => '',
			'badge_class'      => 'absolute left-[0.8%] top-[2.1%] w-[19.5%] pointer-events-none select-none',
			'photo_slot_class' => 'absolute left-[9.2%] top-[16.1%] w-[90.8%] h-[83.9%] overflow-hidden rounded-2xl',
			// One stroke, and it is mid-headline rather than under a whole line: Figma
			// strikes the word "OFX" on the second line (123:2690), at 7.74deg.
			'underline_file'   => 'rm/hero-underline.svg',
			// Measured against the headline breaking across three lines at 72px: the stroke
			// goes under the word "OFX" on the first line, not under a whole line, and the
			// 7.74deg is baked into that vector rather than applied here.
			'underline_class'  => 'hidden lg:block absolute left-[51.6%] top-[62px] w-[23.9%] pointer-events-none select-none',
		),
		// "Why Use OFX?" (123:2715): three centred icon cards, but on a pale panel with
		// white cards rather than on the page with pale ones — the two class strings
		// below are the whole difference from the Accounting page's centred grid.
		'grid'   => array(
			'why_ofx' => array(
				'hat'           => 'Complete Corporate Services',
				'heading'       => 'Why Use OFX?',
				'align'         => 'center',
				'grid_class'    => 'lg:grid-cols-3',
				'section_class' => 'py-xl lg:py-[40px]',
				'panel_class'   => 'bg-surface-panel rounded-[40px] lg:rounded-[56px] px-lg lg:px-[56px] py-xl lg:py-[112px]',
				'card_class'    => 'bg-surface-white',
				'cards'         => array(
					1 => array(
						'icon'  => 'star',
						'title' => 'Enjoy Preferential Rates',
						'text'  => 'Preferential exchange rates and no OFX fees on FX transfers* = real savings back into your wallet.',
						'class' => 'lg:min-h-[345px]',
					),
					2 => array(
						'icon'  => 'shield-check',
						'title' => '24/7 OFXPERTS',
						'text'  => 'Speak to a currency specialist 24/7. No long hold queues, no offshore call centres',
						'class' => 'lg:min-h-[345px]',
					),
					3 => array(
						'icon'  => 'user-sound',
						'title' => 'Ease Of Use',
						'text'  => 'Login and track your transfers when, where and how you want online or via the OFX app.',
						'class' => 'lg:min-h-[345px]',
					),
				),
			),
		),
		// The sign-up band (123:2747 + 123:2748). The asterisk on "FX Transfers*" is
		// the design's own — it points at the fee note in the first card above.
		'signup' => array(
			'heading'       => 'Sign Up With OFX And Start Saving On FX Transfers*.',
			'button_text'   => 'Sign Up With OFX',
			'button_link'   => '/contact-us',
			'button_2_text' => 'Download Brochure',
			'button_2_link' => '/contact-us',
			'photo_left'    => 'rm/signup-left.jpg',
			'photo_right'   => 'rm/signup-right.jpg',
		),
	);

	// The Mortgage Loans page, Figma frame 124:3265 ("Desktop mortgage-loans", file
	// "Untitled", vzdpOnH1U36oXcFcugiyE5). Read off the canvas:
	//
	//   hero (124:3267 + 124:3523) → logos-slider with its own caption (124:3605) →
	//   stats (124:3281) → grid "types" (124:3326) → ways (124:3537) → grid "why"
	//   (124:3399, on a navy panel) → grid "decision" (124:3372) → testimonials →
	//   faq → cta.
	//
	// It adds **no new template part at all** — the first page in the family that
	// doesn't. What it needed instead were five optional bits inside sections that
	// already existed: the hero's promotional pill and its own second brush stroke,
	// the marquee's caption, a fourth check plus small print on the ways card, and on
	// the grid a per-instance header colour (for the navy panel) and an optional
	// figure between a card's title and its copy (for the two rate cards).
	//
	// Three `ci-grid` instances, which is what that part is for: `types` is the four
	// centred cards, `why` the navy panel, `decision` the fixed-or-floating pair.
	//
	// Its FAQ and its final CTA are the site-wide ones — this frame draws the
	// homepage's own questions and closing photograph — so there is no `faq` list and
	// no `cta` key here.
	$mortgage = array(
		'hero'   => array(
			'hat'               => '19 Banks Compared · Free Broker Service',
			'title'             => 'Compare Best Mortgage Rates Across 19 Banks in One Call',
			'text'              => 'Whether you’re buying your first home or refinancing an existing loan, one conversation with our team gets you rates from every major bank — including promotional pricing banks don’t publish.',
			// Figma's own label reads "Copare My Rate" (124:3279). That is a typo, not
			// copy — the same frame's headline spells the word — so it ships corrected
			// rather than verbatim, unlike the duplication artefacts the Foreign and GST
			// sets keep.
			'button_text'       => 'Compare My Rate',
			'button_link'       => '/contact-us',
			'button_2_text'     => '+65 6012 9642',
			'button_2_link'     => 'tel:+6560129642',
			// The wide pill above the buttons (124:3274).
			'promo_text'        => 'Promotional Cash Rebate of Up to S$1,000',
			// Figma's whole image group (124:3523), 594x519: the lightbulb badge overhangs
			// the photo card's top-left corner, so the group is bigger than the 575x457
			// card. The export carries the badge already, hence no `badge`.
			'image'             => 'ml/hero-image.jpg',
			'image_box'         => 'aspect-[594/519]',
			'body_class'        => 'lg:basis-[656px]',
			'image_col_class'   => 'lg:basis-[594px]',
			'badge'             => '',
			'badge_class'       => 'absolute left-[16.8%] top-0 w-[21.1%] pointer-events-none select-none',
			'photo_slot_class'  => 'absolute left-0 top-[12.1%] w-[100%] h-[87.9%] overflow-hidden rounded-2xl',
			// 64px, not the 72px five of the frames draw.
			'title_class'       => 'lg:text-[64px]',
			// Two strokes of different lengths: a short one under "Best" on the first line
			// (124:3270) and a long one under "Mortgage Rates" on the second (124:3271),
			// which runs on past the words. Each is its own vector, since scaling one to the
			// other's width changes its weight. Measured against this headline breaking
			// across four lines at 64px in the 656px column, which is what Figma renders —
			// its 701px text box is wider than the text needs.
			'underline_file'    => 'ml/hero-underline-short.svg',
			'underline_class'   => 'hidden lg:block absolute left-[45.9%] top-[60px] w-[19.6%] pointer-events-none select-none',
			'underline_2_file'  => 'ml/hero-underline.svg',
			'underline_2_class' => 'hidden lg:block absolute left-0 top-[112px] w-[70.3%] pointer-events-none select-none',
		),
		// The marquee's own small print (124:3614). The logos are the site-wide
		// certifications group, same as every other page's marquee.
		'logos'  => array(
			'caption' => '*Cash rebate applies to selected packages only. Terms and conditions apply — ask us for full details during your consultation.',
		),
		// The rates band (124:3281). Four figures and the line that says they move.
		'stats'  => array(
			'note'  => 'Indicative rates, subjected to change from time to time — confirm up to date pricing during your consultation.',
			'cards' => array(
				1 => array(
					'icon'  => 'trend-down',
					'value' => '~1.27%',
					'label' => 'Lowest Floating (Private)',
				),
				2 => array(
					'icon'  => 'calendar-check',
					'value' => '~1.30%',
					'label' => 'Lowest Fixed (2-Year)',
				),
				3 => array(
					'icon'  => 'percent',
					'value' => '2.60%',
					'label' => 'HDB Concessionary Rate',
				),
				4 => array(
					'icon'  => 'bank',
					'value' => '19',
					'label' => 'Banks Compared',
				),
			),
		),
		'grid'   => array(
			// "Switching to Think SME, Without the Hassle" (124:3326): four centred cards,
			// no hat. The heading is the frame's own — it is the Accounting page's wording
			// over a different set of cards, a duplication artefact like the Foreign
			// frame's leftovers, and it ships verbatim.
			'types'    => array(
				'hat'           => '',
				'heading'       => 'Switching to Think SME, Without the Hassle',
				'align'         => 'center',
				'grid_class'    => 'lg:grid-cols-4',
				'section_class' => 'py-xl lg:py-[40px]',
				'cards'         => array(
					1 => array(
						'icon'  => 'house-line',
						'title' => 'New Home Purchase',
						'text'  => 'HDB, condo, or landed — matched to the right package for your buyer profile.',
						'class' => 'lg:min-h-[339px]',
					),
					2 => array(
						'icon'  => 'arrows-clockwise',
						'title' => 'Refinancing',
						'text'  => 'Switch to a lower rate once your lock-in ends — most borrowers overpay simply by not reviewing.',
						'class' => 'lg:min-h-[339px]',
					),
					3 => array(
						'icon'  => 'chart-line',
						'title' => 'Fixed vs. Floating (SORA)',
						'text'  => 'We explain the real trade-off, not just the headline rate, based on your risk comfort.',
						'class' => 'lg:min-h-[339px]',
					),
					4 => array(
						'icon'  => 'bank',
						'title' => 'HDB vs. Bank Loan',
						'text'  => 'We help you weigh the HDB concessionary rate against current bank packages.',
						'class' => 'lg:min-h-[339px]',
					),
				),
			),
			// "Same Team for Your Mortgage and Your Business" (124:3399): the same grid on a
			// navy panel with white cards, which is why the header block names its own
			// colours.
			'why'      => array(
				'hat'           => 'Why Think SME',
				'heading'       => 'Same Team for Your Mortgage and Your Business',
				'align'         => 'left',
				'grid_class'    => 'lg:grid-cols-4',
				'section_class' => 'py-xl lg:py-[40px]',
				'panel_class'   => 'bg-surface-dark rounded-[40px] lg:rounded-[56px] px-lg lg:px-[56px] py-xl lg:py-[64px]',
				'card_class'    => 'bg-surface-white',
				'heading_class' => 'text-text-on-dark',
				'hat_class'     => 'bg-surface-white border-brand-yellow-border text-text-primary',
				'cards'         => array(
					1 => array(
						'icon'  => 'buildings',
						'title' => 'One Consultation, 19 Banks',
						'text'  => 'We do the calling. You get a side-by-side comparison, not 19 separate conversations.',
						'class' => 'lg:min-h-[368px]',
					),
					2 => array(
						'icon'  => 'chart-line-up',
						'title' => 'Business Owner? We Already Know Your Numbers',
						'text'  => 'If we handle your accounting or incorporation, your mortgage application is faster because your financials are already clean.',
						'class' => 'lg:min-h-[368px]',
					),
					3 => array(
						'icon'  => 'user-circle',
						'title' => 'A Person, Not a Portal',
						'text'  => 'Our mortgage team explains the trade-offs — not just the lowest headline rate.',
						'class' => 'lg:min-h-[368px]',
					),
					4 => array(
						'icon'  => 'bell-ringing',
						'title' => 'We Flag Refinancing Windows',
						'text'  => 'Once you’re a client, we tell you when your lock-in is ending — before your rate quietly resets higher.',
						'class' => 'lg:min-h-[368px]',
					),
				),
			),
			// "Fixed or Floating (SORA)?" (124:3372): two centred cards, each with its rate
			// between the title and the copy — the `value` slot that instance is the only
			// user of.
			'decision' => array(
				'hat'           => 'The Big Decision',
				'heading'       => 'Fixed or Floating (SORA)?',
				// The frame repeats the figures band's disclaimer under these two cards
				// (124:3604), which is where the rates it qualifies actually are.
				'note'          => 'Indicative rates, subjected to change from time to time — confirm up to date pricing during your consultation.',
				'align'         => 'center',
				'grid_class'    => 'lg:grid-cols-2',
				'section_class' => 'py-xl lg:py-[40px]',
				'cards'         => array(
					1 => array(
						'icon'  => 'lock-key',
						'title' => 'Fixed Rate',
						'value' => '~1.30% (2-Year)',
						'text'  => 'Payment stability for the lock-in period — no surprises if the market moves. Best if you value certainty or plan to hold the loan through the full lock-in.',
						'class' => 'lg:min-h-[383px]',
					),
					2 => array(
						'icon'  => 'trend-up',
						'title' => 'Floating (SORA-Pegged)',
						'value' => '~1.27%',
						'text'  => 'Tracks the market benchmark plus a bank spread. Can be cheaper when SORA is low or falling, but instalments move if rates rise. Suits borrowers comfortable with some variability.',
						'class' => 'lg:min-h-[383px]',
					),
				),
			),
		),
		// "One Conversation Instead of 19 Calls" (124:3537): the same two-column section
		// the incorporation and Business Loan frames draw, with four checks and a line
		// of small print in the card.
		'ways'   => array(
			'hat'           => 'How It Works',
			'heading'       => 'One Conversation Instead of 19 Calls',
			// Figma sets this heading in a 602px box rather than the 512px the other frames
			// use, which is what breaks it across two lines instead of four.
			'heading_class' => 'max-w-[602px]',
			'text'    => 'Tell us once what you need — purchase or refinance, property type, loan size — and we bring back real offers from across our banking panel.',
			'cards'   => array(
				1 => array(
					'icon'  => 'chart-line-up',
					'title' => 'Compare My Rate',
					'text'  => 'Free, no obligation',
				),
				2 => array(
					'icon'  => 'phone',
					'title' => 'Talk to Our Mortgage Team',
					'text'  => 'Direct advisory, not a call centre',
				),
			),
			'plan'    => array(
				'badge'       => 'Free to you',
				'title'       => 'Zero Cost, Same Great Rate',
				'text'        => 'Banks pay us a referral fee upon successful disbursement — your rate is identical to applying direct.',
				'price'       => '',
				'badge_1'     => '',
				'badge_2'     => '',
				'features'    => array(
					1 => '19 banks compared in one consultation',
					2 => 'Access to promotional rates not published online',
					3 => 'Same rate as applying directly — no premium',
					4 => 'Up to S$1,000 cash rebate on selected packages*',
				),
				'note'        => '*Terms and conditions apply. Ask us which packages qualify.',
				'button_text' => 'Compare My Rate',
				'button_link' => '/contact-us',
			),
		),
	);

	// The PSG Grant page, Figma frame 127:512 ("Desktop PSG Grant", file "Untitled",
	// vzdpOnH1U36oXcFcugiyE5). The sixth assembled frame in that file, so the section
	// order below is the canvas's own, read top to bottom:
	//
	//   hero (127:514 + the photo group 127:524/127:526 + the badge 127:527) →
	//   accreditations (127:895) → grid "benefits" (127:537) → grid "features"
	//   (127:574 + 127:934) → criteria "criteria" (127:580 + 127:618) →
	//   requirements (127:627) → criteria "invoicenow" (127:901 + 127:622) →
	//   grid "credentials" (127:821) → testimonials → faq (127:860) → cta (127:861).
	//
	// It adds exactly two parts, `ci-criteria.php` — the ticked list beside a
	// photograph, drawn twice — and `ci-accreditations.php`, the two marks between
	// rules under the hero. Everything else on the page is a section an earlier frame
	// already draws: the hero, `ci-grid` three times, the pinned rail, the reviews
	// slider, the FAQ and the closing CTA.
	//
	// Nine sections the other pages have are missing from this frame and so are simply
	// not called: the pricing cards, the free-tools tabs, the two routes, the
	// what's-included cards, the figures band, the calculator, the "why" bento, the
	// card carousel and the logo marquee — that last one because this page's two
	// credentials are the `accreditations` strip, not a marquee of client logos.
	//
	// Like the Property Cashout and Business Loan frames it writes its own six FAQ
	// questions rather than drawing the site-wide `faq_item` CPT, and it titles the
	// section one word short of the site-wide heading — hence `hat` and `heading`
	// beside the list here, which faq.php now falls back to.
	$psg = array(
		'hero'            => array(
			'hat'              => 'IMDA Pre-Approved Vendor · SMEs Go Digital',
			// The one headline in the family that is a paragraph rather than a display line:
			// Figma sets it at 40px in a 620px box (127:519), which is why this set names
			// `title_size_class` — flat at every breakpoint, since 40px is both the phone
			// size the shared ramp already uses and the desktop size this frame draws.
			'title'            => 'SMEs are eligible for up to 50% Productivity Solutions Grant (PSG) support for the adoption of Xero Cloud Accounting Software, a Pre-Approved Solution under the IMDA SMEs Go Digital programme.',
			'title_size_class' => 'text-[40px]',
			'text'             => 'As a vendor of pre-approved PSG Xero solutions, you are eligible for substantial cost savings through our offerings — with the setup, training, and ongoing support handled by our own Xero Certified Advisor team.',
			'button_text'      => 'Talk With Our Experts',
			'button_link'      => '/contact-us',
			'button_2_text'    => '+65 6012 9642',
			'button_2_link'    => 'tel:+6560129642',
			// Figma's whole image group: the badge (127:527) overhangs the photo card's
			// top-left corner, so the group is 626x528 where the card is 583x457 — the
			// constraint ci-hero.php documents. The export is the card composited under that
			// badge onto white, which is this page's own ground. The third layer Figma draws
			// (127:526, a cut-out of the same shot) is not carried over: it sits *behind* the
			// card and its own top 57px are empty, so the frame renders nothing of it.
			'image'            => 'psg/hero-image.jpg',
			'image_box'        => 'aspect-[626/528]',
			// 620 for the copy rather than the 703 the six older frames draw, because Figma
			// sets the headline in a 620px box and both brush strokes are measured against
			// the lines that box produces. 626 beside it is the image group, badge included.
			'body_class'       => 'lg:basis-[620px]',
			'image_col_class'  => 'lg:basis-[626px]',
			// The badge is composed into the export above, so the stock branch draws none;
			// `badge_file` is for the upload branch, which paints it back over a plain photo.
			'badge'            => '',
			'badge_class'      => 'absolute left-0 top-0 w-[20%] pointer-events-none select-none',
			'badge_file'       => 'psg/hero-badge.svg',
			'photo_slot_class' => 'absolute left-[6.87%] top-[13.45%] w-[93.13%] h-[86.55%] overflow-hidden rounded-2xl',
			// Two strokes, both measured against this headline at 40px in that 620px column:
			// a long one through "Xero Cloud Accounting" (127:515) and a short one under
			// "Software," on the line below (127:516). Their own vectors rather than the
			// shared stroke scaled, for the reason the Remittance set gives.
			'underline_file'    => 'psg/hero-underline.svg',
			'underline_class'   => 'hidden lg:block absolute left-[8.6%] top-[142px] w-[72.4%] rotate-[2.64deg] pointer-events-none select-none',
			'underline_2_file'  => 'psg/hero-underline-short.svg',
			'underline_2_class' => 'hidden lg:block absolute left-[26.8%] top-[181px] w-[27.3%] -scale-y-100 rotate-[172.03deg] pointer-events-none select-none',
		),
		// The two marks under the hero (127:895). Theme files and not client fields —
		// see the note in template-parts/ci-accreditations.php.
		'accreditations'  => array(
			'logos' => array(
				array(
					'file'  => 'psg/logo-xero-advisor.png',
					'name'  => 'Xero Certified Advisor',
					'class' => 'h-[40px] lg:h-[56px]',
				),
				array(
					'file'  => 'psg/logo-imda-psg.png',
					'name'  => 'IMDA Pre-Approved Solution — eligible for up to 50% Productivity Solutions Grant (PSG) support',
					'class' => 'h-[40px] lg:h-[56px]',
				),
			),
		),
		'grid'            => array(
			// "How the PSG Grant Benefits Your Business" (127:537): the shared centred grid
			// on a navy panel, with no card behind each column — hence `card_class` /
			// `card_pad_class`, and the smaller disc, title and white copy the panel needs.
			'benefits'    => array(
				'hat'                => '',
				'heading'            => 'How the PSG Grant Benefits Your Business',
				'align'              => 'center',
				'grid_class'         => 'lg:grid-cols-4',
				'section_class'      => 'py-xl lg:py-[40px]',
				'panel_class'        => 'bg-surface-dark rounded-[40px] lg:rounded-[56px] px-lg lg:px-3xl py-2xl lg:py-3xl',
				'card_class'         => 'bg-transparent',
				'card_pad_class'     => 'p-0',
				'card_justify_class' => 'justify-start',
				'heading_class'      => 'text-text-on-dark',
				'heading_size_class' => 'text-2xl lg:text-[48px]',
				'header_class'       => 'max-w-[552px]',
				'disc_class'         => 'size-[56px]',
				'icon_class'         => 'size-[32px]',
				'title_class'        => 'text-lg lg:text-[28px] text-text-on-dark',
				'text_class'         => 'text-text-on-dark',
				'cards'              => array(
					1 => array(
						'icon'  => 'trend-down',
						'title' => 'Lower Upfront Cost',
						'text'  => 'Up to 50% of qualifying costs subsidised, lowering the financial barrier to adopting Xero.',
						'class' => '',
					),
					2 => array(
						'icon'  => 'gauge',
						'title' => 'Enhanced Efficiency',
						'text'  => 'Cloud accounting streamlines day-to-day operations, freeing up time spent on manual bookkeeping.',
						'class' => '',
					),
					3 => array(
						'icon'  => 'lightbulb',
						'title' => 'Fosters Innovation',
						'text'  => 'Modern tools free your team to focus on growth and competitiveness, not paperwork.',
						'class' => '',
					),
					4 => array(
						'icon'  => 'plant',
						'title' => 'Long-Term Sustainability',
						'text'  => 'Better processes and cost savings compound over time, supporting sustainable growth.',
						'class' => '',
					),
				),
			),
			// "Everything Included With Xero" (127:574 + 127:934): eight centred cards over
			// two rows of four, titles a step down from the shared 24px.
			'features'    => array(
				'hat'            => 'Cloud-Based Xero Accounting',
				'heading'        => 'Everything Included With Xero',
				'text'           => 'Manage your accounting software from anywhere — here’s what’s covered.',
				'align'          => 'center',
				'grid_class'     => 'lg:grid-cols-4',
				'section_class'  => 'py-xl lg:py-[40px]',
				'card_pad_class' => 'rounded-lg px-md py-xl',
				'card_justify_class' => 'justify-start',
				'title_class'    => 'text-lg text-text-heading-dark',
				'cards'          => array(
					1 => array(
						'icon'  => 'folder-open',
						'title' => 'E-Invoicing',
						'text'  => 'Send, receive, and automate invoices directly within the platform for faster payments.',
						'class' => 'lg:min-h-[331px]',
					),
					2 => array(
						'icon'  => 'archive',
						'title' => 'Inventory',
						'text'  => 'Track stock levels, set reorder points, and monitor product profitability.',
						'class' => 'lg:min-h-[331px]',
					),
					3 => array(
						'icon'  => 'file-text',
						'title' => 'Pay Bills & Expenses',
						'text'  => 'Manage supplier invoices, set up recurring bills, and schedule payments.',
						'class' => 'lg:min-h-[331px]',
					),
					4 => array(
						'icon'  => 'shield-check',
						'title' => 'Security',
						'text'  => 'Multi-factor authentication, encryption, and regular backups keep your data safe.',
						'class' => 'lg:min-h-[331px]',
					),
					5 => array(
						'icon'  => 'buildings',
						'title' => 'Bank Reconciliation',
						'text'  => 'Connect bank accounts and automatically match transactions to your records.',
						'class' => 'lg:min-h-[331px]',
					),
					6 => array(
						'icon'  => 'user-check',
						'title' => 'Unlimited Users',
						'text'  => 'Remote access for unlimited users — collaborate with your team in real time.',
						'class' => 'lg:min-h-[331px]',
					),
					7 => array(
						'icon'  => 'chart-pie-slice',
						'title' => 'Multi-Currency',
						'text'  => 'Automatically calculate gains and losses, invoice, and reconcile in multiple currencies.',
						'class' => 'lg:min-h-[331px]',
					),
					8 => array(
						'icon'  => 'megaphone',
						'title' => 'Invoice Reminders',
						'text'  => 'Automatic nudges for overdue invoices, helping maintain healthy cash flow.',
						'class' => 'lg:min-h-[331px]',
					),
				),
			),
			// "Go Digital With a Partner Who's Actually Credentialed" (127:821): the same
			// centred grid on the pale panel with white cards the Remittance frame draws.
			'credentials' => array(
				'hat'           => 'Complete Corporate Services',
				'heading'       => 'Go Digital With a Partner Who’s Actually Credentialed',
				'text'          => 'We’re dedicated to helping SMEs transform their accounting processes — backed by real, verifiable credentials, not just a claim.',
				'align'         => 'center',
				'grid_class'    => 'lg:grid-cols-4',
				'section_class' => 'py-xl lg:py-[40px]',
				'panel_class'   => 'bg-surface-panel rounded-[40px] lg:rounded-[56px] px-lg lg:px-[56px] py-xl lg:py-[112px]',
				'card_class'    => 'bg-surface-white',
				'card_justify_class' => 'justify-start',
				'header_class'  => 'max-w-[880px]',
				'cards'         => array(
					1 => array(
						'icon'  => 'star',
						'title' => 'Xero Certified Advisor',
						'text'  => 'Setup, migration, and ongoing support handled by a team that’s actually certified on the platform.',
						'class' => 'lg:min-h-[372px]',
					),
					2 => array(
						'icon'  => 'shield-check',
						'title' => 'ACRA Registered Filing Agent',
						'text'  => 'The same firm that can also handle your incorporation, secretary, and compliance filings.',
						'class' => 'lg:min-h-[372px]',
					),
					3 => array(
						'icon'  => 'star',
						'title' => 'IMDA Pre-Approved PSG Grant Vendor',
						// Figma's own spelling of "Officially". Shipped verbatim, the same call the
						// Foreign and GST frames' leftovers got — a typo in body copy is the client's
						// to correct, and the field is right there in wp-admin.
						'text'  => 'Officialy listed with IMDA and independently verifiable — not just a badge on a page.',
						'class' => 'lg:min-h-[372px]',
					),
					4 => array(
						'icon'  => 'folder-simple-star',
						'title' => 'We Handle the Paperwork',
						'text'  => 'From eligibility check to Business Grants Portal submission — we guide the process end to end.',
						'class' => 'lg:min-h-[372px]',
					),
				),
			),
		),
		// The two ticked-list frames (127:580 + 127:618 and 127:901 + 127:622), one part
		// called twice — see template-parts/ci-criteria.php.
		'criteria'        => array(
			'criteria'   => array(
				'hat'        => 'Grant Criteria & Process',
				'heading'    => 'Am I Eligible, and How Do I Apply?',
				'list_title' => 'PSG Grant Criteria',
				'photo'      => 'right',
				'image'      => 'psg/criteria-photo.jpg',
				'image_box'  => 'aspect-[547/590]',
				'items'      => array(
					1 => 'Registered and operating in Singapore',
					2 => 'At least 30% local shareholding (including new start-ups)',
					3 => 'Solution used in Singapore, relevant to your industry',
					4 => 'Solution must be a government-approved offering',
					5 => 'Grants cover up to 50% of qualifying costs',
					6 => '',
					7 => '',
				),
			),
			'invoicenow' => array(
				'hat'       => 'Grant Criteria & Process',
				'heading'   => 'InvoiceNow',
				'text'      => 'Send invoices digitally between the accounting systems of suppliers and buyers. Invoices are transmitted automatically through a secure network, eliminating manual entry.',
				'photo'     => 'left',
				'image'     => 'psg/invoicenow-photo.jpg',
				'image_box' => 'aspect-[547/678]',
				'items'     => array(
					1 => 'Time-saving — no manual re-entry',
					2 => 'Faster payments end to end',
					3 => 'Convenient, secure network transmission',
					4 => '',
					5 => '',
					6 => '',
					7 => '',
				),
				// The authorities behind the claim (127:928 + 127:933), not client uploads.
				'logos'     => array(
					array(
						'file'  => 'psg/logo-imda-invoicenow.png',
						'name'  => 'IMDA SMEs Go Digital and InvoiceNow',
						'class' => 'h-[40px] lg:h-[49px]',
					),
					array(
						'file'  => 'psg/logo-iras.png',
						'name'  => 'Inland Revenue Authority of Singapore',
						'class' => 'h-[56px] lg:h-[72px]',
					),
				),
			),
		),
		// "How to Apply" (127:627) — the pinned progress rail, four steps. This frame is
		// the one that writes its whole step into the *title*: Figma draws no second line
		// under any of them and no timing label, so `text` and `meta` stay empty and
		// ci-requirements.php renders neither. The scattered-blocks artwork behind the
		// panel is the Property Cashout page's, the same export, so it is reused rather
		// than shipped twice.
		'requirements'    => array(
			'hat'        => 'Process',
			'heading'    => 'How to Apply',
			'background' => 'pc/panel-bg.svg',
			'steps'      => array(
				1 => array(
					'icon'  => 'users-three',
					'title' => 'Assess your business needs and identify eligible solutions that fit your objectives.',
					'text'  => '',
				),
				2 => array(
					'icon'  => 'compass',
					'title' => 'Review funding options and guidelines on the Business Grants Portal.',
					'text'  => '',
				),
				3 => array(
					'icon'  => 'folder-open',
					'title' => 'Prepare your business registration details and vendor quotes.',
					'text'  => '',
				),
				4 => array(
					'icon'  => 'shield-check',
					'title' => 'Submit your application, then purchase and claim once approved — typically 2–4 weeks.',
					'text'  => '',
				),
			),
		),
		// The reviews slider is the shared one; this frame only retitles it (127:859),
		// one word off the site-wide heading.
		'testimonials'    => array(
			'hat'     => 'Google Reviews',
			'heading' => 'See Why Founders Recommend Think SME',
		),
		// The six questions this frame writes (127:860). Only the first has an answer on
		// the canvas; the rest open to just the question until the client fills them in,
		// the same way the Property Cashout and Business Loan sets do.
		'faq'             => array(
			'hat'     => 'Common Questions',
			'heading' => 'Questions Business Owners Ask Us First',
			'items'   => array(
				1 => array(
					'question' => 'What is Xero accounting software?',
					'answer'   => 'Xero is a cloud-based accounting software that allows you to manage your finances, invoices, and transactions from anywhere, anytime.',
				),
				2 => array(
					'question' => 'What is the PSG Grant?',
					'answer'   => '',
				),
				3 => array(
					'question' => 'How does the PSG Grant benefit my business?',
					'answer'   => '',
				),
				4 => array(
					'question' => 'How can I apply for the PSG Grant?',
					'answer'   => '',
				),
				5 => array(
					'question' => 'Is my business eligible for the PSG Grant?',
					'answer'   => '',
				),
				6 => array(
					'question' => 'How much funding support can I receive?',
					'answer'   => '',
				),
			),
		),
		'cta'             => array(
			'title' => 'Let’s work together',
			'text'  => 'Our team of experienced consultants is on hand to discuss your goals and how PSG-supported Xero can help.',
			'image' => 'psg/cta-photo.jpg',
		),
	);

	// MRA Grant (Figma frame 130:1489, "Desktop MRA Grant", file "Untitled").
	// The twelfth page in this family and the second grant page. Nine sections, all
	// but one of them a component an earlier page already draws: the hero, the pinned
	// eligibility rail, the accreditation strip, one card grid, the ticked-list frame
	// twice, the reviews slider and the closing CTA. The one new part is
	// `statement` — the centred sentence with the ring around "70%" — which is
	// template-parts/ci-statement.php and inert everywhere else.
	//
	// This frame writes no FAQ and no logo marquee, so this set names neither and the
	// template calls neither. It also writes nothing over the reviews slider or the
	// closing CTA beyond a photograph, so `testimonials` is absent and `cta` names
	// only its image — the shared strings in cta.php are already this frame's words.
	$mra = array(
		'hero'           => array(
			'hat'               => 'Market Readiness Assistance Grant',
			'title'             => 'Secure a government supported Grant to reach new international markets',
			// 64px rather than the 72px five of the frames draw, the same step the GST
			// frame takes — so only the desktop step is named, not the whole ramp.
			'title_class'       => 'lg:text-[64px]',
			'text'              => 'MRA (Market Readiness Assistance Grant) MRA Grant is a grant by Enterprise Singapore for established local SMEs to help them to improve their market knowledge, strengthen their capabilities and expand their networks, as well as to connect them to partners and opportunities overseas.',
			'button_text'       => 'Check My Eligibility',
			'button_link'       => '/contact-us',
			'button_2_text'     => '+65 6012 9642',
			'button_2_link'     => 'tel:+6560129642',
			// Figma's whole image group: the lightbulb badge (130:1502) overhangs the photo
			// card's left edge and the cut-out of the same shot breaks above its top one, so
			// the group is 618x529 where the card is 583x457 — the constraint ci-hero.php
			// documents. The export is all three layers flattened onto white, which is this
			// page's own ground.
			'image'             => 'mra/hero-image.jpg',
			'image_box'         => 'aspect-[618/529]',
			// 656 for the copy (130:1491) rather than the 703 the six older frames draw, and
			// 618 beside it is the image group, badge included.
			'body_class'        => 'lg:basis-[656px]',
			'image_col_class'   => 'lg:basis-[618px]',
			// The badge is composed into the export above, so the stock branch draws none;
			// `badge_file` is for the upload branch, which paints it back over a plain photo.
			'badge'             => '',
			'badge_class'       => 'absolute left-0 top-[3.1%] w-[20.2%] pointer-events-none select-none',
			'badge_file'        => 'mra/hero-badge.svg',
			'photo_slot_class'  => 'absolute left-[5.66%] top-[13.5%] w-[94.34%] h-[86.5%] overflow-hidden rounded-2xl',
			// This is the one hero in the family whose headline carries no brush stroke —
			// Figma draws none over 130:1494 — so the class is `hidden` rather than empty:
			// ci-hero.php always emits the <img> and an unclassed one lands in the flow at
			// its natural size.
			'underline_class'   => 'hidden',
		),
		// The centred sentence with the hand-drawn ring around "70%" (130:1512 + 130:1513)
		// and its button (130:1514) — template-parts/ci-statement.php, and this is the
		// only set that names it.
		'statement'      => array(
			'text'        => 'To help local SMEs get a foothold in overseas markets, the Market Readiness Grant was set up to provide funds to take the first steps. The MRA will cover up to 70% of eligible costs (capped at S$100,000) for each company in each new market.',
			'button_text' => 'View full list of supportable activities',
			'button_link' => '/contact-us',
			// Measured against this sentence breaking into five lines at 40px in Figma's
			// 1039px box, the same way every brush stroke in this theme is placed.
			'ring_file'   => 'mra/statement-circle.svg',
			'ring_class'  => 'hidden lg:block absolute left-[31.5%] top-[134px] w-[12%] pointer-events-none select-none',
		),
		// The three marks between rules under the statement (130:1737). Theme files and
		// not client fields — see the note in template-parts/ci-accreditations.php. This
		// frame draws three where the PSG one draws two.
		'accreditations' => array(
			'logos' => array(
				array(
					'file'  => 'mra/logo-sg-digital.png',
					'name'  => 'SG:Digital',
					'class' => 'h-[32px] lg:h-[48px]',
				),
				array(
					'file'  => 'mra/logo-imda.png',
					'name'  => 'Infocomm Media Development Authority',
					'class' => 'h-[40px] lg:h-[56px]',
				),
				array(
					'file'  => 'mra/logo-enterprise-sg.png',
					'name'  => 'Enterprise Singapore',
					'class' => 'h-[40px] lg:h-[56px]',
				),
			),
		),
		// The two ticked-list frames (130:1520 + 130:1515 and 130:1798 + 130:1793), the
		// same part the PSG page calls twice — see template-parts/ci-criteria.php. Both
		// put the photograph on the left here, and both close on a button, which is what
		// that part's `button_text` / `button_link` were added for.
		'criteria'       => array(
			'covers' => array(
				'hat'         => '',
				'heading'     => 'What covers in MRA Grant in Singapore?',
				'photo'       => 'left',
				'image'       => 'mra/covers-photo.jpg',
				'image_box'   => 'aspect-[547/606]',
				'button_text' => 'Let’s Work Together',
				'button_link' => '/contact-us',
				'items'       => array(
					1 => 'Overseas market promotion (capped at S$20,000)',
					2 => 'Overseas business development (capped at S$50,000)',
					3 => 'Overseas market set-up (capped at S$30,000)',
					4 => '',
					5 => '',
					6 => '',
					7 => '',
				),
			),
			'expect' => array(
				'hat'         => '',
				'heading'     => 'What You Can Expect',
				'text'        => 'The right funding and the right solution can escalate you to your maximum potential.',
				'photo'       => 'left',
				'image'       => 'mra/expect-photo.jpg',
				'image_box'   => 'aspect-[547/561]',
				'button_text' => 'Let’s Work Together',
				'button_link' => '/contact-us',
				'items'       => array(
					// Figma opens this line on a zero-width space (130:1808); it is dropped
					// rather than shipped, since it is an editing artefact and not a character
					// anyone can see or delete from wp-admin.
					1 => 'A team of experts to accompany you in the development of the right strategies for you business',
					2 => 'Constant search for ways of improving your process',
					3 => 'Guidance on grant management',
					4 => 'Full application and project development step by step',
					5 => '',
					6 => '',
					7 => '',
				),
			),
		),
		// "Who can apply For MRA Grant in Singapore?" (130:1694 + 130:1698) — the pinned
		// progress rail, four steps. Like the PSG rail this frame writes the whole
		// criterion into the *title*: no second line under any of them and no timing
		// label, so `text` and `meta` stay empty and ci-requirements.php renders neither.
		// The scattered-blocks artwork behind the panel is the Property Cashout export,
		// the same one the PSG rail reuses. Figma draws the rail 852px wide inside its
		// 1392px panel rather than the 588/718 the other rails take.
		'requirements'   => array(
			'hat'        => 'Process',
			'heading'    => 'Who can apply For MRA Grant in Singapore?',
			'background' => 'pc/panel-bg.svg',
			'rail_class' => 'max-w-[852px]',
			'steps'      => array(
				1 => array(
					'icon'  => 'buildings',
					'title' => 'Business entity is registered/incorporated in Singapore',
					'text'  => '',
				),
				2 => array(
					'icon'  => 'compass',
					'title' => 'New market entry criteria, i.e. target overseas country whereby the applicant has not exceeded S$100,000 in overseas sales in each of the last three preceding years',
					'text'  => '',
				),
				3 => array(
					'icon'  => 'chart-pie-slice',
					'title' => 'At least 30% local shareholding',
					'text'  => '',
				),
				4 => array(
					'icon'  => 'chart-bar',
					'title' => 'Group Annual Sales Turnover of not more than S$100 million; or Company’s Group Employment Size of not more than 200 employees',
					'text'  => '',
				),
			),
		),
		'grid'           => array(
			// "Our solutions" (130:1744): the shared centred grid on the pale panel with
			// white cards, the same shape the Remittance and PSG frames draw. Four cards,
			// 20px titles, and no hat and no intro line — this frame writes a heading and
			// nothing else above the row.
			'solutions' => array(
				'hat'                => '',
				'heading'            => 'Our solutions',
				'align'              => 'center',
				'grid_class'         => 'lg:grid-cols-4',
				'section_class'      => 'py-xl lg:py-[40px]',
				'panel_class'        => 'bg-surface-panel rounded-[40px] lg:rounded-[80px] px-lg lg:px-[56px] py-2xl lg:py-3xl',
				'card_class'         => 'bg-surface-white',
				'card_pad_class'     => 'rounded-lg px-md py-xl',
				'card_justify_class' => 'justify-start',
				'title_class'        => 'text-lg text-text-heading-dark',
				'header_class'       => 'max-w-[880px]',
				'cards'              => array(
					1 => array(
						'icon'  => 'scales',
						'title' => 'Advisory, Legal and Documentation Services',
						'text'  => 'We provide a customized plan, expert guidance, and market intelligence to help you confidently expand internationally, handling legal processes from IP to licensing and joint ventures.',
						'class' => 'lg:min-h-[455px]',
					),
					2 => array(
						'icon'  => 'handshake',
						'title' => 'Business Matching',
						'text'  => 'We identify and connect you with the right overseas partners, from licensees and distributors to joint venture partners, and organize face-to-face meetings for efficient, high-value collaboration.',
						'class' => 'lg:min-h-[455px]',
					),
					3 => array(
						'icon'  => 'megaphone',
						'title' => 'Overseas Marketing & PR',
						'text'  => 'We build brand awareness for your product overseas through in-store promotions, road shows, pop-up stores, and pitching, shaping the first impression that drives purchase decisions.',
						'class' => 'lg:min-h-[455px]',
					),
					4 => array(
						'icon'  => 'storefront',
						'title' => 'Overseas Trade Fairs',
						'text'  => 'We help your brand stand out at foreign trade fairs, from research and show selection to design, construction, and virtual trade fair execution, connecting you with competitors, customers, and partners while building export market visibility.',
						'class' => 'lg:min-h-[455px]',
					),
				),
			),
		),
		// This frame closes on the shared CTA verbatim — "Need help?" over the line
		// cta.php already carries — so the set names only its photograph.
		'cta'            => array(
			'image' => 'mra/cta-photo.jpg',
		),
	);

	// The About Us page, Figma frame 157:280 ("Desktop About"). Not one of the
	// eleven service pages above — it shares this file only because it reuses
	// `ci-hero`, `ci-grid` (three instances) and `ci-stack` (one instance) the
	// same way they do, so its design fallbacks belong in the same one place
	// rather than scattered across its own template parts.
	$about = array(
		'hero'    => array(
			'hat'              => 'Partner for Business Success',
			'title'            => 'Helping Entrepreneurs Start, Run and Grow Their Businesses With Confidence',
			'text'             => 'At Think SME, we believe every entrepreneur deserves more than just a service provider. We provide the guidance, expertise and business solutions that help founders build successful businesses with confidence.',
			'button_text'      => "Let's Build Your Business Together",
			'button_link'      => '/contact-us',
			'button_2_text'    => '+65 6012 9642',
			'button_2_link'    => 'tel:+6560129642',
			// Figma's whole image group (157:300 + the badge 157:304), 619x534: the
			// badge overhangs the photo card's top-left corner, so the group is bigger
			// than the card. The export carries card, corners and badge already
			// composed on transparency, so the stock branch draws no badge over it —
			// and `upload_composed` says the field's own content is that same export,
			// which is what stops the badge being painted on twice.
			'image'            => 'about/hero-image.png',
			'image_box'        => 'aspect-[619/534]',
			'badge'            => '',
			'upload_composed'  => true,
			// Unused while `upload_composed` stands, but kept correct against the group
			// box above so the bare-photo branch still has Figma's numbers if a set
			// ever wants it: badge at 0,0 of the group, card inset into it.
			'badge_class'      => 'absolute left-0 top-0 w-[20.6%] h-[23.46%] pointer-events-none select-none',
			'photo_slot_class' => 'absolute left-[4.19%] top-[8.14%] w-[95.81%] h-[91.86%] overflow-hidden rounded-2xl',
			// This headline breaks across four lines at 64px rather than the family's
			// usual 72px, so the desktop step is a per-page default.
			'title_class'      => 'lg:text-[64px]',
			'underline_class'  => 'hidden lg:block absolute left-[-0.3%] top-[122px] w-[92.6%] rotate-[1.83deg] pointer-events-none select-none',
		),
		'story'   => array(
			'icon'    => 'about/story-icon.svg',
			'title'   => 'What "Partner for Business Success" actually means:',
			'items'   => array(
				"We're there from incorporation, not just at the start",
				'We stay through compliance, accounting, and financing',
				'We support expansion into new markets and technology',
				'One partner, every stage — not a patchwork of vendors',
			),
			'hat'     => 'Our Story',
			'heading' => 'Every successful business starts with a leap of faith.',
			// Paragraph 3 is the one Figma sets in bold (157:512) — a position in the
			// design, not a client choice, the same convention every other bold-by-slot
			// treatment in this theme follows.
			'text_1'  => 'Leaving a stable job, investing your savings and building something from scratch is exciting — but can also be overwhelming.',
			'text_2'  => 'Too often, entrepreneurs spend more time worrying about incorporation, compliance, accounting and financing than growing their business.',
			'text_3'  => "That's why Think SME was founded.",
			'text_4'  => 'We set out to become more than another corporate service provider. Our goal is to become the long-term business partner entrepreneurs can rely on — from starting a company to securing financing, adopting technology and expanding into new markets.',
		),
		'grid'    => array(
			'drive'   => array(
				'hat'           => '',
				'heading'       => 'What Drive Us',
				'align'         => 'center',
				'panel_class'   => 'bg-surface-dark rounded-[40px] lg:rounded-[48px] px-lg lg:px-[56px] py-xl lg:py-[64px]',
				'heading_class' => 'text-text-on-dark',
				'section_class' => 'py-xl lg:py-[40px]',
				// Three equal cards across the panel (157:517 draws them flex-1 in a
				// 1280px row), which six columns cannot divide — the same reason the
				// Corporate Tax frame's four equal cards name their own track.
				'grid_class'    => 'lg:grid-cols-3',
				// Figma pads these 24px horizontally against 32px vertically (157:518),
				// where every earlier instance is square at 32px, and sets 32px between
				// the disc, the title and the copy where they draw 16px.
				'card_pad_class' => 'rounded-lg px-lg py-xl',
				'card_gap_class' => 'gap-xl',
				// The Values card runs longer than Vision's or Mission's (157:538's three
				// bullets against a one-line paragraph each), and Figma keeps every card
				// top-aligned rather than centering the shorter two — the same read the
				// PSG Grant frame's own `justify-start` override documents.
				'card_justify_class' => 'justify-start',
				'cards'         => array(
					1 => array(
						'class' => '',
						'icon'  => 'eye',
						'title' => 'Vision',
						'text'  => "To become Singapore's most trusted business growth partner, empowering entrepreneurs and SMEs to build sustainable, successful businesses.",
					),
					2 => array(
						'class' => '',
						'icon'  => 'lightning',
						'title' => 'Mission',
						'text'  => 'We simplify the business journey by delivering expert guidance, integrated solutions and meaningful partnerships that help entrepreneurs turn ideas into thriving businesses.',
					),
					// Figma writes this card as three bold-led bullets rather than a plain
					// paragraph (157:538) — `items` renders that list; `text` stays the
					// fallback for a client who clears all three back to a single line.
					3 => array(
						'class' => '',
						'icon'  => 'star',
						'title' => 'Values',
						'text'  => '',
						'items' => array(
							array(
								'bold' => 'Client Success Comes First',
								'text' => " — our clients' success is the measure of our own; we always act in their best interests and are committed to helping them achieve sustainable business growth.",
							),
							array(
								'bold' => 'We Guide With Expertise —',
								'text' => ' we do more than deliver services, providing trusted advice, practical solutions, and continuous guidance that empower entrepreneurs to make confident decisions.',
							),
							array(
								'bold' => 'Grow Together —',
								'text' => ' lasting partnerships create lasting businesses; we continuously improve ourselves while creating new opportunities for our clients, partners, and team.',
							),
						),
					),
				),
			),
			'credentials' => array(
				'hat'       => 'Why Businesses Trust Think SME',
				'heading'   => 'Real Credentials, Not Just Claims',
				'align'     => 'center',
				'text'      => "We're dedicated to helping SMEs transform their accounting processes — backed by real, verifiable credentials, not just a claim.",
				'grid_class' => 'lg:grid-cols-4',
				'section_class' => 'py-xl lg:py-[40px]',
				'cards'     => array(
					1 => array(
						'class' => '',
						'icon'  => 'calculator',
						'title' => 'ACRA Registered Filing Agent',
						'text'  => 'Authorised to file incorporation, corporate secretary, and compliance documents directly with ACRA.',
					),
					2 => array(
						'class' => '',
						'icon'  => 'file-text',
						'title' => 'IRAS Tax Filing Agent',
						'text'  => 'Authorised to prepare and submit corporate tax and GST filings directly with IRAS.',
					),
					3 => array(
						'class' => '',
						'icon'  => 'seal-check',
						'title' => 'Xero Certified Advisor',
						'text'  => 'Setup, migration, and ongoing support from a team actually certified on the platform.',
					),
					4 => array(
						'class' => '',
						'icon'  => 'percent',
						'title' => 'IMDA Pre-Approved PSG Vendor',
						'text'  => 'Our Xero solutions qualify clients for Productivity Solutions Grant support.',
					),
				),
			),
			'explore' => array(
				'hat'               => "Wherever You're Starting From",
				'hat_class'         => 'bg-white border-brand-yellow-border text-text-navy',
				'heading'           => 'Explore How We Can Help',
				'heading_size_class' => 'text-2xl lg:text-[52px]',
				'heading_class'     => 'text-text-on-dark',
				'align'             => 'left',
				'text'              => 'ThinkSME guides you through every step — from free consultation to cash in your account.',
				'panel_class'       => 'bg-surface-dark rounded-[40px] lg:rounded-[48px] px-lg lg:px-[56px] py-xl lg:py-[64px]',
				'card_class'        => 'bg-white border border-[rgba(19,47,83,0.08)]',
				'card_pad_class'    => 'rounded-[28px] p-[28px]',
				'grid_class'        => 'lg:grid-cols-4',
				'section_class'     => 'py-xl lg:py-[40px]',
				'cards'             => array(
					1 => array(
						'class' => '',
						'icon'        => 'shield-check',
						'title'       => 'Incorporate Your Company',
						'text'        => 'Register your Singapore company from S$888, ACRA-filed in 1–3 days.',
						'button_text' => 'Learn More',
						'button_link' => '/company-incorporation-local',
					),
					2 => array(
						'class' => '',
						'icon'        => 'hand-coins',
						'title'       => 'Get a Business Loan',
						'text'        => 'Compare 60+ banks and lenders with one application — free, ex-banker team.',
						'button_text' => 'Learn More',
						'button_link' => '/business-loan',
					),
					3 => array(
						'class' => '',
						'icon'        => 'house-line',
						'title'       => 'Unlock Property Equity',
						'text'        => 'Cash out equity from your property at mortgage-level rates, without selling it.',
						'button_text' => 'Learn More',
						'button_link' => '/property-cashout',
					),
					4 => array(
						'class' => '',
						'icon'        => 'seal-check',
						'title'       => 'Claim the PSG Grant',
						'text'        => 'Up to 50% PSG support for Xero cloud accounting — IMDA pre-approved.',
						'button_text' => 'Learn More',
						'button_link' => '/psg-grant',
					),
				),
			),
		),
		'promise' => array(
			'hat'         => 'Our Brand Promise',
			'heading'     => 'From First Step to Every Milestone.',
			'text'        => 'Because every entrepreneur deserves a partner who grows with them.',
			'button_text' => "Let's Build Your Business Together",
			'button_link' => '/contact-us',
		),
		'stack'   => array(
			'serve' => array(
				'hat'     => 'Who We Serve',
				'heading' => 'Built for Every Stage of the Journey',
				'photo'   => 'right',
				'image'   => 'about/serve-photo.jpg',
				'image_box' => 'aspect-[547/415]',
				'cards'   => array(
					1 => array(
						'icon'  => 'user-focus',
						'title' => 'First-Time Founders',
						'text'  => 'Taking the leap for the first time and need a partner who explains the "why," not just the "what."',
					),
					2 => array(
						'icon'  => 'chart-line-up',
						'title' => 'Growing SMEs',
						'text'  => 'Need reliable accounting, financing and strategic guidance to scale with confidence.',
					),
					3 => array(
						'icon'  => 'briefcase',
						'title' => 'Foreign Entrepreneurs',
						'text'  => 'Setting up in Singapore remotely and need a local partner who can act as their eyes on the ground.',
					),
					4 => array(
						'icon'  => 'clock-countdown',
						'title' => 'SMEs Seeking Financing',
						'text'  => 'Looking for business loans or property cashout through our 60+ lender network.',
					),
					5 => array(
						'icon'  => 'bank',
						'title' => 'Grant Applicants',
						'text'  => 'Applying for PSG or other government grants and need an IMDA pre-approved vendor.',
					),
					// The last card is styled navy and tilted by position — see the note
					// in template-parts/ci-stack.php.
					6 => array(
						'icon'  => 'hand-coins',
						'title' => 'Switching Providers',
						'text'  => 'Unhappy with their current accountant or corporate secretary and ready for a single accountable partner.',
					),
				),
			),
		),
		'mission' => array(
			'hat'     => 'Why Think SME Exists',
			'heading' => "Most business owners don't fail because they lack passion.",
			'text_1'  => 'They struggle because they have to navigate incorporation, compliance, accounting, financing, grants and technology — all while trying to grow their business.',
			'text_2'  => 'We believe entrepreneurs should spend their time building their business, not figuring out paperwork.',
			// Figma sets this closing line in bold (157:846) — a position, like the
			// Our Story paragraph above.
			'text_3'  => "That's why we bring everything together under one trusted partner.",
			'image'   => 'about/mission-photo.jpg',
		),
		// This frame closes on the shared CTA verbatim — "Need help?" over its own
		// line rather than cta.php's generic one — so the set names no photograph.
		'cta'     => array(
			'text' => 'From first step to every milestone — talk to us about what you\'re building.',
		),
	);

	return array(
		'local'      => $local,
		'foreign'    => $foreign,
		'secretary'  => $secretary,
		'accounting' => $accounting,
		'tax'        => $tax,
		'gst'        => $gst,
		'cashout'    => $cashout,
		'loan'       => $loan,
		'remittance' => $remittance,
		'mortgage'   => $mortgage,
		'psg'        => $psg,
		'mra'        => $mra,
		'about'      => $about,
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

/**
 * Answer the shared CTA's and reviews slider's copy from the current page's set.
 *
 * Same arrangement as thinksme_ci_cta_photo() above and for the same reason:
 * `template-parts/cta.php` and `template-parts/testimonials.php` are shared by
 * every page and have no business knowing which one they are on, so each exposes
 * its fallback string as a filter. Only the PSG Grant frame writes its own — it
 * closes on "Let's work together" (127:865) and titles its reviews "See Why
 * Founders Recommend Think SME" (127:859) — so every other set names none of these
 * keys and the incoming string passes through unchanged. A value typed into
 * `cta_title` / `testimonials_heading` in wp-admin still wins over all of it.
 *
 * @param string $value Default string from the shared part.
 * @return string
 */
function thinksme_ci_cta_title( $value ) {
	$cta = thinksme_ci_defaults( 'cta' );

	return empty( $cta['title'] ) ? $value : $cta['title'];
}
add_filter( 'thinksme_cta_title', 'thinksme_ci_cta_title' );

/**
 * @param string $value Default string from the shared part.
 * @return string
 */
function thinksme_ci_cta_text( $value ) {
	$cta = thinksme_ci_defaults( 'cta' );

	return empty( $cta['text'] ) ? $value : $cta['text'];
}
add_filter( 'thinksme_cta_text', 'thinksme_ci_cta_text' );

/**
 * @param string $value Default string from the shared part.
 * @return string
 */
function thinksme_ci_testimonials_hat( $value ) {
	$d = thinksme_ci_defaults( 'testimonials' );

	return empty( $d['hat'] ) ? $value : $d['hat'];
}
add_filter( 'thinksme_testimonials_hat', 'thinksme_ci_testimonials_hat' );

/**
 * @param string $value Default string from the shared part.
 * @return string
 */
function thinksme_ci_testimonials_heading( $value ) {
	$d = thinksme_ci_defaults( 'testimonials' );

	return empty( $d['heading'] ) ? $value : $d['heading'];
}
add_filter( 'thinksme_testimonials_heading', 'thinksme_ci_testimonials_heading' );
