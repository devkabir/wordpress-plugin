<?php

namespace PluginPackage\Web;

use PluginPackage\Traits\Singleton;
use PluginPackage\Traits\FileSystem;
use const PluginPackage\DIR;
use const PluginPackage\URL;
use const PluginPackage\NAME;
use const PluginPackage\MODE;
use const PluginPackage\VERSION;

class Shortcodes {
	use FileSystem, Singleton;

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
						wp_enqueue_style( $handle, 'http://localhost:5173/style.scss', array(), VERSION );
						wp_enqueue_script( $handle, 'http://localhost:5173/' . $tag . '/main.js', array(), VERSION );
						$html_path = DIR . 'assets/website/shortcodes/' . $tag . '/index.html';
					} else {
						wp_enqueue_style( $handle, URL . 'assets/website/dist/style.css', array(), VERSION );
						wp_enqueue_script( $handle, URL . 'assets/website/dist/' . $tag . '.js', array(), VERSION, true );
						$html_path = DIR . '/assets/website/dist/' . $tag . '/index.html';
					}

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
					$contents = $files->get_contents( $html_path );
					if ( MODE === 'dev' ) {
						$contents = str_replace( '<script src="./main.js" type="module"></script>', '', $contents );
					}

					return $contents;
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
