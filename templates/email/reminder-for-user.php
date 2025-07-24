<?php

/**
 * Defined Before Includes:
 *
 * @var $name
 * @var $email
 * @var $code
 * @var $message
 */
?>

<p>Hi <?php echo esc_html( $name ); ?>,</p>

<p>
	We are writing you to remind you that you have an incomplete Landscape Preferences Selection for your new home.
</p>
<p>
	Gothic Landscape works with your home seller for your new home to landscape your new home, and it is important
	that you please fill out our simple information request so that we can learn about your preferences so we can
	personalize your new home's landscape.
</p>
<p>
	Access your landscape selections here:
</p>
<p>&lt;<a href="<?php echo get_permalink( $id ); ?>"><?php echo get_permalink( $id ); ?></a>&gt;</p>
<?php if ( ! empty( $code ) ) : ?>
<p><strong>Your Access Code:</strong> <?php echo esc_attr( $code ); ?></p>
<?php endif; ?>

<p>Thank You!</p>

<p>Gothic Landscape</p>


