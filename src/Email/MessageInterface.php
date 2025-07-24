<?php
/**
 * Email Class
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Email
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Email;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Message Interface
 *
 * @since 1.0.0
 *
 * @package matador\MatadorJobs\Email
 */
interface MessageInterface {

	/**
	 * Message
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	public static function message( array $args = [] );
}
