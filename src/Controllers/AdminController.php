<?php // phpcs:ignore

namespace PluginPackage\Controllers;

use PluginPackage\Controllers\Api\SettingController;
use PluginPackage\Traits\Singleton;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\SLUG' ) ) {
	exit;
}
use const PluginPackage\SLUG;
use const PluginPackage\FILE;
use const PluginPackage\VERSION;

class AdminController {


	use Singleton;

	private function __construct() {
		add_action(
			'admin_menu',
			function () {
				add_menu_page(
					__( 'Your Plugin Name', 'your-plugin-name' ),
					__( 'Your Plugin Name', 'your-plugin-name' ),
					'manage_options',
					'your-plugin-name',
					function () {
						wp_enqueue_style( SLUG );
						wp_enqueue_style( SLUG . '-notify' );
						wp_enqueue_script( SLUG );
						add_filter( 'script_loader_tag', array( $this, 'add_module' ), 10, 3 );
						add_filter( 'admin_footer_text', '__return_empty_string', 11 );
						add_filter( 'update_footer', '__return_empty_string', 11 );
						echo wp_kses_post( '<div id="' . SLUG . '">Loading scripts. If you are still here, something went wrong.</div>' );
					},
				);
			}
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * It will add module attribute to script tag.
	 *
	 * @param string $tag of script.
	 * @param string $id of script.
	 *
	 * @return string
	 */
	public function add_module( string $tag, string $id ): string {
		if ( SLUG === $id ) {
			$tag = str_replace( '<script ', '<script type="module" ', $tag );
		}

		return $tag;
	}

	/**
	 * It loads scripts based on plugin's mode, dev or prod.
	 *
	 * @return void
	 */
	public function scripts(): void {
		// comment on production
		wp_register_style( SLUG, 'http://localhost:5000/src/main.css', array(), VERSION );
		wp_register_script( SLUG, 'http://localhost:5000/src/main.js', array(), VERSION, true );
		// comment on production
		wp_register_style( SLUG . '-notify', 'https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css', array(), VERSION );
		wp_register_style( SLUG, plugins_url( 'assets/dist/index.css', FILE ), array(), VERSION );
		wp_register_script( SLUG, plugins_url( 'assets/dist/index.js', FILE ), array(), VERSION, true );
		wp_localize_script(
			SLUG,
			str_replace( '-', '_', SLUG ),
			array(
				'security_token' => wp_create_nonce( 'wp_rest' ),
				'strings'        => $this->strings(),
				'settings_url'   => SettingController::instance()->get_url(),
			),
		);
	}

	private function strings() {
		$string = include plugin_dir_path( FILE ) . 'strings.php';
		return $this->sanitize_array( $string );
	}

	/**
	 * Sanitizes all values in a multidimensional array recursively.
	 *
	 * @param array $array The array to sanitize.
	 * @return array The sanitized array.
	 */
	private function sanitize_array( array &$data ): array {
		foreach ( $data as &$value ) {
			if ( ! is_array( $value ) ) {
				$value = esc_attr( sanitize_text_field( $value ) );
			} else {
				$value = $this->sanitize_array( $value );
			}
		}
		unset( $value ); // Unset the reference to avoid potential bugs
		return $data;
	}
}
