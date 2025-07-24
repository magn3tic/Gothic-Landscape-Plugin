<?php
/**
 * Selections Form
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  SelectionForm
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Processor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\Tools\Export;
use \WP_Query;
use \WP_User;
use Gothic\Selections\Helper\{Misc, Template, Queries};
use Gothic\Selections\PostType\{
	Builder as Builders,
	Community as Communities,
	Model as Models,
	Palette,
	PreferencesOrder as Orders
};
use Gothic\Selections\Email\StepAccessFoundOrder;

/**
 * Class Form
 *
 * @since 1.0.0
 *
 * @final
 */
final class Form {

	/**
	 * Property: Request
	 *
	 * Holds the $_REQUEST global after nonce was checked.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @var array $request
	 */
	private $request = [];

	/**
	 * Property: Data
	 *
	 * Holds the data before/after retrieval from database
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @var array $data
	 */
	private $data = [];

	/**
	 * Property: Errors
	 *
	 * Holds an array of errors that could be returned to the screen upon a failure
	 *
	 * @access private
	 *
	 * @var array $request
	 *
	 * @since 1.0.0
	 */
	private $errors = array();

	/**
	 * Property: Screen
	 *
	 * Holds the $_REQUEST global after nonce was checked.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @var string $screen
	 */
	private $screen = 'start';

	/**
	 * Property: Nonce Key
	 *
	 * The nonce key for the form.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @var string $nonce
	 */
	private static $nonce = 'preferences-request';

	/**
	 * Class Constructor
	 *
	 * Sets up an instance of the class for use in processing a form.
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'load' ] );
	}
	public function redirectGuest( $id ) {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
			$_SESSION["logged"] = $_SESSION["logged"] ?? false;
		}
		
		// if(!$_SESSION["logged"]) {
		// 	wp_safe_redirect( home_url('/landscaping/selection/access')  );
		// 	exit();
		// }

		// if(isset($_SESSION["email"]) && $_SESSION["type"] === 1 && strtolower( sanitize_email( $_SESSION["email"] ) ) != strtolower( get_post_meta( $id, 'email', true ) ) ) {
		// 	wp_safe_redirect( home_url('/landscaping/selection/access') );
		// 	exit();
		// }
	}
	/**
	 * Load
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 */
	public function load() {

		// If not the selections page or a selection single don't route
		if ( ! ( is_singular( Orders::$key ) || is_page( 'landscaping/selection' ) ) ) {

			return;
		}

		// If we have the nonce and a POST request, process the submission (may end this script following an exit)
		if ( ! empty( $_REQUEST[ self::$nonce ] ) && wp_verify_nonce( $_REQUEST[ self::$nonce ], self::$nonce ) ) {
			$this->request = apply_filters( 'gothic_preferences_data_raw', $_REQUEST );
			$this->process();
		}

		// If WP thinks its loading a page
		if ( is_page( 'landscaping/selection' ) ) {

			// If user is logged in, show index or new
			if ( $this->can_list_orders() ) {
				if ( 'report' === get_query_var( 'step' ) && $this->user_can_generate_report() ) {
					$this->screen = 'report';
				} elseif ( 'new' === get_query_var( 'step' ) ) {
					$this->screen = 'new';
				} elseif ( ! get_query_var( 'step' ) ) {
					wp_safe_redirect( get_page_by_path( 'landscape/selections' ) . 'index' );
				} else {
					$this->screen = 'index';
				}
				// If user is not logged in, show access or default "start"
			} else {
				if ( 'access' === get_query_var( 'step' ) ) {
					$this->screen = 'access';
				}
			}
			// If WP thinks its loading an Orders post type post
		} elseif ( is_singular( Orders::$key ) && ! empty( get_query_var( 'name' ) ) ) {

			$this->data = get_page_by_path( get_query_var( 'name' ), 'OBJECT', Orders::$key );

			// If a post is found
			if ( $this->data ) {

				setup_postdata( $this->data );

				$status = get_post_meta( get_the_ID(), '_status', true );
				$step   = get_query_var( 'step', false );

				// If user is logged in
				if ( $this->can_edit_order() ) {

					// Complete orders can be show, transfer, void, cancel
					// Incomplete orders can be edit, cancel, void, remind
					// Voided orders can be deleted by admin
					$admin_screens = [ 'show', 'edit', 'transfer', 'cancel', 'void', 'remind', 'delete' ];

					if ( ! in_array( $step, $admin_screens ) ) {
						$step = false;
					}

					if ( ! $step ) {
						if ( in_array( $status, [ 'complete', 'cancelled', 'voided', 'transferred' ], true ) ) {
							wp_safe_redirect( get_permalink( get_the_ID() ) . 'show' );
							exit;
						} else {
							wp_safe_redirect( get_permalink( get_the_ID() ) . 'edit' );
							exit;
						}
					}

					if ( 'delete' === $step && ( 'voided' !== $status || ! current_user_can( 'administrator' ) ) ) {
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'show' );
						exit;
					}

					if ( in_array( $status, [ 'voided', 'cancelled', 'transferred' ], true ) && 'edit' === $step ) {
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'show' );
						exit;
					}

					if ( 'complete' === $status && in_array( $step, [ 'edit', 'remind', 'void' ] ) ) {
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'show' );
						exit;
					}

					if (
						! in_array( $status, [ 'complete', 'voided', 'cancelled', 'transferred' ], true )
						&& in_array( $step, [ 'show', 'transfer' ] )
					) {
						wp_safe_redirect( get_permalink( get_the_ID() . 'edit' ) );
						exit;
					}

					// If user is not logged in
				} else {

					$user_screens = [
						'start',
						'welcome',
						'model',
						'package',
						'preferences',
						'info',
						'thanks',
						'pending',
						'certify',
					];

					// If they call directly without the url query var, or if its invalid, find the step.

					if ( ! $step || ! in_array( $step, $user_screens, true ) ) {
						$step = strtolower( get_post_meta( get_the_ID(), '_step', true ) );
						if ( ! in_array( $step, $user_screens, true ) ) {
							if ( 'complete' === $status ) {
								$step = 'thanks';
							} elseif ( 'seller_action' === $status ) {
								$step = 'pending';
							} else {
								$step = 'start';
							}
						}
						wp_safe_redirect( get_permalink( get_the_ID() ) . $step );
						exit;
					}

					if ( 'preferences' === $step && Misc::selection_has_no_preferences( get_the_ID() ) ) {
						update_post_meta( get_the_ID(), '_step', 'info' );
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'info' );
						exit;
					}

