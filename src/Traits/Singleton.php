<?php

namespace PluginPackage\Traits;

use PluginPackage\Api\Logs;
use PluginPackage\Admin\Menu;
use PluginPackage\Helpers\Log;
use PluginPackage\Api\Settings;
use PluginPackage\Ajax\Message;
use PluginPackage\Web\Shortcodes;

/**
 * Singleton Trait
 * Provides functionality for creating a singleton instance of a class.
 */
trait Singleton {
	/**
	 *  Singleton instance
	 * @var null|self
	 */
	private static $instance;


	/**
	 * Get the singleton instance of the class.
	 * @return Shortcodes|Menu|Message|Logs|Settings|Log|Singleton
	 */
	public static function instance(): self {
		$args = func_get_args();
		if ( null === self::$instance ) {
			self::$instance = new self( ...$args );
		}

		return self::$instance;
	}


	/**
	 * Prevent cloning of the singleton instance.
	 * @return void
	 */
	final public function __clone() {
	}


	/**
	 * Prevent serializing of the singleton instance.
	 * @return void
	 */
	final public function __wakeup() {
	}
}
