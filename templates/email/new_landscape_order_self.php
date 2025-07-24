<p>Dear <?php echo $name ?>:</p>

<p>Hi! We are Gothic Landscape, Arizona Division, and we are partnered with your home builder
	to provide landscaping for your new home. We are excited to have the opportunity to landscape
	your home, but before we can, we need your help.</p>

<p>In this email is a url and access code for your Landscape Preferences Selections. During a quick
	5-10 minute process, we'll ask you to confirm details about your home and select the types of plants
	we'll plant in your new landscape (by choosing from palettes of handpicked plants by us and your
	home builder).</p>

<p>If you take a break or accidentally close your browser window, you can use this email to
	also access your in-progress Landscape Selection. The easiest way to do this is to click on the link
	below, or you can also log in on our website with your email address and your access code.</p>

<p>&lt;<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_url( $url ); ?></a>&gt;</p>

<p><strong>Your Access Code:</strong> <?php echo esc_attr( get_post_field( 'post_name', $id ) ); ?></p>

<p>Thank You!</p>

<p>Gothic Landscape</p>


