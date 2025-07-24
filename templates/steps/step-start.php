<?php
/**
 * Template: Selections Step Start
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

$page = get_post( get_page_by_path( 'landscaping/preferences' ) );

$default_headline = __( 'One of the most charming parts of a new home is the landscaping. The following screens will walk you through the selection of your landscaping preferences.', 'gothic-theme' );
$default_content  = __( 'Congratulations on your new home! Gothic Landscape of Arizona was asked by your home builder to install landscaping for your new home right before you move in. We’ve worked closely with your home builder to design landscaping that is full of character! The best way you can help us do our best work is to provide us a little information about your preferences so, to the best of our ability, we can give your landscaping a personal touch. The following 4-step process will take about 5 to 10 minutes to complete. We are so excited for you and for the opportunity to landscape your new home!', 'gothic-theme' );

if ( $page ) {
	?>
	<div class="w-100 ">

		<h3><?php echo esc_html( $page ? get_the_subtitle( $page->ID ) : $default_headline ); ?></h3>

		<?php echo $page ? apply_filters( 'the_content', $page->post_content ) : $default_content; // WPCS: XSS ok. ?>

		<p>
			<button>Continue</button>
		</p>

	</div>
	<?php
	$image = get_the_post_thumbnail_url( $page->ID, 'original' );
	if ( $image ) {
		echo '<aside><img src="' . esc_url( $image ) . '" /></aside>';
	}
}