					if ( 'complete' === $status && 'thanks' !== $step ) {
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'thanks' );
						exit;
					}

					update_post_meta( get_the_ID(), '_last_accessed', gmdate( 'Y-m-d H:i:s', time() ) );

					if ( 'seller_action' === $status && 'certify' === $step ) {
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'pending' );
						exit;
					}

				}

				$this->screen = $step;

				// end If post is found
				// If post is not found, 404 it
			} else {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
			}
		}
	}

	/**
	 * Render
	 *
	 * Render the appropriate template for the current state
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function render(): void {

		Template::get_template( "progress-bar", $this->template_args(), 'selections', false, true );

		if ( 'access' === $this->screen ) :

			Template::get_template( "access", $this->template_args(), 'selections', false, true );

		else :
			?>
			<form id="landscape-selection-form"
			      action="<?php echo esc_url( get_permalink( get_the_ID() ) . ( $this->screen ?: '' ) ); ?>"
			      class="landscape-selection-form  <?php echo esc_attr( $this->screen ?: '' ); ?>" method="post">

				<?php
				wp_nonce_field( 'preferences-request', 'preferences-request', 'preferences-request' );

				Template::form_field( [
					'type'  => 'hidden',
					'name'  => '_current_step',
					'value' => $this->screen,
				], true );
				switch ( $this->screen ) {
					case 'model':
					case 'package':
					case 'info':
						self::redirectGuest(get_the_ID());
						if ( self::is_self_order( get_the_ID() ) ) {
							$template = 'step-' . $this->screen . '-self';
						} else {
							$template = 'step-' . $this->screen . '-sales';
						}
						break;
					case 'certify':
					case 'preferences':
					case 'start':
					case 'thanks':
						$template = 'step-' . $this->screen;
						break;
					default:
						$template = $this->screen;
						break;
				}
				Template::get_template( $template, $this->template_args(), 'selections', false, true );
				?>

			</form>
		<?php
		endif;
	}

	/**
	 * Process
	 *
	 * This function is called on the submit of a valid POST request.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 */
	private function process(): void {
		
		session_start();

		if ( empty( $this->request ) ) {
			return;
		}

		if ( self::is_complete( get_the_ID() && ( empty( $this->request['_current_step'] ) || ! in_array( $this->request['_current_step'], [
					'transfer',
					'cancel'
				] ) ) ) ) {
			wp_safe_redirect( get_permalink( get_the_ID() ) . 'thanks' );
			exit;
		}

		switch ( $this->request['_current_step'] ) {

			case 'report':
				if ( ! ( $this->user_can_generate_report() ) ) {
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'start' );
					exit;
				} else {
					Export::export( $this->request );
				}
				break;
			case 'edit':
			case 'new':
				if ( ! ( current_user_can( 'gothic-salesmanager' ) || current_user_can( 'gothic-salesperson' ) || current_user_can( 'administrator' ) ) ) {
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'start' );
					exit;
				} else {
					
					$meta = [];
					
					$meta['home_buyer']        = ! empty( $this->request['home_buyer'] ) ? sanitize_text_field( $this->request['home_buyer'] ) : null;
					$meta['email']             = ! empty( $this->request['email'] ) ? sanitize_email( $this->request['email'] ) : null;
					$meta['phone']             = ! empty( $this->request['phone'] ) ? sanitize_text_field( $this->request['phone'] ) : null;
					$meta['address']           = ! empty( $this->request['address'] ) ? sanitize_text_field( $this->request['address'] ) : null;
					$meta['city']              = ! empty( $this->request['city'] ) ? sanitize_text_field( $this->request['city'] ) : null;
					$meta['state']             = ! empty( $this->request['state'] ) ? sanitize_text_field( $this->request['state'] ) : null;
					$meta['zip']               = ! empty( $this->request['zip'] ) ? sanitize_text_field( $this->request['zip'] ) : null;
					$meta['lot']               = ! empty( $this->request['lot'] ) ? sanitize_text_field( $this->request['lot'] ) : null;
					$meta['builder_id']        = ! empty( $this->request['builder_id'] ) ? intval( $this->request['builder_id'] ) : null;
					$meta['community_id']      = ! empty( $this->request['community_id'] ) ? intval( $this->request['community_id'] ) : null;
					$meta['model_id']          = ! empty( $this->request['model_id'] ) ? intval( $this->request['model_id'] ) : null;
					$meta['package_id']        = ! empty( $this->request['package_id'] ) ? intval( $this->request['package_id'] ) : null;
					$meta['backyard_id']       = ! empty( $this->request['backyard_id'] ) ? intval( $this->request['backyard_id'] ) : null;
					$meta['builder_rep_name']  = ! empty( $this->request['builder_rep_name'] ) ? sanitize_text_field( $this->request['builder_rep_name'] ) : null;
					$meta['builder_rep_email'] = ! empty( $this->request['builder_rep_email'] ) ? sanitize_email( $this->request['builder_rep_email'] ) : null;
					$meta['_status']           = 'in_progress';
					
					
					if ( 'edit' === $this->request['_current_step'] && ! $meta['builder_id'] ) {
						$meta['builder_id'] = get_post_meta( get_the_ID(), 'builder_id', true );
					}
					
					$required = [
						'home_buyer',
						'email',
						'phone',
						'address',
						'city',
						'state',
						'zip',
						'lot',
						'builder_id',
						'community_id',
						'model_id',
						'builder_rep_name',
						'builder_rep_email'
					];
					
					foreach ( $required as $require ) {
						if ( empty( $meta[ $require ] ) ) {
							$this->errors[ $require ] = __( 'This field is required.', 'gothic-selections' );
						}
					}
					
					if ( $meta['community_id'] ) {
						
						list( $front, $back ) = array_values( Queries::packages_meta( $meta['community_id'] ) );
						
						if ( $front && empty( $meta['package_id'] ) ) {
							$this->errors['package_id'] = __( 'This field is required for this community.', 'gothic-selections' );
						}
						
						if ( $back && empty( $meta['backyard_id'] ) ) {
							$this->errors['backyard_id'] = __( 'This field is required for this community.', 'gothic-selections' );
						}
					}
					
					if ( empty( $this->errors ) ) {
						
						$id = Orders::is_post_of_type( get_the_ID() ) ? get_the_ID() : 0;
						
						if ( $id ) {
							if ( ! empty( get_post_meta( get_the_ID(), 'problems', true ) ) ) {
								delete_post_meta( get_the_ID(), 'problems' );
								Notification::send_updated_user_email();
							}
							foreach ( $meta as $key => $value ) {
								update_post_meta( get_the_ID(), $key, $value );
							}
							// // die();
							// $history = json_decode(get_post_meta($id , 'order_history', true )) ?? [];
			
							// $time = current_datetime();
							// $history[] = [
							// 	"history_entry_title" => "Order Information Changed",
							// 	"history_entry_date" => $time
							// ];
							// $history = json_encode($history);
							// update_post_meta($id , 'order_history',  $history);
							if($this->request['_current_step'] == "edit") {
								if(is_user_logged_in()) {
									update_post_meta( get_the_ID(), '_edit_last', get_current_user_id() );
								}

								if(empty($this->request['remind'])) {
									
									$history = json_decode(get_post_meta($id , 'order_history', true )) ?? [];
									$time = current_datetime();
									$history_entry = [
										"history_entry_title" => "Order Information Changed",
										"history_entry_date" => $time
									];
									if(!empty($this->request["resolve"])) {
										$history_entry = [
											"history_entry_title" => "Resolved Errors",
											"history_entry_date" => $time
										];
									}
									$history[] = $history_entry;
									$history = json_encode($history);
									update_post_meta($id , 'order_history',  $history);
								}
							}
						} else {
							$meta['_step']          = 'start';
							$meta['_is_self_order'] = false;
							$temp_args = [
								'ID'          => $id,
								'post_type'   => Orders::$key,
								// 'post_author' => 1,
								'post_status' => 'publish',
								'meta_input'  => $meta,
							];

							
							$id = wp_insert_post( apply_filters( 'gothic_order_post_args', $temp_args, $this->request ) );
						}

						if ( ! is_wp_error( $id ) && 0 !== $id ) {
							if ( Orders::is_post_of_type( get_the_ID() ) && isset( $this->request['remind'] ) ) {
								Notification::send_buyer_reminder( $id );
								$this->errors['invalid'] = __( 'Reminder Sent', 'gothic-selections' );
							} else {
								wp_safe_redirect( get_permalink( $id ) . 'edit' );
							}
						}

					} else {
						$this->errors['invalid'] = __( 'There was an error saving this record.', 'gothic-selections' );
					}
				}

				break;
			case 'transfer':
				if ( ! ( current_user_can( 'gothic-salesmanager' ) || current_user_can( 'gothic-salesperson' ) || current_user_can( 'administrator' ) ) ) {
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'start' );
					exit;
				} else {

					if ( Orders::$key !== get_post_type( get_the_id() ) ) {
						$this->errors['invalid'] = __( 'The request was invalid.', 'gothic-selections' );
					}

					$new = [];

					$new['transfer_address'] = ! empty( $this->request['transfer_address'] ) ? sanitize_text_field( $this->request['transfer_address'] ) : null;
					$new['transfer_city']    = ! empty( $this->request['transfer_city'] ) ? sanitize_text_field( $this->request['transfer_city'] ) : null;
					$new['transfer_state']   = ! empty( $this->request['transfer_state'] ) ? sanitize_text_field( $this->request['transfer_state'] ) : null;
					$new['transfer_zip']     = ! empty( $this->request['transfer_zip'] ) ? sanitize_text_field( $this->request['transfer_zip'] ) : null;
					$new['transfer_lot']     = ! empty( $this->request['transfer_lot'] ) ? sanitize_text_field( $this->request['transfer_lot'] ) : null;

					$required = [
						'transfer_address',
						'transfer_city',
						'transfer_state',
						'transfer_zip',
						'transfer_lot',
					];

					foreach ( $required as $require ) {
						if ( empty( $new[ $require ] ) ) {
							$this->errors[ $require ] = __( 'This field is required.', 'gothic-selections' );
						}
					}

					if ( empty( $this->errors ) ) {

						// Grab the meta, which has any customer responses
						$original_post = get_the_ID();
						$original_meta = get_post_meta( $original_post );

						// Map the meta into a new object
						$new_meta = [];
						foreach ( $original_meta as $meta_key => $value ) {
							$new_meta[ $meta_key ] = $value[0];
						}

						// Update the transfer data onto the new meta
						$new_meta['address'] = $new['transfer_address'];
						$new_meta['city']    = $new['transfer_city'];
						$new_meta['state']   = $new['transfer_state'];
						$new_meta['zip']     = $new['transfer_zip'];
						$new_meta['lot']     = $new['transfer_lot'];

						// Create the new post
						$id = wp_insert_post( apply_filters( 'gothic_transfer_order_post_args', [
							'post_type'   => Orders::$key,
							'post_author' => 1,
							'post_status' => 'publish',
							'meta_input'  => $new_meta,
						], $this->request ) );

						$secret_url = Misc::get_secret_post_name( $id, $new_meta['email'] );

						// Save secret URL
						wp_update_post( [
							'ID'        => $id,
							'post_name' => $secret_url,
						] );

						// Update the original post status
						update_post_meta( $original_post, '_status', 'transferred' );
						update_post_meta( $original_post, '_date_last_status_updated', current_datetime()->format( 'Y-m-d h:i:s' ) );
						update_post_meta( $original_post, '_transferred_to', $id );
						update_post_meta( $id, '_date_last_status_updated', current_datetime()->format( 'Y-m-d h:i:s' ) );

						// redirect
						if ( ! is_wp_error( $id ) && 0 !== $id ) {
							wp_safe_redirect( get_permalink( $id ) . 'edit' );
							exit;
						}
					}

					$this->errors['invalid'] = __( 'The request was invalid.', 'gothic-selections' );
					die( 'end' );
				}


			case 'cancel':
				if ( ! ( current_user_can( 'gothic-salesmanager' ) || current_user_can( 'gothic-salesperson' ) || current_user_can( 'administrator' ) ) ) {
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'start' );
					exit;
				} else {

					if ( 'CANCEL' !== sanitize_text_field( $this->request['confirm_action'] ) ) {
						$this->errors['confirm_action'] = __( 'Type "CANCEL" to confirm', 'gothic-selections' ) . '.';
					}

					if ( empty( $this->errors ) ) {
						update_post_meta( get_the_ID(), '_status', 'cancelled' );
						update_post_meta( get_the_ID(), '_date_last_status_updated', current_datetime()->format( 'Y-m-d h:i:s' ) );
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'show' );
						exit;
					}

				}
				break;
			case 'void':
				if ( ! ( current_user_can( 'gothic-salesmanager' ) || current_user_can( 'gothic-salesperson' ) || current_user_can( 'administrator' ) ) ) {
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'start' );
					exit;
				} else {

					if ( 'VOID' !== sanitize_text_field( $this->request['confirm_action'] ) ) {
						$this->errors['confirm_action'] = __( 'Type "VOID" to confirm', 'gothic-selections' ) . '.';
					}

					if ( empty( $this->errors ) ) {
						update_post_meta( get_the_ID(), '_status', 'voided' );
						update_post_meta( get_the_ID(), '_date_last_status_updated', current_datetime()->format( 'Y-m-d h:i:s' ) );
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'show' );
						exit;
					}
				}
				break;
			case 'delete':
				if ( ! current_user_can( 'administrator' ) ) {
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'start' );
					exit;
				} else {

					if ( 'DELETE' !== sanitize_text_field( $this->request['confirm_action'] ) ) {
						$this->errors['confirm_action'] = __( 'Type "DELETE" to confirm', 'gothic-selections' ) . '.';
					}

					if ( empty( $this->errors ) ) {
						wp_delete_post( get_the_ID(), true );
						wp_safe_redirect( get_permalink( get_page_by_path( 'landscaping/selection' ) ) . 'index' );
						exit;
					}

				}
				break;
			case 'access':
				if(isset($_POST['g-recaptcha-response'])){
					$captcha = $_POST['g-recaptcha-response'];
					if(!$captcha) {
						// $this->errors["captcha"] = __("Captcha Required", "gothic-selections");
					}
				}
				if ( isset( $this->request['_existing'] ) ) {

					if ( empty( $this->request['access_code'] ) ) {
						$this->errors['access_code'] = __( 'Access Code is required.', 'gothic-selections' );
					}

					if ( empty( $this->request['email'] ) ) {
						$this->errors['email'] = __( 'Email is required.', 'gothic-selections' );
					}

					$search = get_page_by_path( esc_attr( $this->request['access_code'] ), OBJECT, Orders::$key );
					

					if ( $search && strtolower( sanitize_email( $this->request['email'] ) ) === strtolower( get_post_meta( $search->ID, 'email', true ) ) ) {
						$_SESSION["email"] = strtolower( sanitize_email( $this->request['email'] ) );
						$_SESSION["logged"] = true;
						$_SESSION["type"] = 1;
						wp_safe_redirect( get_permalink( $search->ID ) );
						exit();
					} else {
						$this->errors['existing_invalid'] = __( 'Your email address or access code is incorrect. Please double check, access the system via the link in your email, or try another option.', 'gothic-selections' );
					}
				}
				

				if ( isset( $this->request['_new'] ) ) {

					if ( ! sanitize_email( $this->request['new_email'] ) ) {
						$this->errors['new_email'] = __( 'A valid email is required.', 'gothic-selections' );
					} else {
						$this->request['new_email'] = sanitize_email( $this->request['new_email'] );
					}

					if ( empty( $this->request['new_email'] ) ) {
						$this->errors['new_email'] = __( 'Email is required.', 'gothic-selections' );
					}

					if ( empty( intval( $this->request['new_lot'] ) ) ) {
						$this->errors['new_lot'] = __( 'Your lot number is required.', 'gothic-selections' );
					} else {
						$this->request['new_lot'] = intval( $this->request['new_lot'] );
					}

					if ( empty( $this->errors ) ) {

						$existing = new WP_Query( [
							'post_type'  => Orders::$key,
							'meta_query' => [
								[
									'key'     => 'lot',
									'compare' => '=',
									'value'   => $this->request['new_lot'],
								],
								[
									'key'     => 'email',
									'value'   => $this->request['new_email'],
									'compare' => '='
								],
								[
									'key'     => '_status',
									'compare' => 'NOT IN',
									'value'   => [ 'cancelled', 'transferred' ]
								],
							],
						] );

						if ( ! is_wp_error( $existing ) && $existing->have_posts() ) {

							$to_use = 0;

							if ( count( $existing->get_posts() ) > 1 ) {

								foreach ( $existing->get_posts() as $each ) {
									if ( 'complete' === get_post_meta( $each->ID, 'status', true ) ) {
										$to_use = $each->ID;
									}
								}
							}

							if ( ! $to_use ) {
								$to_use = $existing->get_posts()[0]->ID;
							}

							$email_args = [
								'to'   => get_post_meta( $to_use, 'email', true ),
								'name' => get_post_meta( $to_use, 'buyer_name', true ),
								'url'  => get_permalink( $to_use ),
								'code' => get_post_field( 'post_name', $to_use ),
							];

							StepAccessFoundOrder::message( $email_args );
							wp_safe_redirect( get_permalink( get_page_by_path( 'landscape/selection' ) . 'check-email' ) );
							exit;

						} else {

							$id = wp_insert_post( apply_filters( 'gothic_order_post_args', array(
								'post_type'   => Orders::$key,
								'post_author' => 1,
								'post_status' => 'publish',
								'meta_input'  => array(
									'_is_self_order' => true,
									'email'          => sanitize_email( $this->request['new_email'] ),
									'lot'            => sanitize_text_field( $this->request['new_lot'] ),
									'_step'          => 'model',
									'_status'        => 'in_progress',
								),
							), $this->request ) );

							$_SESSION["email"] = strtolower(sanitize_email( $this->request['new_email'] ) );
							$_SESSION["logged"] = true;
							$_SESSION["type"] = 1;

							wp_safe_redirect( get_permalink( $id ) . 'model' );
							exit;
						}
					}
				}

				if ( isset( $this->request['_login'] ) ) {

					if ( empty( $this->request['uname'] ) ) {
						$this->errors['uname'] = __( 'Username or email is required.', 'gothic-selections' );
					}

					if ( empty( $this->request['pword'] ) ) {
						$this->errors['pword'] = __( 'Password is required.', 'gothic-selections' );
					}

					if ( empty( $this->errors ) ) {
						$credentials = [
							'user_login'    => sanitize_text_field( $this->request['uname'] ),
							'user_password' => sanitize_text_field( $this->request['pword'] ),
						];
						$login       = wp_signon( $credentials );
					} else {
						$login = null;
					}

					

					if ( $login instanceof WP_User ) {
						$_SESSION["logged"] = true;
						$_SESSION["type"] = 0;
						wp_safe_redirect( get_permalink( get_page_by_path( 'landscape/selections' ) ) );
						exit;
					} else {
						$this->errors['login_invalid'] = __( 'Incorrect username or password.', 'gothic-selections' );
						if(get_user_by("email", sanitize_text_field( $this->request['uname'] )) == false && get_user_by("login", sanitize_text_field( $this->request['uname'] )) == false) {
							$this->errors['login_invalid'] = __( 'User not exists', 'gothic-selections' );
						}
					}
				}
				break;

			
			case 'start':

				update_post_meta( get_the_ID(), '_step', 'model' );
				wp_safe_redirect( get_permalink( get_the_ID() ) . 'model' );
				exit();

				break;

			case 'model':
				
				if ( self::is_self_order( get_the_ID() ) ) {

					foreach ( [ 'builder', 'community', 'model' ] as $each ) {
						$function = 'validate_' . $each;
						$id       = $each . '_id';
						if ( $this->$function() ) {
							update_post_meta( get_the_ID(), $id, $this->request[ $id ] );
						} else {
							$this->errors['invalid'] = __( 'There were errors with your submission. Please check your submission and try again.', 'gothic-selections' );
						}
					}

					if ( empty( $this->errors ) ) {
						update_post_meta( get_the_ID(), '_step', 'package' );
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'package' );
						exit();
					}

				} else {
					$this->process_issues();
					update_post_meta( get_the_ID(), '_step', 'package' );
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'package' );
					exit();
				}

				break;

			case 'package':

				if ( self::is_self_order( get_the_ID() ) ) {

					$community_id = intval( get_post_meta( get_the_ID(), 'community_id', true ) );

					if ( get_post_meta( $community_id, 'frontyard', true ) ) {

						$package = ! empty( $this->request['package_id'] ) ? (int) $this->request['package_id'] : false;

						// Note: this query includes a check if the community
						// has front/back yards enabled. No need to run a specific
						// check, though it couldn't hurt.
						$packages = Queries::packages( [
							'community_id' => $community_id,
						] );

						$packages_ids = [];

						foreach ( $packages as $each ) {
							$packages_ids[] = $each->ID;
						}

						if ( ! empty( $packages_ids ) ) {
							if ( $package ) {
								if ( in_array( $package, $packages_ids, true ) ) {
									update_post_meta( get_the_ID(), 'package_id', $package );
								} else {
									$this->errors['package_id'] = __( 'You selected an invalid front yard package. Please try again.', 'gothic-selections' );
								}
							} else {
								$this->errors['package_id'] = __( 'You must select a front yard package.', 'gothic-selections' );
							}
						}
					}

					if ( 1 === intval( get_post_meta( $community_id, 'backyard', true ) ) ) {

						$backyard = ! empty( $this->request['backyard_id'] ) ? (int) $this->request['backyard_id'] : false;

						// Note: this query includes a check if the community
						// has front/back yards enabled. No need to run a specific
						// check, though it couldn't hurt.
						$backyards = Queries::packages( [
							'community_id' => $community_id,
							'backyard'     => true
						] );

						$backyards_ids = [];

						foreach ( $backyards as $each ) {
							$backyards_ids[] = $each->ID;
						}

						if ( ! empty( $backyards_ids ) ) {
							if ( $backyard ) {
								if ( in_array( $backyard, $backyards_ids, true ) ) {
									update_post_meta( get_the_ID(), 'backyard_id', $backyard );
								} else {
									$this->errors['backyard_id'] = __( 'You selected an invalid back yard package. Please try again.', 'gothic-selections' );
								}
							} else {
								$this->errors['backyard_id'] = __( 'You must select a back yard package.', 'gothic-selections' );
							}
						}
					} elseif ( - 1 === intval( get_post_meta( $community_id, 'backyard', true ) ) ) {

						$opt_in_backyard_upsell = ! empty( $this->request['opt_in_backyard_upsell'] ) ? (int) $this->request['opt_in_backyard_upsell'] : false;

						if ( $opt_in_backyard_upsell ) {
							update_post_meta( get_the_ID(), 'opt_in_backyard_upsell', true );
						}
					}

					if ( ! empty( $this->errors ) ) {
						$this->errors['invalid'] = __( 'Please check your submission and try again.', 'gothic-selections' );
					} else {
						update_post_meta( get_the_ID(), '_step', 'preferences' );
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'preferences' );
						exit();
					}
				} else {

					$community_id = intval( get_post_meta( get_the_ID(), 'community_id', true ) );

					if ( - 1 === intval( get_post_meta( $community_id, 'backyard', true ) ) ) {

						$opt_in_backyard_upsell = ! empty( $this->request['opt_in_backyard_upsell'] ) ? (int) $this->request['opt_in_backyard_upsell'] : false;

						if ( $opt_in_backyard_upsell ) {
							update_post_meta( get_the_ID(), 'opt_in_backyard_upsell', true );
						}
					}

					$this->process_issues();
					update_post_meta( get_the_ID(), '_step', 'preferences' );
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'preferences' );
					exit();
				}

				break;

			case 'preferences':

				$comments = ! empty( $this->request['comments'] ) ? sanitize_textarea_field( $this->request['comments'] ) : null;

				if ( $comments ) {
					Notification::special_request();
					update_post_meta( get_the_ID(), 'comments', $comments );
				}

				$fy_package = get_post_meta( get_the_ID(), 'package_id', true );
				$palette    = ! empty( $this->request['palette_id'] ) ? (int) $this->request['palette_id'] : false;

				if ( $fy_package && $palette && Palette::is_post_of_type( $palette ) ) {
					update_post_meta( get_the_ID(), 'palette_id', $palette );
				} elseif ( ! get_post_meta( $fy_package, 'no_palette', true ) ) {
					$this->errors['invalid'] = __( 'You must select a Palette for the Front Yard.', 'gothic-selections' );
				}

				$by_package = get_post_meta( get_the_ID(), 'backyard_id', true );
				$backyard   = ! empty( $this->request['by_palette_id'] ) ? (int) $this->request['by_palette_id'] : false;

				if ( $by_package && $backyard && Palette::is_post_of_type( $backyard ) ) {
					update_post_meta( get_the_ID(), 'by_palette_id', $backyard );
				} elseif ( ! get_post_meta( $by_package, 'no_palette', true ) ) {
					$this->errors['invalid'] = empty( $this->errors['invalid'] ) ? __( 'You must select a Palette for the Back Yard.', 'gothic-selections' ) : $this->errors['invalid'] . ' ' . __( 'You must also select a Palette for the Back Yard.', 'gothic-selections' );
				}

				if ( empty( $this->errors ) ) {
					update_post_meta( get_the_ID(), '_step', 'info' );
					wp_safe_redirect( get_permalink( get_the_ID() ) . 'info' );
					exit();
					break;
				}

				break;

			case 'info':

				if ( self::is_self_order( get_the_ID() ) ) {

					$meta = [];

					$meta['home_buyer']       = ! empty( $this->request['home_buyer'] ) ? sanitize_text_field( $this->request['home_buyer'] ) : null;
					$meta['email']            = ! empty( $this->request['email'] ) ? sanitize_email( $this->request['email'] ) : null;
					$meta['phone']            = ! empty( $this->request['phone'] ) ? sanitize_text_field( $this->request['phone'] ) : null;
					$meta['address']          = ! empty( $this->request['address'] ) ? sanitize_text_field( $this->request['address'] ) : null;
					$meta['city']             = ! empty( $this->request['city'] ) ? sanitize_text_field( $this->request['city'] ) : null;
					$meta['state']            = ! empty( $this->request['state'] ) ? sanitize_text_field( $this->request['state'] ) : null;
					$meta['zip']              = ! empty( $this->request['zip'] ) ? sanitize_text_field( $this->request['zip'] ) : null;
					$meta['lot']              = ! empty( $this->request['lot'] ) ? sanitize_text_field( $this->request['lot'] ) : null;
					$meta['builder_rep_name'] = ! empty( $this->request['builder_rep_name'] ) ? sanitize_text_field( $this->request['builder_rep_name'] ) : null;

					foreach ( $meta as $key => $value ) {
						update_post_meta( get_the_ID(), $key, $value );
					}

					$required = [ 'home_buyer', 'email', 'phone', 'city', 'address', 'lot' ];

					foreach ( $required as $require ) {
						if ( empty( $meta[ $require ] ) ) {
							$this->errors[ $require ] = __( 'This field is required.', 'gothic-selections' );
						}
					}

					if ( empty( $this->errors ) ) {
						update_post_meta( get_the_ID(), '_step', 'certify' );
						wp_safe_redirect( get_permalink( get_the_ID() ) . 'certify' );
						exit();
					} else {
						$this->errors['invalid'] = __( 'Please check your submission and try again.', 'gothic-selections' );
					}

				} else {
					$this->process_issues();

					update_post_meta( get_the_ID(), '_step', 'certify' );

					if ( get_post_meta( get_the_ID(), 'problems', true ) ) {

						Notification::send_errors_email();

						wp_safe_redirect( get_permalink( get_the_ID() ) . 'pending' );

					}

					wp_safe_redirect( get_permalink( get_the_ID() ) . 'certify' );
					exit();
				}

				break;
			case 'certify':

				if ( ! isset( $this->request['accept_terms'] ) ) {
					$this->errors['accept_terms'] = __( 'Please accept the terms of service and privacy policy.' );
				}
				if ( ! empty( $this->request['confirm_name'] ) && isset( $this->request['accept_terms'] ) ) {
					update_post_meta( get_the_ID(), '_status', 'complete' );
					update_post_meta( get_the_ID(), 'confirm_name', sanitize_text_field( $this->request['confirm_name'] ) );
					update_post_meta( get_the_ID(), 'confirm_time', current_datetime()->format( 'Y-m-d h:i:s' ) );
					update_post_meta( get_the_ID(), '_step', 'thanks' );
					update_post_meta( get_the_ID(), '_date_last_status_updated', current_datetime()->format( 'Y-m-d h:i:s' ) );

					Notification::send_complete_user_email();
					Notification::send_complete_gothic_email();

					wp_safe_redirect( get_permalink( get_the_ID() ) . 'thanks' );
					exit();
				}

				break;
			default:
				break;
		}

		return;
	}

	private function process_issues() {
		if ( isset( $this->request['_problem'] ) && in_array( $this->request['_problem'], [
				'model',
				'info',
				'package'
			] ) ) {

			$problems = get_post_meta( get_the_ID(), 'problems', true );

			if ( ! $problems ) {
				$problems = [];
			}

			if ( ! empty( $this->request['comments'] ) && ! empty( $this->request['contact_name'] ) && ! empty( $this->request['_problem'] ) ) {
				$problems['email']      = sanitize_email( $this->request['contact_email'] );
				$problems['name']       = sanitize_text_field( $this->request['contact_name'] );
				$problems['comments'][] = sanitize_text_field( $this->request['comments'] );
			}

			if ( ! empty( $problems ) ) {
				update_post_meta( get_the_ID(), 'problems', $problems );
				update_post_meta( get_the_ID(), '_status', 'seller_action' );
				$history = json_decode(get_post_meta( get_the_ID(), 'order_history', true )) ?? [];
				$time = current_datetime();
				$history[] = [
					"history_entry_title" => "Noticed Errors",
					"history_entry_date" => $time
				];
				$history = json_encode($history);

				update_post_meta( get_the_ID(), 'order_history', $history );
			}
		}
	}

	private function template_args(): array {
		return [
			'request' => $this->request,
			'errors'  => $this->errors,
			'screen'  => $this->screen,
		];
	}

	/**
	 * Validate Builder
	 *
	 * @param string $key optional
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @uses \Gothic\Selections\PostType\Builder::is_post_of_type()
	 *
	 */
	private function validate_builder( string $key = 'builder_id' ): bool {

		$value = isset( $this->request[ $key ] ) ? intval( $this->request[ $key ] ) : 0;

		if ( 0 !== $value && - 1 !== $value && is_int( $value ) ) {
			if ( Builders::is_post_of_type( $value ) ) {
				$this->request[ $key ] = intval( $this->request[ $key ] );

				return true;
			} else {
				$this->errors[ $key ] = __( 'Oops! You somehow selected an invalid home builder. Try again.', 'gothic-selections' );
			}
		} else {
			$this->errors[ $key ] = __( 'You must select a home builder.', 'gothic-selections' );
		}

		if ( isset( $this->errors[ $key ] ) ) {
			unset( $this->request[ $key ] );
		}

		return false;
	}

	/**
	 * Validate Community
	 *
	 * Validates community and validates its association with the provided builder
	 *
	 * @param string $key optional
	 * @param string $builder_key optional
	 *
	 * @return bool
	 * @uses \Gothic\Selections\PostType\Community::is_post_of_type()
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function validate_community( string $key = 'community_id', string $builder_key = 'builder_id' ): bool {

		$value = isset( $this->request[ $key ] ) ? intval( $this->request[ $key ] ) : 0;

		if ( 0 !== $value && - 1 !== $value && is_int( $value ) ) {
			if ( Communities::is_post_of_type( $value ) ) {
				$this->request[ $key ] = intval( $this->request[ $key ] );
			} else {
				$this->errors[ $key ] = __( 'Oops! You somehow selected an invalid community. Try again.', 'gothic-selections' );
				unset( $this->request[ $key ] );

				return false;
			}
		} else {
			$this->errors[ $key ] = __( 'You must select a community.', 'gothic-selections' );
			unset( $this->request[ $key ] );

			return false;
		}

		if ( $this->validate_builder( $builder_key ) && $this->request[ $builder_key ] === intval( get_post_meta( $value, 'builder_id', true ) ) ) {

			return true;
		} else {
			$this->errors[ $key ] = __( 'Please try again when selecting your community. The community you selected did not belong to the builder you selected. Please try again.', 'gothic-selections' );
			unset( $this->request[ $key ] );

			return false;
		}
	}

	/**
	 * Validate Model
	 *
	 * Validates model and validates its association with the provided community
	 *
	 * @param string $key optional
	 * @param string $community_key optional
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @uses \Gothic\Selections\PostType\Model::is_post_of_type()
	 *
	 */
	private function validate_model( string $key = 'model_id', string $community_key = 'community_id' ): bool {

		$value = isset( $this->request[ $key ] ) ? intval( $this->request[ $key ] ) : 0;

		if ( 0 !== $value && - 1 !== $value && is_int( $value ) ) {
			if ( Models::is_post_of_type( $value ) ) {
				$this->request[ $key ] = intval( $this->request[ $key ] );
			} else {
				$this->errors[ $key ] = __( 'Oops! You somehow selected an invalid model. Try again.', 'gothic-selections' );
				unset( $this->request[ $key ] );

				return false;
			}
		} else {
			$this->errors[ $key ] = __( 'You must select a home model.', 'gothic-selections' );
			unset( $this->request[ $key ] );

			return false;
		}

		if ( $this->validate_community( $community_key ) && $this->request[ $community_key ] === intval( get_post_meta( $value, 'community_id', true ) ) ) {

			return true;
		} else {
			$this->errors[ $key ] = __( 'Please try again when selecting your home model. The home model you selected did not belong to the community you selected. Please try again.', 'gothic-selections' );
			unset( $this->request[ $key ] );

			return false;
		}
	}

	/**
	 * Validate Package
	 *
	 * Validates model and validates its association with the provided community
	 *
	 * @param string $key optional
	 * @param string $backyard optional
	 * @param string $community_key optional
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @uses \Gothic\Selections\PostType\Model::is_post_of_type()
	 *
	 */
	private function validate_package( string $key = 'package_id', bool $backyard = false, string $community_key = 'community_id' ): bool {

		$value = isset( $this->request[ $key ] ) ? intval( $this->request[ $key ] ) : 0;

		if ( 0 !== $value && - 1 !== $value && is_int( $value ) ) {
			if ( Packages::is_post_of_type( $value ) ) {
				$this->request[ $key ] = intval( $this->request[ $key ] );
			} else {
				$this->errors[ $key ] = __( 'Oops! You somehow selected an invalid package. Try again.', 'gothic-selections' );
				unset( $this->request[ $key ] );

				return false;
			}
		} else {
			$this->errors[ $key ] = __( 'You must select a landscape package.', 'gothic-selections' );
			unset( $this->request[ $key ] );

			return false;
		}

		if ( $this->validate_community( $community_key ) && $this->request[ $community_key ] === intval( get_post_meta( $value, 'community_id', true ) ) ) {

			return true;
		} else {
			$this->errors[ $key ] = __( 'Please try again when selecting your home model. The home model you selected did not belong to the community you selected. Please try again.', 'gothic-selections' );
			unset( $this->request[ $key ] );

			return false;
		}
	}

	/**
	 * Is A Self-Initiated Order?
	 *
	 * @param int $id
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 */
	private static function is_self_order( int $id ): bool {

		return get_post_meta( $id, '_is_self_order', true ) ?: false;
	}

	/**
	 * Is Order Complete?
	 *
	 * @param int $id
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 */
	private static function is_complete( int $id ): bool {

		return ( 'complete' === get_post_meta( $id, '_status', true ) ) ?: false;
	}

	private function user_can_generate_report() : bool {
		if ( ! is_user_logged_in() ) {

			return false;
		}

		if ( current_user_can( 'administrator' ) ) {

			return true;
		}

		return false;
	}

	private function can_list_orders(): bool {

		if ( ! is_user_logged_in() ) {

			return false;
		}

		if (
			current_user_can( 'gothic-salesmanager' )
			|| current_user_can( 'gothic-salesperson' )
			|| current_user_can( 'administrator' )
		) {

			return true;
		}

		return false;
	}

	private function can_edit_order( $id = 0 ): bool {
		if ( ! is_user_logged_in() ) {

			return false;
		}

		if ( current_user_can( 'administrator' ) ) {

			return true;
		}

		if ( ! current_user_can( 'gothic-salesmanager' ) && ! current_user_can( 'gothic-salesperson' ) ) {

			return false;
		}

		$user_builder_id  = (int) get_user_meta( get_current_user_id(), 'gothic_user_homebuilder', true );
		$order_builder_id = (int) get_post_meta( get_the_ID(), 'builder_id', true );

		if ( $user_builder_id === $order_builder_id ) {

			return true;
		}

		return false;
	}
}
