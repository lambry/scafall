<?php
/**
 * Create a new post type.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

defined( 'ABSPATH' ) || exit;


class Post {

    private $slug;
    private $labels = [];

    /**
     * Return an instance of the class.
     *
     * @access public
     * @param mixed add()
     * @param object $this
     */
    public function __construct(string $slug, string $name, string $plural) {

        $this->slug = $slug;
        $this->labels['single'] = $name;
        $this->labels['plural'] = $plural;

        return $this;

    }

    /**
     * Set up new post type details.
     *
     * @access public
     * @param string $slug
     * @param string $name
     * @param string $plural
     * @return object $post
     */
    public static function add(string $slug, string $name, string $plural) : Post {

		return new Post($slug, $name, $plural);

    }

    /**
     * Set options and register post type.
     *
     * @access public
     * @param array $options
     * @return void
     */
    public function set(array $options = []) {

        $options = wp_parse_args($options, $this->options());
        $options['labels'] = wp_parse_args($this->labels, $this->labels());

		register_post_type($this->slug, $options);

    }

    /**
     * Setup the default options.
     *
     * @access private
     * @return array $options
     */
    private function options() : array {

        return [
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 10,
            'menu_icon' => 'dashicons-format-aside',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'supports' => [
                'title', 'editor', 'revisions', 'thumbnail', 'excerpt', 'page-attributes'
            ]
		];

    }

    /**
     * Setup the default labels.
     *
     * @access private
     * @return array $labels
     */
    private function labels() : array {

        return [
            'name' => $this->labels['plural'],
            'singular_name' => $this->labels['single'],
            'menu_name' => $this->labels['plural'],
            'name_admin_bar' => $this->labels['single'],
            'archives' => "{$this->labels['single']} Archives",
            'attributes' => "{$this->labels['single']} Attributes",
            'parent_item_colon' => "Parent {$this->labels['single']}",
            'all_items' => "All {$this->labels['plural']}",
            'add_new_item' => "Add New {$this->labels['single']}",
            'add_new' => 'Add New',
            'new_item' => "New {$this->labels['single']}",
            'edit_item' => "Edit {$this->labels['single']}",
            'update_item' => "Update {$this->labels['single']}",
            'view_item' => "View {$this->labels['single']}",
            'view_items' => "View {$this->labels['plural']}",
            'search_items' => "Search {$this->labels['plural']}",
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in Trash',
            'featured_image' => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'insert_into_item' => "Insert into {$this->labels['single']}",
            'uploaded_to_this_item' => "Uploaded to this {$this->labels['single']}",
            'items_list' => "{$this->labels['plural']} list",
            'items_list_navigation' => "{$this->labels['plural']} list navigation",
            'filter_items_list' => "Filter {$this->labels['plural']} list"
		];

    }

}
