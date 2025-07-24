<?php

use Gothic\Selections\Helper\Misc;
$status = get_post_meta( get_the_ID(), '_status', true );
$modifier = get_the_modified_author(get_the_ID());
$statuses = ["in_progress", "seller_action"];
$currentStatus = get_post_meta(get_the_ID(), "_status",true);

$issues = get_post_meta( get_the_ID(), 'problems', true );
$resolveEnabled = false;
if(!empty($issues) || $status == "seller_action" ){
	$resolveEnabled = true;
}
$if_special_request = get_post_meta(get_the_ID()
, 'comments', true) ? " (SR) " : "";
?>
<div class="status-header">
	<?php
	if ( ! in_array( $status, array_keys( Misc::statuses() ), true ) ) {
		$status = 'error';
	}
	?>
	<h4>
		<strong><?php 
			esc_html_e( 'Status', 'gothic-selections' ); 
		?></strong>:
		<?php gothic_the_selection_order_status_tooltip() ;
			echo $if_special_request; ?>
		<?php if ( 'transferred' === $status ) : ?>
			<?php
			$transferred_to = get_post( get_post_meta( get_the_ID(), '_transferred_to', true ) );
			?>
			<a href="<?php echo get_permalink( $transferred_to ); ?>">To Order <?php echo esc_html( $transferred_to->post_name ); ?></a>
		<?php endif; ?>
	</h4>
	<?php
	switch ( $status ) :
		case 'in_progress':
			?>

		<?php
	endswitch;
	?>
	<div class="text-center c-mt-4">
		<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'landscaping/selection' ) ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Go Back', 'gothic-selections' ); ?></a>
		<?php if ( 'complete' === $status ) : ?>
			<a href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'transfer' ); ?>" class="button"><?php esc_html_e( 'Transfer', 'gothic-selections' ); ?></a>
			<a href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'cancel' ); ?>" class="button"><?php esc_html_e( 'Cancel', 'gothic-selections' ); ?></a>
		<?php elseif ( 'seller_action' === $status ) : ?>
			<button type="submit"><?php esc_html_e( 'Save Changes', 'gothic-selections' ); ?></button>
		<?php elseif ( 'in_progress' === $status ) : ?>
			<a href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'void' ); ?>" class="button"><?php esc_html_e( 'Void', 'gothic-selections' ); ?></a>
			<a href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'cancel' ); ?>" class="button"><?php esc_html_e( 'Cancel', 'gothic-selections' ); ?></a>
			<button name="remind" class="button js-remind"><?php esc_html_e( 'Send Reminder', 'gothic-selections' ); ?></button>
			<button type="submit"><?php esc_html_e( 'Save Changes', 'gothic-selections' ); ?></button>
		<?php elseif ( 'voided' === $status && current_user_can( 'administrator' ) ) : ?>
			<a href="<?php echo esc_url( get_permalink( get_the_ID() ) . 'delete' ); ?>" class="button"><?php esc_html_e( 'Delete Voided Selection', 'gothic-selections' ); ?></a>
		<?php endif; ?>

		<?php if($resolveEnabled): ?>
		
			<button type="submit" name="resolve" value="<?= get_the_ID(); ?>" class="crunch-button crunch-button__full-background crunch-button__full-background--primary-color crunch-button__full-background--medium">Resolve</button>

		<?php endif; ?>
	</div>
</div>
