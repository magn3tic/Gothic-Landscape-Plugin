<?php
// if(isset($_POST["resolve"]) && !empty($_POST["resolve"])) {
// 	if(is_user_logged_in()) {
// 		update_post_meta( $_POST["resolve"], '_edit_last', get_current_user_id() );
// 	}
// 	unset($_POST["resolve"]);
// }

?>
<div class="index">

	<div class="actions">

		<div>
			<a class="button" href="<?php echo esc_url( get_permalink( get_page_by_path( 'landscape/selections' ) ) . 'new' ); ?>">
				New Order
			</a>
			<?php if ( current_user_can( 'administrator' ) ) : ?>
			<a class="button button-secondary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'landscape/selections' ) ) . 'report' ); ?>">
				Report (CSV)
			</a>
			<?php endif; ?>
		</div>

		<h2>Landscape Orders</h2>

		<p class="text-right">
			<?php $user = wp_get_current_user(); ?>
			Hello, <?php echo esc_html( $user->first_name ); ?> 
			<a class="c-ml-3 button" href="<?php echo esc_url( wp_logout_url( get_permalink( get_page_by_path( 'landscape/selections' ) ) ) ); ?>">(Logout)</a>
		</p>
	</div>

	<div class="table">
		<?php

		$user        = in_array( 'gothic-salesperson', get_userdata( get_current_user_id() )->roles, true ) || in_array( 'gothic-salesmanager', get_userdata( get_current_user_id() )->roles, true ) ? get_userdata( get_current_user_id() ) : null;
		$homebuilder = $user ? get_user_meta( $user->ID, 'gothic_user_homebuilder', true ) : false;

		$args = [
			'post_type' => 'preferences_order',
			'post_limit' => 100,
		];

		if ( $homebuilder ) {
			$args['columns'] = 'order_number:Order,home_buyer:Home Buyer,community,model,cf:lot:Lot,order_status:Status,date_last_accessed:Last Accessed,date_created:Created,resolver:Action,modifier:Priority';
		} else {
			$args['columns'] = 'order_number:Order,home_buyer:Home Buyer,builder,community,model,cf:lot:Lot,order_status:Status,date_last_accessed:Last Accessed,date_created:Created,resolver:Action,modifier:Priority';
		}

		$shortcode_args = '';

		foreach ( $args as $arg => $values ) {
			$shortcode_args .= ' ' . $arg . '="';
			if ( is_array( $values ) ) {
				$shortcode_args .= implode( ',', $values );
			} else {
				$shortcode_args .= $values;
			}
			$shortcode_args .= '"';
		}

		add_filter( 'posts_table_query_args', function( $args, $post_table ) {
			$args['meta_query'] = [];

			$meta_query = [
				'relation' => 'AND',
				[
					'key' => '_status',
					'compare' => 'IN',
					'value' => [
						'complete',
						'in_progress',
						'seller_action',
						'voided',
						'cancelled',
						'transferred',
					]
				]
			];

			$user        = in_array( 'gothic-salesperson', get_userdata( get_current_user_id() )->roles, true ) || in_array( 'gothic-salesmanager', get_userdata( get_current_user_id() )->roles, true ) ? get_userdata( get_current_user_id() ) : null;
			$homebuilder = $user ? get_user_meta( $user->ID, 'gothic_user_homebuilder', true ) : false;

			if ( $homebuilder ) {
				$meta_query[] = [
					'key'     => 'builder_id',
					'value'   => $homebuilder,
					'compare' => '=',
				];
			}

			$args['meta_query'] = $meta_query;

			return $args;
		}, 10, 2 );

		?>
		<?php echo do_shortcode( sprintf( '[posts_table %s]', $shortcode_args ) );?>
	</div>

</div>