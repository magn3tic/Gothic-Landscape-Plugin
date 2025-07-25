<?php
/**
 * Matador / Emailer
 *
 * @link        http://matadorjobs.com/
 * @since       3.0.0
 *
 * @package     Matador Jobs Board
 * @subpackage  Core
 * @author      Jeremy Scott, Paul Bearne
 * @copyright   Copyright (c) 2017, Jeremy Scott
 *
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace Gothic\Selections\Helper;

class Email {

	/**
	 * Stores the email object.
	 *
	 * @var array
	 */
	public $mail = array();

	public function __construct() {
		$this->headers();
	}

	protected function headers( $headers = array() ) {
		if ( empty( $headers ) ) {
			$headers = array( sprintf( 'From: Gothic Landscape Arizona <%s>', 'az.selections@gothiclandscape.com' ) );
		}
		$this->mail['headers'] = $headers;
	}

	public function subject( $subject ) {
		$this->mail['subject'] = $subject;
	}

	public function recipients( $recipients ) {
		$this->mail['to'] = $recipients;
	}

	public function message( $message ) {
		$this->mail['message'] = $message;
	}

	public function attachment( $attachment ) {
		$this->mail['attachments'][] = $attachment;
	}

	public function send() {
		$success = null;
		if ( ! isset( $this->mail['attachments'] ) ) {
			$this->mail['attachments'] = array();
		}

		if ( ! empty( $this->mail['subject'] ) && ! empty( $this->mail['message'] ) ) {
			add_filter( 'wp_mail_content_type', [ __CLASS__, 'set_content_type_html' ], 22 );

			$success = wp_mail( $this->mail['to'], $this->mail['subject'], $this->mail['message'], $this->mail['headers'], $this->mail['attachments'] );

			remove_filter( 'wp_mail_content_type', [ __CLASS__, 'set_content_type_html' ] );
		}

		return $success;
	}

	public static function set_content_type_html() {
		return 'text/html';
	}
}
