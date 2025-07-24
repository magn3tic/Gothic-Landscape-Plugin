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

<p>Hi! This email confirms the completion of your Landscape Selections Order.</p>

<?php
$post = get_post( $id );
$code = $post->post_name;
?>

<p>
	Now that your order is complete, you cannot access it to make changes, but you can review your selections
	at any time, including to get helpful links to our Landscape Care Guide and Plant Material Guide, by going here:
	&lt;<a href="<?php echo esc_url( home_url('/landscaping/selection/') . $code . '/thanks' ); ?>"><?php echo esc_url( home_url('/landscaping/selection/'). $code . '/thanks' ); ?></a>
</p>

<p>Thank You!</p>

<p>Gothic Landscape</p>


