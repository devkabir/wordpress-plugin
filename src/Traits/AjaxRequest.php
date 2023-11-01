<?php

namespace PluginPackage\Traits;

use const PluginPackage\NAME;

// If this file is called directly, abort.
if (!defined('PluginPackage\NAME')) {
    exit;
}

trait AjaxRequest
{
    // Array to store registered AJAX requests
    private array $request = [];
    // Array to store registered AJAX response
    private array $response = ['message' => 'Saved'];
    // Array to store registered AJAX posted data
    private array $data = [];
    // Array to store registered AJAX errors
    private array $errors = [];

    /**
     * Serve data to AJAX request.
     */
    private function serve(): void
    {
        if (count($this->errors) > 0) {
            wp_send_json_error($this->errors);
        }

        wp_send_json_success($this->response);
    }

    /**
     * Verify AJAX request.
     */
    private function verify(array $keys = []): void
    {
        check_ajax_referer(NAME, 'security_token');
        $this->response = [];
        $this->errors = [];
        $this->request = array_map('sanitize_text_field', $_REQUEST);
        $contents = file_get_contents('php://input');
        $contents = sanitize_text_field($contents);
        $decode = json_decode($contents, true);
        if (!empty($decode)) {
            $data = $this->extract_data($decode, $keys);
            if (empty($data)) {
                $this->data = [];
            } else {
                $this->data = $data;
            }
        }
    }

    /**
     * Extracts data from an array using the given keys.
     *
     * @param array $sanitized The array from which to extract data.
     * @param array $keys The keys to use for extraction.
     *
     * @return array The extracted data.
     */
    public function extract_data(array $sanitized, array $keys): array
    {
        return array_intersect_key($sanitized, array_flip($keys));
    }

    /**
     * Register AJAX actions.
     */
    private function action($action): string
    {
        return sprintf('wp_ajax_%s-%s', NAME, $action);
    }
}
