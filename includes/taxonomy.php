<?php

/**
 * Create a new taxonomy.
 *
 * @package Scafall
 */

namespace Lambry\Scafall;

defined('ABSPATH') || exit;

class Taxonomy
{
	private array $labels = [];
	private array $types = [];
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
	 * Set up new taxonomy details.
	 */
	public static function add(string $slug, string $name, string $plural): Taxonomy
	{
		return new Taxonomy($slug, $name, $plural);
	}

	/**
	 * Set which post types the taxonomy should be added to.
	 */
	public function to(mixed $types): self
	{
		$this->types = (array) $types;

		return $this;
	}

	/**
	 * Set up new taxonomy options.
	 */
	public function options(mixed $options): self
	{
		$this->options = (array) $options;

		return $this;
	}

	/**
	 * Register the new taxonomy.
	 */
	public function register(): void
	{
		$options = wp_parse_args($this->options, $this->defaults());

		register_taxonomy($this->slug, $this->types, $options);
	}

	/**
	 * Setup the default options.
	 */
	private function defaults(): array
	{
		return [
			'hierarchical' => false,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'show_in_quick_edit' => true,
			'show_admin_column' => false,
			'show_in_rest' => true,
			'labels' => [
				'name' => $this->labels['plural'],
				'singular_name' => $this->labels['single'],
				'menu_name' => $this->labels['plural'],
				'all_items' => "All {$this->labels['plural']}",
				'parent_item' => "Parent {$this->labels['single']}",
				'parent_item_colon' => "Parent {$this->labels['plural']}:",
				'new_item_name' => "New {$this->labels['single']} Name",
				'add_new_item' => "Add New {$this->labels['single']}",
				'edit_item' => "Edit {$this->labels['single']}",
				'update_item' => "Update {$this->labels['single']}",
				'view_item' => "View {$this->labels['single']}",
				'separate_items_with_commas' => "Separate {$this->labels['plural']} with commas",
				'add_or_remove_items' => "Add or remove {$this->labels['plural']}",
				'choose_from_most_used' => 'Choose from the most used',
				'popular_items' => "Popular {$this->labels['plural']}",
				'search_items' => "Search {$this->labels['plural']}",
				'not_found' => 'Not Found',
				'no_terms' => "No {$this->labels['plural']}",
				'items_list' => "{$this->labels['plural']} list",
				'items_list_navigation' => "{$this->labels['plural']} list navigation",
				'back_to_items' => "Back to {$this->labels['plural']}"
			]
		];
	}
}
