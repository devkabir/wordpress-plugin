<?php

namespace PluginPackage\Ajax;

use PluginPackage\Helpers\Log;
use PluginPackage\Traits\Singleton;
use const PluginPackage\NAME;

class Message
{
	use Singleton;

	private function __construct()
	{
		add_action('wp_ajax_nopriv_save_message', array($this, 'save'));
		add_action('wp_ajax_save_message', array($this, 'save'));
	}

	public function save()
	{
		if (check_ajax_referer(NAME, 'nonce')) {
			$data = wp_unslash($_POST['message'] ?? array());
			$data = array_column($data, 'value', 'name');
			$data = array_map('sanitize_text_field', $data);
			$invalid = array_filter(
				$data,
				function ($item) {
					return strlen($item) === 0;
				}
			);
			if (empty($invalid)) {
				Log::instance()->write('contact', $data);
				wp_send_json_success(__('We have received your message.', 'your-plugin-name'));
			} else {
				wp_send_json_error(__('Please fillup form properly', 'your-plugin-name'));
			}
		} else {
			wp_send_json_error(__FILE__);
		}
	}

}