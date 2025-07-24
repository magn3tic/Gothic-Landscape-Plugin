<?php

/**
 * Defined Before Includes:
 *
 * @var $to
 * @var $name
 * @var $url
 * @var $code
 */
?>

<p>Dear <?php echo esc_html( $name ); ?>:</p>

<p>Hi! This email is from Gothic Landscape, Arizona Division. We found an existing Landscape Selection order for the email address and lot number you provided. Access it using the code or website link below:</p>

<p>&lt;<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_url( $url ); ?></a>&gt;</p>

<p><strong>Your Access Code:</strong> <?php echo esc_attr( $code ); ?></p>

<p>Thank You!</p>

<p>Gothic Landscape</p>


