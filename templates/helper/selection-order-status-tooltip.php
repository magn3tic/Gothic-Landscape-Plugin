<?php
/**
 * Template Part : Selection Order Status Tooltip
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Templates
 * @subpackage  Helper
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Defined before includes:
 *
 * @var string $label
 * @var string $description
 * @var string $status
 */
?>

<span class="tooltip status-<?php echo esc_attr( $status ); ?>" data-tooltip="<?php echo esc_html( $description ); ?>"><?php echo esc_html( $label ); ?>