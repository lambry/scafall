<?php
/**
 * Post Types
 *
 * Create new post types.
 *
 * @package Lambry
 */

namespace Lambry\Kickoff;

defined( 'ABSPATH' ) || exit;

/* Post Types Class */
class Post_Types {

	/* Variables */
	public $post_types = [];
	public $labels = [];

	/**
	 * Construct
	 *
	 * Creates new post types.
	 *
	 * @param array $post_types
	 */
	public function __construct( $post_types = [] ) {

		// Set variables
		$this->post_types = $post_types;

		// Register
		$this->register();

	}

	/**
	 * Register
	 *
	 * Register new post types.
	 *
	 * @access public
	 * @return null
	 */
	public function register() {

		foreach ( $this->post_types as $type ) {

			// Set labels
			$this->labels['single'] = $type['name'];
			$this->labels['plural'] = ( isset( $type['plural'] ) ) ? $type['plural'] : $this->labels['single'] . 's';

			// Set options
			$options = ( isset( $type['options'] ) ) ? wp_parse_args( $type['options'], $this->default_options() ) : $this->default_options();
			$options['labels'] = ( isset( $type['labels'] ) ) ? wp_parse_args( $type['labels'], $this->default_labels() ) : $this->default_labels();

			// Register post type
			register_post_type( sanitize_title_with_dashes( $type['name'] ), $options );

		}

	}

	/**
	 * Default Options
	 *
	 * Setup the default options.
	 *
	 * @access private
	 * @return array $default_options
	 */
	private function default_options() {

		return [
			'public'      => true,
			'has_archive' => true,
			'menu_icon'   => 'dashicons-format-aside',
			'supports'    => [
				'title', 'editor', 'revisions', 'thumbnail', 'excerpt', 'page-attributes'
			]
		];

	}

	/**
	 * Default Labels
	 *
	 * Setup the default labels.
	 *
	 * @access private
	 * @return array $default_labels
	 */
	private function default_labels() {

		return [
			'name'               => $this->labels['plural'],
			'singular_name'      => $this->labels['single'],
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New ' . $this->labels['single'],
			'edit_item'          => 'Edit ' . $this->labels['single'],
			'new_item'           => 'New ' . $this->labels['single'],
			'all_items'          => 'All ' . $this->labels['plural'],
			'view_item'          => 'View ' . $this->labels['single'],
			'update_item'        => 'Update ' . $this->labels['single'],
			'search_items'       => 'Search ' . $this->labels['plural'],
			'not_found'          => 'No matching ' . strtolower( $this->labels['plural'] ) . ' found',
			'not_found_in_trash' => 'No ' . strtolower( $this->labels['plural'] ) . ' in Trash',
			'parent_item_colon'  => 'Parent ' . $this->labels['single'] . ':',
			'menu_name'          => $this->labels['plural'],
		];

	}

}
