<?php
/**
 * Document shell + site header.
 * Figma: node 30:2342 → the Header component (node 30:2220), desktop.
 *
 * Nav is `wp_nav_menu()` against the `primary` location, so the client manages
 * it from Appearance > Menus and never touches this file. With nothing assigned
 * yet the Figma items render as plain text (same convention as the footer
 * columns) so the header still matches the design.
 *
 * `wp_nav_menu()` only lets us class the <ul>, not each <li>/<a>, so per-item
 * styling — padding, the caret on parents, the megamenu panel, and the mobile
 * panel — lives in src/base.css. The one thing CSS can't do is restructure the
 * submenu markup, which is what Thinksme_Nav_Walker below is for.
 *
 * Phone number for "Call Us" is a theme_mod (Appearance > Customize > Site
 * Details), not an ACF field: it's site-wide chrome, not Home-page content.
 */

$phone          = get_theme_mod( 'thinksme_phone', '+6560129642' );
$fallback_items = array( 'About', 'Corporate Service', 'Finance & Loan', 'Grants', 'Blog', 'Contact Us' );
$icons_uri      = get_template_directory_uri() . '/assets/images/icons';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-surface-white text-text-primary font-base' ); ?>>
<?php wp_body_open(); ?>

<header id="site-header" class="site-header bg-surface-white border-b border-border-soft flex items-center justify-between gap-lg px-lg lg:px-3xl w-full">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo block shrink-0">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<img
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-thinksme.svg' ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="h-[60px] w-[114px] lg:h-[81px] lg:w-[154px]"
			>
		<?php endif; ?>
	</a>

	<button
		type="button"
		class="site-header__toggle lg:hidden shrink-0 flex flex-col justify-center gap-[5px] size-[44px] items-center"
		aria-controls="primary-nav"
		aria-expanded="false"
		aria-label="<?php esc_attr_e( 'Toggle menu', 'thinksme' ); ?>"
	>
		<svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect x="0.5" y="0.5" width="55" height="55" rx="27.5" stroke="#A6A6A6"/>
<path d="M19 28H37" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M19 22H37" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M19 34H37" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

	</button>

	<nav id="primary-nav" class="site-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'thinksme' ); ?>">
		<div class="site-header__nav-scroll">
			<!-- Mobile only: replaces the item list with a back button + the
			     tapped item's title when a submenu is drilled into. Populated by
			     assets/js/header-nav.js; empty/hidden until then, and irrelevant
			     on desktop where submenus open on hover instead. -->
			<div class="site-header__subnav" hidden>
				<button type="button" class="site-header__back" aria-label="<?php esc_attr_e( 'Back', 'thinksme' ); ?>">
					<img src="<?php echo esc_url( "$icons_uri/caret-right.svg" ); ?>" alt="" aria-hidden="true">
				</button>
				<span class="site-header__subnav-title"></span>
			</div>

			<?php
			$menu = wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'site-header__menu',
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'fallback_cb'    => false,
					'depth'          => 0,
					'echo'           => false,
					// Renders 2nd/3rd level as the Figma megamenu panel instead of
					// nested <ul class="sub-menu">. See the class for how menu depth
					// maps onto columns and cards.
					'walker'         => new Thinksme_Nav_Walker(),
				)
			);

			if ( $menu ) {
				echo $menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_nav_menu output.
			} else {
				?>
				<ul class="site-header__menu">
					<?php foreach ( $fallback_items as $item ) : ?>
						<li><span><?php echo esc_html( $item ); ?></span></li>
					<?php endforeach; ?>
				</ul>
				<?php
			}
			?>

			<!-- Mobile only: header-nav.js moves the tapped item's .megamenu
			     here so the CSS can hide the rest of .site-header__menu without
			     hiding the panel it's nested inside; moved back on "back"/close. -->
			<div class="site-header__submenu-slot"></div>
		</div>

		<a
			href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"
			class="site-header__call rounded-pill bg-brand-yellow h-[50px] pl-lg pr-[6px] flex items-center justify-between gap-sm lg:hidden"
		>
			<span class="font-medium text-sm text-text-primary"><?php esc_html_e( 'Call Us', 'thinksme' ); ?></span>
			<span class="bg-surface-white rounded-pill size-[38px] inline-flex items-center justify-center shrink-0">
				<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[20px]">
			</span>
		</a>
	</nav>

	<a
		href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"
		class="btn-outline border border-border-medium rounded-sm h-[50px] px-lg hidden lg:inline-flex items-center justify-center shrink-0 font-medium text-sm whitespace-nowrap"
	><?php esc_html_e( 'Call Us', 'thinksme' ); ?></a>
</header>
