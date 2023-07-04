<?php

namespace PluginPackage\Helpers;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\NAME' ) ) {
	exit;
}

use PluginPackage\Traits\Singleton;
use PluginPackage\Traits\FileSystem;
use const PluginPackage\NAME;

final class Log {
	use Singleton, FileSystem;


	/**
	 * It's writing the log to a file.
	 *
	 * @param string $type of message.
	 * @param mixed $message message to write.
	 */
	public function write( string $type, $message ) {
		$filesystem = $this->filesystem();
		$file       = $this->file( $type );

		if ( is_array( $message ) || is_object( $message ) || is_iterable( $message ) ) {
			$message = wp_json_encode( $message, JSON_PRETTY_PRINT );
		} else {
			$decoded = json_decode( $message, true );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				$message = wp_json_encode( $decoded, JSON_PRETTY_PRINT );
			}
		}
		$date    = sprintf( '[%s]:: ', current_time( 'd-m-Y h:i a' ) );
		$message = PHP_EOL . $date . sanitize_textarea_field( $message );

		return $filesystem->put_contents( $file, $this->read( $type ) . $message, 0644 );
	}


	/**
	 * It's returning the path to the log file.
	 *
	 * @param string $type log type.
	 *
	 * @return string the file path depends on a log type.
	 */
	public function file( string $type ): string {
		return $this->get_dir() . '/' . $type . '.log';
	}

	/**
	 * It's returning the path to the log file.
	 *
	 * @return string path of the log directory.
	 */
	public function get_dir(): string {
		$filesystem  = $this->filesystem();
		$upload_dir  = wp_upload_dir();
		$message_dir = $upload_dir['basedir'] . '/' . NAME . '-files/';
		if ( ! file_exists( $message_dir ) ) {
			$filesystem->mkdir( $message_dir, 0777 );
		}

		return $message_dir;
	}

	/**
	 * It's reading the log file and returning the content.
	 *
	 * @param string $type log type.
	 *
	 * @return string logs
	 */
	public function read( string $type ) {
		$filesystem = self::filesystem();
		$file       = $this->file( $type );

		if ( file_exists( $file ) ) {
			$contents = sanitize_textarea_field( $filesystem->get_contents( $file ) );
			if ( empty( $contents ) ) {
				$log = false;
			} else {
				$log = $contents;
			}
		} else {
			$log = false;
		}

		return $log;
	}


}
