<?php
/**
 * Meta Boxes
 *
 * Register new meta boxes.
 *
 * @package Bow
 */

namespace Lambry\Kickoff\Meta_Boxes;

class Register {

    /* Classes */
    private $fields;
    private $utilities;
    /* Variables */
    private $meta_boxes;
    private $post_types;

    /**
     * Construct
     *
     * Register new meta boxes and load assets.
     *
     * @param array $meta_boxes
     * @param array $post_types
     */
    public function __construct( $meta_boxes, $post_types ) {

        // Set classes
        $this->fields = new Fields;
        $this->utilities = new Utilities;
        // Set variables
        $this->meta_boxes = $meta_boxes;
        $this->post_types = array_map( 'sanitize_title_with_dashes', $post_types );

        // Register sanitizers
        $this->register_sanitizers();

        // Register meta boxes
        add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );

        // Add actions to save meta boxes
        foreach ( $this->post_types as $post_type ) {
            add_action( 'save_post_' . $post_type, [ $this, 'update_meta_boxes' ] );
        }

    }

    /**
     * Register Sanitizers
     *
     * Register sanitizers for meta boxes.
     *
     * @access public
     * @return null
     */
    public function register_sanitizers() {

        foreach ( $this->meta_boxes as $meta_box ) {
            foreach ( $meta_box['fields'] as $field ) {

                switch ( $field['type'] ) {
                    case 'checkbox':
                    case 'repeater':
                        register_meta( 'post', $this->fields->field_name( $field ), [
                            'sanitize_callback' => [ $this->utilities, 'sanitize_array' ]
                        ]);
                        break;
                    case 'editor':
                        break;
                    default:
                        register_meta( 'post', $this->fields->field_name( $field ), [
                            'sanitize_callback' => 'wp_strip_all_tags'
                        ]);
                        break;
                }

            }
        }

    }

    /**
     * Register Meta Boxes
     *
     * Register all meta boxes.
     *
     * @access public
     * @return null
     */
    public function register_meta_boxes() {

        // Set up each meta box
        foreach ( $this->meta_boxes as $meta_box ) {

            // Set meta box defaults
            $context = ( isset( $meta_box['context']) ) ? $meta_box['context'] : 'normal';
            $priority = ( isset( $meta_box['priority']) ) ? $meta_box['priority'] : 'high';

            // Add meta boxes
            foreach ( $this->post_types as $post_type ) {
                add_meta_box( $meta_box['id'], $meta_box['title'], [ $this, 'display_meta_box' ], $post_type, $context, $priority, $meta_box );
            }

        }

    }

    /**
     * Display Meta Box
     *
     * Display all meta boxes.
     *
     * @access public
     * @param  object $post
     * @param  array $meta_box
     * @return null
     */
    public function display_meta_box( $post, $meta_box ) { ?>

        <div class="bow-meta-boxes">
            <?php
                wp_nonce_field( 'bow_meta_boxes', 'bow_meta_boxes_nonce');

                // Display description
                if ( isset( $meta_box['args']['description'] ) ) {
                    echo "<p class='meta-box-descripion'>{$meta_box['args']['description']}</p>";
                }

                // Add fields
                foreach ( $meta_box['args']['fields'] as $field ) {
                    $this->fields->add_field( $field );
                }
            ?>
        </div>

        <?php

    }

    /**
     * Update Meta Boxes
     *
     * Update all meta box data.
     *
     * @access public
     * @param  int $post_id
     * @return null
     */
    public function update_meta_boxes( $post_id ) {

        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        // Check for nonce and autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || ! isset( $_POST['bow_meta_boxes_nonce'] ) ||
             ! wp_verify_nonce( $_POST['bow_meta_boxes_nonce'], 'bow_meta_boxes' ) ) {
            return;
        }

        // Save all meta fields
        foreach ( $this->meta_boxes as $meta_box ) {

            foreach ( $meta_box['fields'] as $field ) {

                $value = ( isset( $_POST[$this->fields->field_name( $field )] ) ) ? $_POST[$this->fields->field_name( $field )] : '';

                if ( $field['type'] === 'repeater' && is_array( $value ) ) {
                    $value = $this->utilities->filter_array( $value );
                }

                update_post_meta( $post_id, $this->fields->field_name( $field ), $value );

            }

        }

    }

}
