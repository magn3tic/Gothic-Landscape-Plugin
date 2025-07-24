<?php
/**
 * Admin Notice
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Admin
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\Plugin;

final class Notice {

	/**
	 * Transient Key
	 *
	 * @since 1.0.0
	 *
	 * @var string $key
	 */
	private static $key = 'gothic-selections-notices';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_notices', [ __CLASS__ , 'render' ] );
	}

	/**
	 * Add Notice
	 *
	 * Adds an admin notice to the array of notices to display on the next admin page load
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $name string
	 * @param $message string
	 * @param $type string Must be 'success', 'info', 'warning', or 'error'
	 *
	 * @return void
	 */
	public static function add( string $name, string $message, string $type = 'info' ) : void {

		$types = array( 'success', 'info', 'warning', 'error' );

		if ( empty( $type ) || ! in_array( $type, $types, true ) ) {
			$type = 'info';
		}

		$notices = get_transient( self::$key ) ?: array();

		$notices[$name] = [
			'message' => $message,
			'type'    => $type,
		];

		set_transient( self::$key, $notices );
	}

	/**
	 * Remove Notice
	 *
	 * Adds an admin notice from the array of notices to before the next admin page load
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $name
	 *
	 * @return void
	 */
	public static function remove( string $name ) : void {

		$notices = get_transient( self::$key ) ?: array();

		if ( ! empty( $notices ) ) {
			foreach ( $notices as $key => $notice ) {
				if ( $key === $name ) {
					unset( $notices[ $key ] );
				}
			}
			if ( ! empty( $notices ) ) {
				set_transient( self::$key, $notices );
			} else {
				delete_transient( self::$key );
			}
		}
	}

	/**
	 * Admin Notices
	 *
	 * Prints the admin notices to the WP Admin Screen
	 *
	 * @since   1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function render() : void {

		$notices = get_transient( self::$key );

		if ( $notices ) {
			foreach ( $notices as $key => $notice ) {
				printf(
					'<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>',
					esc_attr( $notice['type'] ),
					wp_kses_post( $notice['message'] )
				);
			}
		}

		delete_transient( self::$key );
	}
}
