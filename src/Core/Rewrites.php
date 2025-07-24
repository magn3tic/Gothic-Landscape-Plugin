<?php
/**
 * Rewrites
 *
 * Adds custom rewrites for functions where required.
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Core
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Core;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\PostType\PreferencesOrder as Orders;

/**
 * Rewrites Class
 *
 * @since 1.0.0
 *
 * @final
 */
final class Rewrites {

	/**
	 * Rewrites constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'orders_rewrite_rules' ) );
		add_filter( 'query_vars', array( $this, 'register_query_var' ) );
		add_action( 'template_redirect', array( $this, 'url_rewrite_templates' ) );
	}

	public function orders_rewrite_rules() {
		$base_screens = [ 'index', 'new', 'access', 'report', 'check-email' ];
		$post_screens = [ 'start', 'welcome', 'model', 'package', 'preferences', 'info', 'thanks', 'show', 'edit', 'transfer', 'cancel', 'void', 'delete', 'remind', 'pending', 'certify' ];
		add_rewrite_rule( 'landscaping/selection/(' . implode( '|', $base_screens ) . ')',         'index.php?page&pagename=landscaping%2Fselection&step=$matches[1]', 'top' );
		// die('landscaping/selection/(' . implode( '|', $base_screens ) . ')');
		add_rewrite_rule( 'landscaping/selection/([^/]+)/(' . implode( '|', $post_screens ) . ')', 'index.php?post_type=' . Orders::$key . '&name=$matches[1]&step=$matches[2]', 'top' );
	}

	public function register_query_var( $vars ) {
		$vars[] = 'step';
		$vars[] = 'community_id';
		return $vars;
	}

	public function url_rewrite_templates() {
		if ( get_query_var( Orders::$key ) ) {
			if ( get_query_var( 'name' ) && 'index' === get_query_var( 'name' ) && current_user_can( 'administrator' ) ) {

				add_action( 'pre_get_posts', function( $query ) {
					$query->__unset( 'name' );
				} );
				add_filter( 'template_include', function ( $template ) {
					$override = locate_template( array( 'archive-preferences_order.php' ) );
					if ( ! empty( $override ) ) {
						return $override;
					}
					return $template;
				} );

				status_header( 200 );
			} elseif ( get_query_var( 'name' ) && ! get_page_by_path( get_query_var( 'name' ), 'OBJECT', Orders::$key ) ) {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
			} else {
				add_filter( 'template_include', function ( $template ) {
					$override = locate_template( array( 'single-preferences_order.php' ) );
					if ( ! empty( $override ) ) {
						return $override;
					}
					return $template;
				} );
			}
		}
	}
}
