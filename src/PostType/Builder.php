<?php
/**
 * Builders Post Type
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \WP_Post;
use \WP_Query;
use    Gothic\Selections\Plugin;
use Gothic\Selections\Admin\Notice;
use Gothic\Selections\PostMeta\BuilderAbout as AboutMetaBox;
use Gothic\Selections\PostMeta\BuilderCommunities as CommunitiesMetaBox;

/**
 * Registers and sets up the Downloads custom post type
 *
 * @return void
 * @since 1.0.0
 */
final class Builder extends PostTypeAbstract {

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
	public static $key = 'builders';

	/**
	 * Post Type Rewrite Key
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 */
	protected static $rewrite = 'landscaping/preferences/builders';

	/**
	 * This Class Name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 */
	protected static $class = __CLASS__;

	/**
	 * Constructor
	 *
	 * Items to call on constructor. Call parent::__construct() or omit.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'pre_trash_post', [ __CLASS__, 'pre_delete' ], 10, 2 );
		add_action( 'pre_delete_post', [ __CLASS__, 'pre_delete' ], 10, 3 );
		add_action( 'wp_trash_post', [ __CLASS__, 'before_delete' ], 10, 2 );

		add_action( 'save_post', [ __CLASS__, 'clear_queries_caches' ], 20, 2 );

		new AboutMetaBox();
		new CommunitiesMetaBox();
	}

	/**
	 * Singular
	 *
	 * Return the Singular name for the Post Type
	 *
	 * @return string
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 */
	public static function singular(): string {

		return __( 'Builder', 'gothic-selections' );
	}

	/**
	 * Plural
	 *
	 * Return the Plural name for the Post Type
	 *
	 * @return string
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 */
	public static function plural(): string {

		return __( 'Builders', 'gothic-selections' );
	}

	/**
	 * Admin Init
	 *
	 * Items to call upon WP Admin initialization, often which require validation of the current screen.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 */
	public static function admin_init(): void {
	}

	/**
	 * Post Type Args (Child Class Override)
	 *
	 * @return array
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 */
	protected static function args(): array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_args', [
			'description'         => esc_html__( 'Gothic Landscape Builders.', 'gothic-landscape-plugin' ),
			'labels'              => static::labels(),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=' . Community::$key,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 20,
			'menu_icon'           => static::$icon,
			'hierarchical'        => false,
			'supports'            => static::supports(),
			'has_archive'         => false,
			'rewrite'             => false, // static::rewrites(),
			'query_var'           => true,
			'can_export'          => false,
			'capability_type'     => [ 'builder', static::$key ],
		] );
	}

	/**
	 * Post Type Supports
	 *
	 * @return array
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 */
	protected static function supports(): array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_supports', [ 'title', 'thumbnail' ] );
	}

	/**
	 * Clear Queries Caches
	 *
	 * Clears the caches made by the Helper\Queries queries. Called on the save_post action.
	 *
	 * @param int $id
	 * @param WP_Post $post
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 */
	public static function clear_queries_caches( int $id, WP_Post $post ): void {

		if ( self::$key !== $post->post_type ) {

			return;
		}

		if ( isset( $post->post_status ) && 'auto-draft' === $post->post_status ) {

			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			return;
		}

		if ( isset( $_REQUEST['bulk_edit'] ) ) {

			return;
		}

		delete_transient( Plugin::FILTER_PREFIX . self::$key . '_' . $id );
	}


	/**
	 * Don't Delete, Set Status
	 *
	 * A community used in any non-voided order cannot be deleted.
	 *
	 * @param mixed $delete Whether to allow delete. Must be null to continue delete
	 * @param WP_Post $post The post being deleted
	 * @param bool $force
	 *
	 * @return bool|null Any non-null return cancels post delete.
	 */
	public static function pre_delete( $delete, WP_Post $post, bool $force = false ): ?bool {

		if ( self::$key !== $post->post_type ) {

			return $delete;
		}

		if ( $force ) {

			return $delete;
		}


		$orders_query = [
			'post_type'  => PreferencesOrder::$key,
			'meta_query' => [
				[
					'key'     => 'builder_id',
					'value'   => $post->ID,
					'compare' => '='
				],
			],
		];

		$orders = new WP_Query( $orders_query );

		if ( $orders->have_posts() ) {
			update_post_meta( $post->ID, 'inactive', '1' );
			Notice::add( 'cant-delete-builder', __( 'You cannot delete a builder with associated Selections Orders. Instead, the builder was set to "inactive" and is no longer available to users or home salespeople to select.', 'gothic-selections' ), 'warning' );
			wp_safe_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
			die();
		}

		$args = [
			'post_type'  => Community::$key,
			'meta_query' => [
				[
					'key'     => 'builder_id',
					'value'   => $post->ID,
					'compare' => '='
				],
			],
		];

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			update_post_meta( $post->ID, 'inactive', '1' );
			Notice::add( 'cant-delete-builder', __( 'You cannot delete a builder with an associated Community. Instead, the builder was set to "inactive" and is no longer available to users or home salespeople to select. If you wish to delete the builder, remove all communities and try again.', 'gothic-selections' ), 'warning' );
			wp_safe_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
			die();
		}

		return $delete;
	}

	/**
	 * Force Delete
	 *
	 * If a package pass the "don't delete" test, what should we do when it is deleted?
	 *
	 * @param int $id The post id being deleted
	 *
	 * @return void
	 */
	public static function before_delete( $id ) {

		if ( self::$key !== get_post_type( $id ) ) {

			return;
		}

		wp_delete_post( $id, true );

		Notice::add( 'builder-deleted', __( 'Builder successfully deleted', 'gothic-selections' ), 'notice' );

		wp_safe_redirect( admin_url( 'edit.php?post_type=builders' ) );
		die();
	}
}
