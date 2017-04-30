<?php
/**
 * Plugin Name: Kickoff
 * Plugin URI: https://github.com/lambry/kickoff
 * Description: A starter plugin of sorts.
 * Version: 0.1.0
 * Author: Lambry
 * Author URI: http://lambry.com
 * Text Domain: kickoff
 * Domain Path: /languages.
 */

namespace Lambry\Kickoff;

defined('ABSPATH') || exit;

// Handle plugin activation and deactivation
register_activation_hook( __FILE__, [ 'Lambry\Kickoff\Init', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'Lambry\Kickoff\Init', 'deactivate' ] );

/* Init Class */
class Init {

    /*
     * Construct
     */
    public function __construct() {

        $this->includes();

		define('KICKOFF_PATH', plugin_dir_url( __FILE__ ));

        // Load text domain
        load_plugin_textdomain( 'kickoff', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );

        // Add admin assets
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'public_assets' ] );

    }

    /*
     * Includes
     *
     * @access private
     * @return null
     */
    private function includes() {

        require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes/meta-boxes.php';
        require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
        require_once plugin_dir_path(__FILE__) . 'includes/post-types.php';
        require_once plugin_dir_path(__FILE__) . 'includes/taxonomies.php';
        require_once plugin_dir_path(__FILE__) . 'includes/user-roles.php';

		// Temp examples - Remove me
        require_once plugin_dir_path(__FILE__) . 'examples.php';

    }

    /*
     * Activate Plugin
     *
     * @access public
     * @return null
     */
    public static function activate() {

    }

    /*
     * Deactivate Plugin
     *
     * @access public
     * @return null
     */
    public static function deactivate() {

    }

    /*
     * Admin Assets
     *
     * @access public
     * @return null
     */
    public function admin_assets() {

    }

    /*
     * Public Assets
     *
     * @access public
     * @return null
     */
    public function public_assets() {

    }

}

/*
 * Register plugin.
 */
add_action('init', function() {
    new Init();
} );
