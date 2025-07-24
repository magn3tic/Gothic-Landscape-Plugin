<?php
/**
 * Template : Admin / Meta Box
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

use \Gothic\Selections\Helper\Template;
use \Gothic\Selections\PostMeta\PostMetaAbstract;
use \Gothic\Selections\PostMeta\Helper;

/**
 * Defined before includes:
 *
 * @var string $nonce
 * @var string $post_type
 * @var string $meta_box
 * @var WP_Post $post
 * @var array $fields
 */

?>

<?php foreach ( $fields as $field => $args ) : ?>

	<?php

	list( $args, $template ) = Helper::meta_field( $args, $field );

	Template::get_template_part( 'field', $template, $args, 'form-fields', true, true );

	?>

<?php endforeach ?>

