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
use Gothic\Selections\PostType\{Community, Builder as PostType};

final class BuilderCommunities extends PostMetaAbstract {

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

		return PostType::$key . '-communities';
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

		return __( 'Homebuilder Communities', 'gothic-selections' );
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

		$communities = get_posts(
			[
				'orderby'    => 'post_name',
				'order'      => 'ASC',
				'post_type'  => 'communities',
				'meta_query' => [
					[
						'key'     => 'builder_id',
						'value'   => [ $post->ID ],
						'compare' => 'IN',
					],
				],
			]
		) ?: [];

		$template_vars = [
			'callable'        => __CLASS__,
			'meta_box'        => static::key(),
			'post_type'       => static::types(),
			'builder'         => $post,
			'communities_key' => Community::$key,
			'communities'     => $communities,
		];

		Template::get_template( 'meta-box-builder-communities', $template_vars, 'meta-boxes', true, true );
	}
}
