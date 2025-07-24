<?php

/**
 * Defined Before Includes:
 *
 * @var $seller_name
 * @var $buyer_name
 * @var $lot
 * @var $code
 * @var $message
 */
?>

<p>Hi!</p>

<?php
$post = get_post( $id );
$code = $post->post_name;
?>

<p>
	Thanks for your patience. After writing your home builder sales person, they've made the changes you requested
	and you can now certify your Landscape Selections Order. Please visit this page to finalize your selection.
</p>
<p>
	To certify your order, please go here:
	&lt;<a href="<?php echo esc_url( home_url('/landscaping/selection/') . $code . '/certify' ); ?>"><?php echo esc_url( home_url('/landscaping/selection/') . $code . '/certify' ); ?></a>
</p>

<p>Thank You!</p>

<p>Gothic Landscape</p>


