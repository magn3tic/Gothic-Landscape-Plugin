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

use Gothic\Selections\Admin\Notice;
use Gothic\Selections\PostType\{Package as PostType, Community};

final class PackageAbout extends PostMetaAbstract {

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

		return __( 'About Landscape Package', 'gothic-selections' );
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

		if ( isset( $_REQUEST['is_backyard'] ) && 1 == $_REQUEST['is_backyard'] ) {
			$meta['is_backyard'] = (int) $_REQUEST['is_backyard'];
		} elseif ( isset( $_REQUEST['is_backyard'] ) ) {
			$meta['is_backyard'] = 0;
		}

		if ( ! empty( $_REQUEST['about'] ) ) {
			$meta['about'] = wp_kses_post( $_REQUEST['about'] );
		} else {
			$meta['about'] = '';
		}

		if ( isset( $_REQUEST['selected'] ) ) {
			$meta['selected'] = (bool) $_REQUEST['selected'] ? '1' : '0';
		}

		if ( isset( $_REQUEST['inactive'] ) ) {
			$meta['inactive'] = (bool) $_REQUEST['inactive'] ? '1' : '0';
		} else {
			$meta['inactive'] = '0';
		}

		if ( isset( $_REQUEST['is_upgrade'] ) ) {
			$meta['is_upgrade'] = (bool) $_REQUEST['is_upgrade'] ? '1' : '0';
		} else {
			$meta['is_upgrade'] = '0';
		}

		if ( isset( $_REQUEST['no_palette'] ) ) {
			$meta['no_palette'] = (bool) $_REQUEST['no_palette'] ? '1' : '0';
		} else {
			$meta['no_palette'] = '0';
		}

		// Save
		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
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
			$fields['is_backyard']  = [
				'type'  => 'hidden',
				'value' => isset( $_GET['backyard'] ) ? 1 : 0,
			];
		}

		$fields['about']    = [
			'type'        => 'wysiwyg',
			'label'       => __( 'About this Package', 'gothic-selections' ),
			'description' => __( 'Brief description of the plan in a few words for descriptive and marketing purposes. Will be shown on the packages list.', 'gothic-selections' ),
		];

		$fields['is_upgrade'] = [
			'type'        => 'select',
			'label'       => __( 'Is an Upgraded Plan', 'gothic-selection' ),
			'options'     => [
				'0' => __( 'Standard/Included Plan', 'gothic-selections' ),
				'1' => __( 'Upgraded Plan', 'gothic-selections' ),
			],
			'description' => __( 'Whether to pre-select this option on buyer-initiated selections.', 'gothic-selections' ),
		];

		$fields['selected'] = [
			'type'        => 'select',
			'label'       => __( 'Auto Select This Option', 'gothic-selections' ),
			'options'     => [
				'0' => __( 'Do Not Autoselect', 'gothic-selections' ),
				'1' => __( 'Autoselect', 'gothic-selections' ),
			],
			'description' => __( 'Select whether have this option be the pre-selected option when buyers initiate an order (ignored when sellers initiate orders).', 'gothic-selections' ),
		];

		$fields['no_palette'] = [
			'type'        => 'select',
			'label'       => __( 'Does Package Not Require a Palette?', 'gothic-selection' ),
			'options'     => [
				'0' => __( 'Requires a Palette', 'gothic-selections' ),
				'1' => __( 'Do not offer palette.', 'gothic-selections' ),
			],
			'description' => __( 'If this package does not require a palette selection, ie: is a "no backyard", please denote. This will ensure the home buyer is not prompted to select one.', 'gothic-selections' ),
		];

		$fields['inactive'] = [
			'type'        => 'select',
			'label'       => __( 'Status', 'gothic-selections' ),
			'options'     => [
				'0' => __( 'Active', 'gothic-selections' ),
				'1' => __( 'Inactive', 'gothic-selections' ),
			],
			'description' => __( 'Set status to inactive to hide without deleting. As packages with associated selections orders cannot be deleted, use this option to hide new orders.', 'gothic-selections' ),
		];

		return $fields;
	}
}
