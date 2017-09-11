<?php
/**
 * Create a new taxonomy.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

defined( 'ABSPATH' ) || exit;

class Taxonomy {

    // Variables
    private $slug;
    private $labels = [];
    private $types = ['page', 'post'];

    /**
     * Return an instance of the class.
     *
     * @access public
     * @param mixed add()
     * @param object $this
     */
    public function __construct($slug, $name, $plural) {

        // Set variables
        $this->slug = $slug;
        $this->labels['single'] = $name;
        $this->labels['plural'] = $plural;

        return $this;

    }

    /**
     * Set up new taxonomy details.
     *
     * @access public
     * @param string $slug
     * @param string $name
     * @param string $plural
     * @return object $taxonomy
     */
    public static function add(string $slug, string $name, string $plural) {

        return new Taxonomy($slug, $name, $plural);

    }

    /**
     * Set up new taxonomy details.
     *
     * @access public
     * @param mixed  $types
     * @return object $this
     */
    public function to($types) {

		$this->types = array_map('sanitize_title_with_dashes', (array) $types);

		return $this;

    }

    /**
     * Set options and register taxonomy.
     *
     * @access public
     * @param array $options
     * @return void
     */
    public function set(array $options = []) {

        // Set options
        $options = wp_parse_args($options, $this->options());
        $options['labels'] = wp_parse_args($this->labels, $this->labels());

        // Register taxonomy
        register_taxonomy($this->slug, $this->types, $options);

    }

    /**
     * Setup the default options.
     *
     * @access private
     * @return array $options
     */
    private function options() {

        return [
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true
        ];

    }

    /**
     * Setup the default labels.
     *
     * @access private
     * @return array $labels
     */
    private function labels() {

        return [
            'name'                       => $this->labels['plural'],
            'singular_name'              => $this->labels['single'],
            'menu_name'                  => $this->labels['plural'],
            'all_items'                  => "All {$this->labels['plural']}",
            'parent_item'                => "Parent {$this->labels['single']}",
            'parent_item_colon'          => "Parent {$this->labels['plural']}:",
            'new_item_name'              => "New {$this->labels['single']} Name",
            'add_new_item'               => "Add New {$this->labels['single']}",
            'edit_item'                  => "Edit {$this->labels['single']}",
            'update_item'                => "Update {$this->labels['single']}",
            'view_item'                  => "View {$this->labels['single']}",
            'separate_items_with_commas' => "Separate {$this->labels['plural']} with commas",
            'add_or_remove_items'        => "Add or remove {$this->labels['plural']}",
            'choose_from_most_used'      => 'Choose from the most used',
            'popular_items'              => "Popular {$this->labels['plural']}",
            'search_items'               => "Search {$this->labels['plural']}",
            'not_found'                  => 'Not Found',
            'no_terms'                   => "No {$this->labels['plural']}",
            'items_list'                 => "{$this->labels['plural']} list",
            'items_list_navigation'      => "{$this->labels['plural']} list navigation"
        ];

    }

}
