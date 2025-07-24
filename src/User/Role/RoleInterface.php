<?php
/**
 * User Roles Interface
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

/**
 * User Role Abstract Class
 *
 * @since 1.0.0
 */
interface RoleInterface {

	/**
	 * Properties Should Implement
	 *
	 * @param $key string
	 * @param $name string
	 * @param $capabilities array
	 */

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
	public static function add( string $key, string $name, array $capabilities ) : WP_Role;

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
	public static function remove( string $key ) : void;

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
	 * @return WP_Role|null
	 */
	public static function get( string $key ) : ?WP_Role;

	public static function capabilities( bool $uninstall ) : void;
}
