<?php
/**
 * Template: Selections Step Certify
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

use Gothic\Selections\Helper\Template;

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
	<h1><?php esc_html_e( 'Please Confirm and Sign', 'gothic-selections' ); ?></h1>

	<p>
		<?php esc_html_e( 'You\'re almost there!', 'gothic-selections' ); ?>
		<?php echo( 'Please take a second to review your submitted information and <strong>complete your order by e-signing the form below</strong>.'); ?>
		<?php esc_html_e( 'Your signature will send your request to Gothic Landscape and your home builder for processing.', 'gothic-selections' ); ?>
	</p>

	<hr/>
	<h4 style="text-align: center"><?php esc_html_e( 'Your Contact Information', 'gothic-selections' ); ?></h4>

	<?php if ( gothic_selections_form_value( 'home_buyer', $request ) ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Your Name', 'gothic-selections' ); ?></h4>
			<p><?php echo gothic_selections_form_value( 'home_buyer', $request ); ?></p></div>
	<?php endif; ?>

	<?php if ( gothic_selections_form_value( 'email' ) ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Email Address', 'gothic-selections' ); ?></h4>
			<p><?php echo gothic_selections_form_value( 'email', $request ); ?></p></div>
	<?php endif; ?>

	<?php if ( gothic_selections_form_value( 'phone' ) ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Phone Number', 'gothic-selections' ); ?></h4>
			<p><?php echo gothic_selections_form_value( 'phone', $request ); ?></p></div>
	<?php endif; ?>

	<hr />

	<h4 style="text-align: center"><?php esc_html_e( 'About Your New Home', 'gothic-selections' ); ?></h4>

	<?php
	$builder   = gothic_selections_form_value( 'builder_id', $request );
	$community = gothic_selections_form_value( 'community_id', $request );
	$model     = gothic_selections_form_value( 'model_id', $request );
	?>
	<?php if ( $builder ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Your Homebuilder', 'gothic-selections' ); ?></h4>
			<?php
			$builder = get_post( $builder );
			?>
			<p>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $builder->ID, 'large' ) ); ?>"
				     height="160" width="280" alt="<?php echo get_the_title( $builder->post_name ); ?>"
				     title="<?php echo get_the_title( $builder->post_name ); ?>" class="adjustable-element w-100 h-auto">
				<br/><?php echo $builder->post_title; ?>
			</p>
		</div>
	<?php endif; ?>
	<?php if ( $community ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Your Community', 'gothic-selections' ); ?></h4>
			<?php
			$community = get_post( $community );
			?>
			<p>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $community->ID, 'large' ) ); ?>"
				     height="160" width="280" alt="<?php echo get_the_title( $community->post_name ); ?>"
				     title="<?php echo get_the_title( $community->post_name ); ?>" class="adjustable-element w-100 h-auto">
				<br/><?php echo $community->post_title; ?>
			</p>
		</div>
	<?php endif; ?>
	<?php if ( $model ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Your Model', 'gothic-selections' ); ?></h4>
			<?php
			$model = get_post( $model );
			?>
			<p>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $model->ID, 'large' ) ); ?>"
				     height="160" width="280" alt="<?php echo get_the_title( $model->post_name ); ?>"
				     title="<?php echo get_the_title( $model->post_name ); ?>" class="adjustable-element w-100 h-auto">
				<br/><?php echo $model->post_title; ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( gothic_selections_form_value( 'address' ) || gothic_selections_form_value( 'city', $request ) || gothic_selections_form_value( 'state', $request ) || gothic_selections_form_value( 'zip', $request ) ) : ?>
		<div class="selection-group">
			<h4><?php esc_html_e( 'New Home Address', 'gothic-selections' ); ?></h4>
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
		<div class="selection-group"><h4><?php esc_html_e( 'New Home Lot Number', 'gothic-selections' ) ?></h4>
			<p><?php echo gothic_selections_form_value( 'lot', $request ); ?></p></div>
	<?php endif; ?>

	<?php if ( gothic_selections_form_value( 'builder_rep_name', $request ) ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Builder Sales Associate Name', 'gothic-selections' ); ?></h4>
			<p><?php echo gothic_selections_form_value( 'builder_rep_name', $request ); ?></p></div>
	<?php endif; ?>

	<hr />

	<h4 style="text-align: center"><?php esc_html_e( 'Your Selections', 'gothic-selections' ); ?></h4>

	<?php
	$front     = gothic_selections_form_value( 'package_id', $request );
	$back      = gothic_selections_form_value( 'backyard_id', $request );
	$palette   = gothic_selections_form_value( 'palette_id', $request );
	$back_pal  = gothic_selections_form_value( 'by_palette_id', $request );
	$back_opt_in = gothic_selections_form_value( 'opt_in_backyard_upsell', $request );
	$comments  = gothic_selections_form_value( 'comments', $request );
	?>

	<?php if ( $front ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Front Yard Package', 'gothic-selections' ); ?></h4>
			<?php
			$front = get_post( $front );
			$image = get_post_meta( $model->ID, 'plan-' . $front->ID . '-image', true );
			?>
			<p>
				<img src="<?php echo esc_url( wp_get_attachment_image_url($image, "large") ); ?>" height="160" width="280"
				     alt="<?php echo get_the_title( $front->post_name ); ?>"
				     title="<?php echo get_the_title( $front->post_name ); ?>" class="adjustable-element w-100 h-auto">
				<br/><?php echo $front->post_title; ?>
			</p>
		</div>
	<?php endif; ?>
	<?php if ( $palette ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Front Yard Plant Palette', 'gothic-selections' ); ?></h4>
			<?php
			$palette = get_post( $palette );
			?>
			<p>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $palette->ID, 'large' ) ); ?>"
				     height="160" width="280" alt="<?php echo get_the_title( $palette->post_name ); ?>"
				     title="<?php echo get_the_title( $palette->post_name ); ?>" class="adjustable-element w-100 h-auto">
				<br/><?php echo $palette->post_title; ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( $back ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Back Yard Package', 'gothic-selections' ); ?></h4>
			<?php
			$back  = get_post( $back );
			$image = get_post_meta( $model->ID, 'plan-' . $back->ID . '-image', true );
			?>
			<p>
				<img src="<?php echo esc_url( wp_get_attachment_image_url($image, 'large') ); ?>" height="160" width="280"
				     alt="<?php echo get_the_title( $back->post_name ); ?>"
				     title="<?php echo get_the_title( $back->post_name ); ?>" class="adjustable-element w-100 h-auto">
				<br/><?php echo $back->post_title; ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( $back_pal ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Back Yard Plant Palette', 'gothic-selections' ); ?></h4>
			<?php
			$back_pal = get_post( $back_pal );
			?>
			<p>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $back_pal->ID, 'large' ) ); ?>"
				     height="160" width="280" alt="<?php echo get_the_title( $back_pal->post_name ); ?>"
				     title="<?php echo get_the_title( $back_pal->post_name ); ?>" class="adjustable-element w-100 h-auto">
				<br/><?php echo $back_pal->post_title; ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( $back_opt_in ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Back Yard', 'gothic-selections' ); ?></h4>
			<p>
				<?php esc_html_e( 'Yes, you\'d like Gothic Landscape to reach out to you about backyard options.', 'gothic-selections' ); ?>
			</p>
		</div>
	<?php endif; ?>


	<?php if ( $comments ) : ?>
		<div class="selection-group"><h4><?php esc_html_e( 'Your Comments', 'gothic-selections' ); ?></h4>
			<p><?php echo $comments; ?></p></div>
	<?php endif; ?>
	<hr/>
	<h1><?php esc_html_e( 'Confirmation', 'gothic-selections' ); ?></h1>
	<p><em>
		<?php esc_html_e( 'We now ask you to confirm that the information provided during this process is complete.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'With your digital signature you complete this process and submit your Landscape Selection to your home builder and to Gothic Landscape.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'Once you submit, you will be unable to make further revisions to your Landscape Selection through this website.', 'gothic-selections' ); ?>
	</em></p>
	<?php
	Template::form_field( [
		'type'       => 'checkbox',
		'label'      => __( 'I Accept Gothic Landscape Arizona\'s Terms of Service and Privacy Policy', 'gothic-selections' ),
		'name'       => 'accept_terms',
		'value'      => gothic_selections_form_value( 'accept_terms' ),
		'attributes' => [
			'required' => true,
		]
	], true );
	Template::form_field( [
		'type'       => 'text',
		'label'      => __( 'Your Full Legal Name', 'gothic-selections' ),
		'name'       => 'confirm_name',
		'value'      => gothic_selections_form_value( 'confirm_name' ),
		'attributes' => [
			'required' => true,
		]
	], true );
	?>
</div>

<footer>
	<a class="button button-secondary"
	   href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'info' ); ?>"><?php esc_html_e( 'Back', 'gothic-selections' ); ?></a>
	<button><?php esc_html_e( 'Complete', 'gothic-selections' ); ?></button>
</footer>

