<?php

namespace PluginPackage\Api;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\NAME' ) ) {
	exit;
}

use WP_Error;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use PluginPackage\Traits\Api;
use PluginPackage\Helpers\Log;
use PluginPackage\Traits\Singleton;

final class Settings {
	use Api, Singleton;

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
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function settings( WP_REST_Request $request ) {
		$option_key = 'your-plugin-name-settings';
		$options    = get_option( $option_key, array() );
		if ( $request->get_method() === WP_REST_Server::CREATABLE ) {
			$update          = array();
			$update['track'] = $request->get_param( 'track' ) === 'true';
			$options         = array_merge( $options, $update );
			update_option( $option_key, $options );
			Log::instance()->write( 'settings', 'Settings updated by ' . get_current_user_id() );
		}

		return rest_ensure_response(
			array(
				'data'    => $options,
				'message' => __( 'Settings Updated', 'your-plugin-name' ),
			)
		);
	}
}
