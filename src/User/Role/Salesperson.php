<?php
/**
 * Homeseller Salesperson User Role
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

/**
 * User Role Abstract Class
 *
 * @since 1.0.0
 */
final class Salesperson extends RoleAbstract {

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
	public static $key = 'gothic-salesperson';

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
	public static $name = 'Home Salesperson';

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
	public static $capabilities = [];

	/**
	 * User Role Constructor
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 */
	public function __construct() {
		self::add();
	}
}
