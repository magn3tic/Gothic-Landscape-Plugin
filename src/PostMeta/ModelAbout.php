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

use Gothic\Selections\Plugin;
use Gothic\Selections\Admin\Notice;
use Gothic\Selections\PostType\{Model as PostType, Community, Model};

final class ModelAbout extends PostMetaAbstract {

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

		return PostType::$key . '-about';
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

		return __( 'About Home Model', 'gothic-selections' );
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

		// Sanitize
		if ( isset( $_REQUEST['community_id'] ) && Community::$key === get_post_type( (int) $_REQUEST['community_id'] ) ) {
			$meta['community_id'] = (int) $_REQUEST['community_id'];
		} elseif ( get_post_type( get_post_meta( $post_id, 'community_id', true ) ) !== Community::$key ) {
			Notice::add( 'cant-create-unassociated-community-dependency', __( 'You tried to directly create a new item that depends on a community association (ie: a model or package, etc) without initiating your request from within the community editor. Instead, use the "Add New" button inside a community.', 'gothic-selections' ), 'warning' );
			wp_safe_redirect( get_admin_url() . 'edit.php?post_type=' . Community::$key );
			die();
		}

		if ( ! empty( $_REQUEST['plan_id'] ) ) {
			$meta['plan_id'] = esc_attr( $_REQUEST['plan_id'] );
		} else {
			$meta['plan_id'] = '';
		}

		if ( ! empty( $_REQUEST['about'] ) ) {
			$meta['about'] = wp_kses_post( $_REQUEST['about'] );
		} else {
			$meta['about'] = '';
		}

		if ( isset( $_REQUEST['inactive'] ) ) {
			$meta['inactive'] = (bool) $_REQUEST['inactive'] ? '1' : '0';
		} else {
			$meta['inactive'] = '0';
		}

		// Save
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

		if ( 'add' === get_current_screen()->action ) {
			$fields['community_id'] = [
				'type'  => 'hidden',
				//phpcs:ignore
				'value' => isset( $_GET['community_id'] ) ? intval( $_GET['community_id'] ) : null,
			];
		}

		$fields['plan_id'] = [
				'type'        => 'text',
				'label'       => __( 'Builder Model Plan Number', 'gothic-selections' ),
				'description' => __( 'While model names are for marketing by the salesperson, it is with the plan ID that most communication between the builder and the landscaper communicate.', 'gothic-selections' ),
				//PHPCS:ignore
				'value'       => isset( $_GET['plan_id'] ) ? intval( $_GET['plan_id'] ) : null,
		];

		$fields['about']    = [
			'type'        => 'wysiwyg',
			'label'       => __( 'About this Model', 'gothic-selections' ),
			'description' => __( 'Brief description of model in a few words. Currently not used, but could be in the future.', 'gothic-selections' ),
		];

		$fields['inactive'] = [
			'type'        => 'select',
			'label'       => __( 'Status', 'gothic-selections' ),
			'options'     => [
				'0' => __( 'Active', 'gothic-selections' ),
				'1' => __( 'Inactive', 'gothic-selections' ),
			],
			'description' => __( 'Set status to inactive to hide without deleting. As models with associated selections orders cannot be deleted, use this option to hide new from orders.', 'gothic-selections' ),
		];

		return $fields;
	}
}
