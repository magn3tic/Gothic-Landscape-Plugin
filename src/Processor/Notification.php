<?php

namespace Gothic\Selections\Processor;

use Gothic\Selections\Email\{
	ErrorInRecordNotification,
	GothicCompletedSelectionEmail,
	UserCompletedSelectionEmail,
	UserRemindSelectionEmail,
	UserUpdatedSelectionEmail,
	SellerRemindSelectionEmail,
	SpecialRequest
};

final class Notification {

	public static function send_errors_email() {

		$problems = get_post_meta( get_the_ID(), 'problems', true );

		if ( empty( $problems['email'] ) || empty( $problems['name'] ) || empty( $problems['comments'] ) ) {

			return;
		}

		$email_args = [
			'buyer_name'   => $problems['name'],
			'buyer_email'  => $problems['email'],
			'seller_name'  => get_post_meta( get_the_ID(), 'builder_rep_name', true ),
			'seller_email' => get_post_meta( get_the_ID(), 'builder_rep_email', true ),
			'lot'          => get_post_meta( get_the_ID(), 'lot', true ),
			'message'      => $problems['comments'],
			'code'         => get_post()->post_name,
		];

		ErrorInRecordNotification::message( $email_args );
	}

	public static function send_updated_user_email() {
		$email_args = [
			'id'    => get_the_ID(),
			'email' => get_post_meta( get_the_ID(), 'email', true ),
		];

		UserUpdatedSelectionEmail::message( $email_args );
	}

	public static function send_complete_user_email() {
		$email_args = [
			'id'    => get_the_ID(),
			'email' => get_post_meta( get_the_ID(), 'email', true ),
		];

		UserCompletedSelectionEmail::message( $email_args );
	}

	public static function special_request() {
		$email_args = [
			'id'    => get_the_ID(),
			'email' => get_post_meta( get_the_ID(), 'builder_rep_email', true ),
		];

		SpecialRequest::message( $email_args );
	}

	public static function send_complete_gothic_email() {
		$email_args = [
			'id' => get_the_ID()
		];

		GothicCompletedSelectionEmail::message( $email_args );
	}

	public static function send_buyer_reminder( $id ) {
		$args = [
			'id'    => $id,
			'name'  => get_post_meta( $id, 'home_buyer', true ),
			'email' => get_post_meta( $id, 'email', true ),
			'code'  => get_post_field( 'post_name', $id ),
		];

		UserRemindSelectionEmail::message( $args );

		$time = current_datetime();

		update_post_meta( $id, '_last_reminder', $time->format( 'Y-m-d h:i:s' ) );
	}

	public static function send_seller_reminder( $id ) {

		$args = [
			'id'    => $id,
			'name'  => get_post_meta( $id, 'builder_rep_email', true ),
			'email' => get_post_meta( $id, 'builder_rep_name', true ),
			'buyer_name' => get_post_meta( $id, 'home_buyer', true ),
			'lot' => get_post_meta( $id, 'lot', true ),
		];

		SellerRemindSelectionEmail::message( $args );

		$time = current_datetime();

		update_post_meta( $id, '_seller_last_reminder', $time->format( 'Y-m-d h:i:s' ) );
	}
}