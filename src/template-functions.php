<?php

use Gothic\Selections\Helper\Template;
use Gothic\Selections\PostType\PreferencesOrder as Order;

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
function gothic_selections_get_template( string $name, array $args = [], string $subdirectory = '', bool $admin = false, bool $echo = false ) : ?string {
	return Template::get_template( $name, $args, $subdirectory, $admin, $echo );
}

/**
 * Form Field
 *
 * Gets form field
 *
 * @since 1.0.0
 *
 * @param array $args
 * @param bool $echo
 *
 * @return string|null
 */
function gothic_selections_form_field( array $args, bool $echo = true ) : ?string {
	return Template::form_field( $args, $echo );
}

/**
 * Get Form Value from Meta or Request
 *
 * @since 1.0.0
 *
 * @param string $field
 * @param array $request
 * @param string $sanitize
 *
 * @return mixed
 */
function gothic_selections_form_value( string $field, array $request = [], string $sanitize = '' ) {

	if ( ! $field ) {

		return '';
	}

	if ( ! empty( $request ) && ! empty( $request[ $field ] ) ) {
		if ( $sanitize ) {

			return call_user_func( $sanitize, $request[ $field ] );
		} else {

			return sanitize_text_field( $request[ $field ] );
		}
	}

	if ( Order::$key !== get_post_type( get_the_id() ) ) {

		return '';
	}

	if ( get_post_meta( get_the_id(), $field, true ) ) {

		return get_post_meta( get_the_id(), $field, true );
	}

	return '';
}

function gothic_the_selection_order_status( int $id = 0 ) : void {
	echo gothic_get_the_selection_order_status( $id );
}

function gothic_get_the_selection_order_status( $id = 0 ) : string {
	return Template::get_selection_order_status_tooltip( $id );
}

function gothic_the_selection_order_status_tooltip( int $id = 0 ) : void {
	echo gothic_get_selection_order_status_tooltip( $id );
}

function gothic_get_selection_order_status_tooltip( int $id = 0 ) : string {
	return Template::get_selection_order_status_tooltip( $id );
}
