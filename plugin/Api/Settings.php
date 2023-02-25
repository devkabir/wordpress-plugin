<?php
/**
 * Manage settings
 *
 * @package PluginPackage\Api
 * @subpackage Component
 * @since 1.0.0
 */

namespace PluginPackage\Api;

/* This is a security measure to prevent direct access to the plugin file. */

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

/**
 * Class Settings
 * @package PluginPackage\Api
 */
final class Settings extends ApiBase {
	/**
	 * Register the routes for serving data from custom table
	 */
	public function __construct() {
		register_rest_route(
			$this->namespace,
			'/settings',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'settings' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => array(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/settings',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'settings' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => array(),
				),
			)
		);
	}

	/**
	 * Get data from custom table
	 *
	 * @param  WP_REST_Request $request  Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function settings( WP_REST_Request $request ) {
		$option_key = 'your-plugin-name-settings';
		$options    = get_option( $option_key, array() );
		if ( $request->get_method() === WP_REST_Server::CREATABLE ) {
			$update          = array();
			$update['field_1'] = $request->get_param( 'field_1' ) === 'true';
			$options         = array_merge( $options, $update );
			update_option( $option_key, $options );
		}

		return rest_ensure_response( $options );
	}
}