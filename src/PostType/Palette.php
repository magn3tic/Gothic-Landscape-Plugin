<?php
/**
 * Landscape Palettes Post Type
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

use Gothic\Selections\Admin\Notice;
use \WP_Post;
use Gothic\Selections\Plugin;
use Gothic\Selections\PostMeta\PaletteAbout as MetaBox;

/**
* Registers and sets up the Downloads custom post type
*
* @since 1.0.0
* @return void
*/
final class Palette extends PostTypeAbstract {

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
	public static $key = 'palettes';

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
	protected static $rewrite = 'landscaping/preferences/palettes';

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
	protected static $icon = 'dashicons-art';

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

		add_action( 'save_post', [ __CLASS__, 'clear_queries_caches' ], 20, 2 );

		add_filter( 'enter_title_here', [ __CLASS__, 'title_placeholder' ] );
		add_action( 'edit_form_after_title', [ __CLASS__, 'add_community_after_title' ] );
		add_action( 'pre_trash_post', [ __CLASS__, 'pre_delete' ], 10, 2 );
		add_action( 'pre_delete_post', [ __CLASS__, 'pre_delete' ], 10, 3 );
		add_action( 'wp_trash_post', [ __CLASS__, 'before_delete' ], 10, 2 );

		add_action( 'save_post', [ __CLASS__, 'clear_queries_caches' ], 20, 2 );

		parent::__construct();

		new MetaBox();
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

		return __( 'Palette', 'gothic-selections' );
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

		return __( 'Palettes', 'gothic-selections' );
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
			'description'         => esc_html__( 'Community plant and material palettes', 'gothic-selections' ),
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
			'capability_type'     => [ 'palette', static::$key ],
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
	 * Title Placeholder
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	public static function title_placeholder( string $title ) : string {

		$screen = get_current_screen();

		if ( static::$key === $screen->post_type ) {
			$title = __( 'Palette Name', 'gothic-selections' );
		}

		return $title;
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
	public static function pre_delete( $delete, WP_Post $post, bool $force = false ) : ?bool {

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
					'key' => 'palette_id',
					'value' => $post->ID,
					'compare' => '='
				],
			],
		];
		$orders = new \WP_Query( $orders_query );

		if ( $orders->have_posts() ) {
			update_post_meta( $post->ID, 'inactive', '1' );
			Notice::add( 'cant-delete-palette', __( 'You cannot delete a palette with associated Selections Orders. Instead, the palette was set to "inactive" and is no longer available to users or home salespeople to select.', 'gothic-selections' ), 'warning' );
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
}
