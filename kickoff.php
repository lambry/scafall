<?php
/**
 * Plugin Name: Kickoff
 * Plugin URI: https://github.com/lambry/kickoff
 * Description: A simple starter plugin.
 * Version: 0.1.0
 * Author: Lambry
 * Author URI: http://lambry.com
 */

namespace Lambry\Kickoff;

defined('ABSPATH') || exit;

// Handle plugin activation and deactivation
register_activation_hook(__FILE__, ['Lambry\Kickoff\Init', 'activate']);
register_deactivation_hook(__FILE__, ['Lambry\Kickoff\Init', 'deactivate']);

class Init {

    /**
     * Construct
     */
    public function __construct() {

        $this->includes();

        // Add admin assets
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'public_assets']);

    }

    /**
     * Include all autoload files.
     *
     * @access private
     * @return null
     */
    private function includes() {

        $autoload = require_once plugin_dir_path(__FILE__) . 'autoload.php';

        foreach ($autoload as $include) {
            require_once plugin_dir_path(__FILE__) . "{$include}.php";
        }

    }

    /**
     * Activate Plugin
     *
     * @access public
     * @return null
     */
    public static function activate() {

    }

    /**
     * Deactivate Plugin
     *
     * @access public
     * @return null
     */
    public static function deactivate() {

    }

    /**
     * Admin Assets
     *
     * @access public
     * @return null
     */
    public function admin_assets() {

    }

    /**
     * Public Assets
     *
     * @access public
     * @return null
     */
    public function public_assets() {

    }

}

/**
 * Register / Init the plugin.
 */
add_action('init', function () {
    new Init();
});
