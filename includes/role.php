<?php

/**
 * Create a new user role.
 *
 * @package Scafall
 */

namespace Lambry\Scafall;

defined('ABSPATH') || exit;

class Role
{
	private array $capabilities = [];

	/**
	 * Setup properties and add actions.
	 */
	public function __construct(private string $slug, private string $name)
	{
		add_action('init', [$this, 'register']);

		return $this;
	}

	/**
	 * Set up new post type details.
	 */
	public static function add(string $slug, string $name): Role
	{
		return new Role($slug, $name);
	}

	/**
	 * Set role capabilities.
	 */
	public function capabilities(mixed $capabilities): self
	{
		$this->capabilities = (array) $capabilities;

		return $this;
	}

	/**
	 * Add the new role.
	 */
	public function register(): void
	{
		$capabilities = wp_parse_args($this->capabilities, $this->defaults());

		add_role($this->slug, $this->name, $capabilities);
	}

	/**
	 * Setup the default options.
	 */
	public function defaults(): array
	{
		return ['read' => true];
	}
}
