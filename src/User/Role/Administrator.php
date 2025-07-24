<?php
/**
 * Administrator User Role
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
final class Administrator extends RoleAbstract {

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
	public static $key = 'administrator';

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
	public function __construct( bool $remove = false ) {
		if ( ! $remove ) {
			self::capabilities();
		} else {
			self::capabilities( true );
		}
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
	public static function capabilities( bool $uninstall = false ) : void {

		$action = $uninstall ? 'remove_cap' : 'add_cap';

		$capabilities = static::get_site_capabilities();

		if ( $capabilities ) {

			$types = [ 'PreferencesOrder', 'Builder', 'Community', 'Model', 'Package', 'Palette' ];

			foreach ( $types as $type ) {

				$class = 'Gothic\Selections\PostType\\' . $type;

				foreach ( $class::capabilities( 'administrator' ) as $capability ) { //phpcs:ignore

					$capabilities->$action( static::$key, $capability );

				}
			}

			$user_edit_capabilities = [ 'gothic_salesperson_community', 'gothic_salesmanager_builder' ];

			foreach ( $user_edit_capabilities as $cap ) {

				$capabilities->$action( static::$key, $cap );

			}
		}
	}
}
