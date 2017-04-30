<?php
/**
 * Meta Boxes
 *
 * Create new meta boxes.
 *
 * @package Bow
 */

namespace Lambry\Kickoff;

class Meta_Boxes {

    /**
     * Construct
     *
     * Register new meta boxes and load assets.
     *
     * @param array $meta_boxes
     * @param array $post_types
     */
    public function __construct( $meta_boxes, $post_types = [ 'page' ] ) {

		$this->includes();

        // Register meta boxes
        $register = new Meta_Boxes\Register( $meta_boxes, $post_types );

        // Add admin assets
        add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );

    }

	/**
     * Load Customizer Includes
     *
     * Loads all customizer classes.
     *
     * @access private
     * @return null
     */
    public function includes(  ) {

        require_once plugin_dir_path( __FILE__ ) . 'utilities.php';
        require_once plugin_dir_path( __FILE__ ) . 'register.php';
        require_once plugin_dir_path( __FILE__ ) . 'fields.php';

    }

    /**
     * Load Assets
     *
     * Loads all assets.
     *
     * @access public
     * @param  string $hook
     * @return null
     */
    public function load_assets( $hook ) {

        // Check if we should load assets
        if ( $hook !== 'post-new.php' && $hook !== 'post.php') return;

        // Load settings css
        wp_enqueue_style( 'bow-meta-styles', KICKOFF_PATH . 'assets/styles/meta-boxes.css', [ 'wp-color-picker' ], '1.0.0' );
        // Load media assets
        wp_enqueue_media();
        // Load settings js
        wp_enqueue_script( 'bow-meta-scripts', KICKOFF_PATH . 'assets/scripts/meta-boxes.js', [ 'jquery', 'wp-color-picker' ], '1.0.0', true );

    }

}
