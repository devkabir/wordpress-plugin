<?php // phpcs:ignore

namespace PluginPackage\Traits;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\SLUG' ) ) {
	exit;
}
use const PluginPackage\SLUG;
trait FileSystem {

	/**
	 * It's returning the path to the log file.
	 *
	 * @param string $type log type.
	 * @param string $extension file extension.
	 *
	 * @return string the file path depends on a log type.
	 */
	private function file( string $type, string $extension = 'log' ): string {
		return $this->get_dir() . $type . '.' . $extension;
	}


	/**
	 * Initialize the WP file system.
	 *
	 * @return mixed
	 */
	private function load_filesystem() {
			global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
	}
	/**
	 * Retrieves the directory path for the message files.
	 *
	 * @return string The directory path for the message files.
	 */
	private function get_dir(): string {
		$this->load_filesystem();
		global $wp_filesystem;
		$upload_dir  = wp_upload_dir();
		$message_dir = $upload_dir['basedir'] . '/' . SLUG . '-files/';
		if ( ! file_exists( $message_dir ) ) {
			$wp_filesystem->mkdir( $message_dir, FS_CHMOD_DIR );
		}

		return $message_dir;
	}

	private function read_file( $path ) {
		$this->load_filesystem();
		global $wp_filesystem;
		if ( ! file_exists( $path ) ) {
			return false;
		}
		$this->load_filesystem();
		return $wp_filesystem->get_contents( $path );
	}

	private function write_file( $path, $content ) {
		$this->load_filesystem();
		global $wp_filesystem;
		return $wp_filesystem->put_contents( $path, $content, FS_CHMOD_FILE );
	}

	private function delete_file( $path ) {
		$this->load_filesystem();
		global $wp_filesystem;
		if ( ! file_exists( $path ) ) {
			return false;
		}
		return $wp_filesystem->delete( $path );
	}
}
