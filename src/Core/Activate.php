<?php
/**
 * Activator
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

namespace Gothic\Selections\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\Plugin;
use Gothic\Selections\User\Role\{ Homebuyer, Salesperson, SalesManager, Operations, Administrator };

/**
 * Activate
 *
 * @since 1.0.0
 *
 * @final
 */
final class Activate {

	/**
	 * Constructor
	 *
	 * Registers the Activate::activate method to the Plugin Activation Hook
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		register_activation_hook( Plugin::$file, [ __CLASS__, 'activate' ] );
	}

	/**
	 * Activate
	 *
	 * Gathers and fires all routines that should go off only during activation.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public static function activate() : void {

		// Create User Roles & Capabilities
		new Homebuyer();
		new Salesperson();
		new SalesManager();
		new Operations();
		new Administrator();
	}
}
