<?php
/**
 * About Home Model Meta Box
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  PostMeta
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\PostMeta;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\Helper\Queries;
use Gothic\Selections\Plugin;
use Gothic\Selections\PostType\{ Model as PostType, Package };

final class ModelBackyardPlans extends PostMetaAbstract {

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
	 * @static
	 *
	 * @see PostMetaAbstract::__construct()
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( Plugin::FILTER_PREFIX . self::dashes_underscored( static::key() ) . '_disable', [ __CLASS__, 'should_disable' ], 10, 2 );
		add_filter( Plugin::FILTER_PREFIX . self::dashes_underscored( static::key() ) . '_hide_on_new', '__return_true' );
		parent::__construct();
	}

	/**
	 * Meta Box Key
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function key() : string {

		return PostType::$key . '-backyards';
	}

	/**
	 * Meta Box Post Name
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function name() : string {

		return __( 'Backyard Plan Images', 'gothic-selections' );
	}

	/**
	 * Meta Box Post Types
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return array
	 */
	public static function types() : array {

		return [ PostType::$key ];
	}

	/**
	 * Save Metabox
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public static function save( int $post_id ) : void {

		// Checks Nonce & Other Things
		if ( ! self::authorize() ) {
			return;
		}

		$meta = [];

		foreach ( self::meta_fields() as $key => $field ) {
			if ( isset( $_REQUEST[ $key ] ) ) {
				if ( 0 === $_REQUEST[ $key ] || ! $_REQUEST[ $key ] ) {
					$meta[ $key ] = false;
				} else {
					$meta[ $key ] = intval( $_REQUEST[ $key ] );
				}
			}
		}

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	/**
	 * Validate
	 *
	 * Runs before save to validate the request
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return bool
	 */
	protected static function authorize() : bool {

		if ( empty( $_POST[ static::key() . '_nonce' ] ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $_POST[ static::key() . '_nonce' ], plugin_basename( Plugin::$file ) ) ) {

			return false;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return false;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			return false;
		}

		if ( isset( $_REQUEST['bulk_edit'] ) ) {

			return false;
		}

		if ( ! current_user_can( 'edit_models', $_POST['post_type'] ) ) {

			return false;
		}

		return true;
	}

	/**
	 * Meta Box Fields
	 *
	 * The array of fields for the meta box.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @return array
	 */
	protected static function meta_fields() : array {

		$fields = [];

		$plans = Queries::packages( [
			'community_id' => get_post_meta( get_the_ID(), 'community_id', true ),
			'backyard'     => true,
			'inactive'     => true
		] );

		foreach ( $plans as $plan ) {
			$fields[ 'plan-' . $plan->ID . '-image' ] = [
				'type'     => 'file',
				'template' => 'plan-image',
				// Translators: Placeholder is the Package Name
				'label'    => sprintf( __( '%s Thumbnail Image', 'gothic-selections' ), $plan->post_title ),
				'description' => esc_html__( 'A thumbnail of the plan CAD render only. No labels. Close crop recommended.', 'gothic-selections' ),
			];
			$fields[ 'plan-' . $plan->ID . '-full' ]  = [
				'type'     => 'text',
				'template' => 'plan-image',
				'label'    => sprintf( __( '%s Full Size Image', 'gothic-selections' ), $plan->post_title ),
				'description' => esc_html__( 'A not cropped image of the full plan CAD render, including labels. ', 'gothic-selections' ),
			];
		}

		return $fields;
	}

	/**
	 * Should Disable Meta Box
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param bool $show
	 * @param WP_Post $post
	 *
	 * @return bool
	 */
	public static function should_disable( bool $show, $post ) : bool {

		$community = get_post_meta( $post->ID, 'community_id', true );

		if ( "1" !== get_post_meta( $community, 'backyard', true ) ) {
			return true;
		}

		$packages = Queries::packages( [
			'community_id' => $community,
			'backyard'     => true,
			'inactive'     => true
		] );

		if ( empty( $packages ) ) {

			return true;
		}

		return $show;
	}
}
