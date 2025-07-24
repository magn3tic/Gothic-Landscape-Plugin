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

<p>Dear <?php echo esc_html( $seller_name ); ?>:</p>
<p>cc: <?php echo esc_html( $buyer_name ); ?></p>

<p>Hi! This email is from Gothic Landscape, Arizona Division on behalf of your home
	buyer <?php echo esc_html( $buyer_name ); ?> for lot <?php echo esc_html( $lot ); ?>. They were working on their
	Landscape Preferences Order and they told us they either found an error or would like to make a change. We need you
	to please log into Gothic Landscape Arizona Landscape Selections and make an adjustment. If necessary, you may need
	to first contact the home buyer.</p>
<p>When working on their Landscape Preferences Order, they listed the following errors or desired changes:</p>

<?php foreach ( $message as $each ) : ?>
	<p><em>
		<?php echo esc_html( $each ); ?>
	</em></p>
<?php endforeach; ?>

<p><strong>Access the record here (requires a logged in user):</strong> &lt;<a
			href="<?php echo esc_url(home_url('/landscaping/selection/'). $code . '/edit' ); ?>"><?php echo esc_url( home_url('/landscaping/selection/'). $code . '/edit' ); ?></a>
</p>

<p>Thank You!</p>

<p>Gothic Landscape</p>


