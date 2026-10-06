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
		<div class="flex flex-col xl:flex-row items-start gap-3xl xl:gap-2xl">
			<div class="flex flex-col gap-lg items-start w-full xl:w-[300px] shrink-0">
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

			<div class="flex flex-col sm:flex-row sm:flex-wrap gap-xl items-start xl:flex-1 xl:flex-nowrap xl:justify-between">
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
	// Amendment #10 (DONE) — floating WhatsApp button, site-wide via this footer. The number is
	// set in Appearance > Customize > Site Details ("WhatsApp number", theme_mod
	// thinksme_whatsapp_number, international format, currently 6560129642). It only renders once a
	// number is set. To add a pre-filled message later, append "?text=..." to the wa.me URL below.
	$whatsapp_number = get_theme_mod( 'thinksme_whatsapp_number', '' );
	if ( $whatsapp_number ) :
		?>
		<a
			id="whatsapp-float"
			href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $whatsapp_number ) ); ?>"
			class="fixed bottom-8 right-8 z-50 flex items-center justify-center rounded-pill size-[58px]"
			style="background-color: #25D366;"
			target="_blank"
			rel="noopener noreferrer"
			aria-label="<?php esc_attr_e( 'Chat with Think SME on WhatsApp', 'thinksme' ); ?>"
		>
<?php // Official WhatsApp glyph from Simple Icons (https://simpleicons.org, CC0), inline. Amendment #10. ?>
			<svg class="block size-[28px]" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
			</svg>
		</a>
	<?php endif; ?>

	<?php wp_footer(); ?>
</body>
</html>
