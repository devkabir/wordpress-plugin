<?php

namespace PluginPackage\Admin;

use PluginPackage\Traits\Singleton;
use const PluginPackage\MODE;
use const PluginPackage\NAME;
use const PluginPackage\URL;
use const PluginPackage\VERSION;

final class Menu {
	use Singleton;


	public function register() {
		add_menu_page(
			__( 'Your Plugin Name', 'your-plugin-name' ),
			__( 'PluginPackage', 'your-plugin-name' ),
			'manage_options',
			'your-plugin-name',
			array( $this, 'render' ),
			'dashicons-plugins-checked',
			6
		);
	}

	public function render() {
		add_filter( 'admin_footer_text', '__return_empty_string', 11 );
		add_filter( 'update_footer', '__return_empty_string', 11 );
		echo wp_kses_post( '<div id="' . NAME . '"></div>' );
	}

	public function add_module( $tag, $id ) {
		if ( NAME === $id ) {
			$tag = str_replace( '<script ', '<script type="module" ', $tag );
		}

		return $tag;
	}

	public function scripts() {
		if ( 'dev' === MODE ) {
			wp_enqueue_style( NAME, 'http://localhost:5173/style.scss', array(), VERSION );
			wp_enqueue_script( NAME, 'http://localhost:5173/main.js', array(), VERSION, true );
		} else {
			wp_enqueue_style( NAME, URL . 'assets/admin/dist/index.css', array(), VERSION );
			wp_enqueue_script( NAME, URL . 'assets/admin/dist/index.js', array(), VERSION, true );
		}
		wp_enqueue_style( NAME, URL . 'assets/admin/dist/index.css', array(), VERSION );
		wp_enqueue_script( NAME, URL . 'assets/admin/dist/index.js', array(), VERSION, true );
		wp_localize_script(
			NAME,
			'your_plugin_name',
			array(
				'nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);
	}
}
