<?php
/**
 * Message (Abstract)
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
use Gothic\Selections\Helper\Template;
use Gothic\Selections\Helper\DashedUnderscoredTrait;

/**
 * Abstract Class Message
 *
 * @since 1.0.0
 *
 * @package Gothic\Selections\Email
 *
 * @abstract
 */
abstract class MessageAbstract implements MessageInterface {

	use DashedUnderscoredTrait;

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
	public static $key = 'default';

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

		$email->from( self::from( $email->mail['from'], $args ) );

		$email->recipients( self::recipients( $args ) );

		$email->subject( self::subject( $args ) );

		$email->message( self::body( $args ) );

		$email->attachments( self::attachments( $args ) );

		$email->send( static::$key );
	}

	/**
	 * From
	 *
	 * Determine the email sending address. Extend or call with filter. Should recieve the default from field, set in
	 * instatiation of the Email class.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $from
	 * @param array $args
	 *
	 * @return string|array
	 */
	protected static function from( array $from = [], array $args = [] ) {

		/**
		 * Filter: Email [Dynamic] From
		 *
		 * @since 1.0.0
		 *
		 * @var array  $recipients
		 * @var array  $args
		 *
		 * @return string
		 */
		return apply_filters( Plugin::FILTER_PREFIX . '_email_' . static::key_underscored() . '_from', $from, $args );
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

		$recipients = [ 'email', get_bloginfo( 'admin_email' ) ];

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
	 * CC
	 *
	 * Determine CC recipients. Extend or call with filter.
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
	protected static function cc( array $args = [] ) {
		/**
		 * Filter: Email [Dynamic] CC
		 *
		 * @since 1.0.0
		 *
		 * @var array  $recipients
		 * @var array  $args
		 *
		 * @return string
		 */
		return apply_filters( Plugin::FILTER_PREFIX . '_email_' . static::key_underscored() . '_cc', [], $args );
	}

	/**
	 * BCC
	 *
	 * Determine BCC recipients. Extend or call with filter.
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
	protected static function bcc( array $args = [] ) {

		/**
		 * Filter: Email [Dynamic] BCC
		 *
		 * @since 1.0.0
		 *
		 * @var array  $recipients
		 * @var array  $args
		 *
		 * @return string
		 */
		return apply_filters( Plugin::FILTER_PREFIX . '_email_' . static::key_underscored() . '_bcc', [], $args );
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
		return apply_filters( Plugin::FILTER_PREFIX . '_email_' . static::key_underscored() . '_subject', __( 'Email from Gothic Landscape Arizona', 'gothic-selections' ), $args );
	}

	/**
	 * Body
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param array $args
	 * @param string $template
	 *
	 * @return string
	 */
	protected static function body( array $args = [], string $template = '' ) : string {

		if ( empty( $template ) ) {
			$template = static::$key;
		}

		$template = Template::get_template( $template, $args, 'email' );

		/**
		 * Filter: Email [Dynamic] Body
		 *
		 * @since 1.0.0
		 *
		 * @var string $template
		 * @var array  $args
		 *
		 * @return string
		 */
		return apply_filters( Plugin::FILTER_PREFIX . '_email_' . static::key_underscored() . '_body', $template, $args );
	}

	/**
	 * Attachments
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
	protected static function attachments( array $args = [] ) {

		/**
		 * Filter: Email [Dynamic] Attachments
		 *
		 * @since 1.0.0
		 *
		 * @var array  $attachments
		 * @var array  $args
		 *
		 * @return string
		 */
		return apply_filters( Plugin::FILTER_PREFIX . '_email_' . static::key_underscored() . '_attachments', [], $args );
	}

	/**
	 * Key Underscored
	 *
	 * Returns the static class key, which should be formatted with dashes as spaces, with underscores as spaces.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 * @static
	 *
	 * @return string
	 */
	protected static function key_underscored() {

		return self::dashes_underscored( static::$key );
	}
}
