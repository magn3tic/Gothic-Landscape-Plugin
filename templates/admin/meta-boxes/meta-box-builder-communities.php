<?php
/**
 * Template : Admin / Meta Box / Builder Communities
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

use Gothic\Selections\PostType\PreferencesOrder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Defined before includes:
 *
 * @var Gothic\Selections\PostMeta\PostMetaAbstract $callable
 * @var string $post_type
 * @var string $meta_box
 * @var WP_Post $builder
 * @var string $communities_key
 * @var array $communities
 */
?>
<?php if ( is_array( $communities ) && ! empty( $communities ) ) : ?>

	<table class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<th scope="col" id="title" class="manage-column column-title column-primary"><span>Title</span></th>
			<th scope="col" id="title" class="manage-column column-title"><span>Location</span></a></th>
			<th scope="col" id="title" class="manage-column column-title"><span>Orders</span></a></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $communities as $community ) : ?>
			<tr>
				<td>
					<a href="<?php echo esc_url( get_admin_url() . 'post.php?post=' . $community->ID . '&amp;action=edit' ); ?>">
						<?php echo esc_html( $community->post_title ); ?>
					</a>
				</td>
				<td><?php echo esc_html( get_post_meta( $community->ID, 'location', true ) ); ?></td>
				<td>
					<?php
					$orders = new WP_Query(
						[
							'post_type' => Gothic\Selections\PostType\PreferencesOrder::$key,
							'meta_query' => [
								[
									'key' => 'community_id',
									'compare' => 'IN',
									'value' => [ $community->ID ],
								]
							]
						]
					);
					echo $orders->post_count;
					?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

<?php endif; ?>

<p>
	<a href="<?php echo esc_url( get_admin_url() . 'post-new.php?post_type=' . $communities_key . '&builder_id=' . $builder->ID ); ?>"
		class="button-primary"><?php esc_html_e( 'New Community', 'gothic-selections' ); ?></a>
</p>
