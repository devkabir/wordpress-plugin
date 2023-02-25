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

/* This is a security measure to prevent direct access to the plugin file. */
if ( ! defined( 'WPINC' ) ) {
	exit;
}




/* Loading the autoloader file from the vendor directory. */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

use DevKabir\Plugin\Loader;
use PluginPackage\Plugin;

/* Creating a new instances for initialize this plugin */
$instance = Plugin::get_instance();

/**
 * Set the activation hook for a plugin.
 *
 * When a plugin is activated, the action 'activate_PLUGINNAME' hook is
 * called. In the name of this hook, PLUGINNAME is replaced with the name
 * of the plugin, including the optional subdirectory. For example, when the
 * plugin is located in wp-content/plugins/sampleplugin/sample.php, then
 * the name of this hook will become 'activate_sampleplugin/sample.php'.
 *
 * When the plugin consists of only one file and is (as by default) located at
 * wp-content/plugins/sample.php the name of this hook will be
 * 'activate_sample.php'.
 *
 * @param string $file The filename of the plugin including the path.
 * @param callable $callback The function hooked to the 'activate_PLUGIN' action.
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, array( $instance, 'activate' ) );

/**
 * Sets the deactivation hook for a plugin.
 *
 * When a plugin is deactivated, the action 'deactivate_PLUGINNAME' hook is
 * called. In the name of this hook, PLUGINNAME is replaced with the name
 * of the plugin, including the optional subdirectory. For example, when the
 * plugin is located in wp-content/plugins/sampleplugin/sample.php, then
 * the name of this hook will become 'deactivate_sampleplugin/sample.php'.
 *
 * When the plugin consists of only one file and is (as by default) located at
 * wp-content/plugins/sample.php the name of this hook will be
 * 'deactivate_sample.php'.
 *
 * @param string $file The filename of the plugin including the path.
 * @param callable $callback The function hooked to the 'deactivate_PLUGIN' action.
 *
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, array( $instance, 'deactivate' ) );

/**
 * This is a conditional statement that checks if the current page is in the admin area.
 * If it is not, it will load the website related function.
 * If it is, and doing ajax, it will load the ajax related functions.
 * Otherwise, it will load the admin functions.
 *
 * @since 1.0.0
 */
$instance->init();

/**
 * Register all added actions from above classes.
 */
Loader::get_instance()->run();