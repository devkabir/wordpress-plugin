<?php

namespace PluginPackage\Traits;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\NAME' ) ) {
	exit;
}

use WP_Error;

trait Api {
	/**
	 * The namespace of the API.
	 *
	 * @var string
	 */
	protected $namespace = 'your-plugin-name/v1';


	/**
	 * Check if a given request has access to get data from custom table
	 *
	 * @return WP_Error|bool
	 */
	public function permissions_check() {
		return true;
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_cannot_create',
				__( 'Sorry, you are not allowed to use this endpoint' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}
}
