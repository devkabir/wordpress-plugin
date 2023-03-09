<?php

namespace PluginPackage\Traits;

trait Singleton {
	private static $instance;


	public static function instance(): self {
		$args = func_get_args();
		if ( null === self::$instance ) {
			self::$instance = new self( ...$args );
		}

		return self::$instance;
	}


	final public function __clone() {
	}


	final public function __wakeup() {
	}
}
