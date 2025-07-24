<?php
/**
 * User Capabilities
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Users
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * User Role Abstract Class
 *
 * @since 1.0.0
 */
final class Capabilities {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'map_meta_cap', [ __CLASS__, 'map_meta_caps' ], 10, 4 );
	}

	/**
	 * Map Meta Capabilities
	 *
	 * Filters a user’s capabilities depending on specific context and/or privilege.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/map_meta_cap/
	 * @see https://developer.wordpress.org/reference/functions/map_meta_cap/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $caps array of strings
	 * @param string $cap
	 * @param int $user_id
	 * @param array $args
	 *
	 * @return array of Capabilities
	 */
	public static function map_meta_caps( array $caps, $cap, int $user_id, array $args ) : array {

		switch ( $cap ) {

			case 'a_capability':
				break;
		}

		return $caps;
	}

	public static function user_capabilities( string $role = '' ) {

		if ( empty( $role ) ) {

			return [];
		} else {
			$role = strtolower( $role );
		}

		$primative_roles = [ 'administrator', 'gothic-operations', 'gothic-salesmanager', 'gothic-salesperson' ];

		if ( ! in_array( $role, $primative_roles, true ) ) {

			return [];
		}

		$capabilities = [];

		if ( in_array( $role, [ 'administrator', 'gothic-operations' ], true ) ) {

			$capabilities[] = '';

		}

		return $capabilities;
	}
}
