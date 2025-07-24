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
?>
	<h1><?php esc_html_e( 'Tell Us About You', 'gothic-selections' ); ?></h1>

	<p><?php esc_html_e( 'We need to know a little about you in order to match your preferences with your new home. Once the information is complete, submit this to advance to the final step.', 'gothic-selections' ); ?></p>
	<div class="c-pt-5"></div>
<?php if ( ! empty( $errors['invalid'] ) ) : ?>
	<div class="invalid-notice">
		<p><?php echo esc_html( $errors['invalid'] ); ?></p>
	</div>
<?php endif; ?>

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
	'label'      => __( 'City', 'gothic-selections' ),
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
			'label'      => __( 'State (Abbr), eg: AZ', 'gothic-selections' ),
			'name'       => 'state',
			'value'      => gothic_selections_form_value( 'state', $request ),
			'class'      => 'state',
			'attributes' => [
				'autocomplete' => 'off',
				'minlength' => 2,
				'maxlength' => 2,
			],
		] );
		gothic_selections_form_field( [
			'type'       => 'number',
			'template'   => 'text',
			'label'      => __( 'ZIP', 'gothic-selections' ),
			'name'       => 'zip',
			'value'      => gothic_selections_form_value( 'zip', $request ),
			'class'      => 'zip',
			'attributes' => [
				'autocomplete' => 'off',
				'minlength' => 5,
			],
		] );
		?>
	</div>
<?php
gothic_selections_form_field( [
	'type'       => 'number',
	'template'   => 'text',
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
?>

<footer>
	<?php if ( \Gothic\Selections\Helper\Misc::selection_has_no_preferences( get_the_ID() ) ) : ?>
		<a class="button button-secondary"
			href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'packages' ); ?>">Back</a>
	<?php else: ?>
		<a class="button button-secondary"
		    href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'preferences' ); ?>">Back</a>
	<?php endif; ?>
	<button>Continue</button>
</footer>

