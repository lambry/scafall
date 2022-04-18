<?php

/**
 * Create and manage fields.
 *
 * @package Scafall
 */

namespace Lambry\Scafall;

defined('ABSPATH') || exit;

class Field
{
	protected array $fields = [];

	/**
	 * Enqueue assets.
	 */
	protected function fieldAssets(): void
	{
		wp_enqueue_media();
		wp_enqueue_style('scafall-field-styles', SCAFALL_URL . 'assets/styles/field.css', ['wp-color-picker'], SCAFALL_VERSION);
		wp_enqueue_script('scafall-field-scripts', SCAFALL_URL . 'assets/scripts/field.js', ['jquery', 'wp-color-picker'], SCAFALL_VERSION, true);
	}

	/**
	 * Add options to field.
	 */
	public function options(array $options): self
	{
		$this->fields[array_key_last($this->fields)]['options'] = $options;

		return $this;
	}

	/**
	 * Add a text field to fields.
	 */
	public function text(string $name, string $label): self
	{
		$this->addField($name, $label, 'text');

		return $this;
	}

	/**
	 * Add a number field to fields.
	 */
	public function number(string $name, string $label): self
	{
		$this->addField($name, $label, 'number');

		return $this;
	}

	/**
	 * Add a email field to fields.
	 */
	public function email(string $name, string $label): self
	{
		$this->addField($name, $label, 'email');

		return $this;
	}

	/**
	 * Add a date field to fields.
	 */
	public function date(string $name, string $label): self
	{
		$this->addField($name, $label, 'date');

		return $this;
	}

	/**
	 * Add a range field to fields.
	 */
	public function range(string $name, string $label): self
	{
		$this->addField($name, $label, 'range');

		return $this;
	}

	/**
	 * Add a url field to fields.
	 */
	public function url(string $name, string $label): self
	{
		$this->addField($name, $label, 'url');

		return $this;
	}

	/**
	 * Add a password field to fields.
	 */
	public function password(string $name, string $label): self
	{
		$this->addField($name, $label, 'password');

		return $this;
	}

	/**
	 * Add a textarea field to fields.
	 */
	public function textarea(string $name, string $label): self
	{
		$this->addField($name, $label, 'textarea');

		return $this;
	}

	/**
	 * Add a editor field to fields.
	 */
	public function editor(string $name, string $label): self
	{
		$this->addField($name, $label, 'editor');

		return $this;
	}

	/**
	 * Add a select field to fields.
	 */
	public function select(string $name, string $label): self
	{
		$this->addField($name, $label, 'select');

		return $this;
	}

	/**
	 * Add a radio field to fields.
	 */
	public function radio(string $name, string $label): self
	{
		$this->addField($name, $label, 'radio');

		return $this;
	}

	/**
	 * Add a checkbox field to fields.
	 */
	public function checkbox(string $name, string $label): self
	{
		$this->addField($name, $label, 'checkbox');

		return $this;
	}

	/**
	 * Add a boolean field to fields.
	 */
	public function boolean(string $name, string $label): self
	{
		$this->addField($name, $label, 'boolean');

		return $this;
	}

	/**
	 * Add a upload field to fields.
	 */
	public function upload(string $name, string $label): self
	{
		$this->addField($name, $label, 'upload');

		return $this;
	}

	/**
	 * Add a colour field to fields.
	 */
	public function colour(string $name, string $label): self
	{
		$this->addField($name, $label, 'colour');

		return $this;
	}

	/**
	 * Add a info field to fields.
	 */
	public function info(string $name, string $label): self
	{
		$this->addField($name, $label, 'info');

		return $this;
	}

	/**
	 * Add field to fields.
	 */
	public function addField(string $name, string $label, string $type): void
	{
		$this->fields[] = compact('name', 'label', 'type');
	}

	/**
	 * Adds the appropriate field type.
	 */
	public function showField(array $field): void
	{
		$type = 'show' . ucfirst($field['type']);

		$this->$type($field);
	}

	/**
	 * Generates a text field.
	 */
	public function showText(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<input type="text" name="<?= $field['name']; ?>" value="<?= $this->getValue($field); ?>">
		</label>
	<?php }

	/**
	 * Create a date field.
	 */
	public function showDate(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<input type="date" name="<?= $field['name']; ?>" value="<?= $this->getValue($field); ?>">
		</label>
	<?php }

	/**
	 * Create an email field.
	 */
	public function showEmail(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<input type="email" name="<?= $field['name']; ?>" value="<?= $this->getValue($field); ?>">
		</label>
	<?php }

	/**
	 * Create an number field.
	 */
	public function showNumber(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<input type="number" name="<?= $field['name']; ?>" value="<?= $this->getValue($field); ?>">
		</label>
	<?php }

	/**
	 * Create an range field.
	 */
	public function showRange(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<input type="range" name="<?= $field['name']; ?>" value="<?= $this->getValue($field); ?>">
		</label>
	<?php }

	/**
	 * Create an url field.
	 */
	public function showUrl(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<input type="url" name="<?= $field['name']; ?>" value="<?= $this->getValue($field); ?>">
		</label>
	<?php }

	/**
	 * Create an password field.
	 */
	public function showPassword(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<input type="password" name="<?= $field['name']; ?>" value="<?= $this->getValue($field); ?>">
		</label>
	<?php }

