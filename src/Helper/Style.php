<?php
/**
 * Styles Helper
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Core
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Helper;

use Gothic\Selections\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Style
 *
 * @package Gothic\Selections\Core
 */
class Style {

	/**
	 * Add Style
	 *
	 * Register and enqueue a script or style
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $name         Name of the style
	 * @param array  $dependencies Array of dependencies. Optional. Default empty array.
	 *
	 * @return void
	 */
	public static function add( string $name, array $dependencies = [] ) : void {
		wp_register_style( $name, self::file( $name ), $dependencies, self::version() );
		wp_enqueue_style( $name );
	}

	/**
	 * Style Handle
	 *
	 * Generate a style (or script) handle. Combine the Filter Prefix with the passed name and append the class name,
	 * ie: plugin_prefix_admin_style or plugin_prefix_template_script
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $name The name of the style (or script)
	 *
	 * @return string
	 */
	public static function handle( string $name ) : string {

		return Plugin::FILTER_PREFIX . $name . '_' . strtolower( substr( strrchr( __CLASS__, '\\' ), 1 ) );
	}

	/**
	 * Styles Path
	 *
	 * Return the path to the styles folder.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function path() : string {
		return Plugin::$path . 'assets/css/';
	}

	/**
	 * Styles Directory
	 *
	 * Return the director to the styles folder.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function directory() : string {
		return Plugin::$directory . 'assets/css/';
	}

	/**
	 * File w/ URI
	 *
	 * Return the URL to the style.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $name The name of the script or style
	 *
	 * @return string
	 */
	public static function file( string $name ) : string {

		$local_file = '';

		if ( ! defined( 'WP_DEBUG' ) || ( true !== WP_DEBUG ) ) {
			$local_file = self::directory() . $name . '.min.css';
		}

		if ( file_exists( $local_file ) ) {

			return self::path() . $name . '.min.css';
		}

		return self::path() . $name . '.css';
	}

	/**
	 * Version
	 *
	 * Return a script and styles version based on the WP_DEBUG mode.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function version() : string {
		if ( defined( 'WP_DEBUG' ) && ( true === WP_DEBUG ) ) {

			return strtotime( 'now' );
		} else {

			return Plugin::VERSION;
		}
	}
}
