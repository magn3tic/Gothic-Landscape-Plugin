<?php
/**
 * Template : Admin / Meta Box / Community Packages
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
 * @var PostMetaAbstract $callable
 * @var string $post_type
 * @var string $meta_box
 * @var WP_Post $community
 * @var string $packages_key
 * @var bool $is_backyard
 * @var array $packages
 */
?>
<?php if ( is_array( $packages ) && ! empty( $packages ) ) : ?>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<th scope="col" id="title" class="column-title column-primary">
				<span>Name</span>
			</th>
			<th scope="col" id="upgrade" class="manage-column column-title column-secondary">
				<span>Is Upgrade</span>
			</th>
			<th scope="col" id="upgrade" class="manage-column column-title column-secondary">
				<span>Status</span>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $packages as $package ) : ?>
			<tr>
				<td>
					<a href="<?php echo esc_url( get_admin_url() . 'post.php?post=' . $package->ID . '&amp;action=edit' ); ?>"><span><?php echo esc_html( $package->post_title ); ?></span></a>
				</td>
				<td>
					<?php echo get_post_meta( $package->ID, 'is_upgrade', true ) ? __( 'Upgrade Plan', 'gothic-selections' ) : __( 'Included Plan', 'gothic-selections' ); ?>
				</td>
				<td>
					<?php echo get_post_meta( $package->ID, 'inactive', true ) ? __( 'Inactive', 'gothic-selections' ) : __( 'Active', 'gothic-selections' ); ?>
				</td>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>
<?php endif; ?>

<?php if ( $is_backyard ) : ?>
	<p><a href="<?php echo esc_url( get_admin_url() . 'post-new.php?post_type=' . $packages_key . '&community_id=' . $community->ID . '&backyard'); ?>" class="button-primary"><?php esc_html_e( 'Add New', 'gothic-selections' ); ?></a></p>
<?php else : ?>
	<p><a href="<?php echo esc_url( get_admin_url() . 'post-new.php?post_type=' . $packages_key . '&community_id=' . $community->ID ); ?>" class="button-primary"><?php esc_html_e( 'Add New', 'gothic-selections' ); ?></a></p>
<?php endif; ?>

