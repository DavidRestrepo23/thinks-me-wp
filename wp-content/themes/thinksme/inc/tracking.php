<?php
/**
 * Tracking snippets carried over from the live site (thinksme.sg, 28 Oct 2026):
 *
 * - Google Tag Manager GTM-PG3BTFW. GA4 (G-SKH7ZZYZ6Y), Google Ads and Bing UET
 *   all fire from inside this container, so there is no gtag.js here on purpose —
 *   adding one would double-count GA4.
 * - Google Search Console verification meta.
 *
 * The live site printed the GTM <noscript> iframe inside <head>, which is invalid
 * HTML; here it goes right after <body> via wp_body_open, as Google specifies.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THINKSME_GTM_ID', 'GTM-PG3BTFW' );
define( 'THINKSME_GSC_VERIFICATION', 'lnjp64R-LQfXd-p8fCp-gGAKjtNrlPMy70Xa-V1_kvI' );

/**
 * Whether GTM should load. Off on 'local' and 'development' environments so dev
 * traffic doesn't land in GA4 / Ads; on for 'staging' and 'production'.
 * To test locally, define( 'THINKSME_FORCE_TRACKING', true ) in wp-config.php.
 */
function thinksme_tracking_enabled() {
	$enabled = ! in_array( wp_get_environment_type(), array( 'local', 'development' ), true )
		|| ( defined( 'THINKSME_FORCE_TRACKING' ) && THINKSME_FORCE_TRACKING );

	return (bool) apply_filters( 'thinksme_tracking_enabled', $enabled );
}

/**
 * GTM container script — as high in <head> as wp_head allows.
 */
function thinksme_gtm_head() {
	if ( ! thinksme_tracking_enabled() ) {
		return;
	}
	?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( THINKSME_GTM_ID ); ?>');</script>
<!-- End Google Tag Manager -->
	<?php
}
add_action( 'wp_head', 'thinksme_gtm_head', 1 );

/**
 * GTM <noscript> fallback — immediately after the opening <body>.
 */
function thinksme_gtm_body() {
	if ( ! thinksme_tracking_enabled() ) {
		return;
	}
	?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( THINKSME_GTM_ID ); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<?php
}
add_action( 'wp_body_open', 'thinksme_gtm_body', 1 );

/**
 * Search Console verification. Printed on every environment (it's inert), but
 * skipped when Yoast's own "Google verification code" setting is filled in, so
 * the tag never appears twice.
 */
function thinksme_gsc_verification() {
	$yoast = get_option( 'wpseo' );
	if ( is_array( $yoast ) && ! empty( $yoast['googleverify'] ) ) {
		return;
	}
	printf( '<meta name="google-site-verification" content="%s" />' . "\n", esc_attr( THINKSME_GSC_VERIFICATION ) );
}
add_action( 'wp_head', 'thinksme_gsc_verification', 1 );
