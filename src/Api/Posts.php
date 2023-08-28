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
use WP_HTTP_Response;
use PluginPackage\Traits\Api;
use PluginPackage\Helpers\Log;
use PluginPackage\Traits\Singleton;

/**
 * Class Posts
 *
 * @package PluginPackage\Api
 */
final class Posts {
	use Api;
	use Singleton;


	/**
	 * Register the routes for serving data from custom table
	 */
	public function __construct() {
		register_rest_route(
			$this->namespace,
			'/posts',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_data' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => array(),
				),

			)
		);
	}

	/**
	 * Get data from custom table
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_data() {
		$defaults = array(
			'numberposts' => 100,
		);
		return rest_ensure_response( get_posts( $defaults ) );
	}
}
