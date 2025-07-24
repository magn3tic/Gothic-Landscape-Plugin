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

	<h1><?php esc_html_e( 'Transfer Selection', 'gothic-selections' ); ?> #<?php echo get_post()->post_name ?></h1>

	<p>
		<?php esc_html_e( 'Transfer a Landscape Selection Order for a home buyer.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'Use this option when the home buyer\'s selection are complete but their record needs to be moved to a new lot (& address).', 'gothic-selections' ); ?>
		<?php esc_html_e( 'If the home buyer\'s community or home model needs to be changed, please "cancel" their order and re-start.', 'gothic-selections' ); ?>
	</p>

	<p>
		<strong><?php esc_html_e( 'This is a permanent action. Please use with caution.', 'gothic-selections' ); ?></strong>
	</p>

	<h4 class="selections-form-group-header"><?php esc_html_e( 'Original Selection Details', 'gothic-selections' ); ?></h4>

	<?php if ( gothic_selections_form_value( 'home_buyer', $request ) ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Name', 'gothic-selections' ); ?></h4>
			<p><?php echo gothic_selections_form_value( 'home_buyer', $request ); ?></p></div>
	<?php endif; ?>

	<?php if ( gothic_selections_form_value( 'address' ) || gothic_selections_form_value( 'city', $request ) || gothic_selections_form_value( 'state', $request ) || gothic_selections_form_value( 'zip', $request ) ) : ?>
	<div class="selection-group">
		<h4><?php esc_html_e( 'Address', 'gothic-selections' ); ?></h4>
		<p>
			<?php
			echo gothic_selections_form_value( 'address', $request );

			if ( gothic_selections_form_value( 'address' ) && ( gothic_selections_form_value( 'city', $request ) || gothic_selections_form_value( 'state', $request ) || gothic_selections_form_value( 'zip', $request ) ) ) :
				echo '<br />';
			endif;
			echo gothic_selections_form_value( 'city', $request );
			if ( gothic_selections_form_value( 'city' ) && gothic_selections_form_value( 'state', $request ) ) {
				echo ', ';
			}
			echo strtoupper( gothic_selections_form_value( 'state', $request ) );
			echo ' ';
			echo gothic_selections_form_value( 'zip', $request );
			?>
		</p>
	</div>
	<?php endif; ?>

	<?php if ( gothic_selections_form_value( 'lot', $request ) ) : ?>
	<div class="selection-group"><h4><?php esc_html_e( 'Lot Number', 'gothic-selections' ) ?></h4>
		<p><?php echo gothic_selections_form_value( 'lot', $request ); ?></p></div>
	<?php endif; ?>
	<h4 class="selections-form-group-header"><?php esc_html_e( 'Address to Transfer To', 'gothic-selections' ); ?></h4>
	<?php
	gothic_selections_form_field( [
		'type' => 'hidden',
		'name' => 'transfer_source',
		'value' => get_the_id(),
	] );
	gothic_selections_form_field( [
		'type'       => 'text',
		'label'      => __( 'Transfer Address', 'gothic-selections' ),
		'name'       => 'transfer_address',
		'value'      => gothic_selections_form_value( 'transfer_address', $request ),
		'attributes' => [
			'required'     => true,
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
	], true );
	gothic_selections_form_field( [
		'type'       => 'text',
		'label'      => __( 'Transfer City', 'gothic-selections' ),
		'name'       => 'transfer_city',
		'value'      => gothic_selections_form_value( 'transfer_city', $request ),
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
			'label'      => __( 'Transfer State (Abbv)', 'gothic-selections' ),
			'name'       => 'transfer_state',
			'value'      => gothic_selections_form_value( 'transfer_state', $request ),
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
			'label'      => __( 'Transfer ZIP Code', 'gothic-selections' ),
			'name'       => 'transfer_zip',
			'value'      => gothic_selections_form_value( 'transfer_zip', $request, 'intval' ),
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
		'label'      => __( 'Transfer Lot Number', 'gothic-selections' ),
		'name'       => 'transfer_lot',
		'value'      => gothic_selections_form_value( 'transfer_lot', $request, 'intval' ),
		'attributes' => [
			'autocomplete' => 'off',
			'required'     => true,
		],
		'errors'     => $errors,
	], true );

	?>

	<footer style="text-align: center">
		<a class="button button-secondary" href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'show' ); ?>"><?php esc_html_e( 'Cancel', 'gothic-selections' ); ?></a>
		<button type="submit"><?php esc_html_e( 'Submit', 'gothic-selections' ); ?></button>
	</footer>
</div>
