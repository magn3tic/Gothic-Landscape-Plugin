<?php
/**
 * Template: Selections Step Access
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Templates / Selection Steps
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// }gothic-landscape-selections\templates\selections\Captcha
require_once(__DIR__.'/captcha/recaptchalib.php');
$publickey = get_field("site_key", "options");

?>
	<script src='https://www.google.com/recaptcha/api.js' async defer></script>
	<h1><?php esc_html_e( 'Access Landscape Selections', 'gothic-selections' ); ?></h1>
	<p>
		<?php esc_html_e( 'Please select the option below that best describes how you will access the Landscape Selections system.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'If you\'re not sure, selecting an option will reveal further instructions.', 'gothic-selections' ); ?>
	</p>

	<div class="access">
		<div class="select_method">
			<form method="post" class="landscape-selection-form select-login">
				<?php
				gothic_selections_form_field( [
					'type'    => 'radio',
					'label'   => null,
					'name'    => 'login_type',
					'class'   => [ 'select',  'access-select' ],
					'options' => [
						'existing' => __( 'I have an access code', 'gothic-selections' ),
						'new'      => __( 'I don\'t have an access code', 'gothic-selections' ),
						'login'    => __( 'I represent a home builder', 'gothic-selections' ),
					],
				], true );
				?>
				
				<input type="radio" id="login_type-reset" name="login_type" value="reset">
			</form>

		</div>
		<?php $nonce = wp_nonce_field( 'preferences-request', 'preferences-request', 'preferences-request', false ); ?>

		<div class="login_type_forms">
			<form id="landscape-selection-form-existing" class="landscape-selection-form" method="post"
				<?php echo isset( $request['_existing'] ) ? '' : 'style="display:none;"'; ?> >

				<h3><?php esc_html_e( 'Access an Existing Landscape Selections Order', 'gothic-selections' ); ?></h3>

				<p><?php esc_html_e( 'Provide your email address and access code to access your existing Landscape Selections Order.', 'gothic-selections' ); ?></p>

				<?php if ( ! empty( $errors ) && isset( $errors['existing_invalid'] ) ) : ?>
					<p class="error"><?php echo esc_html( $errors['existing_invalid'] ); ?></p>
				<?php endif; ?>

				<?php
				echo $nonce;

				gothic_selections_form_field( array(
					'type'  => 'hidden',
					'name'  => '_current_step',
					'value' => $screen,
				), true );

				gothic_selections_form_field( array(
					'type'       => 'email',
					'template'   => 'text',
					'label'      => __( 'Your Email Address', 'gothic-selections' ),
					'name'       => 'email',
					'value'      => ! empty( $request['email'] ) ? sanitize_email( $request['email'] ) : null,
					'errors'     => $errors,
					'attributes' => array(
						'autocomplete' => 'off',
						'required'     => true,
					),
				), true );
				gothic_selections_form_field( array(
					'type'       => 'text',
					'label'      => __( 'Your Access Code', 'gothic-selections' ),
					'name'       => 'access_code',
					'value'      => ! empty( $request['access_code'] ) ? sanitize_text_field( $request['access_code'] ) : null,
					'errors'     => $errors,
					'attributes' => array(
						'autocomplete' => 'off',
						'required'     => true,
					),
				), true );
				?>
				<p>
				
				<div class="g-recaptcha" data-sitekey="<?= $publickey; ?>"></div>
					<button name="_existing"><?php esc_html_e( 'Continue', 'gothic-selections' ); ?></button>
				</p>
			</form>
			<form id="landscape-selection-form-new" class="landscape-selection-form" method="post"
				<?php echo isset( $request['_new'] ) ? '' : 'style="display:none;"'; ?>>

				<h3><?php esc_html_e( 'Lookup or Create a New Landscape Selections Order', 'gothic-selections' ); ?></h3>

				<p>
					<?php esc_html_e( 'Enter your email address and lot number in the form below.', 'gothic-selections' ); ?>
					<?php esc_html_e( 'We\'ll check to see if we have an existing Selections Order for you, and if so, we\'ll send you an invitation to continue an existing order, or if not, create a new one for you.', 'gothic-selections' ); ?>
				</p>

				<?php if ( ! empty( $errors ) && isset( $errors['new_invalid'] ) ) : ?>
					<p class="error"><?php echo esc_html( $errors['new_invalid'] ); ?></p>
				<?php endif; ?>

				<?php

				echo $nonce;

				gothic_selections_form_field( array(
					'type'  => 'hidden',
					'name'  => '_current_step',
					'value' => $screen,
				), true );

				gothic_selections_form_field( array(
					'type'       => 'email',
					'template'   => 'text',
					'label'      => __( 'Your Email Address', 'gothic-selections' ),
					'name'       => 'new_email',
					'value'      => ! empty( $request['new_email'] ) ? sanitize_email( $request['new_email'] ) : null,
					'errors'     => $errors,
					'attributes' => array(
						'autocomplete' => 'off',
						'required'     => true,
					),
				), true );
				gothic_selections_form_field( [
					'type'       => 'text',
					'label'      => __( 'Your New Home Lot', 'gothic-selections' ),
					'name'       => 'new_lot',
					'value'      => ! empty( $request['new_lot'] ) ? sanitize_email( $request['new_lot'] ) : null,
					'errors'     => $errors,
					'attributes' => [
						'autocomplete' => 'off',
						'required'     => true,
					],
				], true );
				?>
				

				<div class="field">
					<div class="g-recaptcha" data-sitekey="<?= $publickey; ?>"></div>
					<?php if ( ! empty( $errors ) && isset( $errors['captcha'] ) ) : ?>
						<p class="error d-block"><?php echo esc_html( $errors['captcha'] ); ?></p>
					<?php endif; ?>
				</div>
				<button name="_new"><?php esc_html_e( 'Search or Start New', 'gothic-selection' ); ?></button>

			</form>
			<form id="landscape-selection-form-login" class="landscape-selection-form" method="post"
				<?php echo isset( $request['_login'] ) ? '' : 'style="display:none;"'; ?>>

				<h3><?php esc_html_e( 'Access Landscape Selections Management', 'gothic-selections' ); ?></h3>

				<p>
					<?php esc_html_e( 'Welcome back existing Gothic Landscape or Homebuilder user!', 'gothic-selections' ); ?>
					<?php esc_html_e( 'Please enter your login credentials to access the system.', 'gothic-selections' ); ?>
				</p>
				<?php if ( ! empty( $errors ) && isset( $errors['login_invalid'] ) ) : ?>
					<div>
						<p class="error" style="color: red;"><?php echo esc_html( $errors['login_invalid'] ); ?></p>
					</div>
				<?php endif; ?>

				<?php

				echo $nonce;

				gothic_selections_form_field( array(
					'type'  => 'hidden',
					'name'  => '_current_step',
					'value' => $screen,
				), true );

				gothic_selections_form_field( array(
					'type'  => 'hidden',
					'name'  => 'action',
					'value' => 'log-in',
				), true );

				gothic_selections_form_field( array(
					'type'       => 'text',
					'label'      => __( 'Email', 'gothic-selections' ),
					'name'       => 'uname',
					'value'      => ! empty( $request['uname'] ) ? sanitize_text_field( $request['uname'] ) : null,
					'errors'     => $errors,
					'attributes' => array(
						'autocomplete' => 'off',
						'required'     => true,
					),
				), true );
				gothic_selections_form_field( array(
					'type'       => 'password',
					'template'   => 'text',
					'label'      => __( 'Password', 'gothic-selections' ),
					'name'       => 'pword',
					'value'      => null,
					'errors'     => $errors,
					'attributes' => array(
						'autocomplete' => 'off',
						'required'     => true,
					),
				), true );
				?>
				<div class="field">
					<div class="g-recaptcha" data-sitekey="<?= $publickey; ?>"></div>
					<?php if ( ! empty( $errors ) && isset( $errors['captcha'] ) ) : ?>
						<p class="error d-block"><?php echo esc_html( $errors['captcha'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="d-flex">

				<button name="_login"><?php esc_html_e( 'Login', 'gothic-selections' ); ?></button>
					<div class="radio-option c-mx-2 c-mt-4">
						<label for="login_type-reset" class="crunch-button crunch-button__full-background crunch-button__full-background--primary-color crunch-button__full-background--medium">Reset Password</label>
					</div>
				</div>

			</form>

			<form id="landscape-selection-form-reset" class="landscape-selection-form" action="<?php echo wp_lostpassword_url(); ?>" method="post" style="display:none;"> 
				<h3><?php _e('Forgot Your Password?', 'gothic-selections'); ?></h3>

				<p>
					<?php
					_e(
						"Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.",
						'personalize_login'
					);
					?>
				</p>
				<p class="form-row">
					<label for="user_login"><?php _e('Email', 'gothic-selections'); ?>
					<input type="text" name="user_login" id="user_login">
				</p>

				<p class="lostpassword-submit">
					<button type="submit" name="submit" class="lostpassword-button"><?php _e('Reset Password', 'gothic-selections'); ?></button>
				</p>
			</form>
		</div>
	</div>
