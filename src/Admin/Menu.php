<?php

namespace PluginPackage\Admin;

use const PluginPackage\FILE;

// If this file is called directly, abort.
if (!defined('PluginPackage\NAME')) {
    exit;
}

use const PluginPackage\MODE;
use const PluginPackage\NAME;
use const PluginPackage\VERSION;
use PluginPackage\Traits\AjaxRequest;
use PluginPackage\Traits\Singleton;

final class Menu
{
    use Singleton;
    use AjaxRequest;

    /**
     * Menu constructor.
     */
    protected function __construct()
    {
        // Register Menu.
        add_action('admin_menu', array($this, 'register'));
        // Load script.
        add_action('admin_enqueue_scripts', array($this, 'scripts'));
        // Add module attribute.
        add_filter('script_loader_tag', array($this, 'add_module'), 10, 3);
        // Register AJAX actions.
        add_action($this->action('ajax_example'), [$this, 'ajax_example']);

    }

    public function ajax_example()
    {
        $this->verify();
        $this->response['file'] = __FILE__;
        $this->serve();
    }

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
            $style_path = 'http://localhost:5000/style.scss';
            $script_path = 'http://localhost:5000/main.js';
        } else {
            $style_path = plugins_url('assets/admin/dist/index.css', FILE);
            $script_path = plugins_url('assets/admin/dist/index.js', FILE);
        }
        wp_enqueue_style(NAME, $style_path, array(), VERSION);
        wp_enqueue_script(NAME, $script_path, array(), VERSION, true);
        wp_localize_script(
            NAME,
            str_replace('-', '_', NAME),
            array(
                'security_token' => wp_create_nonce(NAME),
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wp_rest'),
                'api_endpoint' => get_rest_url(null, NAME . '/v1/'),
            )
        );
    }
}
