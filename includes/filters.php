<?php
/**
 * Plugin Filters
 *
 * This file registers all WordPress filter hooks for the plugin.
 * Add your filter callbacks here to modify data at specific points in the WordPress lifecycle.
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
 * Filter: sanitize_file_name
 *
 * Filters a sanitized filename string.
 * Use this for: Customizing how uploaded file names are sanitized
 *
 * @param string $filename The sanitized filename
 * @return string Modified filename
 * @link https://developer.wordpress.org/reference/hooks/sanitize_file_name/
 */
add_filter(
	'sanitize_file_name',
	function ( string $filename ): string {
		// Example: Convert filename to lowercase
		// $filename = strtolower($filename);

		// Modify filename sanitization here
		return $filename;
	}
);

/**
 * Filter: admin_footer_text
 *
 * Filters the admin footer text.
 * Use this for: Customizing the WordPress admin footer message
 *
 * @param string $text The content that will be printed
 * @return string Modified footer text
 * @link https://developer.wordpress.org/reference/hooks/admin_footer_text/
 */
add_filter(
	'admin_footer_text',
	function ( string $text ): string {
		// Example: Custom admin footer
		// return 'Thank you for using our plugin!';

		// Return your custom footer text
		return $text;
	}
);
