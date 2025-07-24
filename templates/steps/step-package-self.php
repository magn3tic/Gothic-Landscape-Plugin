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

$selected_package = (int) get_post_meta( get_the_id(), 'package_id', true ) ?: ( isset( $request[ '_' . Packages::$key ] ) ? $request[ '_' . Packages::$key ] : null );
?>
	<h1><?php esc_html_e( 'Your Landscape Package', 'gothic-selections' ); ?></h1>
	<p><?php esc_html_e( 'Select the Landscape Package you chose for your new home.', 'gothic-selections' ); ?></p>
	<p><?php esc_html_e( 'Your new home includes a standard landscape package, but your homebuilder may offer upgraded plans as part of your contract. If you wish to upgrade your plan at this time, contact your homebuilder salesperson. Otherwise, select the option that is on your new home contract.', 'gothic-selections' ); ?></p>

<?php $packages = Queries::packages( [ 'community_id' => get_post_meta( get_the_ID(), 'community_id', true ) ], true ); ?>
<?php if ( $packages ) : ?>
	<ul class="landscape-plans">
		<?php foreach ( $packages as $package ) : ?>
			<li>
				<?php
				$i     = isset( $i ) ? $i : 0;
				$model = get_post_meta( get_the_id(), 'model_id', true );
				$image = get_post_meta( $model, 'plan-' . $package->ID . '-image', true );
				$full  = get_post_meta( $model, 'plan-' . $package->ID . '-full', true );
				?>
				<aside class="image" style="background-image: url('<?php echo $image; ?>');">
					<?php if ( $full ) : ?>
						<a href="<?php echo esc_url( $full ); ?>"></a>
					<?php endif; ?>
				</aside>
				<h4><?php echo esc_html( $package->post_title ); ?></h4>
				<?php echo apply_filters( 'the_content', $package->post_content ); // WPCS: XSS ok. ?>

				<?php

				if ( $i === 0 ) {
					$label = __( 'Select This Package', 'gothic-selections' );
				} else {
					$label = __( 'Select This Upgrade', 'gothic-selections' );
				}

				$i++;

				$field = array(
					'type'    => 'radio',
					'label'   => null,
					'name'    => '_' . Packages::$key,
					'class'   => array( 'select' ),
					'value'   => ( $i === 0 && ! $selected_package ) ? $package->ID : $selected_package,
					'options' => [
						$package->ID => $label,
					],
				);
				gothic_selections_form_field( $field );
				?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

	<footer>
		<p>
			<button><a href="<?php echo esc_url( get_permalink( get_the_ID() ) . '/step/model' ); ?>">Back</a></button>
		</p>
		<p>
			<button>Continue</button>
		</p>
	</footer>
