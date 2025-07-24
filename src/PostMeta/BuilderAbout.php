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
use Gothic\Selections\PostType\Builder as PostType;

final class BuilderAbout extends PostMetaAbstract {

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

		return __( 'About the Homebuilder', 'gothic-selections' );
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
		return array(
			'about' => array(
				'type'        => 'wysiwyg',
				'label'       => 'About this Homebuilder',
				'description' => 'Our design does not currently call for it, but in the future we may want to feature more information about our partner builders.',
			),
			'inactive' => [
				'type'        => 'select',
				'label'       => __( 'Status', 'gothic-selections' ),
				'options'     => [
					'0' => __( 'Active', 'gothic-selections' ),
					'1' => __( 'Inactive', 'gothic-selections' ),
				],
				'description' => __( 'Set status to inactive to hide without deleting. As builders with associated selections orders or communities cannot be deleted, use this option to hide new orders.', 'gothic-selections' ),
			],
		);
	}
}
