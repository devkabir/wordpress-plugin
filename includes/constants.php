<?php
/**
 * Plugin Constants
 *
 * This file defines all constants used throughout the plugin.
 * Constants are defined once and used globally for paths, URLs, versions, and configuration.
 *
 * @package Your_Plugin_Name
 * @author  Firstname Lastname
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Version
 * Used for cache busting assets and database migrations
 */
if ( ! defined( 'YOUR_PLUGIN_VERSION' ) ) {
	define( 'YOUR_PLUGIN_VERSION', '1.0.0' );
}

/**
 * Plugin Directory Path
 * Absolute path to the plugin directory (without trailing slash)
 * Example: /var/www/wp-content/plugins/wordpress-plugin
 */
if ( ! defined( 'YOUR_PLUGIN_DIR' ) ) {
	define( 'YOUR_PLUGIN_DIR', dirname( YOUR_PLUGIN_FILE ) );
}

/**
 * Plugin Directory URL
 * URL to the plugin directory (without trailing slash)
 * Example: https://example.com/wp-content/plugins/wordpress-plugin
 */
if ( ! defined( 'YOUR_PLUGIN_URL' ) ) {
	define( 'YOUR_PLUGIN_URL', plugin_dir_url( YOUR_PLUGIN_FILE ) );
}

/**
 * Plugin Basename
 * The plugin basename (directory/file.php)
 * Example: wordpress-plugin/plugin.php
 */
if ( ! defined( 'YOUR_PLUGIN_BASENAME' ) ) {
	define( 'YOUR_PLUGIN_BASENAME', plugin_basename( YOUR_PLUGIN_FILE ) );
}

/**
 * Plugin Includes Directory Path
 * Path to the includes directory where classes are stored
 */
if ( ! defined( 'YOUR_PLUGIN_INCLUDES_DIR' ) ) {
	define( 'YOUR_PLUGIN_INCLUDES_DIR', YOUR_PLUGIN_DIR . '/includes' );
}

/**
 * Plugin Assets Directory URL
 * URL to the assets directory (CSS, JS, images)
 */
if ( ! defined( 'YOUR_PLUGIN_ASSETS_URL' ) ) {
	define( 'YOUR_PLUGIN_ASSETS_URL', YOUR_PLUGIN_URL . 'assets' );
}

/**
 * Plugin Templates Directory Path
 * Path to the templates directory for view files
 */
if ( ! defined( 'YOUR_PLUGIN_TEMPLATES_DIR' ) ) {
	define( 'YOUR_PLUGIN_TEMPLATES_DIR', YOUR_PLUGIN_DIR . '/templates' );
}

/**
 * Plugin Slug
 * Used for options, transients, and text domain
 */
if ( ! defined( 'YOUR_PLUGIN_SLUG' ) ) {
	define( 'YOUR_PLUGIN_SLUG', 'your-plugin-slug' );
}

/**
 * Plugin Database Version
 * Used for tracking database schema changes
 */
if ( ! defined( 'YOUR_PLUGIN_DB_VERSION' ) ) {
	define( 'YOUR_PLUGIN_DB_VERSION', '1.0.0' );
}

/**
 * Plugin Debug Mode
 * Enable/disable debug mode (set to false in production)
 */
if ( ! defined( 'YOUR_PLUGIN_DEBUG' ) ) {
	define( 'YOUR_PLUGIN_DEBUG', false );
}

/**
 * Plugin Minimum PHP Version
 * Minimum required PHP version
 */
if ( ! defined( 'YOUR_PLUGIN_MIN_PHP_VERSION' ) ) {
	define( 'YOUR_PLUGIN_MIN_PHP_VERSION', '7.4' );
}

/**
 * Plugin Minimum WordPress Version
 * Minimum required WordPress version
 */
if ( ! defined( 'YOUR_PLUGIN_MIN_WP_VERSION' ) ) {
	define( 'YOUR_PLUGIN_MIN_WP_VERSION', '5.8' );
}
