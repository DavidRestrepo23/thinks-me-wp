<?php
/**
 * "Three Ways Think SME Serves Your Business" — Start/Run/Grow accordion.
 * ACF (Home page): overview_hat_text, overview_heading, overview_subheading,
 * and per row (1..3): overview_row_N_badge, plus per row per service (1..3):
 * _title, _image, _link, _description and four _benefit_N texts. All three
 * services in a row are structurally identical — any of them can be the
 * expanded one.
 *
 * TWO nested levels of native <details>/<summary>, each with its own shared
 * `name` attribute, so the browser enforces single-open behavior at both
 * levels with no JS (Baseline since late 2024): `overview-accordion` picks
 * the Start/Run/Grow row, `overview-services-N` picks which of that row's
 * three service cards is expanded. Clicking a card's image expands it to
 * double width and reveals its description + benefits below; the other two
 * collapse back to full-height, image-only cards. The +/- icon swap, the
 * card widths/heights and the gradient/type changes between states are all
 * plain CSS keyed off `[open]` — see src/base.css.
 *
 * The arrow-circle link sits inside <summary>, so clicking it both navigates
 * and toggles the card. The toggle is invisible because navigation unloads
 * the page — but it does mean a service left on the placeholder `#` link
 * will expand and jump to the top instead. Give every service a real link.
 *
 * Figma: node 11:53, file "Untitled" (vzdpOnH1U36oXcFcugiyE5) — a separate,
 * newer design file from the one docs/superpowers/specs/2026-07-30 was
 * written against. It specifies every row except Run's service 1, which is
 * drafted below.
 */

$icons_uri  = get_template_directory_uri() . '/assets/images/icons';
$bg_pattern = get_template_directory_uri() . '/assets/images/icons/accordion-constellation-bg.svg';

$row_labels = array(
	1 => 'Start',
	2 => 'Run',
	3 => 'Grow',
);

// Every service below comes from Figma except Run's service 1, which is
// drafted here (see the note on it). These are defaults, not content: the
// client overrides any of them from the Home page's ACF fields.
$content_defaults = array(
	1 => array(
		1 => array(
			'title'       => 'Company Incorporation',
			'description' => 'Incorporate your Singapore private limited company (Pte Ltd) fully online — as fast as 24 hours. We handle name reservation, SSIC code selection, company constitution, and all ACRA BizFile+ submission on your behalf.',
			'benefits'    => array(
				'Local & foreign entrepreneur incorporation',
				'ACRA name reservation & SSIC advisory',
				'100% online — no Singapore visit required',
				'Grant eligibility check at incorporation',
			),
		),
		2 => array(
			'title'       => 'Corporate Secretarial Services',
			'description' => 'Singapore law requires every Pte Ltd to appoint a qualified company secretary within 6 months of incorporation. Think SME provides a full corporate secretarial service — AGM prep, annual return filing, and ongoing ACRA compliance.',
			'benefits'    => array(
				'Annual return filing with ACRA',
				'AGM preparation & resolutions',
				'Changes to company particulars',
				'Director & shareholder register maintenance',
			),
		),
		3 => array(
			'title'       => 'Registered Office Address',
			'description' => 'Every Singapore company must maintain a local registered address accessible during business hours. Think SME provides a professional Singapore registered office address — ACRA-compliant, mail handling included.',
			'benefits'    => array(
				'ACRA-compliant Singapore business address',
				'Mail collection & forwarding',
				'Suitable for all business types',
				'Immediate setup upon incorporation',
			),
		),
	),
	2 => array(
		// Drafted here, not taken from Figma — the design never shows this card
		// expanded. The regulatory claims (Companies Act record-keeping, SFRS,
		// XBRL) need the client's sign-off before launch.
		1 => array(
			'title'       => 'Accounting & Bookkeeping',
			'description' => 'Singapore’s Companies Act requires every company to keep proper accounting records for five years. Think SME handles your monthly bookkeeping and year-end unaudited financial statements to SFRS standards — always IRAS- and ACRA-ready.',
			'benefits'    => array(
				'Monthly bookkeeping & bank reconciliation',
				'Unaudited financial statements to SFRS',
				'XBRL preparation & ACRA filing',
				'Cloud accounting setup & migration',
			),
		),
		2 => array(
			'title'       => 'GST Registration & Filing',
			'description' => 'Compulsory GST registration when your turnover exceeds S$1 million — and quarterly F5/F8 return filing to IRAS. Think SME tracks every deadline and submits on time, every quarter. Zero late penalties, guaranteed.',
			'benefits'    => array(
				'Compulsory & voluntary GST registration',
				'Quarterly F5 return preparation & IRAS filing',
				'GST F8 deregistration when applicable',
				'InvoiceNow (Peppol) e-invoicing setup',
			),
		),
		3 => array(
			'title'       => 'Corporate Income Tax',
			'description' => 'Singapore’s flat 17% corporate tax rate — but most SMEs pay far less thanks to the Start-up Tax Exemption (SUTE) and Partial Tax Exemption (PTE). Think SME ensures every exemption is claimed and every IRAS deadline is met.',
			'benefits'    => array(
				'Estimated Chargeable Income (ECI) filing',
				'Form C / C-S / C-S Lite preparation & submission',
				'SUTE & PTE exemption maximisation',
				'Financial statement analysis & business planning',
			),
		),
	),
	3 => array(
		1 => array(
			'title'       => 'SME Business Loans',
			'description' => 'One application, matched across 60+ banks, finance companies, digital banks, and licensed alternative lenders. Our team of former bankers knows exactly how lenders assess SME applications — and how to maximise your approval odds.',
			'benefits'    => array(
				'Enterprise Financing Scheme (EFS) — up to S$500K',
				'Trade & invoice financing',
				'Equipment & fixed asset loans',
				'Alternative lenders for bank-rejected cases',
			),
		),
		2 => array(
			'title'       => 'Property Cashout',
			'description' => 'Own commercial, industrial, or private residential property? Unlock up to 90% LTV at rates 3–5× cheaper than any unsecured business loan. Banks rejected your application? Fret not, we have options with deep expertise in property-backed lending to ensure you get the cash you need to realise your business dreams.',
			'benefits'    => array(
				'Commercial, industrial & residential properties',
				'Up to 90% LTV via alternative lenders',
				'Rates from 2% p.a. · Tenure up to 30 years',
				'TDSR does not apply to company borrowers',
			),
		),
		3 => array(
			'title'       => 'PSG Xero Grant & Government Grants',
			'description' => 'As an IMDA Pre-Approved PSG Vendor, Think SME fast-tracks your Xero grant application via the Business Grants Portal — with up to 50% government co-funding. We also advise on the Enterprise Development Grant (EDG) for capability building and the Market Readiness Assistance (MRA) grant for Singapore SMEs expanding overseas.',
			'benefits'    => array(
				'PSG Xero Grant — up to 50% co-funded by government',
				'IMDA Pre-Approved · Business Grants Portal listed',
				'Enterprise Development Grant (EDG)',
				'Market Readiness Assistance (MRA) — overseas expansion',
			),
		),
	),
);

