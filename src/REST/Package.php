<?php
/**
 * REST API Models Endpoint
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

use \WP_Error;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;
use Gothic\Selections\PostType\{
	Package as PackagePostType,
	Community as CommunityPostType };
use Gothic\Selections\Helper\Queries;

class Package extends EndpointAbstract {

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
	 * Routes
	 *
	 * Called by the WordPress rest_api_init action to add the routes by the parent.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function routes() : void {

		register_rest_route( self::namespace(), '/' . CommunityPostType::$key . '/(?P<community_id>\d+)/' . PackagePostType::$key, [
			[
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [ __CLASS__, 'index' ],
				'args'     => self::index_schema(),
				'permission_callback' => '__return_true'
			],
		] );

		register_rest_route( self::namespace(), '/' . CommunityPostType::$key . '/(?P<community_id>\d+)/' . PackagePostType::$key . '/meta', [
			[
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [ __CLASS__, 'meta' ],
				'permission_callback' => '__return_true'
			],
		] );
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

		$packages = Queries::packages( $request->get_params() );

		if ( false === $packages ) {
			return new WP_Error(
				__( 'Unknown Internal Error', 'gothic-selections' ),
				__( 'Something went wrong. Contact the developer.', 'gothic-selections' ),
				[ 'status' => 500 ]
			);
		}

		if ( empty( $packages ) ) {
			return new WP_Error(
				__( 'No Package Found', 'gothic-selections' ),
				__( 'No packages are found for the search query.', 'gothic-selections' ),
				[ 'status' => 404 ]
			);
		}

		return new WP_REST_Response( $packages );
	}

	/**
	 * Returns the JSON schema data for our registered parameters.
	 *
	 * @return array $params A PHP representation of JSON Schema data.
	 */
	public static function index_schema() : array {

		return [
			'format' => [
				'description'       => __( 'The format the data should be returned. Can be default or select2.', 'gothic-selections' ),
				'type'              => 'string',
				'default'           => 'default',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => function ( $field ) {

					return in_array( strtolower( $field ), [ 'default', 'select2' ], true );
				},
			],
		];
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
	public static function meta( WP_REST_Request $request ) {

		return new WP_REST_Response( Queries::packages_meta( $request->get_params()['community_id'] ) );
	}
}
