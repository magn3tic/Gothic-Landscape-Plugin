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

use Gothic\Selections\PostType\Package as Packages;
use Gothic\Selections\Helper\{Queries, Template};

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


$community_id      = get_post_meta( get_the_ID(), 'community_id', true );
$packages          = Queries::packages( [
	'community_id' => $community_id,
] );
$selected_package  = (int) get_post_meta( get_the_id(), 'package_id', true ) ?: ( isset( $request['package_id'] ) ? $request['package_id'] : null );
$backyards         = Queries::packages( [
	'community_id' => $community_id,
	'backyard'     => true
] );
$selected_backyard = (int) get_post_meta( get_the_id(), 'backyard_id', true ) ?: ( isset( $request[ 'backyard_id' ] ) ? $request[ 'backyard_id' ] : null );

$opt_backyard      = ( -1 === intval( get_post_meta( $community_id, 'backyard', true ) ) );

$options = ( count( $packages ) > 1 && ( count( $backyards ) > 1 || $opt_backyard ) ) ? 2 : 1;

?>

<h1><?php echo esc_html( _n( 'Your Landscape Package', 'Your Landscape Options', $options, 'gothic-selections' ) ); ?></h1>
<p>
	<?php echo esc_html( _n( 'Your home builder sales person, when setting up your details, reported the following landscape selection.', 'Your home builder sales person, when setting up your details, reported the following landscape selections.', $options, 'gothic-selections' ) ); ?>
</p>

<?php if ( $packages ) : ?>

	<?php if ( $options > 1 ) : ?>
		<h4><?php esc_html_e( 'Options for Front Yard', 'gothic-selections' ); ?></h4>
	<?php endif; ?>

	<ul class="landscape-plans">
		<?php foreach ( $packages as $package ) : ?>
			<li>
				<?php
				$i     = isset( $i ) ? $i : 0;
				$model = get_post_meta( get_the_id(), 'model_id', true );
				$image = get_post_meta( $model, 'plan-' . $package->ID . '-image', true );
				$full  = get_post_meta( $model, 'plan-' . $package->ID . '-full', true );
				?>
				<aside class="image" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $image, 'model-plan-tile' ) ); ?>');">
					<?php if ( $full ) : ?>
						<a href="<?php echo esc_url( wp_get_attachment_image_url( $full, 'medium' ) ); ?>"></a>
					<?php endif; ?>
				</aside>
				<h4><?php echo esc_html( $package->post_title ); ?></h4>
				<?php echo apply_filters( 'the_content', $package->post_content ); // WPCS: XSS ok. ?>

				<?php

				if ( $i === 0 ) {
					if ( $selected_package === $package->ID ) {
						$label = __( 'I Selected This Package', 'gothic-selections' );
					} else {
						$label = __( 'Not Selected', 'gothic-selections' );
					}
				} else {
					if ( $selected_package === $package->ID ) {
						$label = __( 'I Selected This Upgrade', 'gothic-selections' );
					} else {
						$label = __( 'Not Selected', 'gothic-selections' );
					}
				}

				$i++;

				$field = array(
					'type'    => 'radio',
					'label'   => null,
					'name'    => 'package_id',
					'class'   => array( 'select' ),
					'value'   => ( $i === 0 && ! $selected_package ) ? $package->ID : $selected_package,
					'options' => [
						$package->ID => $label,
					],
					'attributes' => [
						'disabled' => true,
					],
				);
				gothic_selections_form_field( $field );
				if ( $selected_package !== $package->ID ) {
					?>
					<a href="#notify-problem" class="notify-problem crunch-button crunch-button__text-only crunch-button__text-only--secondary-color">
						I'd like to change my selection.
					</a>
					<?php
				} else {
					echo '&nbsp;';
				}
				?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<?php if ( $backyards ) : ?>

	<?php if ( $options > 1 ) : ?>
		<h4><?php esc_html_e( 'Options for Back Yard', 'gothic-selections' ); ?></h4>
	<?php endif; ?>

	<ul class="landscape-plans">
		<?php foreach ( $backyards as $backyard ) : ?>
			<li>
				<?php
				$i     = isset( $i ) ? $i : 0;
				$model = get_post_meta( get_the_id(), 'model_id', true );
				$image = get_post_meta( $model, 'plan-' . $backyard->ID . '-image', true );
				$full  = get_post_meta( $model, 'plan-' . $backyard->ID . '-full', true );
				?>
				<aside class="image" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $image, 'model-plan-tile' ) ); ?>');">
					<?php if ( $full ) : ?>
						<a href="<?php echo esc_url( esc_url( wp_get_attachment_image_url( $full, 'medium' ) ) ); ?>"></a>
					<?php endif; ?>
				</aside>
				<h4><?php echo esc_html( $backyard->post_title ); ?></h4>
				<?php echo apply_filters( 'the_content', $backyard->post_content ); // WPCS: XSS ok. ?>

				<?php

				if ( $i === 0 ) {
					if ( $selected_backyard === $backyard->ID ) {
						$label = __( 'I Selected This Package', 'gothic-selections' );
					} else {
						$label = __( 'Not Selected', 'gothic-selections' );
					}
				} else {
					if ( $selected_backyard === $backyard->ID ) {
						$label = __( 'I Selected This Upgrade', 'gothic-selections' );
					} else {
						$label = __( 'Not Selected', 'gothic-selections' );
					}
				}

				$i++;

				$field = array(
					'type'    => 'radio',
					'label'   => null,
					'name'    => 'backyard_id',
					'class'   => array( 'select' ),
					'value'   => ( $i === 0 && ! $selected_backyard ) ? $backyard->ID : $selected_backyard,
					'options' => [
						$backyard->ID => $label,
					],
					'attributes' => [
						'disabled' => true,
					],
				);
				gothic_selections_form_field( $field );

				if ( $selected_backyard !== $backyard->ID ) {
					?>
						<a href="#notify-problem" class="notify-problem crunch-button crunch-button__text-only crunch-button__text-only--secondary-color">
							I'd like to change my selection.
						</a>
					<?php
				} else {
					echo '&nbsp;';
				}
				?>

			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php if ( $opt_backyard ) : ?>
	<?php if ( $options > 1 ) : ?>
		<h4><?php esc_html_e( 'Options for Back Yard', 'gothic-selections' ); ?></h4>
	<?php endif; ?>
	<p><?php esc_html_e( 'Your home builder does not offer prepared landscape options for your back yard, but let us know if you would like us to prepare a custom design and quote for you. Your new home can be fully landscaped at move-in.', 'gothic-selections' ); ?></p>
	<?php
	gothic_selections_form_field( [
		'type'  => 'checkbox',
		'label' => __( 'Yes, please, I\'d like Gothic Landscape to contact me to discuss back yard landscaping options for my new home.', 'gothic-selections' ),
		'name'  => 'opt_in_backyard_upsell',
		'value' => gothic_selections_form_value( 'opt_in_backyard_upsell' ),
	] );
	?>
