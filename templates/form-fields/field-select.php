<?php
/**
 * Template Part : Select Field
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Templates
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

	<div class="<?php echo esc_attr( Template::classes( $class ) ); ?>">

		<div class="label">

			<?php if ( $label ) : ?>
				<h5 class="field-label">
					<label for="<?php echo esc_attr( $name ); ?>">
						<?php echo esc_html( $label ); ?>
					</label>
				</h5>
			<?php endif; ?>

			<?php if ( $sublabel ) : ?>
				<h6 class="field-sublabel">
					<?php echo esc_html( $sublabel ); ?>
				</h6>
			<?php endif; ?>

		</div>

		<div class="field">

			<select id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>">

				<?php foreach ( $options as $option_value => $option_name ) : ?>

					<?php printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $option_value ), selected( $value, $option_value, false ), esc_html( $option_name ) ); ?>

				<?php endforeach; ?>

			</select>

			<?php if ( $description ) : ?>
				<div class="gothic-field-description">
					<?php echo wp_kses_post( $description ); ?>
				</div>
			<?php endif; ?>

		</div>

	</div>

<?php endif; ?>
