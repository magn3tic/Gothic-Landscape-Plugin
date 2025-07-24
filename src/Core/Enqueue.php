<?php
/**
 * Enqueue Core Scripts/Styles
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
		add_action( 'wp_enqueue_scripts', function() {
			wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', [], '4.0.6-rc.1' );
			wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', [ 'jquery' ], '4.0.6-rc.1', true );
			wp_enqueue_script( 'jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.12.0/jquery.validate.js', [ 'select2' ], '1.12.0', true );
		});

		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'fancybox' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'core' ] );
	}

	/**
	 * Core
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
			'base_url' => rest_url( 'gothic/v1/' )
		];
		Style::add( Style::handle( 'user' ) );
		Script::add( Script::handle( 'user' ), [ 'fancybox', 'jquery-validate' ], true, $i18n );
	}

	/**
	 * Fancybox
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function fancybox() : void {
		Script::add( 'fancybox' );
	}
}
