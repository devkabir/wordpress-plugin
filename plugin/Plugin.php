<?php
/**
 * It will manage plugin based on current screen of WordPress.
 *
 * @package PluginPackage
 */

namespace PluginPackage;

use DevKabir\Plugin\Container;
use DevKabir\Plugin\Loader;
use DevKabir\Plugin\Log;
use DevKabir\Plugin\Notice;
use PluginPackage\Admin\Menu;
use PluginPackage\Web\Shortcodes;

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
	public function activate() {
		$this->wp_register( NAME );
	}

	/**
	 * It deactivates the plugin.
	 */
	public function deactivate() {
		$this->wp_register( NAME );
	}

	/**
	 * It run first, so you can set up your plugin in here.
	 */
	public function init() {
		$message = 'Welcome to ' . NAME;
		Log::get_instance( NAME )->write( 'success', $message );
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
	 * It sets up the ajax call for the plugin.
	 */
	public function ajax() {
		$loader = Loader::get_instance();
		$ajax   = Ajax::get_instance();
		$loader->set_action( 'wp_ajax_wordpress_plugin_settings', array( $ajax, 'settings' ) );
		$loader->set_action( 'wp_ajax_nopriv_wordpress_plugin_settings', array( $ajax, 'settings' ) );
	}

	/**
	 * It will start admin-related classes.
	 */
	public function admin() {
		Menu::get_instance();
	}

	/**
	 * It will start website-related classes.
	 */
	public function web() {
		Shortcodes::get_instance( array( 'hello', 'dolly' ) );
	}

}
