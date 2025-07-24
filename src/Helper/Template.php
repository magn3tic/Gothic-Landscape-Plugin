<?php
/**
 * Template Helpers
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Helpers
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Helper;

use Gothic\Selections\Plugin;

final class Template {

	/**
	 * Get Template Part
	 *
	 * Gets template part (for templates in loops).
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug         Name prefix for template part.
	 * @param string $name         Name of template part to use.
	 * @param array  $args         Array of args passed to template.
	 * @param string $subdirectory Name of subdirectory where template is located.
	 * @param bool   $admin        Whether the template is an admin template, which prohibits theme override
	 * @param bool   $echo         Whether to echo the template or return it as a string.
	 *
	 * @return string|bool
	 */
	public static function get_template_part( $slug, $name = '', $args = array(), $subdirectory = 'parts', $admin = false, $echo = false ) {

		$template_name = "{$slug}-{$name}";

		return self::get_template( $template_name, $args, $subdirectory, $admin, $echo );
	}

	/**
	 * Get Template
	 *
	 * Loads a template and passes an array of variables.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Name of template to use.
	 * @param array $args Array of args passed to template.
	 * @param string $subdirectory Name of subdirectory where template is located.
	 * @param bool $admin Whether the template is an admin template, which prohibits theme override
	 * @param bool $echo Whether to echo the template or return it as a string.
	 *
	 * @return string|null
	 */
	public static function get_template( $name, $args = array(), $subdirectory = null, $admin = false, $echo = false ) : ?string {

		$template = null;

		if ( $name ) {
			$template = self::locate_template( "{$name}.php", $subdirectory, $admin );
		}

		if ( $template ) {

			if ( $args && is_array( $args ) ) {
				extract( $args );
			}

			if ( $echo ) {

				include $template;

				return null;
			} else {
				ob_start();
				include( $template );

				return ob_get_clean();
			}
		} else {

			return null;
		}
	}

	/**
	 * Locate Template
	 *
	 * Locates a template and return the path for inclusion.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Name of template part to use.
	 * @param string $subdirectory Name of subdirectory where template is located.
	 * @param bool $admin Whether the template is an admin template, which prohibits theme override
	 *
	 * @return string|null
	 */
	private static function locate_template( string $name, string $subdirectory = '', bool $admin = false ) : ?string {

		$template = false;

		if ( $admin ) {

			// Check Admin Subdirectory for Admin Templates
			$plugin_admin_template_directory = Plugin::$directory . 'templates' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $subdirectory;

			if ( file_exists( trailingslashit( $plugin_admin_template_directory ) . $name ) ) {
				$template = trailingslashit( $plugin_admin_template_directory ) . $name;
			}
		}

		// If Not Yet Found, Look Within Main Templates Directory
		if ( ! $template ) {

			$plugin_template_directory = Plugin::$directory . 'templates/' . $subdirectory;

			if ( file_exists( trailingslashit( $plugin_template_directory ) . $name ) ) {
				$template = trailingslashit( $plugin_template_directory ) . $name;
			}
		}

		// Filter The Result And Return
		return apply_filters( 'gothic_plugin_locate_template', $template, $name, $subdirectory, $admin );
	}

	public static function classes() {
		return esc_attr( implode( ' ', self::array_classes( func_get_args() ) ) );
	}

	/**
	 * Array of Classes
	 *
	 * Accepts an unlimited number of strings or arrays of classes
	 *
	 * @param array $args
	 * @return array
	 */
	public static function array_classes( $args ) {
		$classes = array();

		if ( is_string( $args ) ) {
			return array( esc_attr( $args ) );
		}

		foreach ( $args as $arg ) {
			if ( empty( $arg ) ) {
				continue;
			}
			if ( is_string( $arg ) ) {
				$arg = explode( ',', $arg );
			}
			if ( ! is_array( $arg ) ) {
				continue;
			}
			foreach ( $arg as $arg_part ) {
				if ( is_string( $arg_part ) ) {
					$classes[] = sanitize_html_class( trim( $arg_part ) );
				}
			}
		}
		return $classes;
	}

	public static function attributes( $attributes_array ) {
		$attributes = array();

		if ( empty( $attributes_array ) || ! is_array( $attributes_array ) ) {
			return '';
		}

		foreach ( $attributes_array as $attribute => $value ) {
			if ( ( is_bool( $value ) && $value ) || 'true' === $value ) {
				$attributes[] = $attribute;
			} else {
				$attributes[] = sprintf( '%1$s="%2$s"', $attribute, $value );
			}
		}

		return implode( ' ', $attributes );
	}

	public static function form_field( $args, $echo = true ) {
		list( $args, $template ) = self::form_field_args( $args );
		return self::get_template_part( 'field', $template, $args, 'form-fields', false, $echo );
	}

	public static function form_field_args( $args = null ) {

		if ( empty( $args ) || empty( $args['name'] ) || empty( $args['type'] ) ) {
			return false;
		}

		// Add all potential indexes to the array
		// to prevent undefined index errors.
		$args = wp_parse_args( $args, array(
			'type'        => 'text',
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

		// Determine Form Template (Default, or Check for Specific)
		$template = ! empty( $args['template'] ) ? $args['template'] : $args['type'];

		// Assign default classes and clean up the argument passed
		$args['class'] = self::array_classes( $args['class'], [
			'field-group',
			'field-' . $args['name'],
			'field-type-' . $args['type'],
			'field-template-' . $template,
		] );

		// Clean Up a Bit
		unset( $args['template'] );

		return array( $args, $template );
	}

	public static function get_selection_order_status_tooltip( int $id = 0 ) : string {

		if ( 0 === $id ) {
			$id = get_the_ID();
		}

		$status = get_post_meta( $id, '_status', true );

		if ( empty( $status ) || ! in_array( $status, array_keys( Misc::statuses() ), true ) ) {

			return '';
		}

		$args = Misc::statuses()[ $status ];

		$args['status'] = $status;

		return self::get_template( 'selection-order-status-tooltip', $args, 'helper', false, false );
	}
}