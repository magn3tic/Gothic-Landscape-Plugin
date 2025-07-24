<?php
/**
 * Template: Selections Step Model (New)
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

use Gothic\Selections\PostType\{ Community as Communities, Builder as Builders, Model as Home_Models};

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

$selected_builder   = get_post_meta( get_the_id(), 'builder_id', true ) ?: ( isset( $request[ '_' . Builders::$key ] ) ? $request[ '_' . Builders::$key ] : null );
$selected_community = get_post_meta( get_the_id(), 'community_id', true ) ?: ( isset( $request[ '_' . Communities::$key ] ) ? $request[ '_' . Communities::$key ] : null );
$selected_model     = get_post_meta( get_the_id(), 'model_id', true ) ?: ( isset( $request[ '_' . Home_Models::$key ] ) ? $request[ '_' . Home_Models::$key ] : null );
?>
	<h1><?php esc_html_e( 'Your Builder, Community, and Model', 'gothic-selections' ); ?></h1>
	<p><?php esc_html_e( 'Before we can get started, we will need to know a little about your new home. Please note that it is very important you provide all the correct information, as it may affect our ability to match your preferences request to your new.', 'gothic-selections' ); ?></p>
	<ul class="boxes">
		<li class="homebuilder field-container">
			<div class="field">
				<label for="_<?php echo esc_attr( Builders::$key ); ?>"><?php esc_html_e( 'Select Your Homebuilder', 'gothic-selections' ); ?></label>
				<select name="_<?php echo esc_attr( Builders::$key ); ?>" required="required">

					<option value="-1" <?php echo ( ! $selected_builder ) ? 'selected' : ''; ?> disabled><?php esc_html_e( 'Select Your Homebuilder', 'gothic-selections' ); ?></option>
					<?php
					$builders = new WP_Query( array( 'post_type' => Builders::$key ) );
					if ( $builders && ! is_wp_error( $builders ) ) {
						foreach ( $builders->get_posts() as $builder ) {
							printf(
								'<option value="%s" data-image="%s" %s>%s</option>',
								intval( $builder->ID ),
								esc_url( get_the_post_thumbnail_url( $builder->ID, 'preferences-tiles' ) ),
								selected( $selected_builder, $builder->ID, false ),
								esc_html( $builder->post_title )
							);
						}
					}
					?>
				</select>
			</div>
			<?php if ( $selected_builder ) : ?>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $selected_builder, 'preferences-tiles' ) ); ?>"
				     height="160" width="280" alt="<?php echo get_the_title( $selected_builder ); ?>"
				     title="<?php echo get_the_title( $selected_builder ); ?>">
			<?php endif; ?>
		</li>
		<li class="community field-container">
			<div class="field">
				<label for="_<?php echo esc_attr( Communities::$key ); ?>"><?php esc_html_e( 'Select Your Community', 'gothic-selections' ); ?></label>
				<select name="_<?php echo esc_attr( Communities::$key ); ?>" required="required">
					<option value="-1" <?php echo ( ! $selected_community ) ? 'selected' : ''; ?> disabled><?php esc_html_e( 'Select Your Community', 'gothic-selections' ); ?></option>
					<?php
					$communities = new WP_Query( array( 'post_type' => Communities::$key, ) );
					if ( $communities && ! is_wp_error( $communities ) ) {
						foreach ( $communities->get_posts() as $community ) {
							printf(
								'<option value="%s" data-image="%s" %s>%s</option>',
								intval( $community->ID ),
								esc_url( get_the_post_thumbnail_url( $community->ID, 'preferences-tiles' ) ),
								selected( $community->ID, $selected_community, false ),
								esc_html( $community->post_title )
							);
						}
					}
					?>
				</select>
			</div>

			<?php if ( $selected_community ) : ?>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $selected_community, 'preferences-tiles' ) ); ?>"
				     height="160" width="280" alt="<?php echo get_the_title( $selected_community ); ?>"
				     title="<?php echo get_the_title( $selected_community ); ?>">
			<?php endif; ?>
		</li>
		<li class="model field-container">
			<div class="field">
				<label for="_<?php echo esc_attr( Home_Models::$key ); ?>"><?php esc_html_e( 'Select Your Model', 'gothic-selections' ); ?></label>
				<select name="_<?php echo esc_attr( Home_Models::$key ); ?>" required="required">
					<option value="-1" <?php echo ( ! $selected_model ) ? 'selected' : ''; ?> disabled><?php esc_html_e( 'Select Your Model', 'gothic-selections' ); ?></option>
					<?php
					$models = new WP_Query( array( 'post_type' => Home_Models::$key ) );
					if ( $models && ! is_wp_error( $models ) ) {
						foreach ( $models->get_posts() as $model ) {
							printf(
								'<option value="%s" data-image="%s" %s>%s</option>',
								intval( $model->ID ),
								esc_url( get_the_post_thumbnail_url( $model->ID, 'preferences-tiles' ) ),
								selected( $model->ID, $selected_model, false ),
								esc_html( $model->post_title ) . ( get_post_meta( $model->ID, 'plan_id', true ) ? esc_html( ' (' . get_post_meta( $model->ID, 'plan_id', true ) . ')' ) : '' )
							);
						}
					}
					?>
				</select>
			</div>

			<?php if ( $selected_model ) : ?>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $selected_model, 'preferences-tiles' ) ); ?>"
				     height="160" width="280" alt="<?php echo get_the_title( $selected_model ); ?>"
				     title="<?php echo get_the_title( $selected_model ); ?>">
			<?php endif; ?>
		</li>
	</ul>

	<footer>
		<button name="_current_step" value="model"><?php esc_html_e( 'Save & Continue', 'gothic-selections' ); ?></button>
	</footer>
<?php
