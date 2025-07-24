<?php
/**
 * Template : Admin / Meta Box / Community Orders
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Templates / Admin
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
 * @var \Gothic\Selections\PostMeta\PostMetaAbstract $callable
 * @var string $post_type
 * @var string $meta_box
 * @var WP_Post $community
 * @var string $orders_key
 * @var array $orders
 */
?>
<?php if ( is_array( $orders ) && ! empty( $orders ) ) : ?>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
			<th scope="col" id="model" class="manage-column column-title column-primary sortable desc">
				<a href="<?php echo esc_url( get_admin_url() . 'post.php?post=' . esc_attr( $community->ID ) . '&action=edit&orderby=lot&order=asc' ); ?>">
					<span>Lot</span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope="col" id="buyer" class="manage-column column-title column-primary sortable desc">
				<a href="<?php echo esc_url( get_admin_url() . 'post.php?post=' . esc_attr( $community->ID ) . '&action=edit&orderby=buyer&order=asc' ); ?>">
					<span>Homebuyer</span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope="col" id="model" class="manage-column column-title column-primary sortable desc">
				<a href="<?php echo esc_url( get_admin_url() . 'post.php?post=' . esc_attr( $community->ID ) . '&action=edit&orderby=status&order=asc' ); ?>">
					<span>Status</span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $orders as $order ) : ?>
			<tr>
				<td></td>
				<td><span><?php echo esc_html( get_post_meta( $order->ID, 'lot', true ) ); ?></span></td>
				<td><span><?php echo esc_html( get_post_meta( $order->ID, 'buyer', true ) ); ?></span></td>
				<td>
					<a href="<?php echo esc_url( get_admin_url() . 'post.php?post=' . $order->ID . '&action=edit' ); ?>">
						<span><?php echo get_post_meta( $order->ID, 'buyer', true ) ? esc_html__( 'Complete', 'gothic-selections' ) : esc_html__( 'Incomplete', 'gothic-selections' ); ?></span>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>
<?php endif; ?>

<p><a href="<?php echo esc_url( get_admin_url() . 'post-new.php?post_type=' . $orders_key . '&community_id=' . $community->ID ); ?>" class="button-primary">Start New Order</a></p>
