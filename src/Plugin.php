<?php

namespace PluginPackage;

use PluginPackage\Admin\Menu;
use PluginPackage\Web\Shortcodes;

class Plugin {
	public static function activate() {
		//		TODO: Add activities during plugin activation
	}

	public static function deactivate() {
		//		TODO: Add activities during plugin deactivation
	}

	public static function uninstall() {
		//		TODO: Add activities during plugin uninstallation
	}

	public static function init() {
		if ( is_admin() ) {
			Menu::instance();
			//		TODO: Add admin based classes here
		}
		Shortcodes::instance( array( 'shortcode-1', 'shortcode-2' ) );
		//		TODO: Add web based classes here
	}
}
