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


<?php
$post = get_post( $id );
$code = $post->post_name;
?>
<p>Hi, Special requests have been noted in <?= $code;?>. Please click here to review:</p>


<p>
	Access your landscape selections here:
</p>
<p>&lt;<a href="<?php echo get_permalink( $id ); ?>"><?php echo get_permalink( $id ); ?></a>&gt;</p>
<?php if ( ! empty( $code ) ) : ?>
<p><strong>Your Access Code:</strong> <?php echo esc_attr( $code ); ?></p>
<?php endif; ?>


<p>Thank You!</p>

<p>Gothic Landscape</p>


