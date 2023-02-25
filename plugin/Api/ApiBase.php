<?php
/**
 * Base class for all apis
 *
 * @package PluginPackage\Api
 * @subpackage Component
 * @since 1.0.0
 */

namespace PluginPackage\Api;

/* This is a security measure to prevent direct access to the plugin file. */

use DevKabir\Plugin\Container;
use WP_Error;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

/**
 * Class Base
 * @package PluginPackage\Api
 */
class ApiBase extends Container {
	/**
	 * The namespace of the API.
	 *
	 * @var string
	 */
	protected string $namespace = 'your-plugin-name/v1';

	/**
	 * It takes an array of items, sorts them, paginates them, and returns the paginated items
	 *
	 * @param string      $sort The column to sort by.
	 * @param array       $items The array of items to paginate.
	 * @param string      $sort_type asc or desc.
	 * @param int         $current_page The current page number.
	 * @param int         $per_page The number of items to show per page.
	 * @param string|null $columns A comma-separated list of columns to return.
	 *
	 * @return array An array with the following keys:
	 * - items
	 * - total_items
	 * - total_pages
	 */
	protected static function paginate_items( string $sort, array $items, string $sort_type, int $current_page, int $per_page, ?string $columns ): array {
		$result = array(
			'items'       => array(),
			'total_items' => 0,
			'total_pages' => 0,
		);
		if ( is_array( $items ) ) {
			$total_items = count( $items );
			if ( $total_items > 0 ) {
				if ( isset( $sort ) ) {
					usort(
						$items,
						static function ( $a, $b ) use ( $sort, $sort_type ) {
							$result = strnatcasecmp( $a[ $sort ], $b[ $sort ] );
							if ( isset( $sort_type ) ) {
								return 'asc' !== $sort_type ? - $result : $result;
							}

							return $result;
						}
					);
				}
				if ( isset( $current_page ) ) {
					$current_page --;
				} else {
					$current_page = 0;
				}

				$items       = array_chunk( $items, $per_page );
				$total_pages = count( $items );
				$items       = $items[ $current_page ];
				if ( isset( $columns ) ) {
					$items = array_map(
						static function ( $row ) use ( $columns ) {
							return array_intersect_key( $row, array_flip( explode( ',', $columns ) ) );
						},
						$items
					);
				}
				$result = array(
					'items'       => $items,
					'total_items' => $total_items,
					'total_pages' => $total_pages,
				);
			}
		}

		return $result;

	}

	/**
	 * Check if a given request has access to get data from custom table
	 *
	 * @return WP_Error|bool
	 */
	public function permissions_check() {
		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '-' ) );
		if ( $ip !== '127.0.0.1' ) {
			return new WP_Error(
				'rest_forbidden',
				esc_html__( 'REST is forbidden for visitors', 'your-plugin-name' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}
}