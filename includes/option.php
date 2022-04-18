<?php

/**
 * Create new options page.
 *
 * @package Scafall
 */

namespace Lambry\Scafall;

use Closure;

defined('ABSPATH') || exit;

class Option extends Field
{
	private string $page;
	private string $slug;
	private array $sections = [];
	private string $placement = 'menu';

	/**
	 * Setup and add actions.
	 */
	public function __construct(private string $menu, private string $title, private array $options)
	{
		$this->slug = sanitize_title_with_dashes($menu);
		$this->options = wp_parse_args($options, $this->menuOptions());

		add_action('admin_menu', [$this, 'menu']);
		add_action('admin_init', [$this, 'register']);
		add_action('admin_enqueue_scripts', [$this, 'assets']);

		return $this;
	}

	/**
	 * Set up new option instance.
	 */
	public static function add(string $menu, string $title, $options = []): Option
	{
		return new Option($menu, $title, $options);
	}

	/**
	 * Set up a new section and fields.
	 */
	public function section(string $id, string $title, Closure $callback): self
	{
		$callback($this);

		$fields = $this->fields;

		$this->sections[] = (object) compact('id', 'title', 'fields');

		$this->fields = [];

		return $this;
	}

	/**
	 * Set we the options page should be registered.
	 */
	public function to(string $placement, $parent = null): void
	{
		$this->placement = $placement;

		if ($parent) {
			$this->parent = $parent;
		}
	}

	/**
	 * Adds the appropriate menu type.
	 */
	public function menu(): void
	{
		switch ($this->placement) {
			case 'menu':
				$this->page = add_menu_page($this->title, $this->menu, $this->options['capability'], $this->slug, [$this, 'page'], $this->options['icon_url'], $this->options['position']);
				break;

			case 'theme':
				$this->page = add_theme_page($this->title, $this->menu, $this->options['capability'], $this->slug, [$this, 'page']);
				break;

			case 'options':
				$this->page = add_options_page($this->title, $this->menu, $this->options['capability'], $this->slug, [$this, 'page']);
				break;

			case 'management':
				$this->page = add_management_page($this->title, $this->menu, $this->options['capability'], $this->slug, [$this, 'page']);
				break;

			case 'submenu':
				$this->page = add_submenu_page($this->parent, $this->title, $this->menu, $this->options['capability'], $this->slug, [$this, 'page']);
				break;
		}
	}

	/**
	 * Loads assets.
	 */
	public function assets(string $hook): void
	{
		if ($this->page !== $hook) return;

		$this->fieldAssets();
	}

	/**
	 * Registers all section settings.
	 */
	public function register(): void
	{
		// Set up each setting section
		foreach ($this->sections as $section) {
			add_settings_section($section->id, $section->title, null, $section->id);

			// Add settings fields
			foreach ($section->fields as $field) {
				$field['section'] = $section->id;
				add_settings_field($field['name'], $field['label'], [$this, 'showField'], $section->id, $section->id, (array) $field);

				// Register setting and callback
				register_setting($section->id, $field['name'], [
					'sanitize_callback' => fn ($value) => $this->sanitizeField($value, $field),
				]);
			}
		}
	}

	/**
	 * Registers the page content and section settings.
	 */
	public function page(): void
	{ ?>
		<div id="<?= $this->slug; ?>" class="wrap scafall scafall-option">
			<h2><?= $this->title; ?></h2>
			<?php settings_errors(); ?>

			<h2 class="nav-tab-wrapper">
				<?php foreach ($this->sections as $section) : ?>
					<a href="?page=<?= $this->slug; ?>&amp;tab=<?= $section->id; ?>" class="nav-tab <?= ($section->id === $this->activeTab()) ? 'nav-tab-active' : ''; ?>">
						<?= $section->title; ?>
					</a>
				<?php endforeach; ?>
			</h2>

			<form action="options.php" method="POST" enctype="post">
				<?php
					foreach ($this->sections as $section) {
						if ($section->id === $this->activeTab()) {
							settings_fields($section->id);
							do_settings_sections($section->id);
							submit_button();
						}
					}
				?>
			</form>
		</div>
	<?php }

	/**
	 * Gets the current tab.
	 */
	private function activeTab(): string
	{
		return $_GET['tab'] ?? $this->sections[0]->id;
	}

	/**
	 * Setup the default options.
	 */
	private function menuOptions(): array
	{
		return [
			'position' => 80,
			'capability' => 'manage_options',
			'icon_url' => 'dashicons-exerpt-view'
		];
	}
}
