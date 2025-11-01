<?php
/**
 * Plugin Uninstall Handler
 *
 * This file is executed when the plugin is uninstalled (deleted) from WordPress.
 * It should clean up all plugin data including options, transients, custom tables, and files.
 *
 * IMPORTANT:
 * - This file runs when the plugin is DELETED, not deactivated
 * - For deactivation tasks, use register_deactivation_hook() in hooks.php
 * - Always check for the WP_UNINSTALL_PLUGIN constant for security
 *
 * @package Your_Plugin_Name
 * @author  Firstname Lastname
 * @link    https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
 */

// Exit if accessed directly or not uninstalling.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Flush rewrite rules.
flush_rewrite_rules();
