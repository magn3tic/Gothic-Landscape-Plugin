<?php
/**
 * Enqueue Admin Scripts/Styles
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

namespace Gothic\Selections\Admin;

use Gothic\Selections\REST\Community as CommunityEndpoint;
use Gothic\Selections\PostType\Community as CommunityPostType;
use Gothic\Selections\Helper\{
	Style,
	Script
};

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Enqueue {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'selectize' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'core' ] );
	}

	/**
	 * Admin
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function core() : void {
		$i18n = [
			'builder_community_json' => get_rest_url( null, '/' . CommunityEndpoint::namespace() . '/' . CommunityPostType::$key ),
			'select_a_community'     => __( 'Select a home community', 'gothic-selections' ),
			'no_communities'         => __( 'No homebuilder communities.', 'gothic-selections' ),
			'no_communities_desc'    => __( 'There are no communities for this homebuilder.', 'gothic-selections' ),
		];
		Script::add( Script::handle( 'admin' ), [ 'selectize' ], true, $i18n );
		Style::add( Style::handle( 'admin' ) );
	}

	/**
	 * Selectize
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function selectize() : void {
		Script::add( 'selectize' );
		Style::add( 'selectize' );
	}
}
