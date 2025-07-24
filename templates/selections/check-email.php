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
?>
<div>

	<h3><?php echo esc_html_e( 'Check Your Email', 'gothic-selections' ); ?></h3>

	<p>
		<?php esc_html_e( 'We found an existing Landscape Preferences Order for your email address and lot number. We emailed you your access code and a URL to continue with your selection. Please follow the link on your email to continue.', 'gothic-selections' ) ?>
	</p>

</div>

<?php
$page  = get_post( get_page_by_path( 'landscaping/selection' ) );
$image = get_the_post_thumbnail_url( $page->ID, 'original' );
?>

<?php if ( $image ) : ?>
	<aside>
		<img src="<?php echo esc_url( $image ); ?>"/>
	</aside>
<?php endif; ?>
