<?php
/**
 * Scripts Helper
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

use Gothic\Selections\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Script extends Style {

	/**
	 * Add
	 *
	 * Register and enqueue a script.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $name         Name of the script
	 * @param array  $dependencies Array of dependencies. Optional. Default empty array.
	 * @param bool   $in_footer    Whether to put in <head> or <footer>. Optional. Default true.
	 * @param array  $i18n         An array of localization. Option. Default empty.
	 *
	 * @return void
	 */
	public static function add( string $name, array $dependencies = [], bool $in_footer = true, array $i18n = [] ) : void {
		wp_register_script( $name, self::file( $name ), $dependencies, self::version(), $in_footer );

		if ( $i18n ) {
			wp_localize_script( $name, $name, $i18n );
		}

		wp_enqueue_script( $name );
	}

	/**
	 * Script/Style Handle
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
	 * Path
	 *
	 * Return the path to scripts folder.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function path() : string {

		return Plugin::$path . 'assets/js/';
	}

	/**
	 * Directory
	 *
	 * Return the directory to scripts folder.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function directory() : string {

		return Plugin::$directory . 'src/assets/js/';
	}

	/**
	 * Filename
	 *
	 * Return the URL to the style.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $name The name of the script
	 *
	 * @return string
	 */
	public static function file( string $name ) : string {

		$local_file = '';

		if ( ! defined( 'WP_DEBUG' ) || ( true !== WP_DEBUG ) ) {
			$local_file = self::directory() . $name . '.min.js';
		}

		if ( file_exists( $local_file ) ) {

			return self::path() . $name . '.min.js';
		}

		return self::path() . $name . '.js';
	}
}
