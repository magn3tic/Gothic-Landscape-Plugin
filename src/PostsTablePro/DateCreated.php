<?php
/**
 * Post Table Pro Custom Data Type - OrderID
 *
 * Gothic's Landscape Selection Tool Administrator/Homebuilder Interface Uses the Posts Table Pro plugin.
 * This class extends a custom data type to make the tables generated by the plugin even more awesome.
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

use \DateTime;
use \Barn2\Plugin\Posts_Table_Pro\Data\Abstract_Table_Data;
use Gothic\Selections\Helper\Misc;

/**
 * Gets data for the 'media_type' column to use in the posts table.
 *
 * @license GPL-3.0
 */
final class DateCreated extends Abstract_Table_Data {

	/**
	 * Get Data
	 *
	 * Based on the tutorial on Barn 2 Media's Knowledge Base
	 * @see https://barn2.co.uk/kb/adding-custom-column-posts-table/
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function get_data() {

		return sprintf( '<time datetime="%s">%s</time>', get_the_date( 'c' ), get_the_date( 'm/d/y' )  );
	}
}