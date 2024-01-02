<?php // phpcs:ignore


namespace PluginPackage\Controllers\Api;

use PluginPackage\Controllers\LogController;
use PluginPackage\Traits\Singleton;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\SLUG' ) ) {
	exit;
}

use PluginPackage\Traits\Api;
use const PluginPackage\SLUG;

class SettingController {
	use Api;
	use Singleton;

	private $route = 'settings';

	private $option = SLUG . '-settings';

	private function __construct() {
		register_rest_route(
			$this->namespace,
			$this->route,
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get' ),
					'permission_callback' => array( $this, 'options_permissions_check' ),
				),
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'post' ),
					'permission_callback' => array( $this, 'options_permissions_check' ),
				),
			)
		);
	}

	public function get() {
		$this->response['data'] = get_option(
			$this->option,
			array(
				'enable' => false,
			)
		);

		return rest_ensure_response( $this->response );
	}

	public function post( \WP_REST_Request $request ) {
		$settings = $request->get_json_params();

		if ( array_key_exists( 'enable', $settings ) ) {
			$this->response['success'] = update_option( $this->option, $settings );

			if ( $this->response['success'] ) {
				$this->response['message'] = __( 'Settings updated successfully', 'your-plugin-name' );
			} else {
				$this->response['message'] = __( 'Same or invalid settings', 'your-plugin-name' );
			}
		}
		$this->response['data'] = get_option( $this->option, array( 'enable' => false ) );
		LogController::instance()->write( 'settings', $this->response );

		return rest_ensure_response( $this->response );
	}
}
