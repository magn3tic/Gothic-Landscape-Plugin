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
$history = json_decode(get_post_meta( get_the_ID(), "order_history", true),true) ?? false;

$modifier = get_the_modified_author(get_the_ID());
$statuses = ["in_progress", "seller_action"];
$issues = get_post_meta( get_the_ID(), 'problems', true );
$currentStatus = get_post_meta(get_the_ID(), "_status",true);
$resolveEnabled = false;
$status =  get_post_meta( get_the_ID(), '_status', true );
if ( !empty($issues) || $status == 'seller_action' ) {
	$resolveEnabled = true;
}
?>

<div class="initiate">

	<header>
		<h1 class="order">
			<?php echo esc_html__( 'Selection', 'gothic-selections' ) . ' #' . esc_html( get_post_field( 'post_name', get_post() ) ); ?>
		</h1>
		<hr>
		<h2 class="buyer_lot">
			<?php echo esc_html__( 'Lot', 'gothic-selections' ) . ' #' . esc_html( get_post_meta( get_the_ID(), 'lot', true ) ); ?>
		</h2>
	</header>

	<hr />

	<?php gothic_selections_get_template( 'loggedin-single-header', [], 'selections/parts', false, true ); ?>

	<hr />

	<?php if ( ! empty( $history ) ) : ?>
	<div class="history c-mt-4">
		<h4 class="selections-form-group-header">History</h4>
		<ul class="history-container c-mx-4 c-mt-4">
			<?php foreach($history as $history_item):?>
				<li class="c-py-1">
					<span class="font-weight-medium name"><?= $history_item["history_entry_title"] ?></span>
					<span> - </span>
					<span class=""><?= date("Y/m/d g:i:s A", strtotime($history_item["history_entry_date"]["date"])); ?></span>
				</li>
			<?php endforeach;?>
		</ul>
	</div>
	<?php endif; ?>

	<?php if ( ! empty( $errors['invalid'] ) ) : ?>
		<div class="invalid-notice">
			<p><?php echo esc_html( $errors['invalid'] ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( 'seller_action' === get_post_meta( get_the_ID(), '_status', true ) ) : ?>
		<div class="invalid-notice">
			<p><strong><?php esc_html_e( 'This order requires review by the home seller. The buyer reported the following errors or requested the following changes:', 'gothic-selections' ); ?></strong></p>
			<?php
			
			if ( ! empty( $issues['comments'] ) ) :
				echo '<ul style="margin-left: 1rem; margin-bottom: 1rem;">';
				foreach( $issues['comments'] as $issue ) :
					?>
					<li style="list-style-type: disc"><?php echo esc_html( $issue ); ?></li>
					<?php
				endforeach;
				echo '</ul>';
			endif;
			?>
			<p><em><?php esc_html_e( 'Upon saving the order, the home buyer will be able to complete the order.', 'gothic-selections' ); ?></em></p>
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
			'name'  => '_builders',
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
			'autocomplete'   => 'off',
			'required'       => true,
			'data-community' => $community_id,
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
	<p class="selections-form-group-description">
		<?php esc_html_e( 'All fields on this form are required and any errors will need to be revised by either you, your colleague, or a Gothic Landscape staff person prior to the home buyer being able to certify and complete the selections order.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'To ensure a speedy reply from the home buyer, please make sure you are providing accurate responses and you have coached your buyer through upgrade options.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'Upon updating a record, if the status was "On Hold", the status will be returned to "In Progress" and the home buyer will be sent an email inviting them to certify their selections order.', 'gothic-selections' ); ?>
	</p>

	<footer style="text-align: center">
		<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'landscaping/selection' ) ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Go Back', 'gothic-selections' ); ?></a>
		
		<?php if($resolveEnabled): ?>
		
			<div class="c-mt-4 c-ml-4">
				<button type="submit" name="resolve" value="<?= get_the_ID(); ?>" class="js-resolve-button crunch-button crunch-button__full-background crunch-button__full-background--primary-color crunch-button__full-background--medium">Resolve</button>
			</div>

		<?php endif; ?>

		<button class="c-ml-4" type="submit"><?php esc_html_e( 'Save Changes', 'gothic-selections' ); ?></button>
	</footer>
</div>
