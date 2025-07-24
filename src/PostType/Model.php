<?php
/**
 * Home Model Type
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
use Gothic\Selections\Plugin;
use Gothic\Selections\Helper\Queries;
use Gothic\Selections\Admin\Notice;
use Gothic\Selections\PostMeta\{
	ModelAbout as AboutMetaBox,
	ModelPackagePlans as FrontYardPlansMetaBox,
	ModelBackyardPlans as BackYardPlansMetaBox };

/**
* Registers and sets up the Downloads custom post type
*
* @since 1.0.0
* @return void
*/
final class Model extends PostTypeAbstract {

	use CommunityDependency;

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
	public static $key = 'models';

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
	protected static $rewrite = 'landscaping/preferences/models';

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
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public function __construct() {

		add_image_size( 'model-plan-tile', 300, 350, true );

		add_action( 'current_screen', [ __CLASS__, 'notice_if_community_has_no_packages' ], 1 );

		add_action( 'post_edit_form_tag', [ __CLASS__, 'multipart_form_tag' ] );

		add_action( 'current_screen', [ __CLASS__, 'redirect_on_invalid_new' ] );
		add_action( 'edit_form_after_title', [ __CLASS__, 'add_community_after_title' ] );
		add_action( 'save_post', [ __CLASS__, 'clear_queries_caches' ], 20, 2 );

		add_action( 'pre_trash_post', [ __CLASS__, 'pre_delete' ], 10, 2 );
		add_action( 'pre_delete_post', [ __CLASS__, 'pre_delete' ], 10, 3 );
		add_action( 'wp_trash_post', [ __CLASS__, 'before_delete' ], 10, 2 );

		parent::__construct();

		new AboutMetaBox();
		new FrontYardPlansMetaBox();
		new BackYardPlansMetaBox();
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

		return __( 'Home Model', 'gothic-selections' );
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

		return __( 'Home Models', 'gothic-selections' );
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
	public static function admin_init() : void {
		add_action( 'admin_head', [ __CLASS__, 'hide_add_new_button' ] );
	}

	/**
	 * Post Type Args (Child Class Override)
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
			'description'         => esc_html__( 'Homebuilder Home Models', 'gothic-selections' ),
			'labels'              => static::labels(),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => false, // 'edit.php?post_type=' . Community::$key,
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
			'capability_type'     => [ 'model', static::$key ],
		] );
	}

	/**
	 * Post Type Supports
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @return array
	 */
	protected static function supports() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_supports', [ 'title', 'thumbnail' ] );
	}


	/**
	 * Clear Queries Caches
	 *
	 * Clears the caches made by the Helper\Queries queries. Called on the save_post action.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param int $id
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public static function clear_queries_caches( int $id, WP_Post $post ) : void {

		if ( self::$key !== $post->post_type ) {

			return;
		}

		if ( isset( $post->post_status ) && 'auto-draft' === $post->post_status) {

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

		// start with the general all call
		$to_delete = [ self::$key ];

		// get the individual community's cache key
		if ( get_post_meta( $id, 'community_id', true ) ) {
			$to_delete[] = Community::$key . '_' . get_post_meta( $id, 'community_id', true ) . '_' . self::$key;
		}

		$transients = [];

		// rebuild with filter prefix and formats
		foreach( $to_delete as $value ) {
			$transients[] = Plugin::FILTER_PREFIX . $value . '_select2';
			$transients[] = Plugin::FILTER_PREFIX . $value . '_default';
		}

		// delete caches
		foreach( $transients as $transient ) {
			delete_transient( $transient );
		}
	}

	/**
	 * Don't Delete, Set Status
	 *
	 * A model used in any non-voided order cannot be deleted.
	 *
	 * @param mixed $delete Whether to allow delete. Must be null to continue delete
	 * @param WP_Post $post The post being deleted
	 * @param bool $force Whether a force call is made (which happens from the delete community call)
	 *
	 * @return bool|null Any non-null return cancels post delete.
	 */
	public static function pre_delete( $delete, WP_Post $post, bool $force = true ) : ?bool {

		if ( self::$key !== $post->post_type ) {

			return $delete;
		}

		if ( $force ) {

			return $delete;
		}

		$orders_query = [
			'post_type' => PreferencesOrder::$key,
			'meta_query' => [
				[
					'key' => 'model_id',
					'value' => $post->ID,
					'compare' => '='
				],
			],
		];

		$orders = new WP_Query( $orders_query );

		if ( $orders->have_posts() ) {
			update_post_meta( $post->ID, 'inactive', '1' );
			Notice::add( 'cant-delete-model', __( 'You cannot delete a model with associated Selections Orders. Instead, the model was set to "inactive" and is no longer available to users or home salespeople to select.', 'gothic-selections' ), 'warning' );
			wp_safe_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
			die();
		}

		return $delete;
	}

	/**
	 * If Delete, Clear Associations
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

		$community = get_post_meta( $id, 'community_id', true );

		wp_delete_post( $id, true );

		Notice::add( 'model-deleted', __( 'Model successfully deleted', 'gothic-selections' ), 'notice' );

		wp_safe_redirect( admin_url( 'post.php?post=' . $community . '&action=edit' ) );
		die();
	}

	/**
	 * Admin Notice if Community Has No Packages
	 *
	 * The edit model screen dynamically generates fields for package model plans based on active packages. Ideally, one
	 * should add packages before models. So this gives a warning if a community doesn't have packages.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function notice_if_community_has_no_packages() : void {

		if ( static::$key !== get_current_screen()->id ) {
			return;
		}

		if ( 'add' === get_current_screen()->action && isset( $_GET['community_id'] ) ) {
			$community = intval( $_GET['community_id'] );
		} elseif ( isset( $_GET['post'] ) ) {
			$community = intval( get_post_meta( intval( $_GET['post'] ), 'community_id', true ) );
		} else {
			$community = 0;
		}

		if ( ! $community ) {
			return;
		}

		if ( 1 !== intval( get_post_meta( $community, 'frontyard', true ) ) && 1 !== intval( get_post_meta( $community, 'backyard', true ) ) ) {
			Notice::add( 'no-selections-for-community-model', __( 'This community has selections turned off, so package plan images cannot be added.', 'gothic-selections' ), 'warning' );
			return;
		}

		$front = Queries::packages( [
			'community_id' => $community,
			'backyard'     => false,
			'inactive'     => true
		] );

		$back = Queries::packages( [
			'community_id' => $community,
			'backyard'     => true,
			'inactive'     => true
		] );

		if ( empty( $front ) && empty( $back ) ) {
			Notice::add( 'no-packages-for-community-model', __( 'This community has no landscape packages, so models cannot be assigned package plan images.', 'gothic-selections' ), 'warning' );
			return;
		}

		if ( "1" === get_post_meta( $community, 'backyard', true ) && empty( $back ) ) {
			Notice::add( 'no-backyard-packages-for-community-model', __( 'This community expects Backyards offered via selections, but has no backyard landscape packages, so models cannot be assigned package plan images.', 'gothic-selections' ), 'warning' );
			return;
		}

		if ( "1" === get_post_meta( $community, 'frontyard', true ) && empty( $front ) ) {
			Notice::add( 'no-backyard-packages-for-community-model', __( 'This community expects front yards offered via selections, but has no backyard landscape packages, so models cannot be assigned package plan images.', 'gothic-selections' ), 'warning' );
			return;
		}
	}
}
