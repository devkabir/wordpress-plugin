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
	private $api     = 'https://example.com/api/org/license';

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
			'url'     => $site_url,
			'action'  => $this->get_current_action(),
			'version' => VERSION,
			'name'    => NAME,
		);

		$headers = array(
			'user-agent'    => 'PluginPackage;' . password_hash( $site_url, PASSWORD_BCRYPT ),
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
}
