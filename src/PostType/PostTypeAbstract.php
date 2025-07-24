<?php
/**
 * Post Type Abstract
 *
 * Abstract class for WordPress Post Types
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  PostType
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\PostType;

use Gothic\Selections\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post Type Abstract Class
 *
 * @since 1.0.0
 */
abstract class PostTypeAbstract implements PostTypeInterface {

	/**
	 * Post Type Key
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 */
	public static $key = 'post_type';

	/**
	 * Post Type Rewrite
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 */
	private static $rewrite = 'post_type';

	/**
	 * Post Type Icon
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 */
	protected static $icon = 'dashicons-admin-post';

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
	private static $class = __CLASS__;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', [ static::$class, 'init' ] );
		add_action( 'admin_init', [ static::$class, 'admin_init' ] );
	}

	/**
	 * Singular
	 *
	 * Return the Singular name for the Post Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function singular() : string {

		return _x( 'Post Type', 'gothic-selections', 'Post Type Singular Name' );
	}

	/**
	 * Plural
	 *
	 * Return the Plural name for the Post Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function plural() : string {

		return _x( 'Post Types', 'gothic-selections', 'Post Type Plural Name' );
	}

	/**
	 * Init
	 *
	 * Items to call upon WP Initialization, often which require validation of the current query.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function init() : void {
		call_user_func( [ static::$class, 'register_post_type' ] );
	}

	/**
	 * Admin Init
	 *
	 * Items to call upon WP Admin initialization, often which require validation of the current screen.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function admin_init() : void {}

	/**
	 * Register Post Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function register_post_type() : void {
		register_post_type( static::$key, static::args() );
	}

	/**
	 * Post Type Args
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @return array
	 */
	protected static function args() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_args', [
			'description'         => ucfirst( static::singular() ) . ' ' . esc_html__( 'Post Type', 'gothic-selections' ),
			'labels'              => static::labels(),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon'           => static::$icon,
			'hierarchical'        => false,
			'supports'            => static::supports(),
			'has_archive'         => true,
			'rewrite'             => static::rewrites(),
			'query_var'           => true,
			'can_export'          => false,
			'capability_type'     => 'post',
		] );
	}

	/**
	 * Post Type Labels
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @return array
	 */
	protected static function labels() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_labels', [
			'name'               => ucfirst( static::plural() ),
			'singular_name'      => ucfirst( static::singular() ),
			'add_new'            => esc_html__( 'Add New', 'gothic-selections' ),
			'add_new_item'       => esc_html__( 'Add New', 'gothic-selections' ) . ' ' . ucfirst( static::singular() ),
			'edit_item'          => esc_html__( 'Edit', 'gothic-selections' ) . ' ' . ucfirst( static::singular() ),
			'new_item'           => esc_html__( 'New', 'gothic-selections' ) . ' ' . ucfirst( static::singular() ),
			'view_item'          => esc_html__( 'View', 'gothic-selections' ) . ' ' . ucfirst( static::singular() ),
			'search_items'       => esc_html__( 'Search', 'gothic-selections' ) . ' ' . ucfirst( static::plural() ),
			// Translators: placeholder is name of post type, lowercase
			'not_found'          => sprintf( esc_html__( 'No %1$s found', 'gothic-selections' ), strtolower( static::plural() ) ),
			// Translators: placeholder is name of post type, lowercase
			'not_found_in_trash' => sprintf( esc_html__( 'No %1$s found in trash', 'gothic-selections' ), strtolower( static::plural() ) ),
			'parent_item_colon'  => '',
			'all_items'          => ucfirst( static::plural() ),
			'menu_name'          => ucfirst( static::plural() ),
		] );
	}

	/**
	 * Post Type Rewrites
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @return array
	 */
	protected static function rewrites() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_rewrites', [
			'slug'       => static::$rewrite,
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
		] );
	}

	/**
	 * Post Type Supports
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @return array
	 */
	protected static function supports() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_supports', [
			'title',
			'editor',
			'excerpt',
			'custom-fields',
		] );
	}

	/**
	 * Is Post of Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function is_post_of_type( int $post_id ) : bool {

		global $post_type;

		$type = '';

		if ( is_numeric( $post_id ) ) {
			$type = get_post_type( $post_id );
		}

		if ( empty( $type ) ) {
			$type = $post_type;
		}

		if ( static::$key !== $type ) {

			return false;
		}

		return true;
	}

	/**
	 * Is Post Type Admin
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return bool
	 */
	public static function is_post_type_admin() : bool {

		if ( ! is_admin() ) {

			return false;
		}

		$screen = \get_current_screen();

		if ( $screen && static::$key === $screen->post_type ) {
			return true;
		}

		return false;
	}

	/**
	 * Set Hierarchial
	 *
	 * Set the post type as hierarchial
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function set_hierarchial( array $args ) : array {

		$args['hierarchical'] = true;

		return $args;
	}

	/**
	 * Set No Archive
	 *
	 * Set the post type to have no archive
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function set_no_archive( array $args ) : array {

		$args['has_archive'] = false;

		return $args;
	}

	/**
	 * Set No Single
	 *
	 * Set the post type to not generate single templates
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function set_no_single( array $args ) : array {

		$args['publicly_queryable'] = false;

		return $args;
	}

	/**
	 * Multipart Form Tag
	 *
	 * Modifies the post new/edit form tag with Multipart for File Upload
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function multipart_form_tag() : void {
		echo ' enctype="multipart/form-data"';
	}

	/**
	 * Capabilities
	 *
	 * Return array of capabilities based on the WordPress default user role given
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param string $role
	 *
	 * @return array
	 */
	public static function capabilities( string $role = '' ) : array {

		if ( empty( $role ) ) {

			return [];
		} else {
			$role = strtolower( $role );
		}

		$primative_roles = [ 'administrator', 'editor', 'author', 'contributor', 'subscriber' ];

		if ( ! in_array( $role, $primative_roles, true ) ) {

			return [];
		}

		$args = static::args();

		if ( empty( $args['capability_type'] ) ) {
			$args['capability_type'] = 'post';
		}

		if ( ! is_array( $args['capability_type'] ) ) {
			$args['capability_type'] = [ $args['capability_type'], $args['capability_type'] . 's' ];
		}

		list( $singular, $plural ) = $args['capability_type'];

		$capabilities = [];

		if ( in_array( $role, [ 'administrator', 'editor' ], true ) ) {
			$capabilities = array_merge( $capabilities, [
				'edit_others_' . $plural,
				'delete_others_' . $plural,
				'delete_private_' . $plural,
				'edit_private_' . $plural,
				'read_private_' . $plural,
			] );
		}

		if ( in_array( $role, [ 'administrator', 'editor', 'author' ], true ) ) {
			$capabilities = array_merge( $capabilities, [
				'edit_published_' . $plural,
				'publish_' . $plural,
				'delete_published_' . $plural,
				'upload_files',
			] );
		}

		if ( in_array( $role, [ 'administrator', 'editor', 'author', 'contributor' ], true ) ) {
			$capabilities = array_merge( $capabilities, [
				'edit_' . $singular,
				'edit_' . $plural,
				'delete_' . $singular,
				'delete_' . $plural,
			] );
		}

		$capabilities[] = 'read';
		$capabilities[] = 'read_' . $singular;

		return $capabilities;
	}
}
