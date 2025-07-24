<?php
/**
 * Builder Meta Box
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
use Gothic\Selections\PostType\Community as PostType;

final class CommunityAbout extends PostMetaAbstract {

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

		return __( 'About Community', 'gothic-selections' );
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
				if ( 'wysiwyg' === $args['type'] ) {
					$meta[ $field ] = wp_kses_post( $_POST[ $field ] ); //PHPCS:ignore
				} elseif ( 'select' === $args['type'] ) {

					$options = $args['options'];

					if ( 'builder_id' === $field ) {
						unset( $options[-1] );
					}

					$allowed = array_keys( $options );

					if ( in_array( $_POST[ $field ], $allowed ) ) {
						$meta[ $field ] = $_POST[ $field ]; //PHPCS:ignore
					} else {
						$meta[ $field ] = null;
					}
				} else {
					$meta[ $field ] = sanitize_text_field( $_POST[ $field ] ); //PHPCS:ignore
				}
			} else {
				$meta[ $field ] = null;
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

		if ( ! current_user_can( 'edit_communities', $_POST['post_type'] ) ) {

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

		$fields = [
			'builder_id' => [
				'type'  => 'select',
				'label' => __( 'Homebuilder', 'gothic-selections' ),
				'value' => isset( $_GET['builder_id'] ) ? intval( $_GET['builder_id'] ) : null, //PHPCS:ignore
			],
			'location'   => [
				'type'  => 'text',
				'label' => __( 'Location (Cross Streets & City)', 'gothic-selections' ),
			],
			'address'    => [
				'type'  => 'text',
				'label' => __( 'Sales Office Address', 'gothic-selections' ),
			],
			'phone'      => [
				'type'  => 'text',
				'label' => __( 'Sales Office Phone', 'gothic-selections' ),
			],
			'warranty'   => [
				'type'        => 'wysiwyg',
				'label'       => __( 'Landscape Warranty Info', 'gothic-selections' ),
				'description' => __( 'Leave blank for Gothic Landscape default warranty. Include any special instructions for how buyers can get warranty support.', 'gothic-selections' ),
			],
			'frontyard' => [
				'type'        => 'select',
				'label'       => __( 'Front Yard Offer Status', 'gothic-selections' ),
				'description' => __( 'Front Yards are typically offered via the selections system but may be disabled for backyard only communities.', 'gothic-selections' ),
				'options'     => [
					'1'  => __( 'Offered via Selections', 'gothic-selections' ),
					'0'  => __( 'Not Offered', 'gothic-selections' ),
				],
			],
			'backyard' => [
				'type'        => 'select',
				'label'       => __( 'Backyard Offer Status', 'gothic-selections' ),
				'description' => __( 'Backyards may be offered via the selections system or via an opt-in for an email with more information.', 'gothic-selections' ),
				'options'     => [
					'0'  => __( 'Not Offered', 'gothic-selections' ),
					'1'  => __( 'Offered via Selections', 'gothic-selections' ),
					'-1' => __( 'Offered via Opt-In', 'gothic-selections' ),
				],
			],
			'inactive' => [
				'type'        => 'select',
				'label'       => __( 'Status', 'gothic-selections' ),
				'options'     => [
					'0' => __( 'Active', 'gothic-selections' ),
					'1' => __( 'Inactive', 'gothic-selections' ),
				],
				'description' => __( 'Set status to inactive to hide without deleting. As packages with associated selections orders cannot be deleted, use this option to hide new orders.', 'gothic-selections' ),
			],
		];

		$builders = get_posts( array(
			'post_type'   => 'builders',
			'numberposts' => 1000,
		) );

		$options = [];

		if ( ! is_wp_error( $builders ) && ! empty( $builders ) ) {

			$options[-1] = __( 'Select a Builder', 'gothic-selections' );

			foreach ( $builders as $builder ) {
				$options[ $builder->ID ] = $builder->post_title;
			}
		}

		$fields['builder_id']['options'] = $options;

		return $fields;
	}
}
