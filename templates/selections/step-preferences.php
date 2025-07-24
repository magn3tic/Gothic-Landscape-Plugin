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

$selected_front = get_post_meta( get_the_id(), 'palette_id', true ) ?: ( isset( $request[ 'palette_id' ] ) ? intval( $request[ 'palette_id' ] ) : null );
$selected_back  = get_post_meta( get_the_id(), 'by_palette_id', true ) ?: ( isset( $request[ 'by_palette_id' ] ) ? intval( $request[ 'by_palette_id' ] ) : null );

$fy_package = get_post_meta( get_the_ID(), 'package_id', true );
if ( get_post_meta( $fy_package, 'no_palette', true ) ) {
	$has_frontyard = false;
} else {
	$has_frontyard = $fy_package;
}

$by_package = get_post_meta( get_the_ID(), 'backyard_id', true );
if ( get_post_meta( $by_package, 'no_palette', true ) ) {
	$has_backyard = false;
} else {
	$has_backyard = $fy_package;
}

?>
<h1><?php esc_html_e( 'Your Landscape Preferences', 'gothic-selections' ); ?></h1>
<p>
	<?php esc_html_e( 'Gothic Landscape has taken the time to arrange common, hearty, Arizona-ready plants into "palettes" that compliment each other with color, flowering seasons, and growth.', 'gothic-selections' ); ?>
	<?php esc_html_e( 'Based on the time of year we install your landscaping, we\'ll landscape your property with plants from this palette appropriate for the planting season.', 'gothic-selections' ); ?>
	<?php esc_html_e( 'Please, select a palette for your new home\'s landscaping.', 'gothic-selections' ); ?>
</p>

<?php if ( ! empty( $errors['invalid'] ) ) : ?>
	<div class="invalid-notice">
		<p><?php echo esc_html( $errors['invalid'] ); ?></p>
	</div>
<?php endif; ?>

<?php $palettes = Queries::palettes( [ 'community_id' => (int) get_post_meta( get_the_ID(), 'community_id', true ), "inactive" => false ], true ); ?>

<?php if ( $palettes ) : ?>

	<ul class="palettes boxes">
		<?php foreach ( $palettes as $palette ) : ?>
			<li>
				<h4><?php echo esc_html( $palette->post_title ); ?></h4>

				<figure class="image" <?php echo get_the_post_thumbnail_url( $palette->ID, 'medium' ) ? 'style="background-image: url(\'' . get_the_post_thumbnail_url( $palette->ID, 'medium' ) . '\');"' : ''; ?>></figure>

				<div class="description">
					<?php echo apply_filters( 'the_content', $palette->post_content ); // WPCS: XSS ok. ?>
				</div>

				<div class="expand">
					<a href="#palette-<?php echo esc_attr( $palette->ID ); ?>">
						<?php esc_html_e( 'Preview plants in this palette.', 'gothic-selections' ); ?>
					</a>
					<!-- <a href="<?= home_url("landscaping/plant-guide/?palette=".  esc_attr( $palette->ID )); ?>" target="_blank">
						<?php esc_html_e( 'Preview plants in this palette.', 'gothic-selections' ); ?>
					</a> -->
				</div>

				<?php
				if ( $has_frontyard ) :
					Template::form_field( array(
						'type'       => 'radio',
						'label'      => null,
						'name'       => 'palette_id',
						'class'      => array( 'select' ),
						'value'      => $selected_front,
						'options'    => array(
							$palette->ID => $has_backyard ? __( 'Select for Front', 'gothic-selections' ) : __( 'Select this Palette', 'gothic-selections' ),
						),
					), true );
				endif;
				if ( $has_backyard ) :
					Template::form_field( array(
						'type'       => 'radio',
						'label'      => null,
						'name'       => 'by_palette_id',
						'class'      => array( 'select' ),
						'value'      => $selected_back,
						'options'    => array(
							$palette->ID => $has_frontyard ? __( 'Select for Back', 'gothic-selections' ) : __( 'Select this Palette', 'gothic-selections' ),
						),
					), true );
				endif;
				?>
				<?php
				echo '<div id="palette-' . esc_attr( $palette->ID ) . '" class="palette-popout" style="display:none;">';
				$materials_types = get_terms(
					array(
						'taxonomy'   => 'materials-types',
						'hide_empty' => true,
						'order'      => 'DESC',
						'orderby'    => 'name'
					)
				);
				foreach ( $materials_types as $term => $args ) {
					$contents = get_post_meta( $palette->ID, $args->slug . '-field', true );
					if ( ! empty( $contents ) ) {
						$plants = new WP_Query( array(
							'post_type' => 'materials',
							'post__in'  => array_map( 'intval', $contents ),
							'order'     => 'ASC'
						) );
						if ( ! is_wp_error( $plants ) && $plants->have_posts() ) {
							echo '<h2>' . esc_html( $args->name ) . '</h2>';

							$plants_list = $plants->get_posts();

							foreach ( $plants_list as $plant ) {

								echo '<a href="'.get_the_permalink($plant->ID).'" target="_blank" class="palette_item hover-opacity-0-75">';

								if ( get_the_post_thumbnail_url( $plant->ID ) ) {
									$thumb = get_the_post_thumbnail_url( $plant->ID );
								} else {
									$thumb = 'http://via.placeholder.com/300x200';
								}

								printf( '<div class="palette_item_image" style="background-image: url(\'%s\')" /></div>', esc_url( $thumb ) );
								echo '<p class="palette_item_name">' . esc_html( $plant->post_title ) . '</p>';
								echo '<p class="palette_item_subname">' . esc_html( get_the_subtitle( $plant->ID, '', '', false ) ) . '</p>';

								echo '</a>';

							}
						}
					}
				}
				?>
				<p><em><?php esc_html_e( 'Plant materials from this palette will be used in your landscape installation, but the exact items will be selected based on availability and seasonal considerations.', 'gothic-selections' ); ?></em></p>
				<?php
				echo '</div>';
				?>

			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>


<div class="comments">
	<h4><?php esc_html_e( 'Special Requests?', 'gothic-selections' ); ?></h4>
	<p>
		<?php esc_html_e( 'If there is anything you\'d like us to know about your landscaping preferences, such as any allergies your family are sensitive to or specific color preferences, please tell us here.', 'gothic-selections' ); ?>
        <?php esc_html_e( 'We will always do our best to honor your requests, but general comments are more likely to be accommodated.', 'gothic-selections' ); ?>
	</p>
	<?php
	Template::form_field( array(
		'type'  => 'textarea',
		'label' => null,
		'name'  => 'comments',
		'value' => get_post_meta( get_the_id(), 'comments', true ) ?: ( isset( $request[ 'comments' ] ) ? sanitize_text_field( $request[ 'comments' ] ) : null ),
	), true );
	?>
</div>

<footer>
	<a class="button button-secondary" href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'package' ); ?>"><?php esc_html_e( 'Back', 'gothic-selections' ); ?></a>
	<button><?php esc_html_e( 'Continue', 'gothic-selections' ); ?></button>
</footer>