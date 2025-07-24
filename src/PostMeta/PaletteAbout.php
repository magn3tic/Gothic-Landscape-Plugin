<?php
/**
 * Plans Palettes Meta Box
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
use Gothic\Selections\PostType\{ Palette as PostType, Community };
use Gothic\Materials\Taxonomy\Type;

final class PaletteAbout extends PostMetaAbstract {

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

		return __( 'Plant Materials Palette Contents', 'gothic-selections' );
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

		if ( 'add' === get_current_screen()->action ) {
			$fields['community_id'] = [
				'type'  => 'hidden',
				//phpcs:ignore
				'value' => isset( $_GET['community_id'] ) ? intval( $_GET['community_id'] ) : null,
			];
		}

		$fields['about']    = [
			'type'        => 'wysiwyg',
			'label'       => __( 'About this Palette', 'gothic-selections' ),
			'description' => __( 'Brief description of the palette\'s features for descriptive and marketing purposes. Will be shown on the palettes list.', 'gothic-selections' ),
		];

		$materials_types = get_terms(
			[
				'taxonomy'   => Type::$key,
				'hide_empty' => true,
			]
		);

		foreach ( $materials_types as $term => $args ) {
			$fields[ $args->slug . '-field' ] = [
				'type'    => 'post-picker',
				'label'   => $args->name . ' on this Palette',
				'options' => [
					'post_type' => 'materials',
					'taxonomy'  => 'materials-types',
					'term'      => $args->slug,
					'multiple'  => true,
				],
			];
		}

		return $fields;
	}
}
