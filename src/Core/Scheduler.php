<?php
/**
 * Scheduled Events
 *
 * This sets up the scheduled events using the WP Cron.
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
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace Gothic\Selections\Core;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\Plugin;
use Gothic\Selections\PostType\PreferencesOrder;
use Gothic\Selections\Processor\Notification;
use WP_Query;
use DateInterval;

/**
 * Scheduler
 *
 * @since 1.0.0
 *
 * final
 */
final class Scheduler {

	/**
	 * Register the Crons
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( false === wp_next_scheduled( Plugin::FILTER_PREFIX . 'send_buyer_reminder' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), '3_days', Plugin::FILTER_PREFIX . 'send_buyer_reminder', [] );
		}
		add_action( Plugin::FILTER_PREFIX . 'send_buyer_reminder', [ __CLASS__, 'send_buyer_reminders' ] );

		if ( false === wp_next_scheduled( Plugin::FILTER_PREFIX . 'send_seller_reminder' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'weekly', Plugin::FILTER_PREFIX . 'send_seller_reminder' );
		}
		add_action( Plugin::FILTER_PREFIX . 'send_seller_reminder', [ __CLASS__, 'send_seller_reminders' ] );
	}

	/**
	 *
	 */
	public static function send_buyer_reminders() {

		$now = current_datetime();

		$ago = $now->sub( new DateInterval( 'P3D' ) );
		// $ago = $now->sub( new DateInterval( 'PT1H' ) );

		$orders = new WP_Query( [
			'post_type'      => PreferencesOrder::$key,
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => '_status',
					'compare' => 'IN',
					'value'   => [ 'in_progress' ],
				],
				[
					'relation' => 'OR',
					[
						'key'     => '_last_reminder',
						'value'   => $ago->format( 'Y-m-d h:i:s' ),
						'compare' => '<=',
						'type'    => 'DATE',
					],
					[
						'key'     => '_last_reminder',
						'compare' => 'NOT EXISTS',
					],
				],
			],
			'posts_per_page' => 1000,
		] );

		if ( ! is_wp_error( $orders ) && $orders->have_posts() ) {
			foreach ( $orders->get_posts() as $order ) {
				Notification::send_buyer_reminder( $order->ID );
			}
		}
	}

	public static function send_seller_reminders() {

		$now = current_datetime();

		$ago = $now->sub( new DateInterval( 'P17D' ) );
		// $ago = $now->sub( new DateInterval( 'PT1H' ) );

		$orders = new WP_Query( [
			'post_type'      => PreferencesOrder::$key,
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => '_status',
					'compare' => 'IN',
					'value'   => [ 'in_progress' ],
				],
				[
					'relation' => 'OR',
					[
						'key'     => '_last_seller_reminder',
						'value'   => $ago->format( 'Y-m-d h:i:s' ),
						'compare' => '<=',
						'type'    => 'DATE',
					],
					[
						'key'     => '_last_seller_reminder',
						'compare' => 'NOT EXISTS',
					],
				],
			],
			'posts_per_page' => 1000,
		] );

		if ( ! is_wp_error( $orders ) && $orders->have_posts() ) {
			foreach ( $orders->get_posts() as $order ) {
				Notification::send_seller_reminder( $order->ID );
			}
		}
	}

}
