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
	<div>
		<h1><?php esc_html_e( 'Thank You!', 'gothic-selections' ); ?></h1>

		<p>Thank you for completing your Landscape Selection. We are looking forward to
			installing your landscaping on your new home when its time. Below is a recap
			of your provided information and selections.</p>

		<hr />
		<h4 style="text-align: center">Your Details</h4>

		<?php if ( gothic_selections_form_value( 'home_buyer' ) ) : ?>
			<div class="selection-group"><h4>Your Name</h4><p><?php echo gothic_selections_form_value( 'home_buyer' ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'email' ) ) : ?>
			<div class="selection-group"><h4>Email Address</h4><p><?php echo gothic_selections_form_value( 'email' ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'phone' ) ) : ?>
			<div class="selection-group"><h4>Phone Number</h4><p><?php echo gothic_selections_form_value( 'phone' ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'address' ) || gothic_selections_form_value( 'city' ) || gothic_selections_form_value( 'state' ) || gothic_selections_form_value( 'zip' ) ) : ?>
			<div class="selection-group">
				<h4>New Home Address</h4>
				<p>
					<?php
					echo gothic_selections_form_value( 'address' );

					if ( gothic_selections_form_value( 'address' ) && ( gothic_selections_form_value( 'city' ) || gothic_selections_form_value( 'state' ) || gothic_selections_form_value( 'zip' ) ) ) :
						echo '<br />';
					endif;
					echo gothic_selections_form_value( 'city' );
					if ( gothic_selections_form_value( 'city' ) && gothic_selections_form_value( 'state' ) ) {
						echo ', ';
					}
					echo strtoupper( gothic_selections_form_value( 'state' ) );
					echo ' ';
					echo gothic_selections_form_value( 'zip' );
					?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'lot' ) ) : ?>
			<div class="selection-group"><h4>New Home Lot</h4><p><?php echo gothic_selections_form_value( 'lot' ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'builder_rep_name' ) ) : ?>
			<div class="selection-group"><h4>Builder Rep</h4><p><?php echo gothic_selections_form_value( 'builder_rep_name' ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'escrow_close' ) ) : ?>
			<div class="selection-group"><h4>Approximate Close of Escrow</h4><p><?php echo gothic_selections_form_value( 'escrow_close' ); ?></p></div>
		<?php endif; ?>

		<hr />

		<h4 style="text-align: center">Your Selections</h4>

		<?php
		$builder = gothic_selections_form_value( 'builder_id' );
		$community = gothic_selections_form_value( 'community_id' );
		$model = gothic_selections_form_value( 'model_id' );
		$front = gothic_selections_form_value( 'package_id' );
		$back = gothic_selections_form_value( 'package_by_id' );
		$palette = gothic_selections_form_value( 'palette_id' );
		$comments = gothic_selections_form_value( 'comments' );
		?>
		<?php if ( $builder ) : ?>
			<div class="selection-group"><h4>Your Homebuilder</h4>
				<?php
				$builder = get_post( $builder );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $builder->ID, 'preferences-tiles' ) ); ?>" height="160" width="280" alt="<?php echo get_the_title( $builder->post_name ); ?>" title="<?php echo get_the_title( $builder->post_name ); ?>">
					<br /><?php echo $builder->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $community ) : ?>
			<div class="selection-group"><h4>Your Community</h4>
				<?php
				$community = get_post( $community );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $community->ID, 'preferences-tiles' ) ); ?>" height="160" width="280" alt="<?php echo get_the_title( $community->post_name ); ?>" title="<?php echo get_the_title( $community->post_name ); ?>">
					<br /><?php echo $community->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $model ) : ?>
			<div class="selection-group"><h4>Your Model</h4>
				<?php
				$model = get_post( $model );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $model->ID, 'preferences-tiles' ) ); ?>" height="160" width="280" alt="<?php echo get_the_title( $model->post_name ); ?>" title="<?php echo get_the_title( $model->post_name ); ?>">
					<br /><?php echo $model->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $front ) : ?>
			<div class="selection-group"><h4>Your Front Yard</h4>
				<?php
				$front = get_post( $front );
				$image = get_post_meta( $model->ID, 'plan-' . $front->ID . '-image', true );
				?>
				<p>
					<img src="<?php echo esc_url( $image ); ?>" height="160" width="280" alt="<?php echo get_the_title( $front->post_name ); ?>" title="<?php echo get_the_title( $front->post_name ); ?>">
					<br /><?php echo $front->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $back ) : ?>
			<div class="selection-group"><h4>Your Backyard</h4>
				<?php
				$back = get_post( $back );
				$image = get_post_meta( $model->ID, 'plan-' . $back->ID . '-image', true );
				?>
				<p>
					<img src="<?php echo esc_url( $image ); ?>" height="160" width="280" alt="<?php echo get_the_title( $back->post_name ); ?>" title="<?php echo get_the_title( $back->post_name ); ?>">
					<br /><?php echo $back->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $palette ) : ?>
			<div class="selection-group"><h4>Your Plant Palette</h4>
				<?php
				$palette = get_post( $palette );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $palette->ID, 'preferences-tiles' ) ); ?>" height="160" width="280" alt="<?php echo get_the_title( $palette->post_name ); ?>" title="<?php echo get_the_title( $palette->post_name ); ?>">
					<br /><?php echo $palette->post_title; ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( $comments ) : ?>
			<div class="selection-group"><h4>Your Comments</h4><p><?php echo $comments; ?></p></div>
		<?php endif; ?>



	</div>
	<footer>
		<button><a href="<?php echo esc_url( get_home_url() ); ?>">Exit Landscape Selections</a></button>
	</footer>
<?php
