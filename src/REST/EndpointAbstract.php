<?php
/**
 * REST Endpoint Abstract
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
use Gothic\Selections\Plugin;

/**
 * Post Type Abstract Class
 *
 * @since 1.0.0
 */
abstract class EndpointAbstract implements EndpointInterface {

	/**
	 * Endpoint Namespace
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 */
	protected static $namespace = 'gothic';

	/**
	 * Endpoint Version
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 */
	protected static $version = '1';

	/**
	 * Class Name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 */
	protected static $class = __CLASS__;

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
		add_action( 'rest_api_init', [ static::$class, 'routes' ] );
	}

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
	public static function namespace() : string {
		return static::$namespace . '/v' . static::$version;
	}

	/**
	 * Routes
	 *
	 * Called in a WordPress rest_api_init action to add the routes.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function routes() : void {
		/* translators: %s: routes() */
		_doing_it_wrong( __METHOD__, sprintf( esc_html__( "Abstract class method '%s' must be defined by inheriting class.", 'gothic-selections' ), __METHOD__ ), esc_html( Plugin::VERSION ) );
	}

	/**
	 * Index
	 *
	 * Gets a collection (index) of items in the resource from a GET call.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public static function index( WP_REST_Request $request ) {
		/* translators: %s: index() */
		_doing_it_wrong( __METHOD__, sprintf( esc_html__( "Abstract class method '%s' must be defined by inheriting class.", 'gothic-selections' ), __METHOD__ ), esc_html( Plugin::VERSION ) );

		return new WP_Error(
			__( 'Not Implemented', 'gothic-selections' ),
			__( 'This call is not available.', 'gothic-selections' ),
			[ 'status' => 501 ]
		);
	}

	/**
	 * Show
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
	 * @return WP_REST_Response|WP_Error
	 */
	public static function show( WP_REST_Request $request ) {
		/* translators: %s: show() */
		_doing_it_wrong( __METHOD__, sprintf( esc_html__( "Abstract class method '%s' must be defined by inheriting class.", 'gothic-selections' ), __METHOD__ ), esc_html( Plugin::VERSION ) );

		return new WP_Error(
			__( 'Not Implemented', 'gothic-selections' ),
			__( 'This call is not available.', 'gothic-selections' ),
			[ 'status' => 501 ]
		);
	}

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
	 * @return WP_REST_Response|WP_Error
	 */
	public static function create( WP_REST_Request $request ) {
		/* translators: %s: create() */
		_doing_it_wrong( __METHOD__, sprintf( esc_html__( "Abstract class method '%s' must be defined by inheriting class.", 'gothic-selections' ), __METHOD__ ), esc_html( Plugin::VERSION ) );

		return new WP_Error(
			__( 'Not Implemented', 'gothic-selections' ),
			__( 'This call is not available.', 'gothic-selections' ),
			[ 'status' => 501 ]
		);
	}

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
	 * @return WP_REST_Response|WP_Error
	 */
	public static function update( WP_REST_Request $request ) {
		/* translators: %s: update() */
		_doing_it_wrong( __METHOD__, sprintf( esc_html__( "Abstract class method '%s' must be defined by inheriting class.", 'gothic-selections' ), __METHOD__ ), esc_html( Plugin::VERSION ) );

		return new WP_Error(
			__( 'Not Implemented', 'gothic-selections' ),
			__( 'This call is not available.', 'gothic-selections' ),
			[ 'status' => 501 ]
		);
	}

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
	 * @return WP_REST_Response|WP_Error
	 */
	public static function destroy( WP_REST_Request $request ) {

		/* translators: %s: destroy() */
		_doing_it_wrong( __METHOD__, sprintf( esc_html__( "Abstract class method '%s' must be defined by inheriting class.", 'gothic-selections' ), __METHOD__ ), esc_html( Plugin::VERSION ) );

		return new WP_Error(
			__( 'Not Implemented', 'gothic-selections' ),
			__( 'This call is not available.', 'gothic-selections' ),
			[ 'status' => 501 ]
		);
	}
}
