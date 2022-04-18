<?php

/**
 * Create a new meta box.
 *
 * @package scafall
 */

namespace Lambry\Scafall;

use Closure;

defined('ABSPATH') || exit;

class Meta extends Field
{
	private array $types = [];
	private array $options = [];

	/**
	 * Setup and add actions.
	 */
	public function __construct(private string $id, private string $title, array $options)
	{
		$this->options = wp_parse_args($options, $this->metaOptions());

		add_action('admin_init', [$this, 'save']);
		add_action('add_meta_boxes', [$this, 'register']);
		add_action('admin_enqueue_scripts', [$this, 'assets']);

		return $this;
	}

	/**
	 * Set up new metabox details.
	 */
	public static function add(string $id, string $title, array $options = []): Meta
	{
		return new Meta($id, $title, $options);
	}

	/**
	 * Set up fields.
	 */
	public function fields(Closure $callback): self
	{
		$callback($this);

		return $this;
	}

	/**
	 * Set post types to add metabox to.
	 */
	public function to($types): self
	{
		$this->types = (array) $types;

		return $this;
	}

	/**
	 * Loads all assets.
	 */
	public function assets(string $hook): void
	{
		if ($hook !== 'post-new.php' && $hook !== 'post.php') return;

		$this->fieldAssets();
	}

	/**
	 * Register the meta box, fields and save callback.
	 */
	public function register(): void
	{
		foreach ($this->types as $type) {
			add_meta_box($this->id, $this->title, [$this, 'box'], $type, $this->options['context'], $this->options['priority']);
		}

		foreach ($this->fields as $field) {
			register_meta('post', $field['name'], [
				'sanitize_callback' => fn ($value) => $this->sanitizeField($value, $field),
			]);
		}
	}

	/**
	 * Setup save actions.
	 */
	public function save(): void
	{
		foreach ($this->types as $type) {
			add_action('save_post_' . $type, [$this, 'update']);
		}
	}

	/**
	 * Display the meta box and fields.
	 */
	public function box()
	{ ?>
		<div class="scafall scafall-meta">
			<?php
				wp_nonce_field('scafall_meta', 'scafall_meta_nonce');

				foreach ($this->fields as $field) {
					$this->showField($field);
				}
			?>
		</div>
	<?php }

	/**
	 * Update all meta box data.
	 */
	public function update(int $id): void
	{
		// Check permissions, nonce and autosave
		if (
			!current_user_can('edit_post', $id) ||
			(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
			!isset($_POST['scafall_meta_nonce']) ||
			!wp_verify_nonce($_POST['scafall_meta_nonce'], 'scafall_meta')
		) {
			return;
		}

		// Save all meta fields
		foreach ($this->fields as $field) {
			update_post_meta($id, $field['name'], $_POST[$field['name']] ?? '');
		}
	}

	/**
	 * Setup the default options.
	 */
	private function metaOptions(): array
	{
		return [
			'context'  => 'normal',
			'priority' => 'high'
		];
	}
}
