<?php
/**
 * Template Part : Checkbox Field
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


<div class="<?php echo esc_attr( Template::classes( $class ) ); ?>">

<!--	<div class="label">-->
<!---->
<!--		--><?php //if ( $label ) : ?>
<!--			<h5 class="field-label"><label>-->
<!--					--><?php //echo esc_html( $label ); ?>
<!--				</label></h5>-->
<!--		--><?php //endif; ?>
<!---->
<!--		--><?php //if ( $sublabel ) : ?>
<!--			<h6 class="field-sublabel">-->
<!--				--><?php //echo esc_html( $sublabel ); ?>
<!--			</h6>-->
<!--		--><?php //endif; ?>
<!---->
<!--	</div>-->

	<div class="field">

		<ul class="checkboxes">

			<?php if ( $options && is_array( $options ) ) : ?>

				<?php foreach ( $options as $option_value => $option_name ) : ?>

					<li class="checkbox">

						<?php

						$id = $name . '-' . $option_value;

						$checked = ( null !== $value ) ? checked( in_array( $option_value, $value, true ), true, false ) : '';

						$input = sprintf( '<input type="checkbox" id="%1$s" name="%2$s[]" value="%3$s" %4$s $5$s />', $id, $name, $option_value, $checked, Template::attributes( $attributes ) );

						printf( '%2$s<label for="%1$s">%3$s</label>', esc_attr( $id ), $input, esc_html( $option_name ) );

						?>

					</li>

				<?php endforeach; ?>

			<?php else : ?>

				<li class="checkbox">

					<?php

					printf( '<label for="%1$s"><input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s %3$s/>%4$s</label>', $name, checked( $value, 1, false ), Template::attributes( $attributes ), $label );

					?>

				</li>

			<?php endif; ?>

		</ul>

		<?php if ( $description ) : ?>
			<div class="gothic-field-description"><?php echo wp_kses_post( $description ); ?></div>
		<?php endif; ?>

	</div>

</div>
