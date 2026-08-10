<?php
/**
 * Contact page opener — headline, e-mail/phone rows, and the enquiry form.
 * Figma: node 62:42, file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * ACF (Contact page): contact_title (basic HTML allowed — <strong> carries the
 * bold half of the headline, matching hero.php), contact_text, contact_email,
 * contact_phone, contact_privacy_url, contact_submit_text.
 *
 * The form posts back to this page; validation, mail delivery and the
 * redirect-after-send live in thinksme_handle_contact_submission()
 * (functions.php), which also owns the field list rendered below.
 *
 * Each field is a pill with its label sitting inside it, as Figma draws it —
 * the label doubles as the placeholder and fades on focus/input (src/base.css).
 */

$icons_uri = get_template_directory_uri() . '/assets/images/icons';

$state  = thinksme_contact_state();
$fields = thinksme_contact_fields();
$errors = $state['errors'];
$values = $state['values'];

$email = thinksme_field( 'contact_email', false, 'hello@thinksme.sg' );
$phone = thinksme_field( 'contact_phone', false, '(+65) 6012 9642' );
// tel: needs the bare number; the displayed form keeps Figma's punctuation.
$phone_link  = preg_replace( '/[^0-9+]/', '', $phone );
$privacy_url = thinksme_field( 'contact_privacy_url', false, '' );

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
		<?php if ( $state['sent'] ) : ?>
			<p class="font-medium text-md text-text-primary leading-normal" role="status">
				<?php esc_html_e( 'Thank you — your message is on its way. We will be in touch shortly.', 'thinksme' ); ?>
			</p>
		<?php else : ?>
			<?php // novalidate: the server-side checks in functions.php are the ones that run, so the browser shouldn't block the post with a second, differently-worded set. ?>
			<form method="post" action="<?php echo esc_url( get_permalink() ); ?>#contact-form" class="relative flex flex-col gap-xl w-full" novalidate>
				<?php wp_nonce_field( 'thinksme_contact', 'thinksme_contact_nonce' ); ?>

				<?php if ( ! empty( $errors['form'] ) ) : ?>
					<p class="contact-form__error" role="alert"><?php echo esc_html( $errors['form'] ); ?></p>
				<?php endif; ?>

				<div class="flex flex-col gap-md w-full">
					<?php
					foreach ( $fields as $key => $field ) :
						$id        = "thinksme-contact-$key";
						$name      = "thinksme_contact_$key";
						$value     = isset( $values[ $key ] ) ? $values[ $key ] : '';
						$has_error = ! empty( $errors[ $key ] );
						?>
						<div class="contact-field<?php echo $has_error ? ' contact-field--invalid' : ''; ?>">
							<?php if ( 'textarea' === $field['type'] ) : ?>
								<textarea
									id="<?php echo esc_attr( $id ); ?>"
									name="<?php echo esc_attr( $name ); ?>"
									placeholder=" "
									required
									aria-required="true"
									<?php echo $has_error ? ' aria-describedby="' . esc_attr( "$id-error" ) . '"' : ''; ?>
								><?php echo esc_textarea( $value ); ?></textarea>
							<?php else : ?>
								<input
									type="<?php echo esc_attr( $field['type'] ); ?>"
									id="<?php echo esc_attr( $id ); ?>"
									name="<?php echo esc_attr( $name ); ?>"
									value="<?php echo esc_attr( $value ); ?>"
									placeholder=" "
									required
									aria-required="true"
									<?php echo $has_error ? ' aria-describedby="' . esc_attr( "$id-error" ) . '"' : ''; ?>
								>
							<?php endif; ?>
							<label for="<?php echo esc_attr( $id ); ?>">
								<?php echo esc_html( $field['label'] ); ?>
								<span class="contact-field__required" aria-hidden="true">*</span>
							</label>
							<?php if ( $has_error ) : ?>
								<p id="<?php echo esc_attr( "$id-error" ); ?>" class="contact-form__error mt-xs pl-lg"><?php echo esc_html( $errors[ $key ] ); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="flex flex-col gap-xs">
					<div class="contact-consent flex gap-md items-start">
						<input type="checkbox" id="thinksme-contact-consent" name="thinksme_contact_consent" value="1" required aria-required="true"<?php echo empty( $errors['consent'] ) ? '' : ' aria-describedby="thinksme-contact-consent-error"'; ?>>
						<label for="thinksme-contact-consent" class="font-normal text-sm text-text-secondary leading-normal tracking-wide">
							<?php esc_html_e( 'I confirm and agree to the storing and processing of my personal data as described in the', 'thinksme' ); ?>
							<?php if ( $privacy_url ) : ?>
								<a href="<?php echo esc_url( $privacy_url ); ?>" class="underline"><?php esc_html_e( 'Privacy Statement.', 'thinksme' ); ?></a>
							<?php else : ?>
								<span class="underline"><?php esc_html_e( 'Privacy Statement.', 'thinksme' ); ?></span>
							<?php endif; ?>
						</label>
					</div>
					<?php if ( ! empty( $errors['consent'] ) ) : ?>
						<p id="thinksme-contact-consent-error" class="contact-form__error"><?php echo esc_html( $errors['consent'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php // Honeypot. Off-screen rather than display:none — some bots skip hidden fields, which is exactly the check we're making. ?>
				<div class="absolute -left-[9999px] w-px h-px overflow-hidden" aria-hidden="true">
					<label for="thinksme-contact-website"><?php esc_html_e( 'Website', 'thinksme' ); ?></label>
					<input type="text" id="thinksme-contact-website" name="thinksme_contact_website" tabindex="-1" autocomplete="off">
				</div>

				<button type="submit" name="thinksme_contact_submit" value="1" class="btn-split flex items-center w-full">
					<span class="bg-brand-yellow rounded-sm h-[50px] flex-1 inline-flex items-center justify-center text-sm font-medium text-text-primary whitespace-nowrap">
						<?php echo esc_html( thinksme_field( 'contact_submit_text', false, 'Submit' ) ); ?>
					</span>
					<span class="bg-brand-yellow rounded-sm size-[50px] inline-flex items-center justify-center shrink-0">
						<img src="<?php echo esc_url( "$icons_uri/arrow-up-right-dark.svg" ); ?>" alt="" class="size-[24px]">
					</span>
				</button>
			</form>
		<?php endif; ?>
	</div>
</section>
