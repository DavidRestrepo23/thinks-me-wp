<?php
/**
 * Template Name: Privacy Policy
 *
 * Privacy Policy page, Figma frame 130:2151 ("Desktop Privacy Policy", file
 * "Untitled", vzdpOnH1U36oXcFcugiyE5). Section order, read straight off the
 * canvas: `legal-hero` (130:2153) → `legal-toc` (130:2165, the sticky
 * numbered sidebar beside the fifteen numbered sections). Header and footer are
 * get_header()/get_footer() verbatim — this frame's header (101:283) and footer
 * (101:729) are byte-for-byte the same nodes those two files already render for
 * every other page, so nothing new was built for them.
 *
 * Auto-used for the page with slug "privacy-policy-2" (WP's page-{slug}.php
 * template hierarchy), which is why the filename carries the "-2" rather than
 * reading as a typo — the plain "privacy-policy" slug already belongs to a
 * separate draft page (post 3) predating this design. Also selectable as a page
 * template ("Privacy Policy") so it keeps working if the page is ever renamed.
 *
 * The fifteen sections are hardcoded below rather than modeled as thirty ACF
 * fields (free-tier ACF has no Repeater, the same ceiling `office_1..4_*` and
 * `roa_plan_benefit_N` document): this is one-off legal copy the client is
 * unlikely to edit section-by-section, not a fixed-quantity content block like
 * a hero or a pricing card. `legal-toc.php`'s sidebar and anchors are generated
 * from this array, so editing a title here can never leave the TOC out of sync.
 *
 * Section 10 ("Withdrawal of Consent") repeats section 9's body verbatim — a
 * copy-paste duplicate in the source design, not a mistake introduced here.
 * Two numbered "10." and "11." entries both read "Withdrawal of Consent" in
 * Figma, and only the second one is actually about withdrawing consent. Shipped
 * as drawn, the same call the Foreign, GST and Mortgage frames' leftover
 * artefacts already get across this theme — inventing replacement copy is the
 * client's call, not the theme's.
 *
 * The editor content renders between the sections and the footer when it isn't
 * empty, the same slot every other page-*.php template gives it.
 */

get_header();

