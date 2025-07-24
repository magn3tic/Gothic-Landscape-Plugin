<?php
/**
 * Template Part : File Field
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

<div class="<?php Template::classes( $class ); ?>">

	<div class="gothic-label">

		<?php if ( $label ) : ?>
			<h5 class="gothic-field-label"><label>
					<?php echo esc_html( $label ); ?>
				</label></h5>
		<?php endif; ?>

		<?php if ( $sublabel ) : ?>
			<h6 class="gothic-field-sublabel">
				<?php echo esc_html( $sublabel ); ?>
			</h6>
		<?php endif; ?>

	</div>

	<div class="gothic-field">

		<div class="gothic-input-file-box">

			<?php $id = rand( 1000, 9999 ); ?>

			<input type="file" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" class="inputfile inputfile-2" data-multiple-caption="{count} <?php esc_html_e( 'files selected', 'gothic-selections' ); ?>"/>
			<label for="<?php echo esc_attr( $id ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
					<path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
					<span><?php echo esc_html( $label ); ?> &hellip;</span>
				</svg>
			</label>
		</div>

		<?php if ( $description ) : ?>
			<div class="gothic-field-description"><?php echo wp_kses_post( $description ); ?></div>
		<?php endif; ?>

	</div>

</div>
