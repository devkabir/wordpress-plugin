<?php
/**
 * Your Plugin Name
 *
 * @package           PluginPackage
 * @author            Your Name
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Your Plugin Name
 * Plugin URI:        https://example.com/your-plugin-name
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Requires at least: 5.3
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://example.com
 * Text Domain:       your-plugin-name
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/your-plugin-name/
 */

/*
|--------------------------------------------------------------------------
| If this file is called directly, abort.
|--------------------------------------------------------------------------
*/

use PluginPackage\Plugin;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Load class autoloader
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Define default constants
|--------------------------------------------------------------------------
*/
define( 'PluginPackage\MODE', 'prod' );
define( 'PluginPackage\NAME', 'your-plugin-name' );
define( 'PluginPackage\VERSION', '1.0.0' );
define( 'PluginPackage\FILE', __FILE__ );
define( 'PluginPackage\DIR', plugin_dir_path( __FILE__ ) );
define( 'PluginPackage\URL', plugin_dir_url( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| Activation, deactivation and uninstall event.
|--------------------------------------------------------------------------
*/
register_activation_hook( __FILE__, array( Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( Plugin::class, 'deactivate' ) );
register_uninstall_hook( __FILE__, array( Plugin::class, 'uninstall' ) );

/*
|--------------------------------------------------------------------------
| Start the plugin
|--------------------------------------------------------------------------
*/
Plugin::init();