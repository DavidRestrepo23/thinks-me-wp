<?php
/**
 * Footer: logo, tagline, social links, 3 nav columns (wp_nav_menu — client
 * edits via Appearance > Menus), plus the floating WhatsApp button.
 * Figma: node 27:2145 (Footer, desktop), node 773:16353 (WhatsApp float button).
 *
 * Tagline, social URLs, WhatsApp number and the header phone are site-wide
 * chrome rather than per-page content, so they're theme_mods edited from
 * Appearance > Customize > Site Details (see functions.php,
 * thinksme_customize_register()) instead of ACF fields on the Home page.
 *
 * The two social circles always render so the footer matches the design before
 * the URLs are filled in; without a URL the circle is a plain span rather than
 * a link to nowhere.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$socials = array(
	'facebook' => array(
		'label' => __( 'Facebook', 'thinksme' ),
		'url'   => get_theme_mod( 'thinksme_facebook_url', '' ),
		'icon'  => "$icons_uri/social-facebook.svg",
		'size'  => 'size-[20px]',
	),
	'linkedin' => array(
		'label' => __( 'LinkedIn', 'thinksme' ),
		'url'   => get_theme_mod( 'thinksme_linkedin_url', '' ),
		'icon'  => "$icons_uri/social-linkedin.svg",
		'size'  => 'size-[24px]',
	),
);

// 'fallback' is the item list from the Figma frame, shown as plain text until
// the client assigns a real menu to that location in Appearance > Menus. Text,
// not links, so nothing points at a page that doesn't exist yet.
$footer_columns = array(
	array(
		'location' => 'footer-1',
		'title'    => __( 'Corporate Service', 'thinksme' ),
		'width'    => 'lg:w-[234px]',
		'fallback' => array(
			'Company Incorporation - Local',
			'Company Incorporation - Foreigner',
			'Corporate Secretary',
			'Registered Address',
			'Accounting & Book-keeping',
			'GST Registration & Filing',
			'Corporate Income Tax',
		),
	),
	array(
		'location' => 'footer-2',
		'title'    => __( 'Finance & Loan', 'thinksme' ),
		'width'    => 'lg:w-[190px]',
		'fallback' => array(
			'SME Business Loan',
			'Property Cashout',
			'Mortgage Loan',
			'Remittance',
		),
	),
	array(
		'location' => 'footer-3',
		'title'    => __( 'Grants & Company', 'thinksme' ),
		'width'    => 'lg:w-[238px]',
		'fallback' => array(
			'PSG Xero Grant',
			'EDG Grant',
			'MRA Grant',
			'About Think SME',
			'Blog',
			'Contact Us',
		),
	),
);

$footer_menu_class = 'site-footer__menu flex flex-col gap-md items-start text-text-secondary text-xs leading-[20px]';
?>

	<footer id="site-footer" class="bg-surface-white w-full pt-[40px] pb-[56px] px-lg lg:px-3xl">
		<div class="flex flex-col lg:flex-row items-start gap-3xl lg:gap-4xl">
			<div class="flex flex-col gap-lg items-start w-full lg:w-[340px] shrink-0">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo block">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<img
							src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-thinksme-footer.svg' ); ?>"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							class="h-[76px] w-[237px]"
						>
					<?php endif; ?>
				</a>

				<p class="text-text-secondary text-sm leading-normal tracking-wide">
					<?php echo esc_html( get_theme_mod( 'thinksme_footer_tagline', 'Your Partner in Business Success. We are committed to help businesses grow, succeed and prosper.' ) ); ?>
				</p>

				<div class="flex gap-md items-start">
					<?php foreach ( $socials as $network => $social ) : ?>
						<?php
						$circle_class = 'site-footer__social border border-border-light rounded-pill flex items-center justify-center p-md';
						$icon         = '<img src="' . esc_url( $social['icon'] ) . '" alt="" class="' . esc_attr( $social['size'] ) . '">';
						?>
						<?php if ( $social['url'] ) : ?>
							<a
								href="<?php echo esc_url( $social['url'] ); ?>"
								class="<?php echo esc_attr( $circle_class ); ?>"
								target="_blank"
								rel="noopener noreferrer"
								aria-label="<?php
									/* translators: %s: social network name. */
									echo esc_attr( sprintf( __( 'Think SME on %s', 'thinksme' ), $social['label'] ) );
								?>"
							>
								<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts. ?>
							</a>
						<?php else : ?>
							<span class="<?php echo esc_attr( $circle_class ); ?>" aria-hidden="true">
								<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts. ?>
							</span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="flex flex-col sm:flex-row sm:flex-wrap gap-xl lg:gap-3xl items-start">
				<?php foreach ( $footer_columns as $column ) : ?>
					<div class="flex flex-col gap-lg items-start w-full <?php echo esc_attr( $column['width'] ); ?>">
						<?php // nowrap: Figma keeps each heading on one line, and Charlevoix Bold at 20px + 1px tracking is a few px wider than the column. ?>
						<p class="font-bold text-lg text-text-heading-dark uppercase leading-[16px] tracking-widest whitespace-nowrap">
							<?php echo esc_html( $column['title'] ); ?>
						</p>
						<?php
						$menu = wp_nav_menu(
							array(
								'theme_location' => $column['location'],
								'container'      => false,
								'menu_class'     => $footer_menu_class,
								'fallback_cb'    => false,
								'depth'          => 1,
								'echo'           => false,
							)
						);

						if ( $menu ) {
							echo $menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_nav_menu output.
						} else {
							?>
							<ul class="<?php echo esc_attr( $footer_menu_class ); ?>">
								<?php foreach ( $column['fallback'] as $item ) : ?>
									<li><?php echo esc_html( $item ); ?></li>
								<?php endforeach; ?>
							</ul>
							<?php
						}
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</footer>

	<?php
	$whatsapp_number = get_theme_mod( 'thinksme_whatsapp_number', '' );
	if ( $whatsapp_number ) :
		?>
		<a
			id="whatsapp-float"
			href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $whatsapp_number ) ); ?>"
			class="fixed bottom-8 right-8 z-50 flex items-center justify-center rounded-pill bg-success size-[58px]"
			target="_blank"
			rel="noopener noreferrer"
			aria-label="<?php esc_attr_e( 'Chat with Think SME on WhatsApp', 'thinksme' ); ?>"
		>
			<span class="block size-[28px]"></span>
		</a>
	<?php endif; ?>

	<?php wp_footer(); ?>
</body>
</html>
