<?php


namespace PluginPackage\Helpers;

use PluginPackage\Traits\Singleton;
use const PluginPackage\FILE;
use const PluginPackage\NAME;
use const PluginPackage\VERSION;

/**
 * LicenseManager
 */
final class LicenseManager {
	use Singleton;

	public const KEY = NAME . '-license-key';
	private $api = 'https://example.com/api/org/license';

	/**
	 * LicenseManager constructor.
	 *
	 */
	private function __construct() {
		$this->register();
	}


	/**
	 * Register this plugin user to support and license system.
	 *
	 * @return bool Returns true if the registration is successful, false otherwise.
	 */
	private function register() {
		$site_url = esc_url( home_url() );

		$data = array(
			'url' => $site_url,
			'action' => $this->get_current_action(),
			'plugins' => $this->get_active_plugins(),
			'server' => $this->get_server_data(),
			'version' => VERSION,
			'name' => NAME,
		);

		$headers = array(
			'user-agent' => 'PluginPackage;' . password_hash( $site_url, PASSWORD_BCRYPT ),
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Origin' => $site_url,
			'Referer' => $site_url,
			'Cache-Control' => 'no-cache',
		);

		$response = wp_remote_post(
			$this->api,
			array(
				'timeout' => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'headers' => $headers,
				'body' => wp_json_encode( $data ),
				'sslverify' => false,
				'cookies' => array(),
			)
		);
		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( false === get_option( self::KEY ) ) {
			$response = wp_remote_retrieve_body( $response );
			update_option( self::KEY, $response );
		}
		return true;
	}

	/**
	 * Get the current action.
	 *
	 * @return string The current action.
	 */
	private function get_current_action(): string {
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );

		return $backtrace[4]['function'];
	}

	/**
	 * Retrieves an array of active plugins.
	 *
	 * This function retrieves an array of all active plugins in WordPress.
	 * It first checks if the `get_plugins` function exists. If not, it includes
	 * the `plugin.php` file from the WordPress admin directory.
	 * It then calls the `get_plugins` function to retrieve an array of all plugins.
	 * It initializes an empty array called `$active_plugins` to store the active plugins.
	 * It also retrieves the list of active plugin keys from the `active_plugins` option.
	 *
	 * For each plugin in the `$plugins` array, it checks if the plugin file is the same as the
	 * current file. If so, it skips the iteration.
	 * It then extracts the name, version, and network status from the plugin data and stores it
	 * in the `$formatted` array.
	 * If the plugin is active (i.e., its file key is present in the `active_plugins` array),
	 * it adds the plugin to the `$active_plugins` array using the plugin's directory name as the key.
	 *
	 * @return array An associative array of active plugins, where the keys are the plugin directory names
	 *               and the values are arrays containing the plugin's name, version, and network status.
	 */
	private function get_active_plugins(): array {
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		$active_plugins = array();
		$active_plugins_keys = get_option( 'active_plugins', array() );

		foreach ( $plugins as $plugin_file => $plugin_data ) {
			if ( plugin_basename( FILE ) === $plugin_file ) {
				continue;
			}
			// Extract the name, version, author, network, and plugin URI from the plugin data.
			$formatted = array(
				'name' => wp_strip_all_tags( $plugin_data['Name'] ),
				'version' => array_key_exists( 'Version',
					$plugin_data ) ? wp_strip_all_tags( $plugin_data['Version'] ) : '-',
				'network' => array_key_exists( 'Network',
					$plugin_data ) ? wp_strip_all_tags( $plugin_data['Network'] ) : false,
			);
			// If a plugin is active, add it to the `active_plugins` array.
			if ( in_array( $plugin_file, $active_plugins_keys, true ) ) {
				$active_plugins[ explode( '/', $plugin_file )[0] ] = $formatted;
			}
		}

		return $active_plugins;
	}

	/**
	 * Returns an array containing server data.
	 *
	 * This function retrieves information about the server environment,
	 * such as the software version, PHP version, and MySQL version.
	 * The server data array is then returned.
	 *
	 * @return array An array containing server data.
	 */
	private function get_server_data(): array {
		global $wpdb;

		$server_data = array();

		if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$server_data['software'] = $_SERVER['SERVER_SOFTWARE'];
		}

		if ( function_exists( 'phpversion' ) ) {
			$server_data['php'] = phpversion();
		}

		$server_data['mysql'] = $wpdb->db_version();

		return $server_data;
	}
}