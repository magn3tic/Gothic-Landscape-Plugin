<?php
/**
 * Template: Selections Progress Bar
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

use Gothic\Selections\Helper\Misc;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$screens = [ 'info', 'certify', 'model', 'packages', 'preferences' ];

if ( in_array( $screen, $screens, true ) ) :
	?>
	<div id="landscape-selection-progress" class="landscape-selection-progress <?php echo esc_attr( $screen ?: '' ); ?>">
		<ul>
			<li class="model"><span class="your">Your</span> Home</li>
			<li class="package"><span class="your">Your</span> Package</li>
			<?php if ( ! Misc::selection_has_no_preferences( get_the_ID() ) ) : ?>
			<li class="preferences"><span class="your">Your</span> Preferences</li>
			<?php endif; ?>
			<li class="info"><span class="your">Your</span> Info</li>
		</ul>
	</div>
	<?php
endif;