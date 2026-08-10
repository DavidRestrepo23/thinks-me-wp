<?php
/**
 * Think SME theme bootstrap.
 * Requires the free Advanced Custom Fields plugin (no PRO features are used —
 * see docs/superpowers/specs for why: fixed-quantity content lives in an ACF
 * field group on the Home page, variable-length lists live in CPTs below).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THINKSME_VERSION', '1.0.0' );
define( 'THINKSME_SWIPER_VERSION', '14.0.7' );

// Primary-nav walker — builds the desktop megamenu out of the menu's own depth.
require_once get_template_directory() . '/inc/class-thinksme-nav-walker.php';

/**
 * Theme setup.
 */
function thinksme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary'  => __( 'Primary (header)', 'thinksme' ),
			'footer-1' => __( 'Footer — Corporate Service', 'thinksme' ),
			'footer-2' => __( 'Footer — Finance & Loan', 'thinksme' ),
			'footer-3' => __( 'Footer — Grants & Company', 'thinksme' ),
		)
	);
}
add_action( 'after_setup_theme', 'thinksme_setup' );

/**
 * Enqueue the theme header stylesheet (style.css, required by WP — carries
 * no rules) and the Tailwind build output (assets/css/style.css, generated
 * by `npm run dev` / `npm run build`, see src/tailwind.config.js).
 */
