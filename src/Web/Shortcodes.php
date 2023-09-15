<?php

namespace PluginPackage\Web;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\NAME' ) ) {
	exit;
}

use PluginPackage\Traits\FileSystem;
use PluginPackage\Traits\Singleton;
use const PluginPackage\FILE;
use const PluginPackage\MODE;
use const PluginPackage\NAME;
use const PluginPackage\VERSION;

class Shortcodes {
	use FileSystem;
	use Singleton;


	/**
	 * It takes an array of shortcode tags, and for each tag, it adds a shortcode callback that calls the `scripts` and
	 * `render` methods
	 *
	 * @param array $tags An array of shortcode tags.
	 */
	protected function __construct( array $tags ) {
		// Load script.
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		// Add module attribute.
		add_filter( 'script_loader_tag', array( $this, 'add_module' ), 10, 3 );
		//      Register all shortcodes
		$this->register( $tags );
	}

	private function register( array $tags ) {
		foreach ( $tags as $tag ) {
			add_shortcode(
				$tag,
				function () use ( $tag ) {
					wp_enqueue_style( NAME );
					wp_enqueue_script( NAME );

					return sprintf( '<div class="%s"><div id="%s"></div></div>', NAME, $tag );
				}
			);
		}
	}

	/**
	 * It loads scripts based on plugin's mode, dev or prod.
	 *
	 * @return void
	 */
	public function scripts() {
		if ( 'dev' === MODE ) {
			$style_path  = 'http://localhost:4000/style.css';
			$script_path = 'http://localhost:4000/main.js';
		} else {
			$style_path  = plugins_url( 'assets/website/dist/index.css', FILE );
			$script_path = plugins_url( 'assets/website/dist/index.js', FILE );
		}
		wp_register_style( NAME, $style_path, array(), VERSION );
		wp_register_script( NAME, $script_path, array(), VERSION );
		wp_localize_script(
			NAME,
			str_replace( '-', '_', NAME ),
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( NAME ),
			)
		);
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
		if ( NAME === $id ) {
			$tag = str_replace( '<script ', '<script type="module" ', $tag );
		}

		return $tag;
	}
}
