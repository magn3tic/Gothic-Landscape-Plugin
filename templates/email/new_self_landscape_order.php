<p>Dear <?php echo $name ?>:</p>

<p>This email is your confirmation that you started a Landscape Selection for your new home.
	We are excited to have the opportunity to landscape your home, but your builder and we
	will need you to complete this Landscape Selection as soon as possible to not delay
	your contract or the start of construction on your home.</p>

<p>If you took a break or accidentally closed your browser window, you can use this email
	to access your in-progress Landscape Selection. The easiest way to do this is to click on the link
	below, or you can also log in on our website with your email address and your access code.</p>

<p>&lt;<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_url( $url ); ?></a>&gt;</p>

<p><strong>Your Access Code:</strong> <?php echo esc_attr( get_post_field( 'post_name', $id ) ); ?></p>

<p>Thank You!</p>

<p>Gothic Landscape</p>


