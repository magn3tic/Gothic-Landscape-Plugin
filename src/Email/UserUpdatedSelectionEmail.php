<?php
/**
 * Selections Updated for User Notification Email
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Email
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections\Email;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Gothic\Selections\Plugin;

/**
 * Abstract Class Message
 *
 * @since 1.0.0
 *
 * @package Gothic\Selections\Email
 *
 * @abstract
 */
final class UserUpdatedSelectionEmail extends MessageAbstract implements MessageInterface {

	/**
	 * Key
	 *
	 * Give your message a name so logging can communicate which email is being sent. Spaces should be separated by
	 * dashes (-) not underscores (_) or other characters. IE: 'administrator-error' not 'administrator_error'.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $key = 'updated-for-user';

	/**
	 * Message
	 *
	 * Compile the data for and send the email.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	public static function message( array $args = [] ) {

		$email = new Email();

		$email->recipients( self::recipients( $args ) );

		$email->subject( self::subject( $args ) );

		$email->message( self::body( $args ) );

		$email->send( static::$key );
	}

	/**
	 * Recipients
	 *
	 * Determine To recipients. Extend or call with filter.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	protected static function recipients( array $args = [] ) {

		if ( empty ( $args ) || empty( $args['email'] ) ) {
			die( esc_html_e( 'No seller email address sent to UserUpdatedSelectionEmail', 'gothic-selections' ) );
		}

		$recipients = $args['email'];

		/**
		 * Filter: Email [Dynamic] Recipients
		 *
		 * @since 1.0.0
		 *
		 * @var array  $recipients
		 * @var array  $args
		 *
		 * @return string
		 */
		return apply_filters( Plugin::FILTER_PREFIX . '_email_' . static::key_underscored() . '_recipients', $recipients, $args );
	}

	/**
	 * Subject
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	protected static function subject( array $args = [] ) {

		$subject = __( 'Completed Your Landscape Selections Order', 'gothic-selections' );

		/**
		 * Filter: Email [Dynamic] Subject
		 *
		 * @since 1.0.0
		 *
		 * @var string $subject
		 * @var array  $args
		 *
		 * @return string
		 */
		return apply_filters( Plugin::FILTER_PREFIX . '_email_' . static::key_underscored() . '_subject', $subject, $args );
	}
}
