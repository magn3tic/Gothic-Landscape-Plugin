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
	<h1><?php esc_html_e( 'Confirm Your Details', 'gothic-selections' ); ?></h1>

	<p><?php esc_html_e( 'Your home sales person provided the following information about you. Please review it. If anything is wrong, let us know at the bottom.', 'gothic-selections' ); ?></p>

<?php
gothic_selections_form_field( [
	'type'       => 'text',
	'label'      => __( 'Your Full Name(s)', 'gothic-selections' ),
	'name'       => 'info_home_buyer',
	'value'      => gothic_selections_form_value( 'home_buyer', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
		'disabled' => 'disabled',
	],
	'errors'     => $errors,
] );
gothic_selections_form_field( [
	'type'       => 'email',
	'template'   => 'text',
	'label'      => __( 'Email Address', 'gothic-selections' ),
	'name'       => 'info_email',
	'value'      => gothic_selections_form_value( 'email', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
		'disabled' => 'disabled',
	],
	'errors'     => $errors,
] );
gothic_selections_form_field( [
	'type'       => 'tel',
	'template'   => 'text',
	'label'      => __( 'Phone Number', 'gothic-selections' ),
	'name'       => 'info_phone',
	'value'      => gothic_selections_form_value( 'phone', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
		'disabled' => 'disabled',
	],
	'errors'     => $errors,
] );
?>
	<hr class="divider">
<?php
gothic_selections_form_field( [
	'type'       => 'text',
	'label'      => __( 'New Home Address', 'gothic-selections' ),
	'name'       => 'info_address',
	'value'      => gothic_selections_form_value( 'address', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
		'disabled' => 'disabled',
	],
	'errors'     => $errors,
] );
gothic_selections_form_field( [
	'type'       => 'text',
	'label'      => __( 'City', 'gothic-selections' ),
	'name'       => 'info_city',
	'value'      => gothic_selections_form_value( 'city', $request ),
	'attributes' => [
		'required'     => true,
		'autocomplete' => 'off',
		'disabled' => 'disabled',
	],
	'errors'     => $errors,
] );
?>
	<div class="field-group">
		<?php
		gothic_selections_form_field( [
			'type'       => 'text',
			'label'      => __( 'State', 'gothic-selections' ),
			'name'       => 'info_state',
			'value'      => gothic_selections_form_value( 'state', $request ),
			'class'      => 'state',
			'attributes' => [
				'autocomplete' => 'off',
				'disabled' => 'disabled',
			],
		] );
		gothic_selections_form_field( [
			'type'       => 'text',
			'label'      => __( 'ZIP', 'gothic-selections' ),
			'name'       => 'info_zip',
			'value'      => gothic_selections_form_value( 'zip', $request ),
			'class'      => 'zip',
			'attributes' => [
				'autocomplete' => 'off',
				'disabled' => 'disabled',
			],
		] );
		?>
	</div>
<?php
gothic_selections_form_field( [
	'type'       => 'text',
	'label'      => __( 'New Home Lot Number', 'gothic-selections' ),
	'name'       => 'info_lot',
	'value'      => gothic_selections_form_value( 'lot', $request ),
	'attributes' => [
		'autocomplete' => 'off',
		'required'     => true,
		'disabled' => 'disabled',
	],
	'errors'     => $errors,
] );
?>
	<hr class="divider">
<?php
gothic_selections_form_field( [
	'type'  => 'text',
	'label' => __( 'Builder Sales Associate Name', 'gothic-selections' ),
	'name'  => 'info_builder_rep_name',
	'value' => gothic_selections_form_value( 'builder_rep_name', $request ),
	'attributes' => [
		'disabled' => 'disabled',
	],
] );
?>

<footer>
	<a class="button button-secondary"
	   href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'preferences' ); ?>">Back</a>
	<button class="c-mx-2">Continue</button>
	<span class="c-mt-4">Or,</span> <a href="#notify-problem" class="c-mx-2 c-mt-4 crunch-button crunch-button__text-only crunch-button__text-only--secondary-color  notify-problem">stop and notify us there is an error.</a>
</footer>
<div id="notify-problem" class="notify-problem-container" style="display:none;">
	<h2>Contact Your Builder</h2>
	<p>Contact your builder to let them know something isn't right. They will make necessary corrections
		and notify you when you can continue.</p>
	<?php
	gothic_selections_form_field( array(
		'type'  => 'text',
		'label' => __( 'Your Name', 'gothic-selections' ),
		'name'  => 'contact_name',
		'value' => get_post_meta( get_the_id(), 'home_buyer', true ),
	), true );
	?>
	<?php
	gothic_selections_form_field( array(
		'type'     => 'email',
		'template' => 'text',
		'label'    => __( 'Your Email Address', 'gothic-selections' ),
		'name'     => 'contact_email',
		'value'    => get_post_meta( get_the_id(), 'email', true ),
	), true );
	?>
	<?php
	gothic_selections_form_field( array(
		'type'  => 'textarea',
		'label' => __( 'Explanation Of What is Wrong. List the items and corrections.', 'gothic-selections' ),
		'name'  => 'comments',
	), true );
	?>
	<button type="submit" name="_problem" value="info" onClick="parent.jQuery.fancybox.close();" >Send Message</button>
</div>