<?php endif; ?>


	<footer>
		<a class="button button-secondary"
		   href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'model' ); ?>">Back</a>
		<button>Continue</button>
	</footer>
<div id="notify-problem" class="notify-problem-container" style="display:none;">
	<h2>Tell Your Builder You'd Like to Update Your Landscaping</h2>
	<p>Your Landscape Package is usually determined when writing your new home contract. If you'd like to update your selection, you will need contact your home builder salesperson through this form below. Please note that Gothic Landscape will install the Landscape as ordered by your homebuilder and that your homebuilder may not be able to make a change to your home purchase at this time.</p>
	<?php
	Template::form_field( array(
		'type'  => 'text',
		'label' => __( 'Your Name', 'gothic-selections' ),
		'name'  => 'contact_name',
		'value' => get_post_meta( get_the_id(), 'home_buyer', true ),
	), true );
	?>
	<?php
	Template::form_field( array(
		'type'     => 'email',
		'template' => 'text',
		'label'    => __( 'Your Preferred Email Address', 'gothic-selections' ),
		'name'     => 'contact_email',
		'value'    => get_post_meta( get_the_id(), 'email', true ),
	), true );
	?>
	<?php
	Template::form_field( array(
		'type'        => 'textarea',
		'label'       => __( 'Explanation Of Change(s)', 'gothic-selections' ),
		'attributes'  => [
			'placeholder' => __( 'Please explain your desired change(s). Your builder salesperson will be emailed your request.', 'gothic-selections' ),
		],
		'name'        => 'comments',
	), true );
	?>
	<button type="submit" name="_problem" value="package" onClick="parent.jQuery.fancybox.close();" >Send Message</button>
</div>