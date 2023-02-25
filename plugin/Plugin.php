<?php
/**
 * It will manage plugin based on current screen of WordPress.
 *
 * @package PluginPackage
 */

namespace PluginPackage;

use DevKabir\Plugin\Container;
use DevKabir\Plugin\License;
use DevKabir\Plugin\Loader;
use DevKabir\Plugin\Log;
use PluginPackage\Admin\Menu;
use PluginPackage\Api\Logs;
use PluginPackage\Api\Settings;
use PluginPackage\Web\Shortcodes;
use stdClass;

/**
 * Class Plugin
 *
 * @package PluginPackage
 */
final class Plugin extends Container {


	/**
	 * It sets the name, version, root, and url properties of the class
	 */
	protected function __construct() {
		define( 'PluginPackage\NAME', 'your-plugin-name' );
		define( 'PluginPackage\VERSION', '1.0.0' );
		define( 'PluginPackage\ROOT', plugin_dir_path( __DIR__ ) );
		define( 'PluginPackage\URL', plugin_dir_url( __DIR__ ) );
	}

	/**
	 * It activates the plugin.
	 */
	public function activate(): void {
		License::get_instance()->wp_register( NAME );
	}

	/**
	 * It deactivates the plugin.
	 */
	public function deactivate(): void {
		License::get_instance()->wp_register( NAME );
	}

	/**
	 * It run first, so you can set up your plugin in here.
	 */
	public function init(): void {
		Log::get_instance()->set_name(NAME);
//		TODO:: Remove test logger before you start coding.
		$this->test_log();

		$this->load_translations();

		Loader::get_instance()->set_action( 'rest_api_init', array( $this, 'apis' ) );

		$this->handle_request();
	}

	/**
	 * This function writes a message to the log file
	 */
	private function test_log(): void {
		$message = 'Testing log for ' . NAME;

		$log =Log::get_instance();
		$log->write( 'text', $message );
		$log->write( 'array', [
			'file'=> __FILE__,
			'line'=> __LINE__
		] );
		$obj = new stdClass();
		$obj->file = __FILE__;
		$obj->line = __LINE__;
		$log->write('object', $obj);
		$log->write('script', '<script>alert("Hacked")</script>');
		$log->write('script', '<?php echo phpinfo() ?>');
	}

	/**
	 * It loads the translations for the plugin
	 */
	private function load_translations(): void {
		Loader::get_instance()->set_action(
			'plugins_loaded',
			function () {
				load_plugin_textdomain(
					NAME,
					false,
					ROOT . '/language/'
				);
			}
		);
	}

	/**
	 * It will start website-related classes.
	 */
	public function web(): void {
		Shortcodes::get_instance( array( 'hello', 'dolly' ) );
	}

	/**
	 * It sets up the ajax call for the plugin.
	 */
	public function ajax(): void {
		$loader = Loader::get_instance();
		$ajax   = Ajax::get_instance();
		$loader->set_action( 'wp_ajax_your_plugin_name_settings', array( $ajax, 'settings' ) );
		$loader->set_action( 'wp_ajax_nopriv_your_plugin_name_settings', array( $ajax, 'settings' ) );
	}

	/**
	 * It will start admin-related classes.
	 */
	public function admin(): void {
		Menu::get_instance();
	}

	/**
	 * It will load all api endpoints.
	 *  - add cors header when your are in localhost.
	 */
	public function apis(): void {
//		TODO:: Remove flush on production
		flush_rewrite_rules(true);
		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '-' ) );
		if ( '127.0.0.1' === $ip ) {
			$this->cors();
		}
		Settings::get_instance();
		Logs::get_instance();
	}

	/**
	 * Enable cors for development
	 */
	private function cors(): void {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: GET, POST, PUT, DELETE' );
	}

	/**
	 * If we're using themes, send us to the web.
	 * If we're in the admin, send us to the admin.
	 * If we're in the admin and doing an ajax request, send us to the ajax
	 */
	private function handle_request(): void {
		if ( wp_using_themes() ) {
			$this->web();
		}
		if ( defined( 'WP_ADMIN' ) && WP_ADMIN ) {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$this->ajax();
			}
			$this->admin();
		}
	}

}