function thinksme_enqueue_assets() {
	wp_enqueue_style( 'thinksme-style', get_stylesheet_uri(), array(), THINKSME_VERSION );

	$tailwind_path = get_template_directory() . '/assets/css/style.css';
	wp_enqueue_style(
		'thinksme-tailwind',
		get_template_directory_uri() . '/assets/css/style.css',
		array(),
		file_exists( $tailwind_path ) ? filemtime( $tailwind_path ) : THINKSME_VERSION
	);

	// Scroll reveal — head, not footer, and deliberately render-blocking: the
	// script adds html.has-scroll-reveal, which is what hides the sections in
	// the first place (src/base.css). Loading it in the footer would paint them
	// visible and then hide them. It's ~1KB and bails out early on its own if
	// the browser or the visitor's motion preference rules the effect out.
	wp_enqueue_script(
		'thinksme-scroll-reveal',
		get_template_directory_uri() . '/assets/js/scroll-reveal.js',
		array(),
		THINKSME_VERSION,
		false
	);

	// Mobile nav panel — every page, not just the homepage (see header.php).
	wp_enqueue_script(
		'thinksme-header-nav',
		get_template_directory_uri() . '/assets/js/header-nav.js',
		array(),
		THINKSME_VERSION,
		true
	);

	// Swiper powers the homepage carousels (hero fan, client logos, Google Reviews).
	if ( is_front_page() ) {
		wp_enqueue_style(
			'swiper',
			get_template_directory_uri() . '/assets/css/vendor/swiper/swiper-bundle.min.css',
			array(),
			THINKSME_SWIPER_VERSION
		);
		wp_enqueue_script(
			'swiper',
			get_template_directory_uri() . '/assets/js/vendor/swiper/swiper-bundle.min.js',
			array(),
			THINKSME_SWIPER_VERSION,
			true
		);

		// Hero photo fan — homepage only (see template-parts/hero.php).
		wp_enqueue_script(
			'thinksme-hero-slider',
			get_template_directory_uri() . '/assets/js/hero-slider.js',
			array( 'swiper' ),
			THINKSME_VERSION,
			true
		);

		// Client logos carousel — homepage only (see template-parts/logos-slider.php).
		wp_enqueue_script(
			'thinksme-logos-slider',
			get_template_directory_uri() . '/assets/js/logos-slider.js',
			array( 'swiper' ),
			THINKSME_VERSION,
			true
		);

		// Google Reviews slider — homepage only (see template-parts/testimonials.php).
		wp_enqueue_script(
			'thinksme-testimonials-slider',
			get_template_directory_uri() . '/assets/js/testimonials-slider.js',
			array( 'swiper' ),
			THINKSME_VERSION,
			true
		);

		// "Three Ways" card deck — homepage only, no Swiper dependency
		// (see template-parts/cards-deck.php).
		wp_enqueue_script(
			'thinksme-cards-deck',
			get_template_directory_uri() . '/assets/js/cards-deck.js',
			array(),
			THINKSME_VERSION,
			true
		);
	}

	// Office cards re-pointing the Google Maps embed — contact page only
	// (see template-parts/offices-map.php).
	if ( is_page_template( 'page-contact-us.php' ) ) {
		wp_enqueue_script(
			'thinksme-contact-map',
			get_template_directory_uri() . '/assets/js/contact-map.js',
			array(),
			THINKSME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'thinksme_enqueue_assets' );

/**
 * Customizer controls for the client logos carousel (template-parts/logos-slider.php),
 * so the client can tune autoplay behavior from Appearance > Customize without
 * touching code. No ACF Options Page is used here (PRO-only, see docs/superpowers/specs).
 */
function thinksme_customize_register( $wp_customize ) {
	/**
	 * Site-wide chrome referenced by header.php / footer.php. These were read
	 * with get_theme_mod() before they had controls, which meant the client
	 * could never actually change them — hence the section below.
	 */
	$wp_customize->add_section(
		'thinksme_site_details',
		array(
			'title'    => __( 'Site Details', 'thinksme' ),
			'priority' => 155,
		)
	);

	$site_details = array(
		'thinksme_footer_tagline'  => array(
			'label'   => __( 'Footer tagline', 'thinksme' ),
			'type'    => 'textarea',
			'default' => 'Your Partner in Business Success. We are committed to help businesses grow, succeed and prosper.',
			'sanitize' => 'sanitize_textarea_field',
		),
		'thinksme_facebook_url'    => array(
			'label'   => __( 'Facebook URL', 'thinksme' ),
			'type'    => 'url',
			'default' => '',
			'sanitize' => 'esc_url_raw',
		),
		'thinksme_linkedin_url'    => array(
			'label'   => __( 'LinkedIn URL', 'thinksme' ),
			'type'    => 'url',
			'default' => '',
			'sanitize' => 'esc_url_raw',
		),
		'thinksme_phone'           => array(
			'label'   => __( 'Header phone number', 'thinksme' ),
			'type'    => 'text',
			'default' => '+6560129642',
			'sanitize' => 'sanitize_text_field',
		),
		'thinksme_whatsapp_number' => array(
			'label'   => __( 'WhatsApp number (floating button — leave empty to hide)', 'thinksme' ),
			'type'    => 'text',
			'default' => '',
			'sanitize' => 'sanitize_text_field',
		),
		'thinksme_contact_recipient' => array(
			'label'   => __( 'Contact form recipient (empty = site admin email)', 'thinksme' ),
			'type'    => 'email',
			'default' => '',
			'sanitize' => 'sanitize_email',
		),
	);

	foreach ( $site_details as $setting_id => $config ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $config['default'],
				'sanitize_callback' => $config['sanitize'],
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$setting_id,
			array(
				'section' => 'thinksme_site_details',
				'label'   => $config['label'],
				'type'    => $config['type'],
			)
		);
	}

	$wp_customize->add_section(
		'thinksme_logos_slider',
		array(
			'title'    => __( 'Client Logos Slider', 'thinksme' ),
			'priority' => 160,
		)
	);

	$wp_customize->add_setting(
		'thinksme_logos_autoplay',
		array(
			'default'           => true,
			'sanitize_callback' => 'thinksme_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'thinksme_logos_autoplay',
		array(
			'section' => 'thinksme_logos_slider',
			'label'   => __( 'Enable autoplay', 'thinksme' ),
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'thinksme_logos_autoplay_speed',
		array(
			'default'           => 3000,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'thinksme_logos_autoplay_speed',
		array(
			'section'     => 'thinksme_logos_slider',
			'label'       => __( 'Scroll speed (ms per slide) — lower is faster', 'thinksme' ),
			'type'        => 'number',
			'input_attrs' => array( 'min' => 1000, 'step' => 250 ),
		)
	);

	$wp_customize->add_setting(
		'thinksme_logos_pause_on_hover',
		array(
			'default'           => true,
			'sanitize_callback' => 'thinksme_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'thinksme_logos_pause_on_hover',
		array(
			'section' => 'thinksme_logos_slider',
			'label'   => __( 'Pause on hover', 'thinksme' ),
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'thinksme_logos_slides_desktop',
		array(
			'default'           => 5,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'thinksme_logos_slides_desktop',
		array(
			'section'     => 'thinksme_logos_slider',
			'label'       => __( 'Logos visible on desktop', 'thinksme' ),
			'type'        => 'number',
			'input_attrs' => array( 'min' => 1, 'max' => 8 ),
		)
	);

}
add_action( 'customize_register', 'thinksme_customize_register' );

/**
 * Checkbox settings save '1'/'' as strings — coerce to a real bool so
 * get_theme_mod() callers can rely on the type.
 */
function thinksme_sanitize_checkbox( $checked ) {
	return (bool) $checked;
}

/**
 * ACF field groups are exported to acf-json/ and versioned in git, so they're
 * reproducible across environments (Local, staging, prod) without manual
 * export/import. See docs/superpowers/specs/2026-07-30-homepage-theme-design.md.
 */
add_filter(
	'acf/settings/save_json',
	function () {
		return get_template_directory() . '/acf-json';
	}
);

add_filter(
	'acf/settings/load_json',
	function ( $paths ) {
		$paths[] = get_template_directory() . '/acf-json';
		return $paths;
	}
);

/**
 * Custom Post Types for client-editable, variable-length lists. Each is a
 * normal WP post type — client adds/edits/deletes from the admin sidebar,
 * never touching code or layout.
 */
function thinksme_register_post_types() {
	register_post_type(
		'testimonial',
		array(
			'labels'       => array(
				'name'          => __( 'Google Reviews', 'thinksme' ),
				'singular_name' => __( 'Google Review', 'thinksme' ),
				'add_new_item'  => __( 'Add New Google Review', 'thinksme' ),
				'edit_item'     => __( 'Edit Google Review', 'thinksme' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-star-filled',
			'supports'     => array( 'title', 'page-attributes' ),
			'has_archive'  => false,
			'rewrite'      => false,
		)
	);

	register_post_type(
		'faq_item',
		array(
			'labels'       => array(
				'name'          => __( 'FAQs', 'thinksme' ),
				'singular_name' => __( 'FAQ', 'thinksme' ),
				'add_new_item'  => __( 'Add New FAQ', 'thinksme' ),
				'edit_item'     => __( 'Edit FAQ', 'thinksme' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-editor-help',
			// page-attributes gives the client the "Order" box, so the accordion
			// order is theirs to set without touching code.
			'supports'     => array( 'title', 'page-attributes' ),
			'has_archive'  => false,
			'rewrite'      => false,
		)
	);

	register_post_type(
		'hero_image',
		array(
			'labels'       => array(
				'name'          => __( 'Hero Photos', 'thinksme' ),
				'singular_name' => __( 'Hero Photo', 'thinksme' ),
				'add_new_item'  => __( 'Add New Hero Photo', 'thinksme' ),
				'edit_item'     => __( 'Edit Hero Photo', 'thinksme' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-images-alt',
			// Featured image is the photo; the title is only the alt text/label.
			// page-attributes gives the client the Order box for the fan order.
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'  => false,
			'rewrite'      => false,
		)
	);

	register_post_type(
		'client_logo',
		array(
			'labels'       => array(
				'name'          => __( 'Client Logos', 'thinksme' ),
				'singular_name' => __( 'Client Logo', 'thinksme' ),
				'add_new_item'  => __( 'Add New Client Logo', 'thinksme' ),
				'edit_item'     => __( 'Edit Client Logo', 'thinksme' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-images-alt2',
			'supports'     => array( 'title', 'thumbnail' ),
			'has_archive'  => false,
			'rewrite'      => false,
		)
	);
}
add_action( 'init', 'thinksme_register_post_types' );

/**
 * "Group" taxonomy on client_logo, so the two homepage logo carousels
 * (template-parts/logos-slider.php, called once below the gallery and once
 * below "overview") can pull independent, non-overlapping logo sets from
 * the same CPT instead of both showing every logo. Category-style checkbox
 * UI in wp-admin — client picks a group per logo, no code change needed to
 * add more.
 */
function thinksme_register_taxonomies() {
	register_taxonomy(
		'logo_group',
		'client_logo',
		array(
			'labels'            => array(
				'name'          => __( 'Logo Groups', 'thinksme' ),
				'singular_name' => __( 'Logo Group', 'thinksme' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'thinksme_register_taxonomies' );

/**
 * Seed the two default logo_group terms once, so the client sees ready-made
 * groups in wp-admin instead of an empty taxonomy on first use. Runs on
 * theme activation rather than every 'init' — the taxonomy itself is
 * already registered by then since thinksme_register_taxonomies() also
 * hooks 'init', which fires before after_switch_theme's redirect reload.
 */
function thinksme_seed_logo_groups() {
	if ( ! term_exists( 'certifications', 'logo_group' ) ) {
		wp_insert_term( 'Certifications', 'logo_group', array( 'slug' => 'certifications' ) );
	}
	if ( ! term_exists( 'clients', 'logo_group' ) ) {
		wp_insert_term( 'Clients', 'logo_group', array( 'slug' => 'clients' ) );
	}
}
add_action( 'after_switch_theme', 'thinksme_seed_logo_groups' );

/**
 * Thin wrapper around get_field() that no-ops instead of fataling when ACF
 * isn't active yet, and normalizes "empty" to a caller-supplied default.
 * Called with no $post_id inside The Loop, this reads the current post's
 * fields — for front-page.php that's whatever page is set as the static
 * front page, which is where the Home ACF field group is assigned.
 */
function thinksme_field( $selector, $post_id = false, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $selector, $post_id );
	return ( '' === $value || null === $value || false === $value ) ? $default : $value;
}

/**
 * Icon set the megamenu cards can use (inc/class-thinksme-nav-walker.php).
 *
 * Slug => admin label. The slug is the filename in
 * assets/images/icons/megamenu/, and the same list populates the ACF select on
 * menu items via the filter below — so adding an icon is one SVG plus one line
 * here, with nothing to re-sync in acf-json/.
 */
function thinksme_menu_icons() {
	return array(
		'network'      => __( 'Network / org chart', 'thinksme' ),
		'file-check'   => __( 'Document with check', 'thinksme' ),
		'circle-check' => __( 'Check in a circle', 'thinksme' ),
		'cloud-cog'    => __( 'Cloud with cog', 'thinksme' ),
	);
}

/**
 * URL for a megamenu card icon. Falls back to the first icon in the set, which
 * is also the one Figma repeats — an unset or stale slug renders a card that
 * looks intentional rather than a broken image.
 *
 * @param string $slug Icon slug.
 * @return string
 */
function thinksme_menu_icon_url( $slug ) {
	$icons = thinksme_menu_icons();

	if ( ! is_string( $slug ) || ! isset( $icons[ $slug ] ) ) {
		$slug = 'network';
	}

	return get_template_directory_uri() . '/assets/images/icons/megamenu/' . $slug . '.svg';
}

/**
 * Feed thinksme_menu_icons() into the ACF select instead of hard-coding the
 * choices in the field group JSON, so the two can't drift apart.
 *
 * @param array $field ACF field.
 * @return array
 */
function thinksme_menu_icon_choices( $field ) {
	$field['choices'] = thinksme_menu_icons();

	return $field;
}
add_filter( 'acf/load_field/name=menu_icon', 'thinksme_menu_icon_choices' );

/**
 * Contact form — validation and delivery for template-parts/contact-form.php.
 *
 * The theme ships no form plugin, so the form posts back to its own page and is
 * handled here on 'template_redirect': early enough to redirect after a
 * successful send (so a refresh can't re-submit) and to have the result ready
 * before the template part renders. Errors don't redirect — the state below is
 * what re-renders them next to the fields with the visitor's input intact.
 *
 * Delivery is wp_mail() to the Customizer's "Contact form recipient" (Appearance
 * > Customize > Site Details), falling back to the site admin email. wp_mail on
 * a stock host is unauthenticated PHP mail and lands in spam more often than
 * not — for production, point the site at a real SMTP/API sender.
 *
 * Spam handling is a nonce plus a honeypot field CSS keeps off screen. No
 * CAPTCHA: that's a third-party script decision for the client to make.
 */
function thinksme_contact_state( $update = null ) {
	static $state = null;

	if ( null === $state ) {
		$state = array(
			// The success message survives the redirect on this flag alone — there
			// is nothing to show but the confirmation, so no transient is needed.
			'sent'   => isset( $_GET['contact'] ) && 'sent' === $_GET['contact'],
			'errors' => array(),
			'values' => array(),
		);
	}

	if ( is_array( $update ) ) {
		$state = array_merge( $state, $update );
	}

	return $state;
}

/**
 * Field name => label, in the order Figma stacks them. Also the list the
 * handler validates and the template renders, so adding a field is one entry
 * here plus its markup.
 */
function thinksme_contact_fields() {
	return array(
		'name'    => array( 'label' => __( 'Name', 'thinksme' ), 'type' => 'text' ),
		'company' => array( 'label' => __( 'Company Name', 'thinksme' ), 'type' => 'text' ),
		'email'   => array( 'label' => __( 'E-mail', 'thinksme' ), 'type' => 'email' ),
		'phone'   => array( 'label' => __( 'Mobile Phone', 'thinksme' ), 'type' => 'tel' ),
		'message' => array( 'label' => __( 'Message', 'thinksme' ), 'type' => 'textarea' ),
	);
}

function thinksme_handle_contact_submission() {
	if ( ! isset( $_POST['thinksme_contact_submit'] ) ) {
		return;
	}

	$fields = thinksme_contact_fields();
	$errors = array();
	$values = array();

	foreach ( $fields as $key => $field ) {
		$raw = isset( $_POST[ "thinksme_contact_$key" ] ) ? wp_unslash( $_POST[ "thinksme_contact_$key" ] ) : '';

		$values[ $key ] = 'textarea' === $field['type']
			? sanitize_textarea_field( $raw )
			: sanitize_text_field( $raw );
	}

	// Kept whatever happens next, so a re-render shows the visitor's own copy
	// back rather than an empty form.
	thinksme_contact_state( array( 'values' => $values ) );

	// A stale nonce (an idle tab, a cached page) is the one failure worth naming
	// on its own — the visitor's copy is intact and re-submitting works.
	if ( ! isset( $_POST['thinksme_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['thinksme_contact_nonce'] ) ), 'thinksme_contact' ) ) {
		thinksme_contact_state( array( 'errors' => array( 'form' => __( 'This form expired before it was sent. Please submit it again.', 'thinksme' ) ) ) );
		return;
	}

	// Honeypot: a real visitor never sees this field, so anything in it is a bot.
	// Reported as sent rather than rejected — a bot told it failed retries.
	if ( ! empty( $_POST['thinksme_contact_website'] ) ) {
		thinksme_contact_state( array( 'sent' => true ) );
		return;
	}

	foreach ( $fields as $key => $field ) {
		if ( '' === $values[ $key ] ) {
			/* translators: %s: field label, e.g. "Mobile Phone". */
			$errors[ $key ] = sprintf( __( '%s is required.', 'thinksme' ), $field['label'] );
		}
	}

	if ( '' !== $values['email'] && ! is_email( $values['email'] ) ) {
		$errors['email'] = __( 'Please enter a valid e-mail address.', 'thinksme' );
	}

	if ( empty( $_POST['thinksme_contact_consent'] ) ) {
		$errors['consent'] = __( 'Please agree to the storing and processing of your personal data.', 'thinksme' );
	}

	if ( $errors ) {
		thinksme_contact_state( array( 'errors' => $errors ) );
		return;
	}

	$recipient = get_theme_mod( 'thinksme_contact_recipient' );
	if ( ! is_email( $recipient ) ) {
		$recipient = get_option( 'admin_email' );
	}

	$body = array();
	foreach ( $fields as $key => $field ) {
		$body[] = $field['label'] . ': ' . $values[ $key ];
	}
	$body[] = '';
	$body[] = __( 'Sent from', 'thinksme' ) . ': ' . get_permalink();

	$sent = wp_mail(
		$recipient,
		sprintf(
			/* translators: 1: site name, 2: sender name. */
			__( '[%1$s] Contact form — %2$s', 'thinksme' ),
			wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
			$values['name']
		),
		implode( "\n", $body ),
		array( 'Reply-To: ' . $values['name'] . ' <' . $values['email'] . '>' )
	);

	if ( ! $sent ) {
		thinksme_contact_state( array( 'errors' => array( 'form' => __( 'Something went wrong sending your message. Please try again, or email us directly.', 'thinksme' ) ) ) );
		return;
	}

	// Redirect-after-post, so a refresh can't send the message twice.
	wp_safe_redirect( add_query_arg( 'contact', 'sent', get_permalink() ) . '#contact-form' );
	exit;
}
add_action( 'template_redirect', 'thinksme_handle_contact_submission' );

/**
 * Nudge in wp-admin if ACF isn't active — most template-parts call get_field()
 * and would otherwise fatal.
 */
function thinksme_acf_missing_notice() {
	if ( ! function_exists( 'get_field' ) && current_user_can( 'activate_plugins' ) ) {
		echo '<div class="notice notice-error"><p>' .
			esc_html__( 'Think SME theme requires the Advanced Custom Fields plugin (free tier).', 'thinksme' ) .
			'</p></div>';
	}
}
add_action( 'admin_notices', 'thinksme_acf_missing_notice' );
