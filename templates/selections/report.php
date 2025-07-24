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

	<h1><?php esc_html_e( 'Export Orders', 'gothic-selections' ); ?></h1>
	<div class="c-mt-5">
		<p>
			<?php esc_html_e( 'Download a CSV with orders since a given date.', 'gothic-selections' ); ?>
		</p>
	</div>

	<hr />

	<?php
	gothic_selections_form_field( [
		'type'       => 'date',
		'label'      => __( 'Export Since Date', 'gothic-selections' ),
		'name'       => 'date',
		'value'      => gothic_selections_form_value( 'confirm_action', $request ),
		'attributes' => [
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
		'description' => __( 'Select a date to download records after. If left blank, all records will be downloaded.', 'gothic-selections' ),
	], true );
	gothic_selections_form_field( [
		'type'       => 'date',
		'label'      => __( 'Export To Date', 'gothic-selections' ),
		'name'       => 'date-to',
		'value'      => gothic_selections_form_value( 'confirm_action', $request ),
		'attributes' => [
			'autocomplete' => 'off',
		],
		'errors'     => $errors,
		'description' => __( 'Select a date to download records before. If left blank, all records will be downloaded.', 'gothic-selections' ),
	], true );
	gothic_selections_form_field( [
		'type'  => 'select-builder',
		'label' => __( 'Export by Homebuilder', 'gothic-selections' ),
		'name'  => 'builder_id',
		'value' => '',
		'attributes' => [
			'autocomplete' => false,
			'data-rule-notEqual' => "-1",
		],
		'class' => 'dynamic-select',
		'errors'     => $errors,
	], true );
	gothic_selections_form_field( [
		'type'       => 'select-community',
		'label'      => __( 'Export by Community', 'gothic-selections' ),
		'name'       => 'community_id',
		'value'      => '',
		'attributes' => [
			'autocomplete' => 'off',
			'data-rule-notEqual' => "-1",
		],
		'class' => 'dynamic-select',
		'errors'     => $errors,
	], true );
	?>

	<footer style="text-align: center">
		<a class="button button-secondary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'landscape/selections/index' ) ) ); ?>"><?php esc_html_e( 'Cancel', 'gothic-selections' ); ?></a>
		<button type="submit"><?php esc_html_e( 'Generate Report', 'gothic-selections' ); ?></button>
	</footer>
</div>
