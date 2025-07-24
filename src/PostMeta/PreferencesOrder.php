<?php
/**
 * Preferences Meta Box
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
use Gothic\Selections\PostType\PreferencesOrder as PostType;

final class PreferencesOrder extends PostMetaAbstract {

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

		return __( 'Preferences Order Details', 'gothic-selections' );
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
		foreach ( self::meta_fields() as $field => $args ) {
			if ( ! empty( $_POST[ $field ] ) ) { //PHPCS:ignore
				$meta[ $field ] = $_POST[ $field ]; //PHPCS:ignore
			}
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

		if ( ! current_user_can( 'edit_preferences_orders', $_POST['post_type'] ) ) {

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
		return [
			'_status'   => [
				'type'  => 'text',
				'label' => __( 'Status', 'gothic-selections' ),
			],
			'_is_self_order'   => [
				'type'  => 'checkbox',
				'label' => __( 'Is Self-Initiated Order', 'gothic-selections' ),
			],
			'_is_invite_sent'  => [
				'type'  => 'checkbox',
				'label' => __( 'Email Invite Sent', 'gothic-selections' ),
			],
			'_step'            => [
				'type'        => 'text',
				'label'       => __( 'step', 'gothic-selections' ),
				'admin_label' => __( 'step', 'gothic-selections' ),
			],
			'home_buyer'       => [
				'type'        => 'text',
				'label'       => __( 'Your Full Name(s)', 'gothic-selections' ),
				'admin_label' => __( 'Homebuyer(s)', 'gothic-selections' ),
			],
			'email'            => [
				'type'     => 'email',
				'template' => 'text',
				'label'    => __( 'Email Address', 'gothic-selections' ),
			],
			'phone'            => [
				'type'     => 'tel',
				'template' => 'text',
				'label'    => __( 'Phone Number', 'gothic-selections' ),
			],
			'address'          => [
				'type'        => 'text',
				'label'       => __( 'New Home Address', 'gothic-selections' ),
				'admin_label' => __( 'Address', 'gothic-selections' ),
			],
			'city'             => [
				'type'        => 'text',
				'label'       => __( 'New Home City', 'gothic-selections' ),
				'admin_label' => __( 'City', 'gothic-selections' ),
			],
			'state'            => [
				'type'        => 'text',
				'label'       => __( 'New Home State', 'gothic-selections' ),
				'admin_label' => __( 'State', 'gothic-selections' ),
				'default'     => __( 'Arizona', 'gothic-selections' ),
			],
			'zip'              => [
				'type'        => 'number',
				'template'    => 'text',
				'label'       => __( 'New Home ZIP Code', 'gothic-selections' ),
				'admin_label' => __( 'ZIP', 'gothic-selections' ),
			],
			'lot'              => [
				'type'  => 'text',
				'label' => __( 'Lot Number', 'gothic-selections' ),
			],
			'builder_rep_name' => [
				'type'  => 'text',
				'label' => __( 'Sales Associate Name', 'gothic-selections' ),
			],
			'builder_rep_email' => [
				'type'  => 'text',
				'label' => __( 'Sales Associate Email', 'gothic-selections' ),
			],
			'builder_id'       => [
				'type'  => 'text',
				'label' => __( 'Home Builder', 'gothic-selections' ),
			],
			'community_id'     => [
				'type'  => 'text',
				'label' => __( 'Community', 'gothic-selections' ),
			],
			'model_id'         => [
				'type'  => 'text',
				'label' => __( 'Model', 'gothic-selections' ),
			],
			'is_corner'        => [
				'type'  => 'checkbox',
				'label' => __( 'Is Corner Lot', 'gothic-selections' ),
			],
			'package_id'       => [
				'type'  => 'text',
				'label' => __( 'Landscape Package', 'gothic-selections' ),
			],
			'package_by_id'    => [
				'type'  => 'text',
				'label' => __( 'Backyard Landscape Package', 'gothic-selections' ),
			],
			'palette_id'       => [
				'type'  => 'text',
				'label' => __( 'Plant Palette', 'gothic-selections' ),
			],
			'comments'         => [
				'type'  => 'textarea',
				'label' => __( 'Comments', 'gothic-selections' ),
			],
			'confirmed_name'   => [
				'type'  => 'text',
				'label' => __( 'Confirmed Name', 'gothic-selections' ),
			],
			'confirmed_time'   => [
				'type'  => 'text',
				'label' => __( 'Confirmed Time', 'gothic-selections' ),
			],
			'order_history'   => [
				'type'  => 'text',
				'label' => __( 'Order history', 'gothic-selections' ),
			],
		];
	}
}
