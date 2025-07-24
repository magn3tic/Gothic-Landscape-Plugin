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
?>
	<h1><?php esc_html_e( 'Access Landscape Selections', 'gothic-selections' ); ?></h1>
	<p><?php esc_html_e( 'Please select the option below that best describes how you will access the Landscape Selections system.', 'gothic-selections' ); ?></p>
	
	<div class="login">

		<div class="select_method">
			<form method="post" class="landscape-selection-form select-login">
				<div>
					<label for="type">
						<input type="radio" id="login_type-existing"  name="login_type" value="existing" <?php checked( isset( $request['_existing'] ), true ); ?>>
						<?php esc_html_e( 'I am a home buyer and I have an access code inviting me to access the system.', 'gothic-selections' ); ?>
					</label>
				</div>

				<div>
					<label for="type">
						<input type="radio" id="login_type-new" name="login_type" value="new" <?php checked( isset( $request['_new'] ), true ); ?>>
						<?php esc_html_e( 'I am a home buyer but I do not have an access code.', 'gothic-selections' ); ?>
					</label>
				</div>

				<div>
					<label for="type">
						<input type="radio" id="login_type-login"  name="login_type" value="login">
						<?php esc_html_e( 'I work with a home seller or Gothic Landscape.', 'gothic-selections' ); ?>
					</label>
				</div>
			</form>

		</div>
		<?php $nonce = wp_nonce_field( 'preferences-request', 'preferences-request', 'preferences-request', false ); ?>

		<div class="login_type_forms">
			<form id="landscape-selection-form-existing" class="landscape-selection-form" method="post"
				<?php echo isset( $request['_existing'] ) ? '' : 'style="display:none;"'; ?> >

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
					<button name="_existing"><?php esc_html_e( 'Continue', 'gothic-selections' ); ?></button>
				</p>
			</form>
			<form id="landscape-selection-form-new" class="landscape-selection-form" method="post"
				<?php echo isset( $request['_new'] ) ? '' : 'style="display:none;"'; ?>>

				<?php if ( ! empty( $errors ) && isset( $errors['new_invalid'] ) ) : ?>
					<p class="error"><?php echo esc_html( $errors['new_invalid'] ); ?></p>
				<?php endif; ?>
				<p>
					<?php

					echo $nonce;

					gothic_selections_form_field( array(
						'type'  => 'hidden',
						'name'  => '_current_step',
						'value' => $screen,
					), true );

					gothic_selections_form_field( array(
						'type'       => 'text',
						'label'      => __( 'Your Name(s)', 'gothic-selections' ),
						'name'       => 'new_name',
						'value'      => ! empty( $request['new_name'] ) ? sanitize_text_field( $request['new_name'] ) : null,
						'errors'     => $errors,
						'attributes' => array(
							'autocomplete' => 'off',
							'minlength'    => 3,
							'required'     => true,
						),
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
					?>
					<button name="_new">Start New</button>
				</p>
			</form>
			<form id="landscape-selection-form-login" class="landscape-selection-form" method="post"
				<?php echo isset( $request['_login'] ) ? '' : 'style="display:none;"'; ?>>

				<?php if ( ! empty( $errors ) && isset( $errors['login_invalid'] ) ) : ?>
					<p class="error"><?php echo esc_html( $errors['login_invalid'] ); ?></p>
				<?php endif; ?>
				<p>
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
						'label'      => __( 'Username or Email', 'gothic-selections' ),
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
					<button name="_login">Login</button>
				</p>
			</form>
		</div>
	</div>
<?php
