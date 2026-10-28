<?php
/**
 * Contact page opener — headline, e-mail/phone rows, and the enquiry form.
 * Figma: node 62:42, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Contact page): contact_title (basic HTML allowed — <strong> carries the
 * bold half of the headline, matching hero.php), contact_text, contact_email,
 * contact_phone, contact_privacy_url, contact_submit_text.
 *
 * The enquiry form is Pepper Cloud's hosted form (CRM), embedded as an iframe
 * — Amendment #13. Submissions go straight to Pepper Cloud; WordPress no longer
 * handles them. The small inline script sends the parent page URL/title to the
 * iframe so Pepper Cloud can attribute each lead to the page it came from.
 * thinksme_handle_contact_submission() in functions.php is now unused here.
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$peppercloud_form = 'https://app.peppercloud.com/form/03e8c82d-8aba-40c7-90a4-3e134b076bcd/embed';

$email = thinksme_field( 'contact_email', false, 'hello@thinksme.sg' );
$phone = thinksme_field( 'contact_phone', false, '(+65) 6012 9642' );
// tel: needs the bare number; the displayed form keeps Figma's punctuation.
$phone_link  = preg_replace( '/[^0-9+]/', '', $phone );

$details = array(
	array(
		'icon'  => 'envelope.svg',
		'label' => __( 'Email', 'thinksme' ),
		'value' => $email,
		'href'  => 'mailto:' . $email,
	),
	array(
		'icon'  => 'phone.svg',
		'label' => __( 'Call us', 'thinksme' ),
		'value' => $phone,
		'href'  => 'tel:' . $phone_link,
	),
);
?>
<section id="contact-form" class="flex flex-col lg:flex-row lg:items-start gap-3xl lg:gap-[109px] w-full px-lg lg:px-3xl py-xl lg:py-[40px]">
	<div class="flex flex-col gap-3xl lg:gap-[64px] w-full lg:w-[624px] shrink-0">
		<div class="flex flex-col justify-center gap-xl w-full">
			<div class="relative">
				<?php // Brush stroke under the bold half of the headline. Desktop only: its placement is tied to the 72px type breaking across exactly four lines. ?>
				<img
					src="<?php echo esc_url( "$icons_uri/contact-underline.svg" ); ?>"
					alt=""
					aria-hidden="true"
					class="hidden lg:block absolute left-0 top-[96%] w-[95%] rotate-[1.74deg] pointer-events-none"
				>
				<h1 class="relative font-medium text-[40px] sm:text-[56px] lg:text-[72px] leading-tight tracking-hero text-text-primary">
					<?php echo wp_kses_post( thinksme_field( 'contact_title', false, 'Contact Accounting Professionals and <strong>Taxation Experts</strong>' ) ); ?>
				</h1>
			</div>

			<p class="font-normal text-sm lg:text-md text-text-secondary leading-normal tracking-wide">
				<?php echo esc_html( thinksme_field( 'contact_text', false, 'We are here to help you succeed. Our team of experienced consultants is on hand to discuss your goals for growth, and how we can help you achieve them.' ) ); ?>
			</p>
		</div>

		<div class="flex flex-col gap-xl lg:gap-[48px] items-start">
			<?php foreach ( $details as $detail ) : ?>
				<div class="flex gap-lg items-center">
					<span class="bg-brand-yellow rounded-pill size-[64px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( "$icons_uri/{$detail['icon']}" ); ?>" alt="" class="size-[32px]">
					</span>
					<span class="flex flex-col gap-xs justify-center">
						<span class="font-normal text-sm text-text-secondary leading-normal tracking-wide"><?php echo esc_html( $detail['label'] ); ?></span>
						<a href="<?php echo esc_url( $detail['href'] ); ?>" class="font-medium text-xl lg:text-[32px] leading-tight tracking-hero text-text-primary break-words">
							<?php echo esc_html( $detail['value'] ); ?>
						</a>
					</span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php // Figma draws the card at a fixed 547px, which left a gap at the right edge on anything wider than the 1440px frame. It fills the column instead. ?>
	<div class="bg-surface-light rounded-lg p-lg lg:p-xl w-full lg:flex-1 lg:min-w-0">
		<div>
			<iframe
				referrerpolicy="unsafe-url"
				id="pc-iframe"
				src="<?php echo esc_url( $peppercloud_form ); ?>"
				title="<?php esc_attr_e( 'Contact form', 'thinksme' ); ?>"
				style="display:block;width:100%;height:600px;border:0;"
			></iframe>
		</div>
		<script>
			( function () {
				const pcIframe = document.getElementById( 'pc-iframe' );
				if ( ! pcIframe ) { return; }
				pcIframe.onload = () => {
					pcIframe.contentWindow.postMessage(
						{
							type: 'pc-parent-info',
							url: window.location.href,
							title: document.title,
						},
						'*'
					);
				};
			} )();
		</script>
	</div>
</section>
