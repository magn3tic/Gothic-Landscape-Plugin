<?php
/**
 * Post Type Interface
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  PostType
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\PostType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Job Listing custom post type
 *
 * @since 1.0.0
 * @final
 */
interface PostTypeInterface {

	/**
	 * Implementing Classes Should Declare the Following Properties
	 *
	 * @property $key     The key (computer name) of the Post Type
	 * @property $icon    The dashicons icon for the Post Type
	 * @property $rewrite The rewrite slug for the Post Type
	 * @property $class   The name of the calling class.
	 */

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public function __construct();

	/**
	 * Init
	 *
	 * Items to call upon WP Initialization, often which require validation of the current query.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function init() : void;

	/**
	 * Admin Init
	 *
	 * Items to call upon WP Admin initialization, often which require validation of the current screen.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function admin_init() : void;

	/**
	 * Singular
	 *
	 * Return the Singular name for the Post Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function singular() : string;

	/**
	 * Plural
	 *
	 * Return the Plural name for the Post Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function plural() : string;

	/**
	 * Register Post Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function register_post_type() : void;

	/**
	 * Is Post of Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function is_post_of_type( int $post_id ) : bool;

	/**
	 * Is Post of Type
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return bool
	 */
	public static function is_post_type_admin() : bool;
}
