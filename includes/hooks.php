<?php
/**
 * Plugin Hooks
 *
 * This file registers all WordPress action hooks for the plugin.
 * Add your hook callbacks here to execute code at specific points in the WordPress lifecycle.
 *
 * @package Your_Plugin_Name
 * @author  Firstname Lastname
 * @link    https://developer.wordpress.org/reference/hooks/
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook: plugins_loaded
 *
 * Fires once all activated plugins have loaded.
 * Use this for: Initializing plugin features, loading text domains, checking dependencies
 *
 * @link https://developer.wordpress.org/reference/hooks/plugins_loaded/
 */
add_action(
	'plugins_loaded',
	function (): void {
	}
);

/**
 * Hook: init
 *
 * Fires after WordPress has finished loading but before any headers are sent.
 * Use this for: Registering custom post types, taxonomies, shortcodes, or handling form submissions
 *
 * @link https://developer.wordpress.org/reference/hooks/init/
 */
add_action(
	'init',
	function (): void {
	}
);

/**
 * Hook: admin_menu
 *
 * Fires before the administration menu loads in the admin.
 * Use this for: Adding custom admin menu pages and submenus
 *
 * @link https://developer.wordpress.org/reference/hooks/admin_menu/
 */
add_action(
	'admin_menu',
	function (): void {
	}
);

/**
 * Hook: admin_enqueue_scripts
 *
 * Fires when scripts and styles are enqueued in the admin area.
 * Use this for: Loading CSS/JS files only on admin pages
 *
 * @param string $hook The current admin page hook
 * @link https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
 */
add_action(
	'admin_enqueue_scripts',
	function (): void {
	}
);

/**
 * Hook: wp_enqueue_scripts
 *
 * Fires when scripts and styles are enqueued on the frontend.
 * Use this for: Loading CSS/JS files for the public-facing site
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 */
add_action(
	'wp_enqueue_scripts',
	function (): void {
	}
);
