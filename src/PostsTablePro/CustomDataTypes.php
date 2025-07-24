<?php
/**
 * Post Table Pro Custom Data Types
 *
 * Gothic's Landscape Selection Tool Administrator/Homebuilder Interface Uses the Posts Table Pro plugin.
 * This class initializes several custom data types we use in the P2P tables.
 *
 * Based on the tutorial on Barn 2 Media's Knowledge Base
 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
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

namespace Gothic\Selections\PostsTablePro;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CustomDataTypes
 *
 * @package Gothic\Selections\PostsTablePro
 *
 * @since 1.0.0
 *
 * @final
 */
final class CustomDataTypes {

	/**
	 * CustomDataTypes Constructor
	 *
	 * Adds filters to summon and initialize custom data types when called by PTP
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$custom_data_types = [ 'community', 'model', 'builder', 'home_buyer', 'order_number', 'order_status', 'date_created', 'date_last_accessed',"modifier", "resolver" ];

		foreach ( $custom_data_types as $type ) {
			add_filter( "posts_table_custom_table_data_$type", [ __CLASS__, "initialize_data_type_$type" ], 10, 3 );
		}
	}

	/**
	 * Initialize Custom Post Table Pro Data Type for Community
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return OrderNumber
	 */
	public static function initialize_data_type_order_number( $data_obj, $post, $args ) : ?OrderNumber {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new OrderNumber( $post );
	}

	/**
	 * Initialize Custom Post Table Pro Data Type for Community
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return OrderCommunity
	 */
	public static function initialize_data_type_community( $data_obj, $post, $args ) : ?OrderCommunity {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new OrderCommunity( $post );
	}

	/**
	 * Initialize Custom Post Table Pro Data Type for Community
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return OrderModel
	 */
	public static function initialize_data_type_model( $data_obj, $post, $args ) : ?OrderModel {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new OrderModel( $post );
	}

	/**
	 * Initialize Custom Post Table Pro Data Type for Community
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return OrderBuilder
	 */
	public static function initialize_data_type_builder( $data_obj, $post, $args ) : ?OrderBuilder {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new OrderBuilder( $post );
	}

	/**
	 * Initialize Custom Post Table Pro Data Type for Home Buyer
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return HomeBuyer
	 */
	public static function initialize_data_type_home_buyer( $data_obj, $post, $args ) : ?HomeBuyer {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new HomeBuyer( $post );
	}


	/**
	 * Initialize Custom Post Table Pro Data Type for Home Buyer
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return OrderStatus
	 */
	public static function initialize_data_type_order_status( $data_obj, $post, $args ) : ?OrderStatus {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new OrderStatus( $post );
	}

	/**
	 * Initialize Custom Post Table Pro Data Type for Date Last Accessed
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return DateLastAccessed
	 */
	public static function initialize_data_type_date_last_accessed( $data_obj, $post, $args ) : ?DateLastAccessed {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new DateLastAccessed( $post );
	}

	/**
	 * Initialize Custom Post Table Pro Data Type for Date Created
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return DateCreated
	 */
	public static function initialize_data_type_date_created( $data_obj, $post, $args ) : ?DateCreated {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new DateCreated( $post );
	}

	/**
	 * Initialize Custom Post Table Pro Data Type for Modifier
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return Modifier
	 */
	public static function initialize_data_type_modifier( $data_obj, $post, $args ) : ?Modifier {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new Modifier( $post );
	}

		/**
	 * Initialize Custom Post Table Pro Data Type for resolver
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param $data_obj
	 * @param $post
	 * @param $args
	 *
	 * @return Resolver
	 */
	public static function initialize_data_type_resolver( $data_obj, $post, $args ) : ?Resolver {
		if ( ! class_exists( '\\Abstract_Posts_Table_Data' ) ) {
			return null;
		}
		return new Resolver( $post );
	}
}