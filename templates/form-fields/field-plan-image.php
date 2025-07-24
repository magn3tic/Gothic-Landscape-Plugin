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

$image = wp_get_attachment_url( $value );

global $post;

?>

<div class="<?php echo Template::classes( $class ); ?>">

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
			<div id="preview-<?php echo esc_attr( $name ); ?>" class="plan-image <?= !$image ? "no-image" : "";  ?>" <?php echo $image ? 'style="background-image: url(\'' . esc_url( $image ) . '\');>"' : ''; ?>></div>
		
		<input type="hidden" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />

		<p>
			<a class="button-primary" title="<?php esc_attr_e( 'Set Image', 'gothic-selections' ); ?>" href="#" id="set-<?php echo esc_attr( $name ); ?>">
				<?php esc_html_e( 'Set Image', 'gothic-selections' ); ?>
			</a>
			<a class="button-secondary" title="<?php esc_attr_e( 'Remove', 'gothic-selections' ); ?>" href="#" id="remove-<?php echo esc_attr( $name ); ?>">
				<?php esc_html_e( 'Remove Image', 'gothic-selections' ); ?>
			</a>
		</p>

		<script type="text/javascript">
			jQuery(document).ready(function($){
				var mediaUploader;
				$('#set-<?php echo esc_attr( $name ); ?>').click(function(e) {
					e.preventDefault();
					if (mediaUploader) {
						mediaUploader.open();
						return;
					}
					mediaUploader = wp.media.frames.file_frame = wp.media({
						title: '<?php esc_html_e( 'Choose Image', 'gothic-selections' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Choose Image', 'gothic-selections' ); ?>',
						}, multiple: false });
					mediaUploader.on('select', function() {
						var attachment = mediaUploader.state().get('selection').first().toJSON();
						$('#<?php echo esc_attr( $name ); ?>').val(attachment.id);
						$('#preview-<?php echo esc_attr( $name ); ?>').attr('style', 'background-image: url(\'' + attachment.url + '\');' );
						$('#preview-<?php echo esc_attr( $name ); ?>').removeClass("no-image");
					});
					mediaUploader.open();
				});
				$('#remove-<?php echo esc_attr( $name ); ?>').click(function(e) {
					e.preventDefault();
					$('#<?php echo esc_attr( $name ); ?>').val(0);
					$('#preview-<?php echo esc_attr( $name ); ?>').attr('style', '');
				});
			});
		</script>

		<?php if ( $description ): ?>
		<p class="description">
			<?php echo esc_html( $description ); ?>
		</p>
		<?php endif; ?>

	</div>

</div>
