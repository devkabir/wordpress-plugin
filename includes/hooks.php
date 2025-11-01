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

		// Initialize filesystem early so it's available for all plugin features.
		\YourPlugin\Filesystem::get_instance();
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
		// Example: Register custom post type
		// register_post_type('custom_post', $args);

		// Register shortcodes, taxonomies, or other WordPress features here
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
		// Example: Add menu page
		// add_menu_page('Page Title', 'Menu Title', 'manage_options', 'menu-slug', 'callback_function');

		// Add your admin menu pages here
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
		// Example: Enqueue admin stylesheet
		// wp_enqueue_style('admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css');

		// Load your admin scripts and styles here
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
		// Example: Enqueue frontend stylesheet
		// wp_enqueue_style('frontend-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');

		// Load your frontend scripts and styles here
	}
);

/**
 * Hook: save_post
 *
 * Fires after a post has been successfully updated or created.
 * Use this for: Saving custom meta data, triggering notifications, updating related content
 *
 * @param int $post_id The post ID
 * @link https://developer.wordpress.org/reference/hooks/save_post/
 */
add_action(
	'save_post',
	function ( int $post_id ): void {
		// Example: Save custom meta field
		// if (isset($_POST['custom_field'])) {
		// update_post_meta($post_id, 'custom_field_key', sanitize_text_field($_POST['custom_field']));
		// }

		// Add your save post logic here
	}
);

/**
 * Hook: wp_insert_post
 *
 * Fires once a post has been saved (both new and updated posts).
 * Use this for: Similar to save_post, but provides more data via the second parameter
 *
 * @param int $post_id The post ID
 * @link https://developer.wordpress.org/reference/hooks/wp_insert_post/
 */
add_action(
	'wp_insert_post',
	function ( int $post_id ): void {
		// This hook fires for both new and updated posts
		// Use this when you need to run logic on any post save operation
	}
);
