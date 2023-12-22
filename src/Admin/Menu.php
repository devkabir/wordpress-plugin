<?php

namespace PluginPackage\Admin;

// If this file is called directly, abort.
if (!defined('PluginPackage\NAME')) {
	exit;
}

use PluginPackage\Traits\Singleton;
use const PluginPackage\FILE;
use const PluginPackage\NAME;
use const PluginPackage\MODE;
use const PluginPackage\VERSION;

final class Menu
{
	use Singleton;


	/**
	 * It registers menu page for admin.
	 * @return void
	 */
	public function register()
	{
		add_menu_page(
			__('Your Plugin Name', 'your-plugin-name'),
			__('PluginPackage', 'your-plugin-name'),
			'manage_options',
			'your-plugin-name',
			array($this, 'render'),
			'dashicons-plugins-checked',
			6
		);
	}

	/**
	 * It will display dashboard template. Also remove admin footer text.
	 * @return void
	 */
	public function render()
	{
		add_filter('admin_footer_text', '__return_empty_string', 11);
		add_filter('update_footer', '__return_empty_string', 11);
		echo wp_kses_post('<div id="' . NAME . '"></div>');
	}

	/**
	 * It will add module attribute to script tag.
	 *
	 * @param string $tag of script.
	 * @param string $id of script.
	 *
	 * @return string
	 */
	public function add_module(string $tag, string $id): string
	{
		if (NAME === $id) {
			$tag = str_replace('<script ', '<script type="module" ', $tag);
		}

		return $tag;
	}

	/**
	 * It loads scripts based on plugin's mode, dev or prod.
	 * @return void
	 */
	public function scripts()
	{
		if ('dev' === MODE) {
			wp_enqueue_style(NAME, 'http://localhost:5174/style.scss', array(), VERSION);
			wp_enqueue_script(NAME, 'http://localhost:5174/main.js', array('wp-i18n'), VERSION, true);
		} else {
			wp_enqueue_style(NAME, plugins_url('assets/admin/dist/index.css', FILE), array(), VERSION);
			wp_enqueue_script(NAME, plugins_url('assets/admin/dist/index.js', FILE), array(), VERSION, true);
		}
		wp_localize_script(
			NAME,
			'your_plugin_name',
			array(
				'nonce' => wp_create_nonce('wp_rest'),
				'api_endpoint' => get_rest_url(null, 'your-plugin-name/v1/'),
			)
		);
	}
}
