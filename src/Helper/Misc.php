<?php

namespace Gothic\Selections\Helper;

use Gothic\Selections\Plugin;
use Gothic\Selections\PostType\PreferencesOrder as Orders;

final class Misc {

	public static function statuses() {

		return [
			'in_progress' => [
				'label'       => __( 'In Progress', 'gothic-selections' ),
				'description' => __( 'Waiting on Home Buyer to complete selection.', 'gothic-selections' ),
			],
			'seller_action' => [
				'label'       => __( 'Seller Revisions Required', 'gothic-selections' ),
				'description' => __( 'Waiting on Home Seller to make buyer-requested changes before they can continue.', 'gothic-selections' ),
			],
			'cancelled'   => [
				'label'       => __( 'Cancelled', 'gothic-selections' ),
				'description' => __( 'Home buyer cancelled home purchase.', 'gothic-selections' ),
			],
			'voided'      => [
				'label'       => __( 'Voided', 'gothic-selections' ),
				'description' => __( 'A duplicate or unnecessary order, deleted.', 'gothic-selections' ),
			],
			'transferred' => [
				'label'       => __( 'Transferred', 'gothic-selections' ),
				'description' => __( 'Home seller transferred selection to a new lot or model.', 'gothic-selections' ),
			],
			'error'       => [
				'label'       => __( 'Error', 'gothic-selections' ),
				'description' => __( 'There is an error getting this selection order\'s status.', 'gothic-selections' ),
			],
			'complete' => [
				'label' => __( 'Complete', 'gothic-selections' ),
				'description' => __( 'Order is complete.', 'gothic-selections' ),
			]
		];
	}

	public static function status_description( string $label = '' ) : string {
		$statuses = self::statuses();

		if ( empty( $label ) || ! in_array( strtolower( $label ), array_keys( $statuses ), true ) ) {
			return '';
		}

		return $statuses[ strtolower( $label ) ]['description'];
	}

	public static function status_label( string $label = '' ) : string {
		$statuses = self::statuses();

		if ( empty( $label ) || ! in_array( strtolower( $label ), array_keys( $statuses ), true ) ) {
			return '';
		}

		return $statuses[ strtolower( $label ) ]['label'];;
	}

	public static function get_placeholder_image( $size = '280-160' ) {

		if ( '300-350' === $size ) {

			return esc_url( Plugin::instance()::$path . 'assets/images/placeholder-300-350.png' );
		}

		return esc_url( Plugin::instance()::$path . 'assets/images/placeholder-280-160.png' );
	}

	/**
	 * Create Secret Post Name
	 *
	 * Using the ID and email as seeds, build an MD5 hash and trim it to 8 characters to create a "secret" post name for
	 * security-through-obscurity construct for the selections system orders.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id
	 * @param string $email
	 *
	 * @return string
	 */
	public static function get_secret_post_name( int $id, string $email ) : string {

		$secret_url = '';

		while ( true ) {
			// MD5 is not cryptographically safe, but homebuyer
			// information is not incredibly sensitive (all is
			// freely available via public records).
			$secret_url = substr( md5( wp_create_nonce( 'landscape_order_' . $id ) . ':' . $email ), 0, 8 );

			// Make sure its unique
			if ( ! get_page_by_path( $secret_url, OBJECT, Orders::$key ) ) {
				break;
			}
		}

		return $secret_url;
	}

	/**
	 * Selection Has No Preferences
	 *
	 * Some landscape selections orders can have no palette or
	 * @param $selection_id
	 *
	 * @return bool
	 */
	public static function selection_has_no_preferences( $selection_id ) {
		$fy_package    = get_post_meta( $selection_id, 'package_id', true );
		$no_fy_palette = false;
		$by_package    = get_post_meta( $selection_id, 'backyard_id', true );
		$no_by_palette = false;

		// If there isn't a FY or BY package (yet), return false.
		// In these cases, there is either an error, OR the function
		// is being used to check for whether the progress bar should
		// show the step.
		if ( ! $fy_package && ! $by_package ) {

			return false;
		}

		if ( empty( $fy_package ) || get_post_meta( $fy_package, 'no_palette', true ) ) {
			$no_fy_palette = true;
		}

		if ( empty( $by_package ) || get_post_meta( $by_package, 'no_palette', true ) ) {
			$no_by_palette = true;
		}

		if ( $no_by_palette && $no_fy_palette ) {
			return true;
		}

		return false;
	}
}