<?php // phpcs:ignore

namespace PluginPackage\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\SLUG' ) ) {
	exit;
}

use const PluginPackage\SLUG;
use const PluginPackage\VERSION;

/**
 * LicenseManager
 */
final class LicenseController {
	private $api = 'https://example.com/wp-json/' . SLUG . '/license';

	/**
	 * LicenseManager constructor.
	 *
	 */
	public function __invoke() {
		$site_url = esc_url_raw( home_url() );

		$data = array(
			'url'     => $site_url,
			'action'  => $this->get_current_action(),
			'version' => VERSION,
			'name'    => SLUG,
		);

		$headers = array(
			'user-agent'    => SLUG . ';' . password_hash( $site_url, PASSWORD_BCRYPT ),
			'Accept'        => 'application/json',
			'Content-Type'  => 'application/json',
			'Origin'        => $site_url,
			'Referer'       => $site_url,
			'Cache-Control' => 'no-cache',
		);

		$response = wp_remote_post(
			$this->api,
			array(
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'headers'     => $headers,
				'body'        => wp_json_encode( $data ),
				'sslverify'   => false,
				'cookies'     => array(),
			)
		);

		if ( false === is_wp_error( $response ) && false === get_option( SLUG . '-license-key' ) ) {
			$response = wp_remote_retrieve_body( $response );
			update_option( SLUG . '-license-key', $response );
		}
	}

	/**
	 * Get the current action.
	 *
	 * @return string The current action.
	 */
	private function get_current_action(): string {
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ); // phpcs:ignore

		return $backtrace[4]['function'];
	}
}
