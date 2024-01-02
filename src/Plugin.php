<?php // phpcs:ignore

namespace PluginPackage;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use PluginPackage\Controllers\Api\SettingController;
use PluginPackage\Controllers\AdminController;
use PluginPackage\Controllers\LicenseController;
use PluginPackage\Controllers\LogController;

class Plugin {
	public static function activate() {
		LicenseController::class;
	}

	public static function deactivate() {
		LicenseController::class;
	}

	public static function uninstall() {
		LicenseController::class;
	}

	public static function init() {
		if ( is_admin() ) {
			AdminController::instance();
		}
		LogController::instance()->write( 'init', 'Plugin is initialized' );
		add_action( 'rest_api_init', array( __CLASS__, 'api' ) );
	}

	public static function api() {
		SettingController::instance();
	}
}
