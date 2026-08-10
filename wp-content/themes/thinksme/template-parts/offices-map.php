<?php
/**
 * Offices — four address cards over a live Google Maps embed. Clicking a card
 * re-points the map at that office (assets/js/contact-map.js).
 * Figma: node 62:126, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Contact page): office_1..4_name, office_1..4_address (textarea — line
 * breaks are kept), office_1..4_map_query (optional; the string handed to Google
 * Maps when the address alone doesn't resolve, e.g. a building name).
 *
 * The embed is the keyless `maps?q=…&output=embed` endpoint, so nothing here
 * needs a Google Cloud project or an API key. Each card is a real link to Google
 * Maps that the script intercepts, which means the section still works — as four
 * links out to Maps — if the script never runs.
 *
 * An office with no name and no address is skipped, so the client can run fewer
 * than four by clearing the fields rather than asking for a template change.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$defaults = array(
	1 => array( 'name' => 'Singapore', 'address' => "380 Jalan Besar,\n#07-01, ARC 380\nSingapore 209000" ),
	2 => array( 'name' => 'Philippines', 'address' => "9/F Fillinvest One Building Northgate Cyberzone,\nMuntinlupa City 1781, Philippines" ),
	3 => array( 'name' => 'Malaysia', 'address' => "Suite 25.03A, Level 25, City Square Office Tower,\n80000 Johor Bahru, Malaysia" ),
	4 => array( 'name' => 'India', 'address' => '559/A Aman Nagar, Mirjapur Road, Hisar 125001, Haryana, India' ),
);

$offices = array();

foreach ( $defaults as $n => $default ) {
	$name    = thinksme_field( "office_{$n}_name", false, $default['name'] );
	$address = thinksme_field( "office_{$n}_address", false, $default['address'] );

	if ( '' === $name && '' === $address ) {
		continue;
	}

	// The search string: an explicit map query wins, otherwise the address with
	// its line breaks flattened, otherwise the office name.
	$query = thinksme_field( "office_{$n}_map_query", false, '' );
	if ( '' === $query ) {
		$query = '' !== $address ? trim( preg_replace( '/\s+/', ' ', $address ) ) : $name;
	}

	$offices[] = array(
		'name'    => $name,
		'address' => $address,
		'query'   => $query,
	);
}

if ( ! $offices ) {
	return;
}

$embed_base = 'https://www.google.com/maps?output=embed&q=';
?>
<section id="offices" class="w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="offices-map relative rounded-lg overflow-hidden w-full">
		<?php // Aspect ratio on desktop reproduces Figma's 1280x714 frame; on mobile the cards sit below the map, so it only needs to be tall enough to read. ?>
		<div class="h-[320px] lg:h-auto lg:aspect-[1280/714] w-full">
			<iframe
				class="offices-map__embed"
				title="<?php esc_attr_e( 'Map of our offices', 'thinksme' ); ?>"
				src="<?php echo esc_url( $embed_base . rawurlencode( $offices[0]['query'] ) ); ?>"
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				allowfullscreen
			></iframe>
		</div>

		<div class="lg:absolute lg:left-[40px] lg:right-[40px] lg:bottom-[40px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md mt-md lg:mt-0">
			<?php foreach ( $offices as $index => $office ) : ?>
				<a
					<?php // Below the desktop breakpoint the cards sit on the page rather than on the map, so they need an outline of their own to still read as cards. ?>
					class="office-card flex flex-col gap-md items-start bg-surface-white border border-border-soft lg:border-transparent rounded-lg p-lg lg:p-xl h-full"
					href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $office['query'] ) ); ?>"
					target="_blank"
					rel="noopener"
					data-map-src="<?php echo esc_url( $embed_base . rawurlencode( $office['query'] ) ); ?>"
					data-active="<?php echo 0 === $index ? 'true' : 'false'; ?>"
				>
					<span class="bg-brand-yellow rounded-pill size-[48px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( "$icons_uri/map-pin.svg" ); ?>" alt="" class="size-[21px]">
					</span>
					<span class="flex flex-col gap-xs justify-center">
						<span class="font-medium text-xl leading-tight tracking-hero text-text-primary"><?php echo esc_html( $office['name'] ); ?></span>
						<span class="font-normal text-sm text-text-secondary leading-normal tracking-wide"><?php echo nl2br( esc_html( $office['address'] ) ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
