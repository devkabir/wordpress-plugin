<?php
/**
 * Plugin Autoloader
 *
 * This file loads all required plugin components in the correct order.
 * Files are loaded during plugin initialization to register hooks, filters, and constants.
 *
 * Load Order:
 * 1. constants.php - Define plugin constants (paths, versions, etc.)
 * 2. hooks.php     - Register WordPress action hooks
 * 3. filters.php   - Register WordPress filter hooks
 *
 * @package Your_Plugin_Name
 * @author  Firstname Lastname
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PSR-4 Class Autoloader
 *
 * Automatically loads plugin classes when they are instantiated.
 * Classes should be in the 'includes' or 'src' directory with proper namespace structure.
 *
 * Example:
 * - Namespace: YourPlugin\Admin\Settings
 * - File: includes/Admin/Settings.php
 */
spl_autoload_register(
	function ( $class_name ) {
		// Project-specific namespace prefix.
		$prefix = 'YourPlugin\\';

		// Base directory for the namespace prefix.
		$base_dir = __DIR__ . '/includes/';

		// Check if the class uses the namespace prefix.
		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class_name, $len ) !== 0 ) {
			// No, move to the next registered autoloader.
			return;
		}

		// Get the relative class name.
		$relative_class = substr( $class_name, $len );

		// Replace namespace separators with directory separators.
		// Replace underscores with directory separators (for legacy class names).
		$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		// If the file exists, require it.
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);

// Load plugin constants first (required by other files).
require_once __DIR__ . '/constants.php';

// Load action hooks.
require_once __DIR__ . '/hooks.php';

// Load filter hooks.
require_once __DIR__ . '/filters.php';
