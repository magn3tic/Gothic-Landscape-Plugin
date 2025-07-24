<?php
/**
 * Post Meta Abstract
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

use \WP_Post;
use Gothic\Selections\Plugin;
use Gothic\Selections\Helper\{ Template, DashedUnderscoredTrait };


abstract class PostMetaAbstract implements PostMetaInterface {

	use DashedUnderscoredTrait;

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
	 * @return void
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', [ static::$class, 'register' ] );
		add_action( 'save_post', [ static::$class, 'save' ] );
	}

	/**
	 * Meta Box Post Key
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function key() : string {

		return 'meta_box';
	}

	/**
	 * Meta Box Post Types
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function name() : string {

		return __( 'Meta Box', 'gothic-selections' );
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

		return [ 'post' ];
	}

	/**
	 * Add Meta Box
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function register() : void {
		foreach ( static::types() as $type ) {

			global $post;

			if ( apply_filters( Plugin::FILTER_PREFIX . self::dashes_underscored( static::key() ) . '_disable', false, $post ) ) {
				continue;
			}

			if ( apply_filters( Plugin::FILTER_PREFIX . self::dashes_underscored( static::key() ) . '_hide_on_new', false ) ) {
				if ( ! isset( $post ) || $post->post_status === 'auto-draft' ) {
					continue;
				}
			}

			add_meta_box( static::key(), static::name(), [ static::class, 'render' ], $type );
		}
	}

	/**
	 * Save Meta Box
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
	public static function save( int $post_id ) : void {}

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

		global $post;

		if ( $post && ! in_array( get_post_type( $post->ID ), static::types(), true ) ) {
			return false;
		}

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

		return true;
	}

	/**
	 * Sanitize
	 *
	 * Runs before save to validate the request
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $input
	 *
	 * @return array
	 */
	protected static function sanitize( array $input ) : array {

		return $input;
	}

	/**
	 * Nonce
	 *
	 * Generates a nonce based on the plugin key
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function nonce() : string {
		$instance = Plugin::instance();
		return wp_nonce_field( plugin_basename( $instance::$file ), static::key() . '_nonce' );
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

		return [];
	}

	/**
	 * Render Meta Box
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public static function render( WP_Post $post ) : void {
		$template_vars = [
			'nonce'     => static::nonce(),
			'meta_box'  => static::key(),
			'post_type' => static::types(),
			'post'      => $post,
			'fields'    => static::meta_fields(),
		];

		Template::get_template( 'meta-box-general', $template_vars, 'meta-boxes', true, true );
	}
}
