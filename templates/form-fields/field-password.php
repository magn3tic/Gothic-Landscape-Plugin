<?php
/**
 * Template Part : Password Field
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

	<div class="gothic-label">

		<?php if ( $label ) : ?>
			<h5 class="gothic-field-label">
				<label for="<?php echo esc_attr( $name ); ?>">
					<?php echo esc_html( $label ); ?>
				</label>
			</h5>
		<?php endif; ?>
		<?php if ( $sublabel ) : ?>
			<h6 class="gothic-field-sublabel"><label>
					<?php echo esc_html( $sublabel ); ?>
				</label></h6>
		<?php endif; ?>

	</div>
	<div class="gothic-field">

		<?php printf( '<input type="password" id="%1$s" name="%1$s" value="%2$s" %3$s />', esc_attr( $name ), esc_attr( $value ), Template::attributes( $attributes ) ); ?>

		<?php if ( $description ) : ?>
			<div class="gothic-field-description">
				<?php echo wp_kses_post( $description ); ?>
			</div>
		<?php endif; ?>

	</div>

</div>
