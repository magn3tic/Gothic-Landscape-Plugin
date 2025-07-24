<?php
/**
 * Dashed and Underscored Trait
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Helper
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

/**
 * Trait DashedUnderscoredTrait
 *
 * @since 1.0.0
 *
 * @package Gothic\Selections\Helper
 */
trait DashedUnderscoredTrait {
	/**
	 * Dashed to Underscored
	 *
	 * Returns the string with underscores instead of dashes.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	protected static function dashes_underscored( string $string ) : string {

		return str_replace( '-', '_', $string );
	}

	/**
	 * Key Underscored
	 *
	 * Returns the string with dashes instead of underscores.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	protected static function underscores_dashed( string $string ) : string {

		return str_replace( '_', '-', static::$key );
	}
}