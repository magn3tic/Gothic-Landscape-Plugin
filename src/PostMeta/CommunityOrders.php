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

use \WP_Post;
use Gothic\Selections\Plugin;
use Gothic\Selections\Helper\Template;
use Gothic\Selections\PostType\{
	Community as PostType,
	PreferencesOrder as Order };

final class CommunityOrders extends PostMetaAbstract {

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

		return PostType::$key . '-orders';
	}


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @see PostMetaAbstract::__construct()
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( Plugin::FILTER_PREFIX . self::dashes_underscored( static::key() ) . '_hide_on_new', '__return_true' );
		parent::__construct();
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

		return __( 'Community Orders', 'gothic-selections' );
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
	 * Render Meta Box
	 *
	 * The array of fields for the meta box.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public static function render( WP_Post $post ) : void {

		$orders = get_posts(
			[
				'orderby'    => 'post_name',
				'order'      => 'ASC',
				'post_type'  => Order::$key,
				'meta_query' => [
					[
						'key'     => 'community_id',
						'value'   => [ $post->ID ],
						'compare' => 'IN',
					],
				],
			]
		);

		if ( $orders && ! is_wp_error( $orders ) ) {

			$template_vars = [
				'callable'   => __CLASS__,
				'meta_box'   => static::key(),
				'post_type'  => static::types(),
				'community'  => $post,
				'orders_key' => Order::$key,
				'orders'     => $orders,
			];

			Template::get_template( 'meta-box-community-orders', $template_vars, 'meta-boxes', true, true );
		}
	}
}
