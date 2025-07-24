<?php
/**
 * Template: Selections Step Packages (New)
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

/**
 * Defined before includes:
 *
 * @var array $errors
 * @var array $request
 * @var array $data
 */

if ( empty( $request ) ) {
	$request = [];
}
?>
	<h1><?php esc_html_e( 'Tell Us About You', 'gothic-selections' ); ?></h1>

	<p><?php esc_html_e( 'We need to know a little about you in order to match your preferences with your new home. Once the information is complete, submit this to advance to the final step.', 'gothic-selections' ); ?></p>

<?php
gothic_selections_form_field( [
	'type'       => 'text',
	'label'      => __( 'Your Full Name(s)', 'gothic-selections' ),
	'name'       => 'home_buyer',
	'value'      => gothic_selections_form_value( 'home_buyer', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
	],
	'errors'     => $errors,
] );
gothic_selections_form_field( [
	'type'       => 'email',
	'template'   => 'text',
	'label'      => __( 'Email Address', 'gothic-selections' ),
	'name'       => 'email',
	'value'      => gothic_selections_form_value( 'email', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
	],
	'errors'     => $errors,
] );
gothic_selections_form_field( [
	'type'       => 'tel',
	'template'   => 'text',
	'label'      => __( 'Phone Number', 'gothic-selections' ),
	'name'       => 'phone',
	'value'      => gothic_selections_form_value( 'phone', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
	],
	'errors'     => $errors,
] );
?>
	<hr class="divider">
<?php
gothic_selections_form_field( [
	'type'       => 'text',
	'label'      => __( 'New Home Address', 'gothic-selections' ),
	'name'       => 'address',
	'value'      => gothic_selections_form_value( 'address', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
	],
	'errors'     => $errors,
] );
gothic_selections_form_field( [
	'type'       => 'text',
	'label'      => __( 'New Home City', 'gothic-selections' ),
	'name'       => 'city',
	'value'      => gothic_selections_form_value( 'city', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
	],
	'errors'     => $errors,
] );
?>
	<div class="field-group">
		<?php
		gothic_selections_form_field( [
			'type'       => 'text',
			'label'      => __( 'New Home State', 'gothic-selections' ),
			'name'       => 'state',
			'value'      => gothic_selections_form_value( 'state', $request ),
			'class'      => 'state',
			'attributes' => [
				'autocomplete' => 'off',
			],
		] );
		gothic_selections_form_field( [
			'type'       => 'text',
			'label'      => __( 'New Home ZIP Code', 'gothic-selections' ),
			'name'       => 'zip',
			'value'      => gothic_selections_form_value( 'zip', $request ),
			'class'      => 'zip',
			'attributes' => [
				'autocomplete' => 'off',
			],
		] );
		?>
	</div>
<?php
gothic_selections_form_field( [
	'type'       => 'text',
	'label'      => __( 'New Home Lot Number', 'gothic-selections' ),
	'name'       => 'lot',
	'value'      => gothic_selections_form_value( 'lot', $request ),
	'attributes' => [
		'autocomplete' => 'off',
		'required'     => true,
	],
	'errors'     => $errors,
] );
?>
	<hr class="divider">
<?php
gothic_selections_form_field( [
	'type'  => 'text',
	'label' => __( 'Builder Sales Associate Name', 'gothic-selections' ),
	'name'  => 'builder_rep_name',
	'value' => gothic_selections_form_value( 'builder_rep_name', $request ),
] );
gothic_selections_form_field( [
	'type'  => 'text',
	'label' => __( 'Approximate Close of Escrow, If Known', 'gothic-selections' ),
	'name'  => 'escrow_close',
	'value' => gothic_selections_form_value( 'escrow_close', $request ),
] );
?>

<footer>
	<p>
		<button><a href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'step/preferences' ); ?>">Back</a></button>
	</p>
	<p>
		<button>Continue</button>
	</p>
</footer>

