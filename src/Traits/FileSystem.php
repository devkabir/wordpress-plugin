<?php

namespace PluginPackage\Traits;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\NAME' ) ) {
	exit;
}

trait FileSystem {
	/**
	 * It will hold wp file system instance.
	 * @var null
	 */
	private $filesystem = null;

	/**
	 * Initialize the WP file system.
	 *
	 * @return mixed
	 */
	private function filesystem() {
		if ( null === $this->filesystem ) {
			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$this->filesystem = $wp_filesystem;
		}

		return $this->filesystem;
	}
}
