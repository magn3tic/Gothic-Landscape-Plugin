<?php


namespace Gothic\Selections\PostType;

use Gothic\Selections\Plugin;
use Gothic\Selections\Admin\Notice;

trait CommunityDependency {

	/**
	 * After Edit Form Title
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function add_community_after_title() {
		if ( static::$key !== get_current_screen()->id ) {
			return;
		}

		if ( 'add' === get_current_screen()->action ) {
			$community = intval( $_GET['community_id'] );
			$builder   = intval( get_post_meta( $community, 'builder_id', true ) );
		} else {
			global $post;
			$community = intval( get_post_meta( $post->ID, 'community_id', true ) );
			$builder   = intval( get_post_meta( $community, 'builder_id', true ) );
		}

		if ( $community ) {

			$community_name = get_the_title( $community );
			$builder_name = get_the_title( $builder );

			$format = 'for <a href="%1$s">%2$s</a> by <a href="%3$s">%4$s</a>';
			$string = sprintf( $format, esc_url( admin_url() . '/post.php?action=edit&post=' . $community ), esc_html( $community_name ), esc_url( admin_url() . '/post.php?action=edit&post=' . $builder ), esc_html( $builder_name ) );

			echo '<h3>' . apply_filters( Plugin::FILTER_PREFIX . static::$key . '_after_title', $string ) . '</h3>';
		}
	}

	/**
	 * Redirect on Invalid New
	 *
	 * In order to create a Package, you must query the post-new.php page with a query of a valid community_id. If you
	 * do not, you will be redirect to the Communities admin index.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function redirect_on_invalid_new() : void {
		if ( static::$key !== get_current_screen()->id ) {
			return;
		}

		if ( 'add' !== get_current_screen()->action ) {
			return;
		}

		$redirect = false;

		if ( ! isset( $_GET[ 'community_id' ] ) ) {
			$redirect = true;
		}

		$community = intval( $_GET['community_id'] );

		if ( ! $redirect && Community::$key !== get_post_type( $community ) ) {
			$redirect = true;
		}

		if ( $redirect ) {
			Notice::add( 'cant-create-unassociated-community-dependency', __( 'You tried to directly create a new item that depends on a community association (ie: a model or package, etc) without initiating your request from within the community editor. Instead, use the "Add New" button inside a community.', 'gothic-selections' ), 'warning' );
			wp_safe_redirect( get_admin_url() . 'edit.php?post_type=' . Community::$key );
			die();
		}
	}

	/**
	 * Hide "Add New" Buttons for Community Dependencies
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function hide_add_new_button() {

		if ( ! is_admin() ) {

			return;
		}

		global $pagenow;

		if ( $pagenow !== 'edit.php' && $pagenow !== 'post.php' ) {

			return;
		}


		if ( get_current_screen()->post_type !== static::$key ) {

			return;
		}

		echo "<style>.page-title-action,.add-new-h2{display: none;}</style>";
	}
}