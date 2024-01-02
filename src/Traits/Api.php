<?php // phpcs:ignore

namespace PluginPackage\Traits;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\SLUG' ) ) {
	exit;
}

use WP_Error;
use WP_REST_Response;
use const PluginPackage\SLUG;
use const PluginPackage\VERSION;
trait Api {
	/**
	 * The namespace of the API.
	 *
	 * @var string
	 */
	private $namespace = SLUG . '/v' . VERSION;
	/**
	 * Base response.
	 * @var array
	 */
	private $response = array(
		'success' => true,
		'message' => '',
		'data'    => array(),
	);


	/**
	 * Retrieves the URL for the API endpoint.
	 *
	 * @return string The URL for the API endpoint.
	 */
	public function get_url() {
		return get_rest_url() . $this->namespace . '/' . $this->route;
	}


	/**
	 * Check if a given request has access to get data from custom table
	 *
	 * @return WP_Error|bool
	 */
	public function options_permissions_check() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to use this endpoint' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}
}