$rows = array();
foreach ( $row_labels as $r => $default_label ) {
	$services = array();
	foreach ( array( 1, 2, 3 ) as $s ) {
		$defaults         = isset( $content_defaults[ $r ][ $s ] ) ? $content_defaults[ $r ][ $s ] : array();
		$default_benefits = isset( $defaults['benefits'] ) ? $defaults['benefits'] : array( '', '', '', '' );

		$services[ $s ] = array(
			'title'       => thinksme_field( "overview_row_{$r}_service_{$s}_title", false, isset( $defaults['title'] ) ? $defaults['title'] : '' ),
			'image'       => thinksme_field( "overview_row_{$r}_service_{$s}_image" ),
			'link'        => thinksme_field( "overview_row_{$r}_service_{$s}_link", false, '#' ),
			'description' => thinksme_field( "overview_row_{$r}_service_{$s}_description", false, isset( $defaults['description'] ) ? $defaults['description'] : '' ),
			// Benefits fill the two-column grid top-to-bottom, not left-to-right
			// (grid-flow-col in the markup), so 1/2 land in the left column and
			// 3/4 in the right — matching Figma.
			'benefits'    => array_filter(
				array(
					thinksme_field( "overview_row_{$r}_service_{$s}_benefit_1", false, $default_benefits[0] ),
					thinksme_field( "overview_row_{$r}_service_{$s}_benefit_2", false, $default_benefits[1] ),
					thinksme_field( "overview_row_{$r}_service_{$s}_benefit_3", false, $default_benefits[2] ),
					thinksme_field( "overview_row_{$r}_service_{$s}_benefit_4", false, $default_benefits[3] ),
				)
			),
		);
	}
	// The card that starts expanded is the first one that actually has copy to
	// reveal — an expanded card with an empty body would leave a hole under its
	// 412px image. When no service in the row has copy (the Grow row today),
	// none opens and all three stay full-height, which is exactly the collapsed
	// design.
	$open_service = 0;
	foreach ( $services as $s => $service ) {
		if ( $service['description'] || $service['benefits'] ) {
			$open_service = $s;
			break;
		}
	}

	$rows[ $r ] = array(
		'badge'    => thinksme_field( "overview_row_{$r}_badge", false, $default_label ),
		'open'     => $open_service,
		'services' => $services,
	);
}
?>
<section id="overview" class="flex flex-col items-center gap-3xl w-full mt-xl md:mt-3xl mb-[60px] px-lg">
	<div class="flex flex-col items-center gap-lg text-center" style="max-width: 843px;">
		<span class="bg-brand-yellow-soft border border-brand-yellow-border rounded-pill h-[32px] px-lg inline-flex items-center justify-center text-xs font-medium text-text-primary">
			<?php echo esc_html( thinksme_field( 'overview_hat_text', false, 'Everything Under One Roof' ) ); ?>
		</span>
		<h2 class="font-medium text-2xl lg:text-3xl text-text-primary" style="letter-spacing: var(--tracking-tight);">
			<?php echo esc_html( thinksme_field( 'overview_heading', false, 'Three Ways Think SME Serves Your Business' ) ); ?>
		</h2>
		<p class="font-normal text-md text-text-secondary" style="max-width: 592px; letter-spacing: var(--tracking-tight);">
			<?php echo esc_html( thinksme_field( 'overview_subheading' ) ); ?>
		</p>
	</div>

	<div class="relative w-full bg-surface-dark rounded-[40px] md:rounded-[80px] overflow-hidden px-lg md:px-[56px] py-3xl md:py-[136px]">
		<div
			class="absolute inset-x-0 bottom-0 h-1/2 bg-no-repeat bg-bottom pointer-events-none"
			style="background-image: url('<?php echo esc_url( $bg_pattern ); ?>'); background-size: cover;"
			aria-hidden="true"
		></div>

		<div class="relative flex flex-col gap-xl md:gap-[48px]">
			<?php foreach ( $rows as $row_index => $row ) : ?>
				<details class="overview-accordion-row border-b border-white/15 pb-xl md:pb-[48px]" name="overview-accordion" <?php echo 1 === $row_index ? 'open' : ''; ?>>
					<summary class="overview-accordion-toggle flex items-center justify-end gap-lg md:gap-[48px] cursor-pointer list-none w-full">
						<span class="overview-accordion-icon shrink-0 bg-brand-yellow rounded-pill flex items-center justify-center size-[64px] md:size-[112px]">
							<img src="<?php echo esc_url( "$icons_uri/icon-plus.svg" ); ?>" alt="" class="icon-plus size-[28px] md:size-[48px]">
							<img src="<?php echo esc_url( "$icons_uri/icon-minus.svg" ); ?>" alt="" class="icon-minus size-[28px] md:size-[48px]">
						</span>
						<span class="overview-accordion-badge font-medium text-text-on-dark text-[56px] md:text-[144px]" style="line-height: 1.12;">
							<?php echo esc_html( $row['badge'] ); ?>
						</span>
					</summary>

					<div class="overview-accordion-content pt-xl md:pt-[48px]">
						<div class="overview-services flex flex-col md:flex-row gap-xs w-full">
							<?php foreach ( $row['services'] as $service_index => $service ) : ?>
								<details class="overview-service" name="overview-services-<?php echo esc_attr( $row_index ); ?>" <?php echo $service_index === $row['open'] ? 'open' : ''; ?>>
									<summary class="overview-service-card relative block rounded-lg overflow-hidden cursor-pointer">
										<?php if ( ! empty( $service['image'] ) ) : ?>
											<img src="<?php echo esc_url( $service['image']['url'] ); ?>" alt="<?php echo esc_attr( $service['image']['alt'] ); ?>" class="absolute inset-0 w-full h-full object-cover">
										<?php endif; ?>
										<span class="overview-service-scrim absolute inset-0" aria-hidden="true"></span>
										<span class="overview-service-caption absolute inset-x-0 bottom-0 flex flex-col items-start gap-lg">
											<a href="<?php echo esc_url( $service['link'] ); ?>" class="overview-service-link bg-surface-dark rounded-pill flex items-center justify-center shrink-0 size-[40px] md:size-[56px]">
												<img src="<?php echo esc_url( "$icons_uri/arrow-up-right.svg" ); ?>" alt="" class="size-[20px] md:size-[32px]">
											</a>
											<span class="overview-service-title font-medium text-text-on-dark" style="line-height: var(--line-height-snug);">
												<?php echo esc_html( $service['title'] ); ?>
											</span>
										</span>
									</summary>

									<?php if ( $service['description'] || $service['benefits'] ) : ?>
										<div class="overview-service-content bg-surface-dark-glass rounded-lg flex flex-col gap-xl p-lg md:p-xl mt-xs">
											<?php if ( $service['description'] ) : ?>
												<p class="font-normal text-text-on-dark text-sm" style="line-height: var(--line-height-loose);">
													<?php echo esc_html( $service['description'] ); ?>
												</p>
											<?php endif; ?>
											<?php if ( $service['benefits'] ) : ?>
												<ul class="grid grid-cols-1 sm:grid-rows-2 sm:grid-flow-col sm:auto-cols-fr gap-sm w-full">
													<?php foreach ( $service['benefits'] as $benefit ) : ?>
														<li class="flex gap-md items-start">
															<span class="bg-success rounded-pill flex items-center justify-center shrink-0 size-[24px] mt-px">
																<img src="<?php echo esc_url( "$icons_uri/check.svg" ); ?>" alt="" class="size-[16px]">
															</span>
															<span class="font-normal text-text-on-dark text-xs" style="line-height: var(--line-height-loose);">
																<?php echo esc_html( $benefit ); ?>
															</span>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</details>
							<?php endforeach; ?>
						</div>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
