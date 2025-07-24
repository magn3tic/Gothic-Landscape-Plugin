<?php
/**
 * User Roles Abstract Class
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  User
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\User\Role;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \WP_Role;
use \WP_Roles;

/**
 * User Role Abstract Class
 *
 * @since 1.0.0
 */
abstract class RoleAbstract implements RoleInterface {

	/**
	 * User Role Key
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 */
	public static $key = 'role';

	/**
	 * User Role Name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 */
	public static $name = 'Role';

	/**
	 * User Role Capabilities
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 */
	public static $capabilities = [
		'read' => true,
	];

	/**
	 * Add User Role
	 *
	 * Add a user role. Wrapper for WordPress add_role() function.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key the key/id/computer-name for the role. Optional. Default static::$key
	 * @param string $name the title/human name for the role. Optional. Default static::$name
	 * @param array $capabilities the array of WordPress capabilities for the role. Optional. Default static::$capabilities
	 *
	 * @return WP_Role
	 */
	public static function add( string $key = '', string $name = '', array $capabilities = [] ) : WP_Role {

		if ( empty( $key ) ) {
			$key = static::$key;
		}

		if ( empty( $name ) ) {
			$name = static::$name;
		}

		if ( empty( $capabilities ) ) {
			$capabilities = static::$capabilities;
		}

		$role = add_role( $key, $name, $capabilities );

		// If add_role returns null, role already exists. So get role.
		// @see https://developer.wordpress.org/reference/functions/add_role/
		if ( null === $role ) {
			return self::get( $key );
		}

		return $role;
	}

	/**
	 * Remove User Role
	 *
	 * Remove a user role. Likely never used. Wrapper for WordPress remove_role() function.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key the key/id/computer-name for the role. Optional. Default static::$key
	 *
	 * @return void
	 */
	public static function remove( string $key = '' ) : void {

		if ( empty( $key ) ) {
			$key = static::$key;
		}

		remove_role( $key );
	}

	/**
	 * Get User Role
	 *
	 * Get a user role object. Wrapper for WordPress get_role() function.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key the key/id/computer-name for the role. Optional. Default static::$key
	 *
	 * @return WP_Role|null if not found
	 */
	public static function get( string $key = '' ) : ?WP_Role {

		if ( empty( $key ) ) {
			$key = static::$key;
		}

		return get_role( $key );
	}

	/**
	 * (Un)Register Capabilities
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param bool $uninstall Whether to add or remove. Default false.
	 *
	 * @return void
	 */
	public static function capabilities( bool $uninstall ) : void {}

	/**
	 * Get WordPress Capabilities Object
	 *
	 * Fetches the WP Roles Object so we can add/remove Capabilities
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return WP_Roles|null
	 */
	public static function get_site_capabilities() : ?WP_Roles {
		global $wp_roles;

		if ( class_exists( '\WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles(); //PHPCS:ignore
			}
		}

		if ( $wp_roles instanceof WP_Roles ) {

			return $wp_roles;
		}

		return null;
	}
}
