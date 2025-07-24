<?php
/**
 * Template Part : Select Builder Field
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
use Gothic\Selections\PostType\Builder as Builders;
?>

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

		<select id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php echo Template::attributes( $attributes ); ?> >

			<option value="-1" <?php selected( ( empty( $value ) || -1 === $value ), true, true ); ?> disabled><?php echo esc_html( $label ); ?></option>

			<?php
			$builders = new WP_Query( array( 'post_type' => Builders::$key ) );

			if ( $builders && ! is_wp_error( $builders ) ) :
				foreach ( $builders->get_posts() as $builder ) :
					?>
					<option value="<?php echo intval( $builder->ID ); ?>" <?php selected( $builder->ID, $value, true ); ?>>
						<?php echo esc_html( $builder->post_title ); ?>
					</option>
					<?php
				endforeach;
			endif;
			?>

		</select>

		<?php if ( $description ) : ?>
			<div class="field-description">
				<?php echo wp_kses_post( $description ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $errors ) && isset( $errors[ $name ] ) ) : ?>

			<p class="error"><?php echo esc_html( $errors[ $name ] ); ?> </p>

		<?php endif; ?>

	</div>

</div>
