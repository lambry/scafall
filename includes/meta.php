<?php
/**
 * Create a new meta box.
 *
 * @package kickoff
 */

namespace Lambry\Kickoff;

defined('ABSPATH') || exit;

class Meta {

    private $id;
    private $title;
    private $description;
    private $types;
    private $fields = [];
    private $options;

    /**
     * Return an instance of the class.
     *
     * @access public
     * @param mixed add()
     * @param object $this
     */
    public function __construct(string $id, string $title, string $description) {

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;

        return $this;

    }

    /**
     * Set up new metabox details.
     *
     * @access public
     * @param string $id
     * @param string $title
     * @param string $description
     * @param object $metabox
     */
    public static function add(string $id, string $title, string $description = '') : Meta {

        return new Meta($id, $title, $description);

    }

    /**
     * Set fields for metabox.
     *
     * @access public
     * @param array $fields
     * @return void
     */
    public function fields(array $fields) {

        $this->fields = array_merge($this->fields, $fields);

        return $this;

    }

    /**
     * Set repeater fields for metabox.
     *
     * @access public
     * @param string $id
     * @param string $label
     * @param string $description
     * @param array  $fields
     * @return void
     */
    public function repeat(string $id, string $label, string $description, array $fields) {

		$type = 'repeater';

		$this->fields[] = array_merge(compact('id', 'label', 'description', 'type'), ['fields' => $fields]);

        return $this;

	}

	/**
     * Set post types to add metabox to.
     *
     * @access public
     * @param array $types
     * @return object $this
     */
    public function to($types) {

        $this->types = array_map('sanitize_title_with_dashes', (array) $types);

        return $this;

    }

    /**
     * Set options and get the ball rolling.
     *
     * @access public
     * @param array $options
     * @return void
     */
    public function set($options = []) {

        $this->options = wp_parse_args($options, $this->options());

        // Load assets
        add_action('admin_enqueue_scripts', [$this, 'assets']);

        // Register sanitizers
        $this->sanitizers();

        // Register meta boxes
        add_action('add_meta_boxes', [$this, 'register']);

        // Add actions to save meta boxes
        foreach ($this->types as $type) {
            add_action('save_post_' . $type, [$this, 'update']);
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

        if ($hook !== 'post-new.php' && $hook !== 'post.php') return;

        wp_enqueue_media();
        wp_enqueue_style('kickoff-meta-styles', KICKOFF_URL . 'assets/styles/meta.css', ['wp-color-picker'], '1.0.0');
        wp_enqueue_script('kickoff-meta-scripts', KICKOFF_URL . 'assets/scripts/meta.js', ['jquery', 'wp-color-picker'], '1.0.0', true);

    }

    /**
     * Register sanitizers for meta boxes.
     *
     * @access public
     * @return void
     */
    public function sanitizers() {

        foreach ($this->fields as $field) {

            switch ($field['type']) {
                case 'checkbox':
                case 'repeater':
                    register_meta('post', $field['id'], [
                        'sanitize_callback' => [$this, 'sanitize_array']
                    ]);
                    break;
                case 'editor':
                    break;
                default:
                    register_meta('post', $field['id'], [
                        'sanitize_callback' => 'wp_strip_all_tags'
                    ]);
                    break;
            }

        }

    }

    /**
     * Register the meta box.
     *
     * @access public
     * @return void
     */
    public function register() {

        foreach ($this->types as $type) {
            add_meta_box($this->id, $this->title, [$this, 'display'], $type, $this->options['context'], $this->options['priority']);
        }

	}

    /**
     * Display the meta box and fields.
     *
     * @access public
     * @return void
     */
    public function display() { ?>

        <div class="kickoff-meta">
            <?php
                wp_nonce_field('kickoff_meta_box', 'kickoff_meta_box_nonce');

                if ($this->description) {
                    echo "<p class='meta-description'>{$this->description}</p>";
                }

                foreach ($this->fields as $field) {
                    $this->add_field($field);
                }
            ?>
        </div>

        <?php

    }

	/**
     * Update all meta box data.
     *
     * @access public
     * @param  int $post_id
     * @return void
     */
    public function update(int $post_id) {

        if (! current_user_can('edit_post', $post_id)) return;

        // Check for nonce and autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || ! isset($_POST['kickoff_meta_box_nonce']) ||
             ! wp_verify_nonce($_POST['kickoff_meta_box_nonce'], 'kickoff_meta_box')) {
            return;
        }

        // Save all meta fields
		foreach ($this->fields as $field) {

			$value = (isset($_POST[$this->name($field)])) ? $_POST[$this->name($field)] : '';

			if ($field['type'] === 'repeater' && is_array($value)) {
				$value = $this->filter_array($value);
			}

			update_post_meta($post_id, $this->name($field), $value);

		}

	}

