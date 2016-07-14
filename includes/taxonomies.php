<?php
/**
 * Taxonomies
 *
 * Create new taxonomies.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

defined( 'ABSPATH' ) || exit;

/* Taxonomies Class */
class Taxonomies {

	/* Variables */
	public $taxonomies = [];
	public $labels = [];

	/**
	 * Construct
	 *
	 * Creates a new taxonomy.
	 *
	 * @param array $taxonomies
	 */
	public function __construct( $taxonomies = [] ) {

		// Set variables
		$this->taxonomies = $taxonomies;

		// Register
		$this->register();

	}

	/**
	 * Register
	 *
	 * Register new taxonomies.
	 *
	 * @access private
	 * @return null
	 */
	private function register() {

		foreach ( $this->taxonomies as $taxonomy ) {

			// Set labels
			$this->labels['single'] = $taxonomy['name'];
			$this->labels['plural'] = ( isset( $taxonomy['plural'] ) ) ? $taxonomy['plural'] : $this->labels['single'] . 's';

			// Setup post types
			$post_types = [];
			foreach ( $taxonomy['post_types'] as $type ) {
				$post_types[] = sanitize_title_with_dashes( $type );
			}

			// Setup options
			$options = ( isset( $taxonomy['options'] ) ) ? wp_parse_args( $taxonomy['options'], $this->default_options() ) : $this->default_options();
			$options['labels'] = ( isset( $taxonomy['labels'] ) ) ? wp_parse_args( $taxonomy['labels'], $this->default_labels() ) : $this->default_labels();

			// Register taxonomy
			register_taxonomy( sanitize_title_with_dashes( $this->labels['single'] ), $post_types, $options );

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
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true
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
			'name'                       => $this->labels['plural'],
			'singular_name'              => $this->labels['single'],
			'menu_name'                  => $this->labels['plural'],
			'all_items'                  => 'All ' . $this->labels['plural'],
			'add_new_item'               => 'Add New ' . $this->labels['single'],
			'new_item_name'              => 'New ' . $this->labels['single'] . ' Name',
			'update_item'                => 'Update ' . $this->labels['single'],
			'edit_item'                  => 'Edit ' . $this->labels['single'],
			'not_found'                  => 'No matching ' . strtolower( $this->labels['plural'] ) . ' found',
			'parent_item'                => 'Parent ' . $this->labels['single'],
			'parent_item_colon'          => 'Parent ' . $this->labels['single'] . ':',
			'search_items'               => 'Search ' . $this->labels['plural'],
			'add_or_remove_items'        => 'Add or remove ' . strtolower( $this->labels['plural'] ),
			'choose_from_most_used'      => 'Choose from the most used ' . strtolower( $this->labels['plural'] ),
			'separate_items_with_commas' => 'Separate  ' . strtolower( $this->labels['single'] ) . ' with commas'
		];

	}

}