	/**
	 * Generates a textarea.
	 */
	public function showTextarea(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<textarea name="<?= $field['name']; ?>" cols="50" rows="10"><?= $this->getValue($field); ?></textarea>
		</label>
	<?php }

	/**
	 * Generates a WordPress editor.
	 */
	public function showEditor(array $field): void
	{ ?>
		<div class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>

			<?php wp_editor($this->getValue($field), $field['name'], ['textarea_name' => $field['name']]); ?>
		</div>
	<?php }

	/**
	 * Generates a select box.
	 */
	public function showSelect(array $field): void
	{ ?>
		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<select name="<?= $field['name']; ?>">
				<option value=""><?= __('-- select --', 'scafall'); ?></option>
				<?php foreach ($field['options'] as $value => $label) : ?>
					<option value="<?= $value; ?>" <?php selected($this->getValue($field), $value); ?>><?= $label; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	<?php }

	/**
	 * Generates a list of radio buttons.
	 */
	public function showRadio(array $field): void
	{ ?>
		<div class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<?php foreach ($field['options'] as $value => $label) : ?>
				<label><?= $label; ?>
					<input type="radio" name="<?= $field['name']; ?>" value="<?= $value; ?>" <?php checked($this->getValue($field), $value); ?>>
				</label>
			<?php endforeach; ?>
		</div>
	<?php }

	/**
	 * Generates a list of checkboxes.
	 */
	public function showCheckbox(array $field): void
	{ ?>
		<div class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<?php
			$i = 0;
			$option = $this->getValue($field, []);

			foreach ($field['options'] as $value => $label) : ?>
				<label><?= $label; ?>
					<input type="checkbox" name="<?= $field['name']; ?>[]" value="<?= $value; ?>" <?php checked(in_array($value, $option), true); ?>>
				</label>
			<?php $i++;

			endforeach;
			?>
		</div>
	<?php }

	/**
	 * Generates a single checkbox.
	 */
	public function showBoolean(array $field): void
	{
		$option = $this->getValue($field); ?>

		<label class="scafall-field">
			<span class="scafall-label"><?= $field['label'] ?></span>
			<input type="checkbox" name="<?= $field['name']; ?>" value="1" <?php checked($option, '1'); ?>>
		</label>
	<?php }

	/**
	 * Generates a colour picker.
	 */
	public function showColour(array $field): void
	{ ?>
		<div class="scafall-field">
			<span class="scafall-label"><?= $field['label']; ?></span>
			<label>
				<input type="text" name="<?= $field['name']; ?>" value="<?= $this->getValue($field); ?>" class="scafall-colour-picker">
			</label>
		</div>
	<?php }

	/**
	 * Generates an upload field.
	 */
	public function showUpload(array $field): void
	{
		$value = $this->getValue($field);
		$upload = $value ? get_post($value) : null;
		$isImage = $upload ? str_contains(get_post_mime_type($upload), 'image/') : false; ?>

		<div class="scafall-field">
			<div class="scafall-upload">
				<label>
					<span class="scafall-label"><?= $field['label']; ?></span>
					<input type="hidden" name="<?= $field['name']; ?>" value="<?= $value; ?>" class="scafall-upload-id">
				</label>

				<div class="scafall-upload-preview <?= $upload ? 'scafall-show' : ''; ?>">
					<img class="scafall-upload-image <?= $isImage ? 'scafall-show' : ''; ?>" src="<?= ($upload && $isImage) ? wp_get_attachment_image_url($upload->ID, 'thumbnail') : ''; ?>">
					<div class="scafall-upload-file <?= !$isImage ? 'scafall-show' : ''; ?>"><?= ($upload && !$isImage) ? wp_get_attachment_url($upload->ID) : ''; ?></div>
					<button type="button" class="scafall-upload-remove" aria-label="<?php _e('Remove image', 'scafall'); ?>">
						<i class="dashicons dashicons-no-alt"></i>
					</button>
				</div>

				<button class="button scafall-upload-select" type="button">
					<?php _e('Select', 'scafall'); ?>
				</button>
			</div>
		</div>
	<?php }

	/**
	 * Generates a info section.
	 */
	public function showInfo(array $field): void
	{ ?>
		<p class="scafall-field scafall-info">
			<?= $field['label']; ?>
		</p>
<?php }

	/**
	 * Gets an options value.
	 */
	public function getValue(array $field, mixed $default = ''): mixed
	{
		global $post;

		$context = (new \ReflectionClass($this))->getShortName();

		if ($context === 'Option') {
			return get_option($field['name'], $default) ?: $default;
		}

		return get_post_meta($post->ID, $field['name'], true) ?: $default;
	}

	/**
	 * Sanitize a fields value.
	 */
	public function sanitizeField(mixed $value, array $field): mixed
	{
		switch ($field['type']) {
			case 'number':
			case 'boolean':
				$value = (int) $value;
				break;
			case 'email':
				$value = sanitize_email($value);
				break;
			case 'url':
				$value = sanitize_url($value);
				break;
			case 'checkbox':
				$value = is_array($value) ? array_map('sanitize_text_field', $value) : [];
				break;
			case 'textarea':
				$value = sanitize_textarea_field($value);
				break;
			case 'editor':
				$value = wp_kses_post($value);
				break;
			default:
				$value = sanitize_text_field($value);
				break;
		}

		return $value;
	}
}
