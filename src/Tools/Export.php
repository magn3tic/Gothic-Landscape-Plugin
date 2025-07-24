<?php
/**
 * Orders CSV Export Tool
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Tools
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2019 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Tools;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\PostType\PreferencesOrder;
use \WP_Query;
use \DateTime;

final class Export {

	/**
	 * Get Template
	 *
	 * Produces a CSV file template for external manipulation.
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	public static function export( $args = [] ) : void {

		$csv_file_name = 'landscape-selections-orders.csv';

		header( 'Content-type: text/csv' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Content-Disposition: attachment; filename=' . $csv_file_name );
		header( 'Content-type: application/x-msdownload' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		$header = [
			'buyer_name',
			'email',
			'phone',
			'address',
			'city',
			'state',
			'zip',
			'lot',
			'builder',
			'community',
			'model',
			'front_package',
			'front_palette',
			'back_package',
			'back_palette',
			'contact_for_BY_quote',
			'special_requests',
			'salesperson_name',
			'salesperson_email',
			'date_last_status_updated',
			'status',
			'confirm_name',
			'confirm_time',
			'transferred_to',
		];

		$data = [ $header ];

		$export_query_args = [
			'numberofposts' => -1,
			'posts_per_page' => -1,
			'post_type'      => PreferencesOrder::$key,
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => '_status',
					'compare' => 'IN',
					'value'   => [ 'complete', 'transferred', 'cancelled' ],
				],
			],
		];

		if ( ! empty( $args['builder_id'] ) && empty( $args['community_id'] ) ) {
			$export_query_args['meta_query'][] = [
				'key'     => 'builder_id',
				'value'   => $args['builder_id'],
				'compare' => '=',
				'type'    => 'NUMBER',
			];
		}

		if ( ! empty( $args['community_id'] ) ) {
			$export_query_args['meta_query'][] = [
				'key'     => 'community_id',
				'value'   => $args['community_id'],
				'compare' => '=',
				'type'    => 'NUMBER',
			];
		}

		if ( ! empty( $args['date'] ) ) {

			$date = DateTime::createFromFormat( 'Y-m-d', $args['date'] );

			$date->setTime(0,0,0);

			$export_query_args['meta_query'][] = [
				'key'     => '_date_last_status_updated',
				'value'   => $date->format( 'Y-m-d h:i:s' ),
				'compare' => '>=',
				'type'    => 'DATE',
			];

			if ( ! empty( $args['date-to'] ) ) {
				$date = DateTime::createFromFormat( 'Y-m-d', $args['date-to'] );
				$date->setTime(0,0,0);
				$export_query_args['meta_query'][] = [
					'key'     => '_date_last_status_updated',
					'value'   => $date->format( 'Y-m-d h:i:s' ),
					'compare' => '<=',
					'type'    => 'DATE',
				];

				$export_query_args['meta_query']['relation'] = 'AND';
			}
		}

		$export_query = new WP_Query( $export_query_args );


		if ( ! is_wp_error( $export_query ) && $export_query->have_posts() ) {

			foreach ( $export_query->get_posts() as $exportable ) {

				$meta = get_post_meta( $exportable->ID );

				$line   = [
					self::meta_export( $meta, 'home_buyer' ),
					self::meta_export( $meta, 'email' ),
					self::meta_export( $meta, 'phone' ),
					self::meta_export( $meta, 'address' ),
					self::meta_export( $meta, 'city' ),
					self::meta_export( $meta, 'state' ),
					self::meta_export( $meta, 'zip' ),
					self::meta_export( $meta, 'lot' ),
					self::get_post_name( self::meta_export( $meta, 'builder_id' ) ),
					self::get_post_name( self::meta_export( $meta, 'community_id' ) ),
					self::get_post_name( self::meta_export( $meta, 'model_id' ) ),
					self::get_post_name( self::meta_export( $meta, 'package_id' ) ),
					self::get_post_name( self::meta_export( $meta, 'palette_id' ) ),
					self::get_post_name( self::meta_export( $meta, 'backyard_id' ) ),
					self::get_post_name( self::meta_export( $meta, 'by_palette_id' ) ),
					self::get_bool( self::meta_export( $meta, 'opt_in_backyard_upsell' ) ),
					self::meta_export( $meta, 'comments' ),
					self::meta_export( $meta, 'builder_rep_name' ),
					self::meta_export( $meta, 'builder_rep_email' ),
					self::meta_export( $meta, '_date_last_status_updated' ),
					self::meta_export( $meta, '_status' ),
					self::meta_export( $meta, 'confirm_name' ),
					self::meta_export( $meta, 'confirm_time' ),
					self::meta_export( $meta, '_transferred_to' ),
				];
				$data[] = $line;
			}
		}


		$buffer = fopen( 'php://output', 'w' );

		foreach ( $data as $row ) {
			fputcsv( $buffer, $row, ',', '"', '\\' );
		}

		$result = fgets( $buffer );

		fclose( $buffer );

		echo $result;

		die();
	}

	private static function get_post_name( $id ) {
		$post = get_post( $id );
		if ( $post ) {
			return $post->post_title;
		} else {
			return '';
		}
	}

	private static function get_bool( $value ) {
		if ( $value ) {
			return 'Yes';
		}
		return 'No';
	}

	private static function meta_export( $meta, $key ) {
		if ( ! empty( $meta[ $key ] ) ) {
			if ( is_serialized( $meta[ $key ][0] ) ) {
				return implode( ', ', unserialize( $meta[ $key ][0] ) );
			}
			return $meta[ $key ][0];
		}
		return '';
	}
}
