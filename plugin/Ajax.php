<?php
/**
 * It will handle all ajax callbacks registered in Plugin class.
 *
 * @package PluginPackage
 */

namespace PluginPackage;

use DevKabir\Plugin\Container;

/**
 * Class Ajax
 *
 * @package PluginPackage
 */
final class Ajax extends Container {
	/**
	 * It sends a json success message.
	 */
	public function settings() {
		wp_send_json_success( 'Saved' );
	}
}
