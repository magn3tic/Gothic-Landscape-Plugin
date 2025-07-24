<?php
/**
 * Template : Admin / Meta Box / Community Models
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
 * @var string $post_type
 * @var string $meta_box
 * @var WP_Post $community
 * @var string $palette_key
 * @var array $palettes
 */
?>
<?php if ( is_array( $palettes ) && ! empty( $palettes ) ) : ?>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<th scope="col" id="title" class="manage-column column-title column-primary">
				<span>Title</span>
			</th>
			<th scope="col" id="upgrade" class="manage-column column-title column-secondary">
				<span>Status</span>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $palettes as $palette ) : ?>
			<tr>
				<td>
					<a href="<?php echo esc_url( get_admin_url() . 'post.php?post=' . $palette->ID . '&amp;action=edit' ); ?>"><?php echo esc_html( $palette->post_title ); ?></a>
				</td>
				<td>
					<?php echo get_post_meta( $palette->ID, 'inactive', true ) ? __( 'Inactive', 'gothic-selections' ) : __( 'Active', 'gothic-selections' ); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<p><a href="<?php echo esc_url( get_admin_url() . 'post-new.php?post_type=' . $palette_key . '&community_id=' . $community->ID ); ?>" class="button-primary"><?php esc_html_e( 'Add New', 'gothic-selections' ); ?></a></p>
