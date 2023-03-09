<?php

namespace PluginPackage\Traits;

use WP_Error;

trait Api {
	/**
	 * The namespace of the API.
	 *
	 * @var string
	 */
	protected $namespace = 'your-plugin-name/v1';

	/**
	 * It takes an array of items, sorts them, paginates them, and returns the paginated items
	 *
	 * @param string $sort The column to sort by.
	 * @param array $items The array of items to paginate.
	 * @param string $sort_type asc or desc.
	 * @param int $current_page The current page number.
	 * @param int $per_page The number of items to show per page.
	 * @param string|null $columns A comma-separated list of columns to return.
	 *
	 * @return array An array with the following keys:
	 * - items
	 * - total_items
	 * - total_pages
	 */
	private static function paginate_items( string $sort, array $items, string $sort_type, int $current_page, int $per_page, ?string $columns ): array {
		$result = array(
			'items'       => array(),
			'total_items' => 0,
			'total_pages' => 0,
		);

		$total_items = count( $items );

		if ( $total_items > 0 ) {
			$sort_type_multiplier = 'asc' === $sort_type ? 1 : - 1;
			usort(
				$items,
				static function ( $a, $b ) use ( $sort, $sort_type_multiplier ) {
					return $sort_type_multiplier * strnatcasecmp( $a[ $sort ], $b[ $sort ] );
				}
			);

			$current_page = empty( $current_page ) ? 0 : $current_page - 1;

			$start_index = $current_page * $per_page;
			$items       = array_slice( $items, $start_index, $per_page );

			if ( isset( $columns ) ) {
				$columns = explode( ',', $columns );
				$items   = array_map(
					static function ( $row ) use ( $columns ) {
						return array_intersect_key( $row, array_flip( $columns ) );
					},
					$items
				);
			}

			$total_pages = ceil( $total_items / $per_page );

			$result = array(
				'items'       => $items,
				'total_items' => $total_items,
				'total_pages' => $total_pages,
			);
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
		if ( '127.0.0.1' !== $ip ) {
			return new WP_Error(
				'rest_forbidden',
				esc_html__( 'REST is forbidden for visitors', 'your-plugin-name' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}
}
