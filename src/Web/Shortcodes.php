<?php

namespace PluginPackage\Web;

// If this file is called directly, abort.
if ( ! defined( 'PluginPackage\NAME' ) ) {
	exit;
}

use PluginPackage\Traits\Singleton;
use PluginPackage\Traits\FileSystem;
use const PluginPackage\NAME;
use const PluginPackage\MODE;
use const PluginPackage\FILE;
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
		$files = $this->filesystem();
		foreach ( $tags as $tag ) {
			add_shortcode(
				$tag,
				static function () use ( $files, $tag ) {
					$handle = NAME . '-' . $tag;
					if ( 'dev' === MODE ) {
						$style_path  = 'http://localhost:5173/style.scss';
						$script_path = 'http://localhost:5173/' . $tag . '/main.js';
						$html_path   = 'assets/website/shortcodes/' . $tag . '/index.html';
					} else {
						$style_path  = plugins_url( 'assets/website/dist/style.css', FILE );
						$script_path = plugins_url( 'http://localhost:5173/' . $tag . '/main.js', FILE );
						$html_path   = '/assets/website/dist/' . $tag . '/index.html';
					}
					wp_enqueue_style( $handle, $script_path, array(), VERSION );
					wp_enqueue_script( $handle, $script_path, array(), VERSION, true );
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

					return $files->get_contents( plugin_dir_path( FILE . $html_path ) );
				}
			);
		}
		add_action(
			'wp_footer',
			function () {
				?>
				<script>
					const your_plugin_name =
					<?php
					echo wp_json_encode(
						array(
							'ajax_url' => admin_url( 'admin-ajax.php' ),
							'nonce'    => wp_create_nonce( NAME ),
						)
					)
					?>
				</script>
				<?php
			}
		);
	}
}
