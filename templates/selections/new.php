<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Gothic\Selections\Helper\Queries;

/**
 * Defined before includes:
 *
 * @var array $errors
 * @var array $request
 * @var array $data
 */

$user        = in_array( 'gothic-salesperson', get_userdata( get_current_user_id() )->roles, true ) || in_array( 'gothic-salesmanager', get_userdata( get_current_user_id() )->roles, true ) ? get_userdata( get_current_user_id() ) : null;
$homebuilder = $user ? get_user_meta( $user->ID, 'gothic_user_homebuilder', true ) : false;

?>
<div class="initiate">

	<h1><?php esc_html_e( 'Initiate New Selections Order', 'gothic-selections' ); ?></h1>

	<p class="c-pt-4">
		<?php esc_html_e( 'Start a new Landscape Selection Order for a home buyer.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'The home buyer will be sent an email inviting them to complete their order.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'This process is designed to simplify and expedite collecting your home buyer\'s response.', 'gothic-selections' ); ?>
	</p>

	<?php if ( ! empty( $errors['invalid'] ) ) : ?>
		<div class="invalid-notice">
			<p><?php echo esc_html( $errors['invalid'] ); ?></p>
		</div>
	<?php endif; ?>

	<h4 class="selections-form-group-header"><?php esc_html_e( 'Homebuyer Contact Information', 'gothic-selections' ); ?></h4>

	<?php

	gothic_selections_form_field( [
		'type'       => 'text',
		'label'      => __( 'Home buyer Full Name(s)', 'gothic-selections' ),
		'name'       => 'home_buyer',
		'value'      => gothic_selections_form_value( 'home_buyer', $request ),
		'attributes' => [
			'required'     => true,
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
	], true );
	gothic_selections_form_field( [
		'type'       => 'email',
		'template'   => 'text',
		'label'      => __( 'Home buyer Email Address', 'gothic-selections' ),
		'name'       => 'email',
		'value'      => gothic_selections_form_value( 'email', $request, 'sanitize_email' ),
		'attributes' => [
			'required'     => true,
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
	], true );
	gothic_selections_form_field( [
		'type'       => 'tel',
		'template'   => 'text',
		'label'      => __( 'Home buyer Phone Number', 'gothic-selections' ),
		'name'       => 'phone',
		'value'      => gothic_selections_form_value( 'phone', $request ),
		'attributes' => [
			'required'     => true,
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
	], true );
	?>
	<h4 class="selections-form-group-header"><?php esc_html_e( 'New Home Address', 'gothic-selections' ); ?></h4>
	<?php
	gothic_selections_form_field( [
		'type'       => 'text',
		'label'      => __( 'Homebuyer New Home Address', 'gothic-selections' ),
		'name'       => 'address',
		'value'      => gothic_selections_form_value( 'address', $request ),
		'attributes' => [
			'required'     => true,
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
	], true );
	gothic_selections_form_field( [
		'type'       => 'text',
		'label'      => __( 'Homebuyer New Home City', 'gothic-selections' ),
		'name'       => 'city',
		'value'      => gothic_selections_form_value( 'city', $request ),
		'attributes' => [
			'required'     => true,
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
	], true );
	?>
	<div class="field-group">
		<?php
		gothic_selections_form_field( [
			'type'       => 'text',
			'label'      => __( 'Homebuyer New Home State (Abbv)', 'gothic-selections' ),
			'name'       => 'state',
			'value'      => gothic_selections_form_value( 'state', $request ),
			'class'      => 'state',
			'attributes' => [
				'required' => true,
				'autocomplete' => 'off',
				'minlength' => 2,
				'maxlength' => 2,
			],
		], true );
		gothic_selections_form_field( [
			'type'       => 'number',
			'template'   => 'text',
			'label'      => __( 'Homebuyer New Home ZIP Code', 'gothic-selections' ),
			'name'       => 'zip',
			'value'      => gothic_selections_form_value( 'zip', $request, 'intval' ),
			'class'      => 'zip',
			'attributes' => [
				'required' => true,
				'autocomplete' => 'off',
				'minlength' => 5,
			],
		], true );
		?>
	</div>

	<?php
	gothic_selections_form_field( [
		'type'       => 'number',
		'template'   => 'text',
		'label'      => __( 'Lot Number', 'gothic-selections' ),
		'name'       => 'lot',
		'value'      => gothic_selections_form_value( 'lot', $request, 'intval' ),
		'attributes' => [
			'autocomplete' => 'off',
			'required'     => true,
		],
		'errors'     => $errors,
	], true );

	?>
	<h4 class="selections-form-group-header"><?php esc_html_e( 'Community & Purchase Information', 'selections' ); ?></h4>
	<p class="selections-form-group-description">
		<?php esc_html_e( 'Please provide the information related to the contractual details of this home purchase.', 'gothic-selections' );?>
		<?php esc_html_e( 'Your home buyer will be shown upgrade options, but will not be able to select them, and it is recommended that you coach them through the options prior to completing this form.', 'gothic-selections' ); ?>
	</p>
	<?php

	if ( $homebuilder ) {

		gothic_selections_form_field( [
			'type'  => 'hidden',
			'name'  => 'builder_id',
			'value' => $homebuilder,
		], true );

	} else {
		gothic_selections_form_field( [
			'type'  => 'select-builder',
			'label' => __( 'Select Homebuilder', 'gothic-selections' ),
			'name'  => 'builder_id',
			'value' => gothic_selections_form_value( 'builder_id', $request, 'intval' ),
			'attributes' => [
				'autocomplete' => false,
				'required'     => true,
				'data-rule-notEqual' => "-1",
			],
			'class' => 'dynamic-select',
			'errors'     => $errors,
		], true );
	}

	$community_id = gothic_selections_form_value( 'community_id', $request, 'intval' );

	gothic_selections_form_field( [
		'type'       => 'select-community',
		'label'      => __( 'Select Community', 'gothic-selections' ),
		'name'       => 'community_id',
		'value'      => $community_id,
		'attributes' => [
			'autocomplete' => 'off',
			'required'     => true,
			'data-builder' => $homebuilder ?: gothic_selections_form_value( 'builder_id', $request, 'intval' ),
			'data-rule-notEqual' => "-1",
		],
		'class' => 'dynamic-select',
		'errors'     => $errors,
	], true );

	gothic_selections_form_field( [
		'type'       => 'select-model',
		'label'      => __( 'Select Model', 'gothic-selections' ),
		'name'       => 'model_id',
		'value'      => gothic_selections_form_value( 'model_id', $request, 'intval' ),
		'attributes' => [
			'autocomplete'       => 'off',
			'required'           => true,
			'data-builder'       => $homebuilder ?: gothic_selections_form_value( 'builder_id', $request, 'intval' ),
			'data-community'     => $community_id,
			'data-rule-notEqual' => "-1",
		],
		'class' => 'dynamic-select',
		'errors'     => $errors,
	], true );

	if ( $community_id ) {
		list( $front, $back ) = array_values( Queries::packages_meta( (int) $community_id ) );
	} else {
		list( $front, $back ) = [ false, false ];
	}

	$show_front = ( $front || gothic_selections_form_value( '_packages', $request, 'intval' ) || ! empty( $errors['_packages'] ) );

	gothic_selections_form_field( [
		'type'       => 'select-package',
		'label'      => __( 'Select Front Yard Package', 'gothic-selections' ),
		'name'       => 'package_id',
		'value'      => gothic_selections_form_value( 'package_id', $request, 'intval' ),
		'attributes' => [
			'autocomplete'   => 'off',
			'required'       => $front,
			'data-community' => $community_id,
			'data-rule-notEqual' => $front ? "-1" : "",
		],
		'class'      => $show_front ? [ 'dynamic-select' ] : [ 'dynamic-select', 'hidden' ],
		'errors'     => $errors,
	], true );

	$show_back = ( $back || gothic_selections_form_value( 'sbackyard', $request, 'intval' ) || ! empty( $errors['_backyards'] ) );

	gothic_selections_form_field( [
		'type'       => 'select-package',
		'label'      => __( 'Select Back Yard Package', 'gothic-selections' ),
		'name'       => 'backyard_id',
		'value'      => gothic_selections_form_value( 'backyard_id', $request, 'intval' ),
		'attributes' => [
			'autocomplete'   => 'off',
			'required'       => $back,
			'data-community' => $community_id,
			'data-backyard'  => true,
			'data-rule-notEqual' => $back ? "-1" : "",
		],
		'class'      => $show_back ? [ 'dynamic-select' ] : [ 'dynamic-select', 'hidden' ],
		'errors'     => $errors,
	], true );

	?>
	<h4 class="selections-form-group-header"><?php esc_html_e( 'Salesperson Information', 'selections' ); ?></h4>
	<p class="selections-form-group-description">
		<?php esc_html_e( 'Enter the contact information for the salesperson in charge of this home purchase.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'In the event the user finds an error or wants to choose an upgrade, they will be emailed to assist with the change.', 'gothic-selections' ); ?>
		<?php echo $user ? __( 'As you are a logged in home seller user, your information has been pre-populated, but this should be revised if you are not the salesperson in charge of the home purchase.', 'gothic-selections' ) : ''; ?>
	</p>
	<?php
	gothic_selections_form_field( [
		'type'  => 'text',
		'label' => __( 'Builder Sales Associate Name', 'gothic-selections' ),
		'name'  => 'builder_rep_name',
		'value' => gothic_selections_form_value( 'builder_rep_name', $request ) ?: ( $user ? $user->first_name . ' ' . $user->last_name : '' ),
		'attributes' => [
			'required' => true,
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
	], true );
	gothic_selections_form_field( [
		'type' => 'email',
		'template'  => 'text',
		'label' => __( 'Builder Sales Associate Email', 'gothic-selections' ),
		'name'  => 'builder_rep_email',
		'value' => gothic_selections_form_value( 'builder_rep_email', $request ) ?: ( $user ? $user->user_email : '' ),
		'attributes' => [
			'required' => true,
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
	], true );
	?>
	<h4 class="selections-form-group-header"><?php esc_html_e( 'Save and Send', 'selections' ); ?></h4>
	<p class="selections-form-group-description">
		<?php esc_html_e( 'All fields on this form are required and any errors will need to be revised by either you, your colleague, or a Gothic Landscape staff person prior to the home buyer being able to certify and complete the selections order.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'To ensure a speedy reply from the home buyer, please make sure you are providing accurate responses and you have coached your buyer through upgrade options.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'Upon completing this form, the home buyer will be sent an email inviting them to complete thier selections order.', 'gothic-selections' ); ?>
	</p>

	<footer style="text-align: center">
		<a class="button button-secondary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'landscape/selections/index' ) ) ); ?>"><?php esc_html_e( 'Cancel', 'gothic-selections' ); ?></a>
		<button type="submit"><?php esc_html_e( 'Submit', 'gothic-selections' ); ?></button>
	</footer>
</div>