    /**
     * Display the appropraite field.
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

            case 'repeater':
                $this->repeater($field);
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

        <div class="meta-field meta-text">
            <label><?php echo $field['label']; ?></label>
            <input type="text" name="<?php echo $this->name($field); ?>" value="<?php echo $this->value($field); ?>">
            <?php $this->description($field); ?>
        </div>

    <?php }

    /**
     * Generates a textarea.
     *
     * @access private
     * @param  array $field
     * @return void
     */
    private function textarea(array $field) { ?>

        <div class="meta-field meta-textarea">
            <label><?php echo $field['label']; ?></label>
            <textarea name="<?php echo $this->name($field); ?>" cols="50" rows="10"><?php echo $this->value($field); ?></textarea>
            <?php $this->description($field); ?>
        </div>

    <?php }

    /**
     * Generates a WordPress editor.
     *
     * @access private
     * @param  array $field
     * @return void
     */
    private function editor(array $field) { ?>

        <div class="meta-field meta-editor">
            <label><?php echo $field['label']; ?></label>
            <?php wp_editor($this->value($field), $field['id'], [ 'textarea_name' => $this->name($field) ]); ?>
            <?php $this->description($field); ?>
        </div>

    <?php }

    /**
     * Generates a select box.
     *
     * @access private
     * @param  array $field
     * @return void
     */
    private function select(array $field) { ?>

        <div class="meta-field meta-select">
            <label><?php echo $field['label']; ?></label>

            <select name="<?php echo $this->name($field); ?>">
                <option>-- select --</option>
                <?php foreach ($field['choices'] as $value => $label) : ?>
                    <option value="<?php echo $value; ?>" <?php selected($this->value($field), $value); ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php $this->description($field); ?>
        </div>

    <?php }

    /**
     * Generates a list of radio buttons.
     *
     * @access private
     * @param  array $field
     * @return void
     */
    private function radio(array $field) { ?>

        <div class="meta-field meta-radio">
            <?php foreach ($field['choices'] as $value => $label) : ?>
                <label><?php echo $label; ?>
                    <input type="radio" name="<?php echo $this->name($field); ?>" value="<?php echo $value; ?>" <?php checked($this->value($field), $value); ?>>
                </label>
            <?php endforeach; ?>
            <?php $this->description($field); ?>
        </div>

    <?php }

    /**
     * Generates a list of checkboxes.
     *
     * @access private
     * @param  array $field
     * @return void
     */
    private function checkbox(array $field) {

        $option = $this->value($field); ?>

        <div class="meta-field meta-checkbox">
            <?php $i = 0; foreach ($field['choices'] as $value => $label) :
                $checked = (is_array($option) && in_array($value, $option)) ? true : false; ?>
                <label><?php echo $label; ?>
                    <input type="checkbox" name="<?php echo $this->name($field) . "[{$i}]"; ?>" value="<?php echo $value; ?>" <?php checked(true, $checked); ?>>
                </label>
            <?php $i++; endforeach; ?>
            <?php $this->description($field); ?>
        </div>

    <?php }

    /**
     * Generates a single checkbox.
     *
     * @access private
     * @param  array $field
     * @return void
     */
    private function on_off(array $field) {

        $option = $this->value($field); ?>

        <div class="meta-field meta-on-off">
            <label><?php echo $field['label']; ?>
                <input type="checkbox" name="<?php echo $this->name($field); ?>" value="1" <?php checked($option, '1'); ?>>
            </label>
            <?php $this->description($field); ?>
        </div>

    <?php }

    /**
     * Generates an upload field.
     *
     * @access private
     * @param  array $field
     * @return void
     */
    private function upload(array $field) {

        $option = $this->value($field); ?>

        <div class="meta-field meta-upload">
            <label><?php echo $field['label']; ?></label>

            <div class="upload">
                <div class="upload-image <?php echo (! $option) ? 'hide' : ''; ?>">
                    <img src="<?php echo $option; ?>" alt="<?php echo $option; ?>">
                    <i class="upload-remove remove dashicons dashicons-no-alt"></i>
                </div>

                <input type="hidden" name="<?php echo $this->name($field); ?>" value="<?php echo $option; ?>" class="upload-file">
                <button class="button upload-select block" type="button"><?php _e('Select', 'bow'); ?></button>
                <?php $this->description($field); ?>
            </div>

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

        <div class="meta-field meta-text">
            <label><?php echo $field['label']; ?></label>
            <input type="text" name="<?php echo $this->name($field); ?>" value="<?php echo $this->value($field); ?>" class="color-picker">
            <?php $this->description($field); ?>
        </div>

    <?php }

