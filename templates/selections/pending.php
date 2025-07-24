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

	<h3 class="c-px-3"><?php echo esc_html_e( 'Awaiting Your Homeseller', 'gothic-selections' ); ?></h3>

	<p class="c-px-3">
		<?php esc_html_e( 'We require your home seller\'s salesperson to make the proper updates to your record before you continue.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'We have emailed them notice of your concerns and asked them to log in and make changes.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'We will email you when you should resume your selections.', 'gothic-selections' ); ?>
		<?php esc_html_e( 'Given the time-sensitive nature of this process, it is a good idea to call your salesperson to also follow-up.', 'gothic-selections' ); ?>
	</p>

	<footer class="d-flex justify-content-start c-mx-3">
		<a class="crunch-button crunch-button__full-background crunch-button__full-background--primary-color" href="<?php echo esc_url( get_home_url() ); ?>"><?php esc_html_e( 'Exit', 'gothic-selections' ); ?></a>
	</footer>

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
