<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Defined before includes:
 *
 * @var array $errors
 * @var array $request
 * @var array $data
 */
?>
<div class="initiate">

	<h1><?php esc_html_e( 'Cancel Selection', 'gothic-selections' ); ?> #<?php echo get_post()->post_name ?></h1>

	<p>
		<?php esc_html_e( 'When a selection is created for a home purchase that is later cancelled, you should cancel the selection.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'If a home model or community changes, the order must also be cancelled, and you must start a new order under the new community or with the new home model.', 'gothic-selections' ); ?>
	</p>

	<hr />

	<p>
		<?php esc_html_e( 'To confirm the voiding and deletion of this order, type "CANCEL" into the box below.', 'gothic-selections' ); ?>
	</p>


	<?php
	gothic_selections_form_field( [
		'type'       => 'text',
		'label'      => __( 'Type "CANCEL" To Confirm', 'gothic-selections' ),
		'name'       => 'confirm_action',
		'value'      => gothic_selections_form_value( 'confirm_action', $request ),
		'attributes' => [
			'autocomplete' => 'off',
			'required'     => true,
		],
		'errors'     => $errors,
	], true );
	?>

	<p>
		<strong><?php esc_html_e( 'This is a permanent action. Please use with caution.', 'gothic-selections' ); ?></strong>
	</p>

	<footer style="text-align: center">
		<a class="button button-secondary" href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'show' ); ?>"><?php esc_html_e( 'Go Back', 'gothic-selections' ); ?></a>
		<button type="submit"><?php esc_html_e( 'Cancel Selection', 'gothic-selections' ); ?></button>
	</footer>
</div>
