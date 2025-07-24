<?php
/**
 * Template : Admin / Palette Meta Box
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Admin Templates
 * @subpackage  Form Fields
 * @author      Jeremy Scott
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
 * @var string $type
 * @var string $name
 * @var string $label
 * @var string $sublabel
 * @var string $description
 * @var mixed $default
 * @var mixed $value
 * @var array $attributes
 * @var array $class
 */

use Gothic\Selections\Helper\Template;

?>

<?php if ( $options || is_array( $options ) ) : ?>

	<div class="<?php echo esc_attr( Template::classes( array_merge( $class, [ 'selectize' ] ) ) ); ?>">

		<div class="gothic-label">

			<?php if ( $label ) : ?>
				<h5 class="gothic-field-label">
					<label for="<?php echo esc_attr( $name ); ?>">
						<?php echo esc_html( $label ); ?>
					</label>
				</h5>
			<?php endif; ?>

			<?php if ( $sublabel ) : ?>
				<h6 class="gothic-field-sublabel">
					<?php echo esc_html( $sublabel ); ?>
				</h6>
			<?php endif; ?>

		</div>

		<div class="gothic-field">

			<select id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>[]" <?php echo ( ! empty( $options['multiple'] ) ) ? 'multiple' : ''; ?> >

				<?php

				$posts_args = array(
					'post_type'      => $options['post_type'],
					'posts_per_page' => 1000,
				);

				if ( ! empty( $options['taxonomy'] ) ) {
					if ( ! empty( $options['term'] ) ) {
						$posts_args['tax_query'] = array(
							array(
								'taxonomy' => $options['taxonomy'],
								'terms'    => $options['term'],
								'field'    => 'slug',
							),
						);
					} else {
						$posts_args['tax_query'] = array(
							array(
								'taxonomy' => $options['taxonomy'],
							),
						);
					}
				}

				$picker = new WP_Query( $posts_args );

				if ( $picker->have_posts() && ! is_wp_error( $picker ) ) {
					foreach ( $picker->get_posts() as $material ) {

						printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $material->ID ), selected( in_array( (string) $material->ID, $value, true ), true, false ), esc_html( $material->post_title ) );
					}
				}

				?>
			</select>

			<?php if ( $description ) : ?>
				<div class="gothic-field-description">
					<?php echo wp_kses_post( $description ); ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
<?php endif; ?>
