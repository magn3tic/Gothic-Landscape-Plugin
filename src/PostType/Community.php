<?php
/**
 * Community Post Type
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

use \WP_Query;
use \WP_Post;
use Gothic\Selections\Plugin;
use Gothic\Selections\PostMeta\{
	CommunityAbout as AboutMetaBox,
	CommunityModels as ModelsMetaBox,
	CommunityPackages as PackagesMetaBox,
	CommunityBackyards as BackyardsMetaBox,
	CommunityPalettes as PaletteMetaBox,
	CommunityOrders as OrdersMetaBox };
use Gothic\Selections\Admin\Notice;

/**
* Registers and sets up the Downloads custom post type
*
* @since 1.0.0
* @return void
*/
final class Community extends PostTypeAbstract {

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
	public static $key = 'communities';

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
	protected static $rewrite = 'landscaping/preferences/communities';

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
	protected static $icon = 'dashicons-admin-multisite';

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
		parent::__construct();

		add_action( 'save_post', [ __CLASS__, 'clear_queries_caches' ], 20, 2 );

		add_action( 'admin_menu', array( $this, 'remove_add_new_from_submenu' ) );

		add_action( 'pre_trash_post', [ __CLASS__, 'pre_delete' ], 10, 2 );
		add_action( 'pre_delete_post', [ __CLASS__, 'pre_delete' ], 10, 3 );
		add_action( 'wp_trash_post', [ __CLASS__, 'before_delete' ], 10, 2 );

		new AboutMetaBox();
		new ModelsMetaBox();
		new PaletteMetaBox();
		new PackagesMetaBox();
		new BackyardsMetaBox();
		new OrdersMetaBox();
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

		return __( 'Community', 'gothic-selections' );
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

		return __( 'Communities', 'gothic-selections' );
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
		add_filter( 'manage_posts_columns', [ __CLASS__, 'admin_columns' ], 5 );
		add_action( 'manage_posts_custom_column', [ __CLASS__, 'admin_columns_content' ], 5, 2 );
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
			'description'         => esc_html__( 'Homebuilder Communities.', 'gothic-landscape-plugin' ),
			'labels'              => static::labels(),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
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
			'capability_type'     => [ 'community', static::$key ],
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
	 * Remove 'Add New' From Admin Submenu
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function remove_add_new_from_submenu() : void {
		remove_submenu_page( 'edit.php?post_type=' . self::$key, 'post-new.php?post_type=' . self::$key );
	}

	/**
	 * Admin Columns (& Header Labels)
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public static function admin_columns( $columns ) {

		if ( self::is_post_type_admin() ) {

			// Replace Title, Name it 'Community Name'
			$columns['title'] = __( 'Community Name', 'gothic-selections' );

			// Add Home Builder & Location Fields
			$columns['builder']  = __( 'Home Builder', 'gothic-selections' );
			$columns['orders']   = __( 'Selections Orders', 'gothic-selections' );

			// Remove Date Column
			unset( $columns['date'] );
		}

		// Return Columns Array
		return $columns;
	}

	/**
	 * Admin Columns Content
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $column_name
	 * @param int $post_id
	 *
	 * @return void
	 */
	public static function admin_columns_content( $column_name, $post_id ) {
		if ( self::is_post_type_admin() ) {
			if ( 'orders' === $column_name ) {

				$orders = new WP_Query(
					[
						'post_type' => PreferencesOrder::$key,
						'meta_query' => [
							[
								'key' => 'community_id',
								'compare' => 'IN',
								'value' => [ $post_id ],
							]
						]
					]
				);
				echo $orders->post_count;
			}
			if ( 'builder' === $column_name ) {

				$builder_id = get_post_meta( $post_id, 'builder_id', true );

				echo '<a href="' . esc_url( get_admin_url() . 'post.php?post=' . $builder_id . '&amp;action=edit' ) . '">' . esc_html( get_the_title( $builder_id ) ) . '</a>';
			}
		}
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
					'key' => 'community_id',
					'value' => $post->ID,
					'compare' => '='
				],
			],
		];

		$orders = new WP_Query( $orders_query );

		if ( $orders->have_posts() ) {
			update_post_meta( $post->ID, 'inactive', '1' );
			Notice::add( 'cant-delete-model', __( 'You cannot delete a community with associated Selections Orders. Instead, the model was set to "inactive" and is no longer available to users or home salespeople to select.', 'gothic-selections' ), 'warning' );
			wp_safe_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
			die();
		}

		$types_to_check = [
			Model::$key,
			Palette::$key,
			Package::$key,
		];

		foreach ( $types_to_check as $check ) {
			$args = [
				'post_type' => $check,
				'meta_query' => [
					[
						'key' => 'community_id',
						'value' => $post->ID,
						'compare' => '='
					],
				],
			];

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				update_post_meta( $post->ID, 'inactive', '1' );
				Notice::add( 'cant-delete-community', __( 'You cannot delete a community with associated Models, Packages, or Palettes. Instead, the community was set to "inactive" and is no longer available to users or home salespeople to select. If you wish to delete the community, remove all models, packages, and palettes and try again.', 'gothic-selections' ), 'warning' );
				wp_safe_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
				die();
			}
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

		wp_delete_post( $id, true );

		Notice::add( 'community-deleted', __( 'Community successfully deleted', 'gothic-selections' ), 'notice' );

		wp_safe_redirect( admin_url( 'edit.php?post_type=' . self::$key ) );
		die();
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

		// get the individual builder's cache key
		if ( get_post_meta( $id, 'builder_id', true ) ) {
			$to_delete[] =  self::$key . '_' . $id;
		}

		$transients = [];

		// rebuild with filter prefix and formats
		foreach( $to_delete as $value ) {
			$transients[] = Plugin::FILTER_PREFIX . $value . '_select2';
			$transients[] = Plugin::FILTER_PREFIX . $value . '_default';
			$transients[] = Plugin::FILTER_PREFIX . $value . '_image';
		}

		// delete caches
		foreach( $transients as $transient ) {
			delete_transient( $transient );
		}
	}
}
