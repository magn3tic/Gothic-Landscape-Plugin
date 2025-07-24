<?php
/**
 * Gothic Landscape Selections Plugin
 *
 * The one class that powers the plugin and makes it extendable.
 *
 * @link        https://garten.co/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections Plugin
 * @subpackage  Core
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com
 * @copyright   (c) 2018-2020 Gothic Landscape
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Gothic\Selections;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Exception;

/**
 * Class Plugin
 *
 * @final
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Constant Version
	 *
	 * @since 1.0.0
	 *
	 * @var string VERSION
	 */
	const VERSION = '1.0.0';

	/**
	 * Constant Filter Prefix
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @var string $path
	 */
	const FILTER_PREFIX = 'gothic_selections_';

	/**
	 * Variable Directory
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @var string $directory
	 */
	public static $directory;

	/**
	 * Variable Plugin File
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @var string $file
	 */
	public static $file;

	/**
	 * Variable Path
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @var string $path
	 */
	public static $path;

	/**
	 * Variable Instance
	 *
	 * @access private
	 * @static
	 * @since 1.0.0
	 *
	 * @var Plugin $instance
	 */
	private static $instance;

	/**
	 * Instance Builder
	 *
	 * Singleton pattern means we create only one instance of the class.
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @return Plugin
	 *
	 * @throws Exception
	 */

	public static function instance() : Plugin {
		if ( ! ( isset( self::$instance ) ) && ! ( self::$instance instanceof Plugin ) ) {

			self::$instance = new Plugin();

			spl_autoload_register( [ self::$instance, 'autoload' ] );

			self::$instance->setup_properties();

			self::$instance->load();

			add_action( 'plugins_loaded', [ self::$instance, 'textdomain' ] );

		}

		return self::$instance;
	}

	/**
	 * Class Constructor
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		// Silence is Golden
	}

	/**
	 * Throw error on object clone.
	 *
	 * Singleton design pattern means is that there is a single object,
	 * and therefore, we don't want or allow the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No can do! You may not clone an instance of the plugin.', 'gothic-selections' ), esc_attr( self::VERSION ) );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * Unserializing of the class is also forbidden in the singleton pattern.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __wakeup() : void {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No can do! You may not unserialize an instance of the plugin.', 'gothic-selections' ), esc_attr( self::VERSION ) );
	}

	/**
	 * Setup Properties
	 *
	 * @access private
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function setup_properties() : void {
		self::$directory = plugin_dir_path( __FILE__ );
		self::$file      = self::$directory . 'index.php';
		self::$path      = plugin_dir_url( self::$file );
	}

	/**
	 * Load Plugin
	 *
	 * @access private
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load() : void {

		add_image_size( 'preferences-tiles', 280, 160, true );

		new User\Capabilities();

		new PostType\Builder();
		new PostType\Community();
		new PostType\Package();
		new PostType\Model();
		new PostType\Palette();
		new PostType\PreferencesOrder();

		new REST\Model();
		new REST\Community();
		new REST\Package();

		new Core\Activate();
		new Core\Rewrites();
		new Core\Enqueue();
		new Core\Scheduler();

		new PostsTablePro\CustomDataTypes();

		if ( is_admin() ) {
			new Admin\Enqueue();
			new Admin\Notice();
			new User\UserMeta();
		}

		include_once self::$directory . '/src/template-functions.php';

		global $gothic_orders;
		$gothic_orders = new Processor\Form();
	}

	/**
	 * Plugin Textdomain
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'gothic-selections', false, self::$path . '/languages' );
	}

	/**
	 * Plugin Autoloader
	 *
	 * @param string $class Class Name
	 */
	public function autoload( $class ) {

		// project-specific namespace prefix
		$prefix = __NAMESPACE__ . '\\';

		// base directory for the namespace prefix
		$base_dir = self::$directory . 'src/';

		// does the class use the namespace prefix?
		$len = strlen( $prefix );

		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			// no, move to the next registered autoloader
			return;
		}

		// get the relative class name
		$relative_class = substr( $class, $len );

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php
		$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		// if the file exists, require it
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
}
