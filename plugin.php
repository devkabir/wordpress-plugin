<?php
/**
 * Plugin Name:       Your Plugin Name
 * Plugin URI:        https://example.com/plugins/your-plugin
 * Description:       A brief description of what your plugin does.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://example.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       your-plugin-slug
 * Domain Path:       /languages
 *
 * @package Your_Plugin_Name
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin File Path
 * The main plugin file path (usually plugin.php)
 */
if ( ! defined( 'YOUR_PLUGIN_FILE' ) ) {
	define( 'YOUR_PLUGIN_FILE', __FILE__ );
}

// Load plugin files.
require_once __DIR__ . '/includes/autoload.php';
