<?php
/**
 * Collection of logs
 *
 * @package PluginPackage\Api
 * @subpackage Component
 * @since 1.0.0
 */

namespace PluginPackage\Api;

/* This is a security measure to prevent direct access to the plugin file. */

use DevKabir\Plugin\Log;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

/**
 * Class Logs
 * @package PluginPackage\Api
 */
final class Logs extends ApiBase {
	/**
	 * Instance of log class
	 * @var Log
	 */
	private $log;

	/**
	 * Register the routes for serving data from custom table
	 */
	public function __construct() {
		$this->log = Log::get_instance();
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
		unlink( $this->log->file( $type ) );

		return rest_ensure_response( array( 'message' => 'Log cleared successfully.' ) );
	}

	/**
	 * It reads the contents of the log file and returns an array of the lines
	 *
	 * @return array
	 */
	private function read_logs(): array {
		$file_array = array();
		if ( is_dir( $this->log->get_dir() ) ) {
			$files = array_diff( scandir( $this->log->get_dir() ), array( '.', '..' ) );

			foreach ( $files as $file ) {
				$key                = preg_replace( '/\.log$/', '', $file );
				$file_array[ $key ] = $this->log->read( $key );
			}
		}

		return $file_array;
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
}