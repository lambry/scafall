<?php

/**
 * Create a new post type.
 *
 * @package Scafall
 */

namespace Lambry\Scafall;

defined('ABSPATH') || exit;

class Post
{
	private array $labels = [];
	private array $supports = [];
	private array $options = [];

	/**
	 * Setup properties and add actions.
	 */
	public function __construct(private string $slug, string $name, string $plural)
	{
		$this->labels['single'] = $name;
		$this->labels['plural'] = $plural;

		add_action('init', [$this, 'register']);

		return $this;
	}

	/**
	 * Set up new post type details.
	 */
	public static function add(string $slug, string $name, string $plural): Post
	{
		return new Post($slug, $name, $plural);
	}

	/**
	 * Set supported features.
	 */
	public function supports(mixed $supports): self
	{
		$this->supports = (array) $supports;

		return $this;
	}

	/**
	 * Set up new post type options.
	 */
	public function options(mixed $options): self
	{
		$this->options = (array) $options;

		return $this;
	}

	/**
	 * Set post type labels.
	 */
	public function labels(array $labels): self
	{
		$this->labels = $labels;

		return $this;
	}

	/**
	 * Register the new post type.
	 */
	public function register(): void
	{
		$options = wp_parse_args($this->options, $this->defaults());

		if ($this->supports) {
			$options['supports'] = $this->supports;
		}

		if ($this->labels) {
			$options['labels'] = $this->labels;
		}

		register_post_type($this->slug, $options);
	}

	/**
	 * Setup the default options.
	 */
	private function defaults(): array
	{
		return [
			'label' => $this->labels['single'],
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 10,
			'menu_icon' => 'dashicons-format-aside',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'show_in_rest' => true,
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'post',
			'map_meta_cap' => true,
			'supports' => [
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'page-attributes',
				'revisions',
				'author',
				'custom-fields'
			],
			'labels' => [
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
			],
		];
	}
}
