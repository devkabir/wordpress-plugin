<?php

namespace PluginPackage\Api;

use PluginPackage\Helpers\Log;
use PluginPackage\Traits\Api;
use PluginPackage\Traits\Singleton;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class Logs
 *
 * @package PluginPackage\Api
 */
final class Logs {
	use Api, Singleton;


	/**
	 * Register the routes for serving data from custom table
	 */
	public function __construct() {
		register_rest_route(
			$this->namespace,
			'/logs',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_data' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => array(),
				),

			)
		);
		register_rest_route(
			$this->namespace,
			'/logs/clear-all',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'remove_data' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => array(),
				),
			)
		);
	}

	/**
	 * It deletes the log file
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response The response is being returned as an array.
	 */
	public function remove_data( WP_REST_Request $request ) {
		$type = $request->get_param( 'file' );
		unlink( Log::instance()->file( $type ) );

		return rest_ensure_response( array( 'message' => __( 'Log cleared successfully.', 'your-plugin-name' ) ) );
	}

	/**
	 * Get data from custom table
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_data() {
		$contents = $this->read_logs();

		return rest_ensure_response(
			array(
				'items' => $contents,
			)
		);
	}

	/**
	 * It reads the contents of the log file and returns an array of the lines
	 *
	 * @return array
	 */
	private function read_logs(): array {
		$logger     = Log::instance();
		$file_array = array();
		if ( is_dir( $logger->get_dir() ) ) {
			$files = array_diff( scandir( $logger->get_dir() ), array( '.', '..' ) );

			foreach ( $files as $file ) {
				$key                = preg_replace( '/\.log$/', '', $file );
				$file_array[ $key ] = $logger->read( $key );
			}
		}

		return $file_array;
	}
}
