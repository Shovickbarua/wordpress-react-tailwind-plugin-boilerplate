<?php
/*
  Plugin Name: Test Plugin
  Version: 1.0
  Author: Shovick Barua
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Change path name according to your project
define("TEST_PATH", plugin_dir_path(__FILE__));
define("TEST_PATH_URL", plugins_url(__FILE__));

// Change class name
class Test
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'create_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_scripts']);
        register_activation_hook(__FILE__, [$this, 'accounting_plugin_activate']);
        $this->includes(); // Call includes method to include necessary files
    }

    public function create_admin_menu()
    {
        $capability = 'manage_options';
        $slug = 'test-crud';

        add_menu_page(
            __('test-crud', 'test crud'),
            __('Test Crud', 'Test Crud'),
            $capability,
            $slug,
            [$this, 'menu_page_template']
        );
    }

    public function load_scripts()
    {
        wp_enqueue_script('test-plugin-scripts', plugin_dir_url(__FILE__) . 'build/App.js', ['wp-element'], wp_rand(), true);
        wp_enqueue_style('test-plugin-style', plugin_dir_url(__FILE__) . 'build/output.css');
        wp_localize_script('test-plugin-crud', 'appLocalizer', [
            'apiUrl' => home_url('/wp-json'),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }

    public function accounting_plugin_activate()
    {
        include TEST_PATH . 'migrations/test-crud-migrations.php';
    }

    public function includes()
    {
        require TEST_PATH . 'classes/class-test.php';
    }

    public function menu_page_template()
    {
        echo '<div id="test-app"></div>';
    }
}

$test = new Test(); // Instantiate the class to initialize the plugin
