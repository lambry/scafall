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

// Define kickoff constants
define('KICKOFF_PREFIX', '_kickoff_');
define('KICKOFF_URL', plugin_dir_url( __FILE__ ));

class Init {

    /**
     * Add actions and require files.
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
     * Activate plugin.
     *
     * @access public
     * @return null
     */
    public static function activate() {

    }

    /**
     * Deactivate plugin.
     *
     * @access public
     * @return null
     */
    public static function deactivate() {

    }

    /**
     * Add admin assets.
     *
     * @access public
     * @return null
     */
    public function admin_assets() {

    }

    /**
     * Add public assets.
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
