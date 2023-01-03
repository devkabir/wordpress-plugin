<?php
/**
 * It will manage shortcodes for website
 *
 * @package PluginPackage
 */

namespace PluginPackage\Web;

use DevKabir\Plugin\Container;
use DevKabir\Plugin\Loader;
use const PluginPackage\NAME;
use const PluginPackage\ROOT;
use const PluginPackage\URL;
use const PluginPackage\VERSION;

/**
 * Class Shortcodes
 *
 * @package PluginPackage\Web
 */
class Shortcodes extends Container {




	/**
	 * It takes an array of shortcode tags, and for each tag, it adds a shortcode callback that calls the `scripts` and
	 * `render` methods
	 *
	 * @param array $tags An array of shortcode tags.
	 */
	protected function __construct( $tags ) {
		Loader::get_instance()->set_action(
			'init',
			function () use ( $tags ) {
				foreach ( $tags as $tag ) {
					add_shortcode(
						$tag,
						function () use ( $tag ) {
							$this->scripts( $tag );
							$this->render( $tag );
						}
					);
				}
			}
		);

	}

	/**
	 * It registers a script and a style, and then adds a filter to the script tag to make it a module for specific shortcode
	 *
	 * @param string $tag The name of the shortcode.
	 */
	private function scripts( $tag ) {
		$handle = NAME . '-' . $tag;
		wp_register_style( $handle, URL . 'assets/website/dist/style.css', array(), VERSION );
		wp_register_script( $handle, URL . 'assets/website/dist/' . $tag . '.js', array(), VERSION, true );
		add_filter(
			'script_loader_tag',
			function ( $tag, $id ) use ( $handle ) {
				if ( $handle === $id ) {
					$tag = str_replace( '<script ', '<script type="module" ', $tag );
				}

				return $tag;
			},
			10,
			3
		);
	}

	/**
	 * It enqueues the styles and scripts for a given tag, then includes the index.html file for that tag
	 *
	 * @param string $tag name of the tag.
	 *
	 * @return string The contents of the index.html file.
	 */
	private function render( $tag ) {
		wp_enqueue_style( NAME . '-' . $tag );
		wp_enqueue_script( NAME . '-' . $tag );
		ob_start();
		include ROOT . '/assets/website/dist/' . $tag . '/index.html';
		return ob_get_contents();
	}
}
