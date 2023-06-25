<?php

namespace PluginPackage;

use PluginPackage\Api\Logs;
use PluginPackage\Api\Posts;
use PluginPackage\Admin\Menu;
use PluginPackage\Ajax\Message;
use PluginPackage\Api\Settings;
use PluginPackage\Web\Shortcodes;

class Plugin {
	public static function activate() {
		// License::register( NAME );
	}

	public static function deactivate() {
		// License::register( NAME );
	}

	public static function uninstall() {
		// License::register( NAME );
	}

	public static function init() {
		if ( is_admin() ) {
			self::admin();
		}
		Message::instance();
		Shortcodes::instance( array( 'form' ) );
		add_action( 'rest_api_init', array( self::class, 'api' ) );
	}

	/**
	 * @return void
	 */
	public static function admin(): void {
		$menu = Menu::instance();
		// Register Menu.
		add_action( 'admin_menu', array( $menu, 'register' ) );
		// Load script.
		add_action( 'admin_enqueue_scripts', array( $menu, 'scripts' ) );
		// Add module attribute.
		add_filter( 'script_loader_tag', array( $menu, 'add_module' ), 10, 3 );
	}

	public static function api() {
		Settings::instance();
		Logs::instance();
		Posts::instance();
	}
}
