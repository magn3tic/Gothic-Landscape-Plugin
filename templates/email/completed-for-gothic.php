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

<p>Hi! This email confirms the completion of a new Landscape Selections Order.</p>

<?php
$post = get_post( $id );
$code = $post->post_name;
?>

<p><strong>Access the record here (requires a logged in user):</strong> &lt;<a
			href="<?php echo esc_url( home_url('/landscaping/selection/') . $code ); ?>"><?php echo esc_url( home_url('/landscaping/selection/') . $code ); ?></a>
</p>

<p>Thank You!</p>

<p>Gothic Landscape</p>


