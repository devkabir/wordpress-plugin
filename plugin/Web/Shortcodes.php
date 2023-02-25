<?php
/**
 * It will manage shortcodes for website
 *
 * @package PluginPackage
 */

namespace PluginPackage\Web;

use DevKabir\Plugin\Container;
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
	 * @param array $tags  An array of shortcode tags.
	 */
	protected function __construct( array $tags ) {

		foreach ( $tags as $tag ) {
			add_shortcode(
				$tag,
				static function () use ( $tag ) {
					$handle = NAME . '-' . $tag;
					wp_enqueue_style( $handle, URL . 'assets/website/dist/style.css', array(), VERSION );
					wp_enqueue_script( $handle, URL . 'assets/website/dist/' . $tag . '.js', array(), VERSION, true );
					wp_localize_script(
						$handle,
						'you_plugin_name',
						array(
							'ajax_url' => admin_url( 'admin-ajax.php' ),
							'nonce'    => wp_create_nonce( $tag ),
						)
					);
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

					return file_get_contents( ROOT . '/assets/website/dist/' . $tag . '/index.html' );
				}
			);
		}
	}
}
