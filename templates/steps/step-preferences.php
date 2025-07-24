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

$selected_palette = get_post_meta( get_the_id(), 'palette_id', true ) ?: ( isset( $request[ 'palette_id' ] ) ? intval( $request[ 'palette_id' ] ) : null );

?>
<h1><?php esc_html_e( 'Your Landscape Preferences', 'gothic-selections' ); ?></h1>
<p><?php esc_html_e( 'Gothic Landscape has taken the time to arrange common, hearty, Arizona-ready plants into "palettes" that compliment each other with color, flowering seasons, and growth. Based on the time of year we install your landscaping, we\'ll landscape your property with plants from this palette appropriate for the planting season. Please, select a palette for your new home\'s landscaping.', 'gothic-selections' ); ?></p>

<?php $palettes = Queries::palettes( [ 'community_id' => (int) get_post_meta( get_the_ID(), 'community_id', true ), "inactive" => false ], true ); ?>

<?php if ( $palettes ) : ?>

	<ul class="palettes boxes">
		<?php foreach ( $palettes as $palette ) : ?>
			<li>
				<h4>
					<?php echo esc_html( $palette->post_title ); ?>
				</h4>

				<div style="width: 100%; height: 200px; background-image: url( <?php echo get_the_post_thumbnail_url( $palette->ID, 'medium' ); ?>); background-size: cover;"></div>

				<?php echo apply_filters( 'the_content', $palette->post_content ); // WPCS: XSS ok. ?>
				<p style="text-align: center; margin-top: 20px;"><a
							href="#palette-<?php echo esc_attr( $palette->ID ); ?>" class="expand">Preview plants in
						this palette.</a></p>
				<?php
				Template::form_field( array(
					'type'       => 'radio',
					'label'      => null,
					'name'       => 'palette_id',
					'class'      => array( 'select' ),
					'value'      => $selected_palette,
					'options'    => array(
						$palette->ID => __( 'Select This Palette', 'gothic-selections' ),
					),
					'attributes' => array(
						'required' => true,
					),
				), true );
				?>
				<?php
				echo '<div id="palette-' . esc_attr( $palette->ID ) . '" class="palette-popout" style="display: none">';
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

								echo '<div class="palette_item">';

								if ( get_the_post_thumbnail_url( $plant->ID ) ) {
									$thumb = get_the_post_thumbnail_url( $plant->ID );
								} else {
									$thumb = 'http://via.placeholder.com/300x200';
								}

								printf( '<div class="palette_item_image" style="background-image: url(\'%s\')" /></div>', esc_url( $thumb ) );
								echo '<p class="palette_item_name">' . esc_html( $plant->post_title ) . '</p>';
								echo '<p class="palette_item_subname">' . esc_html( get_the_subtitle( $plant->ID, '', '', false ) ) . '</p>';

								echo '</div>';

							}
						}
					}
				}
				echo '</div>';
				?>

			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>


<div class="comments">
	<h4>Special Requests?</h4>
	<p>If there is anything you'd like us to know about your landscaping preferences, such as any allergies your
		family are sensitive to or specific color preferences, please tell us here. We will always do our best
		to honor your requests, but general comments are more likely to be accommodated.</p>
	<?php
	Template::form_field( array(
		'type'  => 'textarea',
		'label' => null,
		'name'  => 'comments',
		'value' => get_post_meta( get_the_id(), 'comments', true ) ?: ( isset( $request[ 'comments' ] ) ? santitize_text_field( $request[ 'comments' ] ) : null ),
	), true );
	?>
</div>

<footer>
	<p>
		<button><a href="<?php echo esc_url( get_permalink( get_the_ID() ) . '/step/package' ); ?>">Back</a></button>
	</p>
	<p>
		<button>Continue</button>
	</p>
</footer>