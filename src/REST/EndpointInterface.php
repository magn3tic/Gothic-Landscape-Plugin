<?php
/**
 * REST Endpoint Interface
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  REST
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\REST;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \WP_REST_Request;
use \WP_REST_Response;
use \WP_Error;

/**
 * REST API Endpoint Interface
 *
 * @since 1.0.0
 */
interface EndpointInterface {

	/**
	 * Implementing Classes Should Declare the Following Properties
	 *
	 * @property string $namespace Endpoint Namespace.
	 * @property string $version   Endpoint Version.
	 * @property string $class     Class name.
	 */

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct();

	/**
	 * Namespace
	 *
	 * Returns a constructed namespace based on the namespace and version static properties.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function namespace() : string;

	/**
	 * Routes
	 *
	 * Called to the WordPress in a WordPress rest_api_init action to add the routes.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function routes() : void;

	/**
	 * Index
	 *
	 * Gets a collection (index) of items in the resource with a GET call.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public static function index( WP_REST_Request $request );

	/**
	 * Index
	 *
	 * Gets an item in the resource with a GET call.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public static function show( WP_REST_Request $request );

	/**
	 * Create
	 *
	 * Creates an item or items in the resource with a POST call.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public static function create( WP_REST_Request $request );

	/**
	 * Update
	 *
	 * Updates an item or items in the resource with a PUT call.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public static function update( WP_REST_Request $request );

	/**
	 * Destroy
	 *
	 * Deletes an item or items in the resource with a DELETE call.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public static function destroy( WP_REST_Request $request );
}
