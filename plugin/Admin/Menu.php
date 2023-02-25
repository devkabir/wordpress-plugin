<?php
/**
 * It will provide a nice ui for managing this plugin.
 *
 * @package PluginPackage
 */

namespace PluginPackage\Admin;

use DevKabir\Plugin\Container;
use DevKabir\Plugin\Loader;
use const PluginPackage\NAME;
use const PluginPackage\URL;
use const PluginPackage\VERSION;

/**
 * Class Menu
 *
 * @package PluginPackage\Admin
 */
class Menu extends Container {



	/**
	 * It sets the name, version, url, and root properties of the class, then registers the `register` and `scripts` methods
	 * as actions
	 */
	protected function __construct() {
		$loader = Loader::get_instance();
		$loader->set_action(
			'admin_print_scripts',
			function () {
				global $wp_filter;
				if ( is_user_admin() ) {
					if ( isset( $wp_filter['user_admin_notices'] ) ) {
						unset( $wp_filter['user_admin_notices'] );
					}
				} elseif ( isset( $wp_filter['admin_notices'] ) ) {
					unset( $wp_filter['admin_notices'] );
				}
				if ( isset( $wp_filter['all_admin_notices'] ) ) {
					unset( $wp_filter['all_admin_notices'] );
				}
			}
		);
		$loader->set_action( 'admin_menu', array( $this, 'register' ) );
		$loader->set_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

	}

	/**
	 * It adds a menu page to the admin panel.
	 */
	public function register(): void {
		add_menu_page(
			__( 'Your Plugin Name', NAME ),
			__( 'PluginPackage', NAME ),
			'manage_options',
			NAME,
			array( $this, 'render' ),
			'dashicons-plugins-checked',
			6
		);
	}
	/**
	 * We're enqueuing our CSS and JS files, and then we're adding a filter to the script tag to make sure that the JS file is
	 * loaded as a module
	 */
	public function scripts(): void {
		wp_enqueue_style(
			NAME,
			URL . 'assets/admin/dist/index.css',
			array(),
			VERSION
		);
		wp_enqueue_script(
			NAME,
			URL . 'assets/admin/dist/index.js',
			array(),
			VERSION,
			true
		);
		wp_enqueue_style(
			NAME . '-notyf',
			'https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css',
			array(),
			VERSION
		);
		add_filter(
			'script_loader_tag',
			function ( $tag, $id ) {
				if ( NAME === $id ) {
					$tag = str_replace( '<script ', '<script type="module" ', $tag );
				}

				return $tag;
			},
			10,
			3
		);
		wp_localize_script(
			NAME,
			'your_plugin_name',
			array(
				'nonce1' => wp_create_nonce( 'your_plugin_name_1' ),
				'nonce2' => wp_create_nonce( 'wp_rest' ),
				'nonce3' => wp_create_nonce( 'your_plugin_name_3' ),
			)
		);

	}

	/**
	 * It renders the plugin.
	 */
	public function render(): void {
		echo '<div id="' . NAME . '"></div>';
	}


}
