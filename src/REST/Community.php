<?php
/**
 * REST API Community Endpoint
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
	Builder as BuilderPostType, Builder, Community as CommunityPostType
};
use Gothic\Selections\Helper\Queries;

class Community extends EndpointAbstract {

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

		register_rest_route( self::namespace(), '/' . BuilderPostType::$key . '/(?P<builder_id>\d+)/' . CommunityPostType::$key, [
			[
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [ __CLASS__, 'index' ],
				'args'     => self::index_schema(),
				'permission_callback' => '__return_true'
			],
		] );

		register_rest_route( self::namespace(), '/' . CommunityPostType::$key, [
			[
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [ __CLASS__, 'index' ],
				'args'     => self::index_schema(),
				'permission_callback' => '__return_true'
			],
		] );

		register_rest_route( self::namespace(), '/' . CommunityPostType::$key . '/(?P<id>\d+)/', [
			[
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [ __CLASS__, 'show' ],
				'args'     => self::show_schema(),
				'permission_callback' => '__return_true'
			],
		] );
	}


	/**
	 * Builder Communities
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

		$communities = Queries::communities( $request->get_params() );

		if ( false === $communities ) {
			return new WP_Error(
				__( 'Unknown Internal Error', 'gothic-selections' ),
				__( 'Something went wrong. Contact the developer.', 'gothic-selections' ),
				[ 'status' => 500 ]
			);
		}

		if ( empty( $communities ) ) {
			return new WP_Error(
				__( 'No Communities Found', 'gothic-selections' ),
				__( 'No communities are found for the search query.', 'gothic-selections' ),
				[ 'status' => 404 ]
			);
		}

		return new WP_REST_Response( $communities );
	}

	/**
	 * Get Community Index Schema
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
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
	 * Get Community
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

		$model = Queries::community( $request->get_params() );

		if ( false === $model ) {
			return new WP_Error(
				__( 'Unknown Internal Error', 'gothic-selections' ),
				__( 'Something went wrong. Contact the developer.', 'gothic-selections' ),
				[ 'status' => 500 ]
			);
		}

		if ( empty( $model ) ) {
			return new WP_Error(
				__( 'No Community Found', 'gothic-selections' ),
				__( 'No communities are found for the search query.', 'gothic-selections' ),
				[ 'status' => 404 ]
			);
		}

		return new WP_REST_Response( $model );
	}

	/**
	 * Get Community Schema
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return array $params A PHP representation of JSON Schema data.
	 */
	public static function show_schema() : array {

		return [
			'format' => [
				'description'       => __( 'The format the data should be returned. Can be default or select2.', 'gothic-selections' ),
				'type'              => 'string',
				'default'           => 'default',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => function ( $field ) {

					return in_array( strtolower( $field ), [ 'default', 'image' ], true );
				},
			],
			'builder_id' => [
				'description'       => __( 'ID of the builder, to return only builder\'s communities.', 'gothic-selections' ),
				'type'              => 'integer',
				'default'           => null,
				'sanitize_callback' => 'intval',
				'validate_callback' => function ( $field ) {

					return ( get_post_type( $field ) === Builder::$key );
				},
			],
		];
	}
}
