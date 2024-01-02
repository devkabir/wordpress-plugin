<?php // phpcs:ignore

namespace PluginPackage\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\SLUG' ) ) {
	exit;
}

use PluginPackage\Traits\Singleton;
use PluginPackage\Traits\FileSystem;

final class LogController {
	use Singleton;
	use FileSystem;

	/**
	 * It's writing the log to a file.
	 *
	 * @param string $type of message.
	 * @param mixed $message message to write.
	 */
	public function write( string $type, $message ) {
		try {
			$file = $this->file( $type );

			if ( is_array( $message ) || is_object( $message ) || is_iterable( $message ) ) {
				$message = wp_json_encode( $message, JSON_PRETTY_PRINT );
			} else {
				$decoded = json_decode( $message, true );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					$message = wp_json_encode( $decoded, JSON_PRETTY_PRINT );
				}
			}
			$date    = sprintf( '[%s]::', gmdate( 'd-M-Y h:i:s A' ) );
			$message = $this->read( $type ) . PHP_EOL . $date . sanitize_textarea_field( $message );

			return $this->write_file( $file, $message );
		} catch ( \Throwable $th ) {
			throw $th;
		}
	}


	/**
	 * It's reading the log file and returning the content.
	 *
	 * @param string $type log type.
	 *
	 * @return string logs
	 */
	public function read( string $type ) {
		$file = $this->file( $type );

		return $this->read_file( $file );
	}


	/**
	 * It's deleting the log file.
	 *
	 * @param string $type log type.
	 *
	 * @return bool
	 */
	public function delete( string $type ) {
		$file = $this->file( $type );
		return $this->delete_file( $file );
	}
}
