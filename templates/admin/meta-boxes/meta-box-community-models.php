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
 * @var \Gothic\Selections\PostMeta\PostMetaAbstract $callable
 * @var string $post_type
 * @var string $meta_box
 * @var WP_Post $community
 * @var string $models_key
 * @var array $models
 */
?>
<?php if ( is_array( $models ) && ! empty( $models ) ) : ?>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<th scope="col" id="title" class="column-title column-primary"><span>Model Name</span></th>
			<th scope="col" id="model" class="column-title column-secondary"><span>Plan ID</span></th>
			<th scope="col" id="model" class="column-title column-secondary"><span>Status</span></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $models as $model ) : ?>
			<tr>
				<td><a href="<?php echo esc_url( get_admin_url( ) . 'post.php?post=' . $model->ID . '&amp;action=edit' ); ?>"><span><?php echo esc_html( $model->post_title ); ?></span></a></td>
				<td><a href="<?php echo esc_url( get_admin_url( ) . 'post.php?post=' . $model->ID . '&amp;action=edit' ); ?>"><span><?php echo esc_html( get_post_meta( $model->ID, 'plan_id', true ) ); ?></span></a></td>
				<td><span><?php echo get_post_meta( $model->ID, 'inactive', true ) ? __( 'Inactive', 'gothic-selections' ) : __( 'Active', 'gothic-selections' ); ?></span></td>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>
<?php endif; ?>

<p><a href="<?php echo esc_url( get_admin_url() . 'post-new.php?post_type=' . $models_key . '&community_id=' . $community->ID ); ?>" class="button-primary"><?php esc_html_e( 'Add New', 'gothic-selections' ); ?></a></p>
