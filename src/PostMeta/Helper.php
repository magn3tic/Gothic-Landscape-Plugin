<?php
/**
 * Resource Meta Box
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

final class Helper {
	public static function meta_field( $args = null, $field = null ) {

		if ( ! is_array( $args ) || ! $field ) {
			return false;
		}

		$args = wp_parse_args( $args, array(
			'type'        => null,
			'template'    => null,
			'name'        => null,
			'default'     => null,
			'label'       => null,
			'sublabel'    => null,
			'description' => null,
			'options'     => null,
			'attributes'  => array(),
			'class'       => array(),
			'value'       => null,
			'sanitize'    => null,
		) );

		$args['name'] = $field;

		// Determine Form Template (Default, or Check for Specific)
		$template = ! empty( $args['template'] ) ? $args['template'] : $args['type'];

		// Add Classes
		$args['class'] = array(
			'gothic-meta-field-group',
			'gothic-meta-field-' . $field,
			'gothic-meta-field-template-' . $template,
			'gothic-meta-field-type-' . $args['type'],
		);

		$args['value'] = $args['value'] ?: get_post_meta( get_the_id(), $field, true );

		// Clean Up a Bit
		unset( $args['template'], $args['sanitize'], $args['supports'] );

		return array( $args, $template );
	}
}