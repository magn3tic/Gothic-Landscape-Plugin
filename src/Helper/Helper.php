<?php
/**
 * Helper Functions
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Helpers
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Helper {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * WPS Subtitle Plugin Is Active
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function wps_subtitle_active() {

		return is_plugin_active( 'wp-subtitle/wp-subtitle.php' );
	}
}
