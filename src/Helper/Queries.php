<?php
/**
 * Queries Helper
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  REST
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\Plugin;
use Gothic\Selections\PostType\{
	Builder,
	Model,
	Community,
	Package,
	Palette };

final class Queries {


	/**
	 * Get Builders
	 *
	 * Get or pull cache for builders
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param bool $nocache Bust or skip cache.
	 *
	 * @return array|mixed
	 */
	public static function builders( array $args = [], bool $nocache = false ) : array {

		if ( ! isset( $args['format'] ) ) {
			$args['format'] = 'default';
		}

		$cache_key = Plugin::FILTER_PREFIX . Builder::$key . '_' . $args['format'];

		$cache = get_transient( $cache_key );

		if ( $nocache || ! $cache ) {

			$query = [
				'orderby'        => 'post_name',
				'order'          => 'ASC',
				'post_type'      => Builder::$key,
				'posts_per_page' => 1000,
			];

			$builders = get_posts( $query );

			if ( is_wp_error( $builders ) || ! $builders ) {
				return [];
			}

			switch ( $args['format'] ) {

				case 'select2':
					$results = [
						[
							'id'   => - 1,
							'text' => 'Select Your Builder',
						],
					];

					foreach ( $builders as $builder ) {
						$results[] = [
							'id'   => $builder->ID,
							'text' => $builder->post_title,
						];
					}

					$cache = [
						'results'    => $results,
						'pagination' => [
							'more' => false,
						],
					];
					break;

				case 'default':
				default:
					$cache = $builders;
					break;
			}

			set_transient( $cache_key, $cache, 24 * HOUR_IN_SECONDS );
		}

		return $cache;
	}

	/**
	 * Get Community
	 *
	 * Get or pull cache for a community.
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param bool $nocache Bust or skip cache.
	 *
	 * @return array|mixed
	 */
	public static function community( array $args = [], bool $nocache = false ) : array {

		if ( ! isset( $args['id'] ) || ! is_numeric( $args['id'] ) || get_post_type( $args['id'] ) !== Community::$key ) {
			return [];
		}

		if ( ! isset( $args['format'] ) ) {
			$args['format'] = 'default';
		}

		$cache_key = Plugin::FILTER_PREFIX . Community::$key . '_' . $args['id'] . '_' . $args['format'];

		$cache = get_transient( $cache_key );

		if ( $nocache || ! $cache ) {

			$community = get_post( $args['id'], ARRAY_A );

			if ( is_wp_error( $community ) || ! $community ) {
				return [];
			}

			switch ( $args['format'] ) {

				case 'image':
					$cache = [
						[
							'id'    => $community['ID'],
							'title' => $community['post_title'],
							'image' => get_the_post_thumbnail_url( $community['ID'], 'preferences-tiles' ) ?: Misc::get_placeholder_image(),
						],
					];
					break;

				case 'default':
				default:
					$cache = $community;
					break;
			}

			set_transient( $cache_key, $cache, 24 * HOUR_IN_SECONDS );
		}

		return $cache;
	}

	/**
	 * Get Communities
	 *
	 * Get or pull cache for models
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param bool $nocache Bust or skip cache.
	 *
	 * @return array|mixed
	 */
	public static function communities( array $args = [], bool $nocache = false ) : array {

		if ( ! isset( $args['format'] ) ) {
			$args['format'] = 'default';
		}

		$cache_key = Plugin::FILTER_PREFIX . Community::$key . '_' . $args['format'];

		if ( isset( $args['builder_id'] ) && is_numeric( $args['builder_id'] ) ) {
			if ( get_post_type( $args['builder_id'] ) === Builder::$key ) {
				$cache_key = Plugin::FILTER_PREFIX . Builder::$key . '_' . $args['builder_id'] . '_' . Community::$key . '_' . $args['format'];
			} else {
				return [];
			}
		} else {
			unset( $args['builder_id'] );
		}

		$cache = get_transient( $cache_key );

		if ( $nocache || ! $cache ) {

			$communities_query = [
				'orderby'        => 'post_name',
				'order'          => 'ASC',
				'post_type'      => Community::$key,
				'posts_per_page' => 1000,
			];

			if ( isset( $args['builder_id'] ) ) {
				$communities_query['meta_query'] = [
					[
						'key'     => 'builder_id',
						'value'   => [ (int) $args['builder_id'] ],
						'compare' => 'IN',
					],
				];
			}

			$communities = get_posts( $communities_query );

			if ( is_wp_error( $communities ) || ! $communities ) {
				return [];
			}

			switch ( $args['format'] ) {

				case 'select2':
					$results = [
						[
							'id'   => - 1,
							'text' => 'Select Your Community',
						],
					];

					foreach ( $communities as $community ) {
						$results[] = [
							'id'   => $community->ID,
							'text' => $community->post_title,
						];
					}

					$cache = [
						'results'    => $results,
						'pagination' => [
							'more' => false,
						],
					];
					break;

				case 'default':
				default:
					$cache = $communities;
					break;
			}

			set_transient( $cache_key, $cache, 24 * HOUR_IN_SECONDS );
		}

		return $cache;
	}

	/**
	 * Get Model
	 *
	 * Get or pull cache for models
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param bool $nocache Bust or skip cache.
	 *
	 * @return array|mixed
	 */
	public static function model( array $args = [], bool $nocache = false ) : array {

		if ( ! isset( $args['id'] ) || ! is_numeric( $args['id'] ) || get_post_type( $args['id'] ) !== Model::$key ) {
			return [];
		}

		if ( ! isset( $args['format'] ) ) {
			$args['format'] = 'default';
		}

		$cache_key = Plugin::FILTER_PREFIX . Model::$key . '_' . $args['id'] . '_' . $args['format'];

		$cache = get_transient( $cache_key );

		if ( $nocache || ! $cache ) {

			$model = get_post( $args['id'], ARRAY_A );

			if ( is_wp_error( $model ) || ! $model ) {
				return [];
			}

			switch ( $args['format'] ) {

				case 'image':
					$cache = [
						[
							'id'    => $model['ID'],
							'title' => $model['post_title'],
							'image' => get_the_post_thumbnail_url( $model['ID'], 'preferences-tiles' ) ?: Misc::get_placeholder_image(),
						],
					];
					break;

				case 'default':
				default:
					$cache = $model;
					break;
			}

			set_transient( $cache_key, $cache, 24 * HOUR_IN_SECONDS );
		}

		return $cache;
	}

	/**
	 * Get Models
	 *
	 * Get or pull cache for models
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param bool $nocache Bust or skip cache.
	 *
	 * @return array|mixed
	 */
	public static function models( array $args = [], bool $nocache = false ) : array {

		if ( isset( $args['inactive'] ) ) {
			$nocache = true;
		}

		if ( ! isset( $args['format'] ) ) {
			$args['format'] = 'default';
		}

		$cache_key = Plugin::FILTER_PREFIX . Model::$key . '_' . $args['format'];

		if ( isset( $args['community_id'] ) && is_numeric( $args['community_id'] ) ) {
			if ( get_post_type( $args['community_id'] ) === Community::$key ) {
				$cache_key = Plugin::FILTER_PREFIX . Community::$key . '_' . $args['community_id'] . '_' . Model::$key . '_' . $args['format'];
			} else {
				return [];
			}
		} else {
			unset( $args['community_id'] );
		}

		$cache = false;// get_transient( $cache_key );

		if ( $nocache || ! $cache ) {

			$models_query = [
				'orderby'        => 'post_name',
				'order'          => 'ASC',
				'post_type'      => Model::$key,
				'posts_per_page' => 1000,
				'meta_query'     => [
					'relation' => 'AND',
				],
			];

			if ( isset( $args['community_id'] ) ) {
				$models_query['meta_query']['community_id'] = [
					'key'     => 'community_id',
					'value'   => [ (int) $args['community_id'] ],
					'compare' => 'IN',
				];
			} else {
				$models_query['meta_query']['community_id'] = [
					'key'     => 'community_id',
					'value'   => (int) get_post_meta( get_the_ID(), 'community_id', true ),
					'compare' => '=',
				];
			}

			if ( ! ( isset( $args['inactive'] ) && $args['inactive'] ) ) {
				$models_query['meta_query']['inactive'] = [
					'key' => 'inactive',
					'value' => [ 0 ],
					'compare' => 'IN'
				];
				$models_query['orderby'] = [
					'title' => 'ASC',
				];
			} else {
				$models_query['meta_query']['inactive'] = [
					'key' => 'inactive',
					'value' => [ 0, 1 ],
					'compare' => 'IN'
				];
				$models_query['orderby'] = [
					'inactive' => 'ASC',
					'title'    => 'ASC',
				];
			}

			$models = get_posts( $models_query );

			if ( is_wp_error( $models ) || ! $models ) {
				return [];
			}

			switch ( $args['format'] ) {

				case 'select2':
					$results = [
						[
							'id'   => - 1,
							'text' => 'Select Your Model',
						],
					];

					foreach ( $models as $model ) {
						$results[] = [
							'id'   => $model->ID,
							'text' => $model->post_title,
							'data-image' => get_the_post_thumbnail_url( $model->ID, 'preferences-tiles' ) ?: Misc::get_placeholder_image(),
						];
					}

					$cache = [
						'results'    => $results,
						'pagination' => [
							'more' => false,
						],
					];
					break;

				case 'default':
				default:
					$cache = $models;
					break;
			}

			set_transient( $cache_key, $cache, 24 * HOUR_IN_SECONDS );
		}

		return $cache;
	}

	/**
	 * Get Packages
	 *
	 * Get or pull cache for packages, including for a given community
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param bool $nocache Bust or skip cache.
	 *
	 * @return array|mixed
	 */
	public static function packages( array $args = [], bool $nocache = false ) : array {

		if ( ! isset( $args['format'] ) ) {
			$args['format'] = 'default';
		}

		if ( isset( $args['inactive'] ) ) {
			$nocache = true;
		}

		// so far: 'packages'
		$cache_key = Package::$key;

		if ( isset( $args['backyard'] ) && ! empty( $args['backyard'] ) ) {
			$args['backyard'] = 1;
			// if, so far: 'packages_backyards'
			$cache_key .= '_backyards';
		} else {
			$args['backyard'] = 0;
		}

		if ( isset( $args['community_id'] ) && is_numeric( $args['community_id'] ) ) {
			if ( 1 === $args['backyard'] ) {
				$active = ( 1 === (int) get_post_meta( $args['community_id'], 'backyard', true ) );
			} else {
				$active = ( 1 === (int) get_post_meta( $args['community_id'], 'frontyard', true ) );
			}
			if ( ! $active && ! $nocache ) {
				return [];
			}
			if ( get_post_type( $args['community_id'] ) === Community::$key ) {
				// if, so far: 'communities_##_packages_backyards' or 'communities_##_packages'
				$cache_key = Community::$key . '_' . $args['community_id'] . $cache_key;
			} else {
				return [];
			}
		} else {
			unset( $args['community_id'] );
		}

		// eg: 'gothic_selections_communities_##_packages_backyards_default' or 'gothic_selections_packages_select2'
		$cache_key .= Plugin::FILTER_PREFIX . $cache_key . '_' . $args['format'];

		$cache = get_transient( $cache_key );

		if ( $nocache || ! $cache ) {

			$packages_query = [
				'posts_per_page' => 100,
				'post_type'  => Package::$key,
				'meta_query' => array(
					'relation' => 'AND',
					'is_backyard' => [
						'key'     => 'is_backyard',
						'compare' => '=',
						'value'   => $args['backyard'],
					],
					'is_upgrade' => [
						'key'     => 'is_upgrade',
						'compare' => 'IN',
						'value' => [ 1, 0 ],
					],
				),
			];

			if ( isset( $args['community_id'] ) ) {
				$packages_query['meta_query']['community_id'] = [
					'key'     => 'community_id',
					'value'   => [ (int) $args['community_id'] ],
					'compare' => 'IN',
				];
			} else {
				$packages_query['meta_query']['community_id'] = [
					'key'     => 'community_id',
					'value'   => (int) get_post_meta( get_the_ID(), 'community_id', true ),
					'compare' => '=',
				];
			}

			if ( ! ( isset( $args['inactive'] ) && $args['inactive'] ) ) {
				$packages_query['meta_query']['inactive'] = [
					'key' => 'inactive',
					'compare' => 'IN',
					'value' => [ 0 ],
				];
				$packages_query['orderby'] = [
					'is_upgrade' => 'ASC',
					'title' => 'ASC',
				];
			} else {
				$packages_query['meta_query']['inactive'] = [
					'key' => 'inactive',
					'compare' => 'IN',
					'value' => [ 1, 0 ],
				];
				$packages_query['orderby'] = [
					'inactive'   => 'ASC',
					'is_upgrade' => 'ASC',
					'title'      => 'ASC',
				];
			}

			$packages = get_posts( $packages_query );

			if ( is_wp_error( $packages ) || ! $packages ) {
				return [];
			}

			switch ( $args['format'] ) {

				case 'select2':
					$results = [
						[
							'id'   => - 1,
							'text' => __( 'Select Your Package', 'gothic-selections' ),
						],
					];

					foreach ( $packages as $model ) {
						$results[] = [
							'id'   => $model->ID,
							'text' => $model->post_title,
						];
					}

					$cache = [
						'results'    => $results,
						'pagination' => [
							'more' => false,
						],
					];
					break;

				case 'default':
				default:
					$cache = $packages;
					break;
			}

			set_transient( $cache_key, $cache, 24 * HOUR_IN_SECONDS );
		}

		return $cache;
	}

	/**
	 * Get Packages Meta
	 *
	 * Determine if, for a given community, 1. package types are enabled, and 2. if packages exist for type. Return
	 * array.
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @param int $community_id
	 *
	 * @return array
	 */
	public static function packages_meta( int $community_id ) : array {

		if ( empty( $community_id ) ) {

			return [];
		}

		$result = [
			'front' => false,
			'back' => false,
		];

		$is_front = (bool) get_post_meta( $community_id, 'frontyard', true );

		$has_front = self::packages( [
			'community_id' => $community_id,
			'backyard'     => false,
			'inactive'     => true
		] ) ? true : false;

		if ( $is_front && $has_front ) {
			$result['front'] = true;
		}

		$is_back = (bool) get_post_meta( $community_id, 'backyard', true );

		$has_back = self::packages( [
			'community_id' => $community_id,
			'backyard'     => true,
			'inactive'     => true
		] ) ? true : false;

		if ( $is_back && $has_back ) {
			$result['back'] = true;
		}

		return $result;
	}

	/**
	 * Get Palettes
	 *
	 * Get or pull cache for palettes, including for a given community
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param bool $nocache Bust or skip cache.
	 *
	 * @return array|mixed
	 */
	public static function palettes( array $args = [], bool $nocache = false ) : array {

		if ( ! isset( $args['format'] ) ) {
			$args['format'] = 'default';
		}

	

		$cache_key = Plugin::FILTER_PREFIX . Palette::$key . '_' . $args['format'];

		if ( isset( $args['community_id'] ) && is_numeric( $args['community_id'] ) ) {
			if ( get_post_type( $args['community_id'] ) === Community::$key ) {
				$cache_key = Plugin::FILTER_PREFIX . Community::$key . '_' . $args['community_id'] . '_' . Palette::$key . '_' . $args['format'];
			} else {
				return [];
			}
		} else {
			unset( $args['community_id'] );
		}

		$cache = get_transient( $cache_key );
	

		if ( $nocache || ! $cache ) {

			$query = [
				'post_type'  => Palette::$key,
				'order' => 'ASC',
			];

			if(isset($args["inactive"])){
				if($args["inactive"] === false) {
					$query['meta_query'][] = [
						'key' => 'inactive',
						'compare' => 'NOT EXISTS'
					];
				}
			}
			
			if ( isset( $args['community_id'] ) ) {
				$query['meta_query'][] = [
					[
						'key'     => 'community_id',
						'value'   => [ (int) $args['community_id'] ],
						'compare' => 'IN',
					],
				];
			}

			if(isset($args["community_id"]) && isset($args["inactive"])) {
				$query['meta_query']['relation'] ='AND';
			}


			$palettes = get_posts( $query );

			if ( is_wp_error( $palettes ) || ! $palettes ) {
				return [];
			}

			switch ( $args['format'] ) {

				case 'select2':
					$results = [
						[
							'id'   => - 1,
							'text' => __( 'Select Your Palette', 'gothic-selections' ),
						],
					];

					foreach ( $palettes as $model ) {
						$results[] = [
							'id'   => $model->ID,
							'text' => $model->post_title,
						];
					}

					$cache = [
						'results'    => $results,
						'pagination' => [
							'more' => false,
						],
					];
					break;

				case 'default':
				default:
					$cache = $palettes;
					break;
			}

			set_transient( $cache_key, $cache, 24 * HOUR_IN_SECONDS );
		}

		return $cache;
	}
}