    /**
     * Generates repeater fields.
     *
     * @access private
     * @param  array $fields
     * @return void
     */
    private function repeater($fields) {

        global $post;

        $meta = get_post_meta($post->ID, $this->name($fields), false);
        $repeats = (is_array($meta) && ! empty($meta[0])) ? $meta[0] : [ 'default' ]; ?>

        <p><strong><?php echo $fields['label']; ?></strong></p>
        <div class="meta-repeater">
            <div class="meta-sortable">
                <?php $i = 0;
                    foreach ($repeats as $repeat) : ?>
                    <div data-index="<?php echo $i; ?>" class="repeater <?php echo ($repeat === 'default') ? 'hide' : ''; ?>">
                        <i class="repeater-remove remove dashicons dashicons-no-alt"></i>
                        <?php
                            foreach ($fields['fields'] as $field) {
                                $field['repeater'] = [
                                    'inc'   => $i,
                                    'id'    => $fields['id'],
                                    'value' => (isset($repeat[$field['id']])) ? $repeat[$field['id']] : '',
                                ];
                                $this->add_field($field);
                            }
                        ?>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
            <?php $this->description($fields); ?>
            <button class="button repeater-add block" type="button">
                <?php echo (isset($fields['button'])) ? $fields['button'] : _e('Add New +', 'bow'); ?>
            </button>
        </div>

    <?php }

    /**
     * Generates a field name.
     *
     * @access public
     * @param  string $field
     * @return string $name
     */
    public function name(array $field) : string {

        if (isset($field['repeater'])) {
            return KICKOFF_PREFIX . $field['repeater']['id'] . '[' . $field['repeater']['inc'] . ']' . '[' . $field['id'] . ']';
        } else {
            return KICKOFF_PREFIX . $field['id'];
        }

    }

    /**
     * Displays the fields description.
     *
     * @access private
     * @param  array $field
     * @return void
     */
    private function description(array $field) {

        if (isset($field['description'])) : ?>
            <p class="description"><?php echo $field['description']; ?></p>
        <?php endif;

    }

    /**
     * Gets the current fields value.
     *
     * @access private
     * @param  array $field
     * @return mixed $meta_value
     */
    private function value(array $field) {

        global $post;

        if (isset($field['repeater'])) {
            return $field['repeater']['value'];
        } else {
            return get_post_meta($post->ID, $this->name($field), true);
        }

    }

	/**
	 * Helper function to display a meta value.
	 *
	 * @access public
	 * @param string $key
	 * @param int $id
	 * @param bool $single
	 * @return void
	 */
	public static function show($key, $id = 0, $single = true) {

		echo self::get($key, $id, $single);

	}

	/**
	 * Helper function to get a meta value.
	 *
	 * @access public
	 * @param string $key
	 * @param int $id
	 * @param bool $single
	 * @return mixed $value
	 */
	public static function get($key, $id = 0, $single = true) {

		if (! $id) {
			global $post;
			$id = $post->ID;
		}

		return get_post_meta($id, KICKOFF_PREFIX . $key, $single);

	}

    /**
     * Setup the default options.
     *
     * @access private
     * @return array $options
     */
    private function options() : array {

        return [
            'context'  => 'normal',
            'priority' => 'high'
        ];

    }

    /**
     * Sanitizes a meta array, i.e repeaters.
     *
     * @access public
     * @param  array $array
     * @return array $array
     */
    public function sanitize_array($array) : array {

        if (! is_array($array)) {
            return wp_strip_all_tags($array);
        }

        array_walk_recursive($array, function(&$value, $key) {
            if (! is_array($value)) {
                $value = wp_strip_all_tags($value);
            }
        });

        return $array;

    }

    /**
     * Filters out empty repeaters.
     *
     * @access public
     * @param  array $array
     * @return array $filtered_array
     */
    public function filter_array(array $array) : array {

        $filtered_array = [];

        foreach ($array as $item) {

            $filtered_items = array_filter($item, function($value, $key) {
                return $value;
            }, ARRAY_FILTER_USE_BOTH);

            if ($filtered_items) {
                array_push($filtered_array, $item);
            }

        }

        return $filtered_array;

    }

}
