<?php
/**
 * Landscape Preferences Order Post Type
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

use Gothic\Selections\Plugin;
use Gothic\Selections\PostMeta\PreferencesOrder as MetaBox;
use Gothic\Selections\Helper\{Email, Misc, Template};

/**
* Registers and sets up the Downloads custom post type
*
* @since 1.0.0
* @return void
*/
final class PreferencesOrder extends PostTypeAbstract {

	/**
	 * Post Type Key
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 */
	public static $key = 'preferences_order';

	/**
	 * Post Type Rewrite Key
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 */
	protected static $rewrite = 'landscaping/selection';

	/**
	 * Post Type Icon
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 */
	protected static $icon = 'dashicons-clipboard';

	/**
	 * This Class Name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 */
	protected static $class = __CLASS__;

	/**
	 * Constructor
	 *
	 * Items to call on constructor. Call parent::__construct() or omit.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		new MetaBox();
		add_action( 'save_post_' . static::$key, [ __CLASS__, 'save' ] );
	}

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
	public static function singular() : string {

		return __( 'Preference Order', 'gothic-selections' );
	}

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
	public static function plural() : string {

		return __( 'Preference Orders', 'gothic-selections' );
	}
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
	public static function admin_init() : void {}


	/**
	 * Post Type Args (Child Class Override)
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @return array
	 */
	protected static function args() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_args', [
			'description'         => ucfirst( static::singular() ) . ' ' . esc_html__( 'Post Type', 'gothic-selections' ),
			'labels'              => static::labels(),
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 20,
			'menu_icon'           => static::$icon,
			'hierarchical'        => false,
			'supports'            => static::supports(),
			'has_archive'         => false,
			'rewrite'             => static::rewrites(),
			'query_var'           => true,
			'can_export'          => false,
			'capability_type'     => [ static::$key, static::$key . 's' ],
			'show_in_rest'        => false,
		] );
	}

	/**
	 * Post Type Labels (Child Class Override)
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @return array
	 */
	protected static function labels() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_labels', [
			'name'               => esc_html_x( 'Preference Order', 'Taxonomy General Name', 'gothic-landscape-plugin' ),
			'singular_name'      => esc_html_x( 'Preference Orders', 'Taxonomy Singular Name', 'gothic-landscape-plugin' ),
			'add_new'            => esc_html__( 'New Order', 'gothic-landscape-plugin' ),
			'add_new_item'       => esc_html__( 'Add New Order', 'gothic-landscape-plugin' ),
			'edit_item'          => esc_html__( 'Edit Order', 'gothic-landscape-plugin' ),
			'new_item'           => esc_html__( 'New', 'gothic-landscape-plugin' ),
			'view_item'          => esc_html__( 'View', 'gothic-landscape-plugin' ),
			'search_items'       => esc_html__( 'Search', 'gothic-landscape-plugin' ),
			'not_found'          => esc_html__( 'None found', 'gothic-landscape-plugin' ),
			'not_found_in_trash' => esc_html__( 'None found in Trash', 'gothic-landscape-plugin' ),
			'parent_item_colon'  => '',
			'all_items'          => esc_html__( 'Orders', 'gothic-landscape-plugin' ),
			'menu_name'          => esc_html__( 'Preferences', 'gothic-landscape-plugin' ),
		] );
	}

	/**
	 * Post Type Supports
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @return array
	 */
	protected static function supports() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_supports', [ 'title' ] );
	}

	/**
	 * Post Type Rewrites
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @return array
	 */
	protected static function rewrites() : array {

		return apply_filters( Plugin::FILTER_PREFIX . static::$key . '_rewrites', [
			'slug'       => static::$rewrite,
			'with_front' => false,
			'pages'      => false,
			'feeds'      => false,
		] );
	}

	/**
	 * Save Post Actions
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

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		self::set_title( $post_id );

		self::set_name( $post_id );

		self::send_code_email( $post_id );
	}

	/**
	 * Set "Post" Title
	 *
	 * Automatically sets the order's title upon creation of entry and/or draft
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	private static function set_title( int $post_id ) : void {

		$title = get_post( $post_id )->post_title;

		if ( 'Auto Draft' === $title || empty( $title ) ) {

			// Unhook save routine to prevent infinite loops.
			remove_action( 'save_post_' . static::$key, [ __CLASS__, 'save' ] );

			// Set the Name
			wp_update_post( array(
				'ID'         => $post_id,
				'post_title' => 'Landscape Preferences Request',
			) );

			// Safe to re-hook the save routine.
			add_action( 'save_post_' . static::$key, [ __CLASS__, 'save' ] );
		}
	}

	

	/**
	 * Set "Post" Name/Slug (& Code)
	 *
	 * Automatically sets the post's name/slug, which is used as the emailed access code.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	private static function set_name( int $post_id ) : void {

		$sent = get_post_meta( $post_id, '_is_invite_sent', true );

		$email = get_post_meta( $post_id, 'email', true );

		if ( ! $sent && ! empty( $email ) ) {
			// Unhook save routine to prevent infinite loops.
			remove_action( 'save_post_' . self::$key, [ __CLASS__, 'save' ] );

			$secret_url = Misc::get_secret_post_name( $post_id, $email );

			wp_update_post( [
				'ID'        => $post_id,
				'post_name' => $secret_url,
			] );

			// Safe to re-hook the save routine.
			add_action( 'save_post_' . self::$key, [ __CLASS__, 'save' ] );
		}
	}

	/**
	 * Send Code Email
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public static function send_code_email( int $post_id ) : void {

		$invited = get_post_meta( $post_id, '_is_invite_sent', true );
		$history = json_decode(get_post_meta( $post_id, 'order_history', true )) ?? [];
		var_dump($history);

		if ( ! $invited ) {

			$email = get_post_meta( $post_id, 'email', true );
			$name  = get_post_meta( $post_id, 'home_buyer', true );

			if ( ! ( $email && $name ) ) {
				return;
			}

			$self_order = get_post_meta( $post_id, '_is_self_order', true );

			$template_vars = array(
				'id'    => $post_id,
				'name'  => $name,
				'email' => $email,
				'url'   => get_permalink( $post_id ),
			);

			$mail = new Email();

			if ( $self_order ) {
				$mail->subject( 'Your Landscaping Preferences Request.' );
				$mail->recipients( sprintf( '%s <%s>', $name, $email ) );
				$mail->message( wpautop( Template::get_template( 'new_landscape_order_self', $template_vars, 'email' ) ) );
			} else {
				$mail->subject( 'We\'re Going to Landscape Your New Home. Help Us Make It Special.' );
				$mail->recipients( sprintf( '%s <%s>', $name, $email ) );
				$mail->message( wpautop( Template::get_template( 'new_landscape_order_sales', $template_vars, 'email' ) ) );
			}

			if ( $mail->send() ) {
				$time = current_datetime();
				$history[] = [
					"history_entry_title" => "Order Created",
					"history_entry_date" => $time
				];
				$history = json_encode($history);
				
				update_post_meta( $post_id, 'order_history',  $history);
				update_post_meta( $post_id, '_is_invite_sent', true );
				update_post_meta( $post_id, '_last_reminder', wp_date( DATE_RFC3339, $time ) );
			}

			return;
		}
	}
}