$sections = array(
	array(
		'title' => 'Introduction',
		'body'  => '<p>At Think SME Pte Ltd (&#8220;ThinkSME&#8221;, &#8220;we&#8221;, &#8220;us&#8221;, &#8220;our&#8221;), one of our main priorities is the privacy of our visitors. This Privacy Policy outlines the types of personal data we collect, how we use it, and the measures we take to protect it. This policy applies to all personal data in our possession or under our control, including personal data in the possession of organizations we have engaged to collect, use, disclose, or otherwise process personal or company data for our purposes.</p>',
	),
	array(
		'title' => 'Consent',
		'body'  => '<p>By using our website, you hereby consent to our Privacy Policy and agree to its terms.</p>'
			. '<p>We may collect personal data from you, including:</p>'
			. '<ul>'
			. '<li>Name</li>'
			. '<li>Contact information (address, email address, telephone number)</li>'
			. '<li>Company name</li>'
			. '<li>Employment information</li>'
			. '<li>Information about your usage of and interaction with our website</li>'
			. '<li>Any additional information you provide when you contact us directly</li>'
			. '</ul>',
	),
	array(
		'title' => 'Collection, Use, and Disclosure of Personal Data',
		'body'  => '<p>We may collect personal data from customers, business partners, contractors, employees, and other individuals such as job applicants. These personal data may be furnished to us in forms filled out by you, face-to-face meetings, email messages, or telephone conversations. The personal data collected may be used for any or all of the following purposes:</p>'
			. '<ul>'
			. '<li>To provide services to you</li>'
			. '<li>For business operations</li>'
			. '<li>For job application and recruitment purposes</li>'
			. '<li>For billing and reporting purposes</li>'
			. '<li>For follow-up actions regarding complaints, feedback, queries, or requests</li>'
			. '<li>Assisting in law enforcement and investigations</li>'
			. '</ul>',
	),
	array(
		'title' => 'How We Use Your Information',
		'body'  => '<p>We use the information we collect for various purposes, including to:</p>'
			. '<ul>'
			. '<li>Provide, operate, and maintain our website</li>'
			. '<li>Improve, personalize, and expand our website</li>'
			. '<li>Understand and analyze how you use our website</li>'
			. '<li>Develop new products, services, features, and functionality</li>'
			. '<li>Communicate with you for customer service, updates, marketing, and promotional purposes</li>'
			. '<li>Send you emails</li>'
			. '<li>Prevent fraud</li>'
			. '</ul>'
			. '<p>We may disclose your personal data with your consent, where such disclosure is required for performing obligations in connection with our services, or to comply with any applicable laws, regulations, codes of practice, guidelines, or rules. We may also disclose your personal data to third-party service providers, agents, and other organizations we have engaged to perform functions for us.</p>',
	),
	array(
		'title' => 'Log Files',
		'body'  => '<p>ThinkSME follows a standard procedure of using log files. These files log visitors when they visit websites. The information collected includes IP addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users&#8217; movement on the website, and gathering demographic information.</p>',
	),
	array(
		'title' => 'Cookies and Web Beacons',
		'body'  => '<p>Like any other website, ThinkSME uses &#8216;cookies&#8217;. These cookies store information including visitors&#8217; preferences and the pages on the website that the visitor accessed or visited. The information is used to optimize the users&#8217; experience by customizing our web page content based on visitors&#8217; browser type and/or other information.</p>',
	),
	array(
		'title' => 'Google DoubleClick DART Cookie',
		'body'  => '<p>Google is one of the third-party vendors on our site. It uses cookies, known as DART cookies, to serve ads to our site visitors based on their visit to our website and other sites on the internet. However, visitors may choose to decline the use of DART cookies by visiting the Google ad and content network Privacy Policy.</p>',
	),
	array(
		'title' => 'Our Advertising Partners',
		'body'  => '<p>Some of the advertisers on our site may use cookies and web beacons. Each of our advertising partners has their own Privacy Policy for their policies on user data. We advise you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. You can choose to disable cookies through your individual browser options.</p>',
	),
	array(
		'title' => 'Children&#8217;s Information',
		'body'  => '<p>Protecting children while using the internet is another part of our priority. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity. ThinkSME does not knowingly collect any personally identifiable information from children under the age of 13. If you think that your child provided this kind of information on our website, we encourage you to contact us immediately, and we will do our best efforts to promptly remove such information from our records.</p>',
	),
	array(
		// Duplicate of section 9's body in the source design — see the note at
		// the top of this file. Shipped verbatim rather than silently corrected.
		'title' => 'Withdrawal of Consent',
		'body'  => '<p>Protecting children while using the internet is another part of our priority. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity. ThinkSME does not knowingly collect any personally identifiable information from children under the age of 13. If you think that your child provided this kind of information on our website, we encourage you to contact us immediately, and we will do our best efforts to promptly remove such information from our records.</p>',
	),
	array(
		'title' => 'Withdrawal of Consent ',
		'body'  => '<p>You may withdraw your consent for the collection, use, and disclosure of your personal data at any time by contacting us at <a href="mailto:hello@thinksme.sg">hello@thinksme.sg</a>.</p>',
	),
	array(
		'title' => 'Access to and Correction of Personal Data',
		'body'  => '<p>If you wish to access a copy of your personal data or update any personal data we hold about you, you may contact our Data Protection Officer at <a href="mailto:hello@thinksme.sg">hello@thinksme.sg</a>.</p>',
	),
	array(
		'title' => 'Protection of Personal Data',
		'body'  => '<p>To safeguard your personal data from unauthorized access, collection, use, disclosure, copying, modification, disposal, or similar risks, we have introduced appropriate measures, including administrative, physical, and technical measures. However, no method of transmission over the Internet or method of electronic storage is completely secure, and while security cannot be guaranteed, we strive to protect your personal data.</p>',
	),
	array(
		'title' => 'Retention of Personal Data',
		'body'  => '<p>We may retain your personal data for as long as it is necessary to fulfil the purposes for which it was collected, or as required or permitted by applicable laws.</p>',
	),
	array(
		'title' => 'Contact Us',
		'body'  => '<p>If you have any questions or require more information about our Privacy Policy, do not hesitate to contact us at <a href="mailto:hello@thinksme.sg">hello@thinksme.sg</a>.</p>',
	),
);
?>

<main id="main-content" class="sections-spaced">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/legal-hero' );
		get_template_part( 'template-parts/legal-toc', null, array( 'sections' => $sections ) );

		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="w-full px-lg lg:px-3xl py-xl">
				<div class="entry-content max-w-[720px]">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;

	endwhile;
	?>
</main>

<?php
get_footer();
