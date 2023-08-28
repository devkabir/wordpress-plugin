<?php

namespace PluginPackage\Traits;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\NAME' ) ) {
	exit;
}



trait Singleton {
	/**
	 *  Singleton instance
	 * @var null|self
	 */
	private static $instance;


	/**
	 * Get the singleton instance of the class.
	 * @return self
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
