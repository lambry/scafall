<?php
/**
 * Create new settings page.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

defined('ABSPATH') || exit;

class Setting {

	// Variables
    private $type;
    private $menu;
    private $title;
	private $menu_slug;
    private $sections = [];
    private $options;
    private $page;

    /**
     * Return an instance of the class.
     *
     * @access public
     * @param mixed add()
     * @param object $this
     */
	public function __construct($type, $menu, $title) {

        // Set variables
		$this->type = $type;
        $this->menu = $menu;
        $this->title = $title;
        $this->menu_slug = sanitize_title_with_dashes($menu);

        return $this;

    }

    /**
     * Set up new setting instance.
     *
     * @access public
     * @param string $type
     * @param string $menu
     * @param string $title
     * @param object $setting
     */
    public static function add(string $type, string $menu, string $title) {

        return new Setting($type, $menu, $title);

    }

    /**
     * Set up a new section.
     *
     * @access public
     * @param string $id
     * @param string $title
     * @param string $description
     * @param array $fields
     */
    public function section(string $id, string $title, string $description, array $fields) {

        $this->sections[] = compact('id', 'title', 'description', 'fields');

        return $this;

    }

    /**
     * Set up options and register.
     *
     * @access public
     * @param string $options
     * @return void
     */
    public function set($options = []) {

        $this->options = wp_parse_args($options, $this->options());

		// Add admin menu
		add_action('admin_menu', [$this, 'menu']);

		// Add admin assets
		add_action('admin_enqueue_scripts', [$this, 'assets']);

		// Register settings
		add_action('admin_init', [$this, 'register']);

	}

	/**
	 * Adds the appropriate menu type.
	 *
	 * @access public
	 * @return void
	 */
	public function menu() {

		switch ($this->type) {
			case 'menu':
				$this->page = add_menu_page($this->title, $this->menu, $this->options['capability'], $this->menu_slug, [$this, 'page'], $this->options['icon_url'], $this->options['position']);
				break;

			case 'theme':
				$this->page = add_theme_page($this->title, $this->menu, $this->options['capability'], $this->menu_slug, [$this, 'page']);
				break;

			case 'option':
				$this->page = add_options_page($this->title, $this->menu, $this->options['capability'], $this->menu_slug, [$this, 'page']);
				break;

			case 'management':
				$this->page = add_management_page($this->title, $this->menu, $this->options['capability'], $this->menu_slug, [$this, 'page']);
				break;

			case 'submenu':
				$this->page = add_submenu_page($this->type, $this->title, $this->menu, $this->options['capability'], $this->menu_slug, [$this, 'page']);
				break;
		}

	}

	/**
	 * Loads all assets.
	 *
	 * @access public
	 * @param  string $hook
	 * @return void
	 */
	public function assets(string $hook) {

		if ($this->page !== $hook) return;

		wp_enqueue_media();
		wp_enqueue_style('kickoff-setting-styles', KICKOFF_URL . 'assets/styles/setting.css', ['wp-color-picker'], '0.1.0');
		wp_enqueue_script('kickoff-setting-scripts', KICKOFF_URL . 'assets/scripts/setting.js', ['jquery','wp-color-picker'], '0.1.0', true);

	}

	/**
	 * Registers all section settings.
	 *
	 * @access public
	 * @return void
	 */
	public function register() {

		// Set up each setting section
		foreach ($this->sections as $section) {

			$setting_id = KICKOFF_PREFIX . $section['id'];

			// Create option
			if (! get_option($setting_id)) {
				add_option($setting_id);
			}

			// Add settings section
			add_settings_section($section['id'], $section['title'], [$this, 'section_description'], $setting_id);

			// Add settings fields
			foreach ($section['fields'] as $field) {
				$field['section'] = $setting_id;
				add_settings_field($field['id'], $field['label'], [$this, 'add_field'], $setting_id, $section['id'], $field);
			}

			// Register setting
			register_setting($setting_id, $setting_id);

		}

	}

	/**
	 * Registers the page content and section settings.
	 *
	 * @access public
	 * @return void
	 */
	public function page() { ?>

		<div id="<?php echo $this->menu_slug; ?>" class="wrap kickoff-setting">
			<h2><?php echo $this->title; ?></h2>
			<?php settings_errors(); ?>

			<h2 class="nav-tab-wrapper">
				<?php foreach ($this->sections as $section) : ?>
					<a href="?page=<?php echo $this->menu_slug; ?>&amp;tab=<?php echo $section['id']; ?>"
					   class="nav-tab <?php echo ($section['id'] === $this->tab()) ? 'nav-tab-active' : ''; ?>">
						<?php echo $section['title']; ?>
					</a>
				<?php endforeach; ?>
			</h2>

			<form action="options.php" method="POST" enctype="post">
				<?php
					foreach ($this->sections as $section) {
						if ($section['id'] === $this->tab()) {
							$section_name = KICKOFF_PREFIX . $section['id'];
							settings_fields($section_name);
							do_settings_sections($section_name);
							submit_button();
						}
					}
				?>
			</form>

		</div>

	<?php }

	/**
	 * Displays the sections description.
	 *
	 * @access public
	 * @param  array $section
	 * @return void
	 */
	public function section_description(array $section) {

		foreach ($this->sections as $sections) {
			if ($sections['id'] === $section['id']) {
				echo "<p class='section-description'>{$sections['description']}</p>";
			}
		}

	}

	/**
	 * Adds the appropriate field type.
	 *
	 * @access public
	 * @param  array $field
	 * @return void
	 */
	public function add_field(array $field) {

		switch ($field['type']) {
			case 'text':
				$this->text($field);
				break;

			case 'textarea':
				$this->textarea($field);
				break;

			case 'editor':
				$this->editor($field);
				break;

			case 'select':
				$this->select($field);
				break;

			case 'radio':
				$this->radio($field);
				break;

			case 'checkbox':
				$this->checkbox($field);
				break;

			case 'on_off':
				$this->on_off($field);
				break;

			case 'upload':
				$this->upload($field);
				break;

			case 'color':
				$this->color($field);
				break;

			case 'block':
				$this->block($field);
				break;

			default:
				$this->text($field);
				break;
		}

	}

	/**
	 * Generates a text field.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function text(array $field) { ?>

		<input type="text" name="<?php echo $this->name($field); ?>" value="<?php echo $this->value($field); ?>">

		<?php $this->description($field);

	}

	/**
	 * Generates a textarea.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function textarea(array $field) { ?>

		<textarea name="<?php echo $this->name($field); ?>" cols="50" rows="10"><?php echo $this->value($field); ?></textarea>

		<?php $this->description($field);

	}

	/**
	 * Generates a WordPress editor.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function editor(array $field) {

		wp_editor($this->value($field), $field['id'], [ 'textarea_name' => $this->name($field) ]);

		$this->description($field);

	}

	/**
	 * Generates a select box.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function select(array $field) { ?>

		<select name="<?php echo $this->name($field); ?>">
			<option value=""><?php echo __('-- select --', 'kickoff'); ?></option>
			<?php foreach ($field['choices'] as $value => $label) : ?>
				<option value="<?php echo $value; ?>" <?php selected($this->value($field), $value); ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
		</select>

		<?php $this->description($field);

	}

	/**
	 * Generates a list of radio buttons.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function radio(array $field) {

		foreach ($field['choices'] as $value => $label) : ?>
			<label><?php echo $label; ?>
				<input type="radio" name="<?php echo $this->name($field); ?>"
					   value="<?php echo $value; ?>" <?php checked($this->value($field), $value); ?>>
			</label>
		<?php endforeach;

		$this->description($field);

	}

	/**
	 * Generates a list of checkboxes.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function checkbox(array $field) {

		$option = $this->value($field, []); ?>

		<?php $i = 1;
		foreach ($field['choices'] as $value => $label) :
			if (! isset($option[$i])) $option[$i] = false; ?>
			<label><?php echo $label; ?>
				<input type="checkbox" name="<?php echo $this->name($field) . "[{$i}]"; ?>"
					   value="<?php echo $value; ?>" <?php checked($option[$i], $value); ?>>
			</label>
		<?php $i++; endforeach;

		$this->description($field);

	}

	/**
	 * Generates a single checkbox.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function on_off(array $field) {

		$option = $this->value($field); ?>

		<label>
			<input type="checkbox" name="<?php echo $this->name($field); ?>" value="1" <?php checked($option, '1'); ?>>
		</label>

		<?php $this->description($field);

	}

	/**
	 * Generates an upload field.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function upload(array $field) {

		$option = $this->value($field); ?>

		<div class="upload">
			<div class="upload-image <?php echo (! $option) ? 'hide' : ''; ?>">
				<img src="<?php echo $option; ?>" alt="<?php echo $option; ?>">
				<i class="upload-remove dashicons dashicons-no-alt"></i>
			</div>

			<input type="hidden" name="<?php echo $this->name($field); ?>" value="<?php echo $option; ?>" class="upload-file">
			<button class="button upload-select" type="button"><?php _e('Select', 'kickoff'); ?></button>

			<?php $this->description($field); ?>
		</div>

	<?php }

	/**
	 * Generates a color picker.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function color(array $field) { ?>

		<input type="text" name="<?php echo $this->name($field); ?>" value="<?php echo $this->value($field); ?>" class="color-picker">

		<?php $this->description($field);

	}

	/**
	 * Generates a block section.
	 *
	 * @access private
	 * @param  array $field
	 * @return void
	 */
	private function block(array $field) { ?>

		<div class="block">
			<?php echo $field['content']; ?>
		</div>

	<?php }

	/**
	 * Generates a field name.
	 *
	 * @access private
	 * @param  string $field
	 * @return string $name
	 */
	private function name(array $field) {

		return $field['section'] . '[' . $field['id'] . ']';

	}

	/**
	 * Gets the current fields value.
	 *
	 * @access private
	 * @param array $field
	 * @param mixed $default
	 * @return string $option
	 */
	private function value(array $field, $default = '') {

		$options = get_option($field['section']);
		return $options[$field['id']] ?? $default;

	}

	/**
	 * Displays the fields description.
	 *
	 * @access private
	 * @param array $field
	 * @return void
	 */
	private function description(array $field) {

		if (isset($field['description'])) : ?>
			<p class="setting-description"><?php echo $field['description']; ?></p>
		<?php endif;

	}

	/**
	 * Gets the current tab.
	 *
	 * @access private
	 * @return string $tab
	 */
	private function tab() {

		return $_GET['tab'] ?? $this->sections[0]['id'];

	}

	/**
	 * Gets and display a setting.
	 *
	 * @access public
	 * @param  string $section
	 * @param  string $field
	 * @return string $setting
	 */
	public static function show(string $section, string $field) {

		echo self::get($section, $field);

	}

	/**
	 * Gets a setting value.
	 *
	 * @access public
	 * @param  string $section
	 * @param  string $field
	 * @param  mixed  $default
	 * @return string $setting
	 */
	public static function get(string $section, $field = '', $default = false) {

		$setting = get_option(KICKOFF_PREFIX . $section, $default);

		if ($setting && $field && isset($setting[$field])) {
			$setting = $setting[$field];
		}

		return $setting;

	}

    /**
     * Setup the default options.
     *
     * @access private
     * @return array $options
     */
    private function options() {

        return [
            'capability' => 'manage_options',
            'icon_url'   => 'dashicons-exerpt-view',
            'position'   => 80
    	];

    }

}
