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
	<?php echo esc_html( _n( 'Select the Landscape Package you chose for your new home.', 'Select the Landscape Packages you chose for your home.', $options, 'gothic-selections' ) ); ?>
	<?php echo esc_html( _n( 'Your new home includes a standard landscape option, but your home builder may offer upgraded plans as part of your contract.', 'Your new home includes standard landscape options, but your home builder may offer upgrade options as part of your contract.', $options, 'gothic-selections' ) ); ?>
	<?php echo esc_html( _n( 'If you wish to upgrade your option at this time, contact your home builder salesperson.', 'If you wish to upgrade your options at this time, contact your home builder salesperson.', $options, 'gothic-selections' ) ); ?>
	<?php echo esc_html( _n ( 'Otherwise, select the option that is on your new home contract.', 'Otherwise, select the options that are on your new home contract.', $options, 'gothic-selections' ) ); ?>
</p>

<?php if ( ! empty( $errors['invalid'] ) ) : ?>
	<div class="invalid-notice">
		<p><?php echo esc_html( $errors['invalid'] ); ?></p>
	</div>
<?php endif; ?>

<?php if ( count( $packages ) > 1 ) : ?>

	<?php if ( $options > 1 ) : ?>
		<h4><?php esc_html_e( 'Options for Front Yard', 'gothic-selections' ); ?></h4>
	<?php endif; ?>

	<?php if ( ! empty( $errors['package_id'] ) ) : ?>
		<div class="invalid-notice">
			<p><?php echo esc_html( $errors['package_id'] ); ?></p>
		</div>
	<?php endif; ?>

	<ul class="landscape-plans<?php echo ( 4 === count( $packages ) ) ? ' four-plans' : ''; ?>">
		<?php foreach ( $packages as $package ) : ?>
			<li>
				<?php
				$model = get_post_meta( get_the_id(), 'model_id', true );
				$image = get_post_meta( $model, 'plan-' . $package->ID . '-image', true );
				$full  = get_post_meta( $model, 'plan-' . $package->ID . '-full', true );
				?>
				<aside class="image" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $image, 'model-plan-tile' ) ); ?>');">
					<?php if ( $full ) : ?>
						<a href="#package-expanded-<?php echo esc_attr( $package->ID ); ?>"></a>
					<?php endif; ?>
				</aside>
				<aside id="package-expanded-<?php echo esc_attr( $package->ID ); ?>" class="package-expanded" style="width:600px;display:none;padding:0;">
					<figure style="width: 100%;">
						<img src="<?php echo esc_url( wp_get_attachment_image_url( $full, 'medium' ) ); ?>" style="width:100%;"/>
						<figcaption style="color:white;background-color:#00a2e5;padding:30px 20px;">
							<?php esc_html_e( 'Plans shown are for illustration purposes only. Per-lot plans will vary based on several factors, including lot size, elevation, whether it is a corner, and more.', 'gothic-landscape' ); ?>
							<a href="<?php echo esc_url( wp_get_attachment_image_url( $full, 'medium' ) ); ?>" class="printable" style="text-decoration: underline; color:white">Print</a>
						</figcaption>
					</figure>
				</aside>
				<h4><?php echo esc_html( $package->post_title ); ?></h4>
				<?php echo apply_filters( 'the_content', $package->post_content ); // WPCS: XSS ok. ?>

				<?php

				$default    = get_post_meta( $package->ID, 'selected', true );
				$is_upgrade = get_post_meta( $package->ID, 'is_upgrade', true );

				$field = [
					'type'    => 'radio',
					'label'   => null,
					'name'    => 'package_id',
					'class'   => array( 'select' ),
					'value'   => ( $default && ! $selected_package ) ? $package->ID : $selected_package,
					'options' => [
						$package->ID => $is_upgrade ? __( 'I Selected This Upgrade', 'gothic-selections' ) : __( 'I Selected This Package', 'gothic-selections' ),
					],
				];

				if ( $is_upgrade ) {
					$field['sublabel'] = __( 'This is an upgrade option that must be separately confirmed with your home builder and included on your home contract. Gothic Landscape will only install the landscape package ordered by your home builder.', 'gothic-selections' );
				}

				gothic_selections_form_field( $field );
				?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php if ( count( $backyards ) > 1 ) : ?>

	<?php if ( $options > 1 ) : ?>
		<h4><?php esc_html_e( 'Options for Back Yard', 'gothic-selections' ); ?></h4>
	<?php endif; ?>

	<?php if ( ! empty( $errors['backyard_id'] ) ) : ?>
		<div class="invalid-notice">
			<p><?php echo esc_html( $errors['backyard_id'] ); ?></p>
		</div>
	<?php endif; ?>

	<ul class="landscape-plans<?php echo ( 4 === count( $backyards ) ) ? ' four-plans' : ''; ?>">
		<?php foreach ( $backyards as $backyard ) : ?>
			<li>
				<?php
				$model = get_post_meta( get_the_id(), 'model_id', true );
				$image = get_post_meta( $model, 'plan-' . $backyard->ID . '-image', true );
				$full  = get_post_meta( $model, 'plan-' . $backyard->ID . '-full', true );
				?>
				<aside class="image" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $image ) ); ?>');">
					<?php if ( $full ) : ?>
						<a href="#package-expanded-<?php echo esc_attr( $backyard->ID ); ?>"></a>
					<?php endif; ?>
				</aside>
				<aside id="package-expanded-<?php echo esc_attr( $backyard->ID ); ?>" class="package-expanded" style="width:600px;display:none;padding:0;">
					<figure style="width: 100%;">
						<img src="<?php echo esc_url( wp_get_attachment_image_url( $full, 'full' ) ); ?>" style="width:100%;"/>
						<figcaption style="color:white;background-color:#00a2e5;padding:30px 20px;">
							<?php esc_html_e( 'Plans shown are for illustration purposes only. Per-lot plans will vary based on several factors, including lot size, elevation, whether it is a corner, and more.', 'gothic-landscape' ); ?>
							<a href="<?php echo esc_url( wp_get_attachment_image_url( $full, 'full' ) ); ?>" class="printable" style="text-decoration: underline; color:white">Print</a>
						</figcaption>
					</figure>
				</aside>
				<h4><?php echo esc_html( $backyard->post_title ); ?></h4>
				<?php echo apply_filters( 'the_content', $backyard->post_content ); // WPCS: XSS ok. ?>

				<?php
				$default = get_post_meta( $backyard->ID, 'selected', true );
				$is_upgrade = get_post_meta( $backyard->ID, 'is_upgrade', true );

				$field = [
					'type'    => 'radio',
					'label'   => null,
					'name'    => 'backyard_id',
					'class'   => array( 'select' ),
					'value'   => ( $default && ! $selected_backyard ) ? $backyard->ID : $selected_backyard,
					'options' => [
						$backyard->ID => $is_upgrade ? __( 'I Selected This Upgrade', 'gothic-selections' ) : __( 'I Selected This Package', 'gothic-selections' ),
					],
				];
				if ( $is_upgrade ) {
					$field['sublabel'] = __( 'This is an upgrade option that must be separately confirmed with your home builder and included on your home contract. Gothic Landscape will only install the landscape package ordered by your home builder.', 'gothic-selections' );
				}
				gothic_selections_form_field( $field );
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
