<?php
/**
 * Settings
 *
 * Create new settings page.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

defined( 'ABSPATH' ) || exit;

/* Settings Class */
class Settings {

	/* Variables */
	private $type;
	private $settings;
	private $title;
	private $title_clean;
	private $menu;
	private $menu_title;
	private $page;

	/**
	 * Construct
	 *
	 * Creates new settings pages.
	 *
	 * @param string $type
	 * @param array  $settings
	 * @param string $title
	 * @param string $menu
	 * @param string $menu_title
	 */
	public function __construct( $type, $settings, $title, $menu = '', $menu_title = '' ) {

		if ( ! is_admin() ) return;

		// Set variables
		$this->type = $type;
		$this->settings = $settings;
		$this->title = $title;
		$this->title_clean = sanitize_title_with_dashes( $this->title );
		$this->menu = $menu;
		$this->menu_title = ( $menu_title ) ? $menu_title : $title;

		// Register settings
		add_action( 'admin_init', [ $this, 'register_settings' ] );

		// Add admin menu
		add_action( 'admin_menu', [ $this, 'add_menu' ] );

		// Add admin assets
		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );

	}

	/**
	 * Add Menu
	 *
	 * Adds the appropriate menu type.
	 *
	 * @access public
	 * @return null
	 */
	public function add_menu() {

		switch ( $this->type ) {
			case 'menu':
				$this->page = add_menu_page( $this->title, $this->menu_title, 'manage_options', $this->title_clean, [ $this, 'register_page' ] );
				break;

			case 'theme':
				$this->page = add_theme_page( $this->title, $this->menu_title, 'manage_options', $this->title_clean, [ $this, 'register_page' ] );
				break;

			case 'option':
				$this->page = add_options_page( $this->title, $this->menu_title, 'manage_options', $this->title_clean, [ $this, 'register_page' ] );
				break;

			case 'management':
				$this->page = add_management_page( $this->title, $this->menu_title, 'manage_options', $this->title_clean, [ $this, 'register_page' ] );
				break;

			case 'submenu':
				$this->page = add_submenu_page( $this->menu, $this->title, $this->menu_title, 'manage_options', $this->title_clean, [ $this, 'register_page' ] );
				break;
		}

	}

	/**
	 * Load Assets
	 *
	 * Loads all assets.
	 *
	 * @access public
	 * @param  string $hook
	 * @return null
	 */
	public function load_assets( $hook ) {

		if ( $this->page !== $hook ) return;

		// Load settings css
		wp_enqueue_style( 'kickoff-settings-style', plugin_dir_url( __FILE__ ) . 'assets/styles/settings.css', [ 'wp-color-picker' ], '0.1.0' );
		// Load media assets
		wp_enqueue_media();
		// Load settings js
		wp_enqueue_script( 'kickoff-settings-scripts', plugin_dir_url( __FILE__ ) . 'assets/scripts/settings.js', [ 'jquery','wp-color-picker' ], '0.1.0', true );

	}

	/**
	 * Register settings
	 *
	 * Registers all section settings.
	 *
	 * @access public
	 * @return null
	 */
	public function register_settings() {

		// Set up each setting section
		foreach ( $this->settings as $section ) {

			$setting_id = $this->setting_name( $this->title_clean, $section['id'] );

			// Create option
			if ( ! get_option( $setting_id ) ) {
				add_option( $setting_id );
			}

			// Add settings section
			add_settings_section( $section['id'], $section['title'], [ $this, 'section_description' ], $setting_id );

			// Add settings fields
			foreach ( $section['fields'] as $field ) {
				$field['section'] = $setting_id;
				add_settings_field( $field['id'], $field['label'], [ $this, 'add_field' ], $setting_id, $section['id'], $field );
			}

			// Register setting
			register_setting( $setting_id, $setting_id );

		}

	}

	/**
	 * Register Page
	 *
	 * Registers the page content and section settings.
	 *
	 * @access public
	 * @return null
	 */
	public function register_page() { ?>

		<div id="<?php echo $this->title_clean; ?>" class="wrap kickoff-settings">
			<h2><?php echo $this->title; ?></h2>
			<?php settings_errors(); ?>

			<h2 class="nav-tab-wrapper">
				<?php foreach ( $this->settings as $section ) : ?>
					<a href="?page=<?php echo $this->title_clean; ?>&amp;tab=<?php echo $section['id']; ?>"
					   class="nav-tab <?php echo ( $section['id'] === $this->current_tab() ) ? 'nav-tab-active' : ''; ?>">
						<?php echo $section['title']; ?>
					</a>
				<?php endforeach; ?>
			</h2>

			<form action="options.php" method="POST" enctype="post">
				<?php
					foreach ( $this->settings as $section ) {
						if ( $section['id'] === $this->current_tab() ) {
							$setting_name = $this->setting_name( $this->title_clean, $section['id'] );
							settings_fields( $setting_name );
							do_settings_sections( $setting_name );
							submit_button();
						}
					}
				?>
			</form>

		</div>

	<?php }

	/**
	 * Section Description
	 *
	 * Displays the sections description.
	 *
	 * @access public
	 * @param  array $section
	 * @return null
	 */
	public function section_description( $section ) {

		foreach ( $this->settings as $setting ) {
			if ( $setting['id'] === $section['id'] ) {
				echo "<p class='section-description'>{$setting['description']}</p>";
			}
		}

	}

	/**
	 * Add Field
	 *
	 * Adds the appropriate field type.
	 *
	 * @access public
	 * @param  array $field
	 * @return null
	 */
	public function add_field( $field ) {

		switch ( $field['type'] ) {
			case 'text':
				$this->text( $field );
				break;

			case 'textarea':
				$this->textarea( $field );
				break;

			case 'editor':
				$this->editor( $field );
				break;

			case 'select':
				$this->select( $field );
				break;

			case 'radio':
				$this->radio( $field );
				break;

			case 'checkbox':
				$this->checkbox( $field );
				break;

			case 'on_off':
				$this->on_off( $field );
				break;

			case 'upload':
				$this->upload( $field );
				break;

			case 'color':
				$this->color( $field );
				break;

			case 'block':
				$this->block( $field );
				break;

			default:
				$this->text( $field );
				break;
		}

	}

	/**
	 * Text
	 *
	 * Generates a text field.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function text( $field ) { ?>

		<input type="text" name="<?php echo $this->field_name( $field ); ?>" value="<?php echo $this->field_value( $field ); ?>">

		<?php $this->field_description( $field );

	}

	/**
	 * Textarea
	 *
	 * Generates a textarea.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function textarea( $field ) { ?>

		<textarea name="<?php echo $this->field_name( $field ); ?>" cols="50" rows="10"><?php echo $this->field_value( $field ); ?></textarea>

		<?php $this->field_description( $field );

	}

	/**
	 * Editor
	 *
	 * Generates a WordPress editor.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function editor( $field ) {

		wp_editor( $this->field_value( $field ), $field['id'], [ 'textarea_name' => $this->field_name( $field ) ] );

		$this->field_description( $field );

	}

	/**
	 * Select
	 *
	 * Generates a select box.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function select( $field ) { ?>

		<select name="<?php echo $this->field_name( $field ); ?>">
			<option value=""><?php echo __( '-- select --', 'kickoff' ); ?></option>
			<?php foreach ( $field['choices'] as $value => $label ) : ?>
				<option value="<?php echo $value; ?>" <?php selected( $this->field_value( $field ), $value ); ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
		</select>

		<?php $this->field_description( $field );

	}

	/**
	 * Radio
	 *
	 * Generates a list of radio buttons.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function radio( $field ) {

		foreach ( $field['choices'] as $value => $label ) : ?>
			<label><?php echo $label; ?>
				<input type="radio" name="<?php echo $this->field_name( $field ); ?>"
					   value="<?php echo $value; ?>" <?php checked( $this->field_value( $field ), $value ); ?>>
			</label>
		<?php endforeach;

		$this->field_description( $field );

	}

	/**
	 * Checkbox
	 *
	 * Generates a list of checkboxes.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function checkbox( $field ) {

		$option = $this->field_value( $field ); ?>

		<?php $i = 1;
		foreach ( $field['choices'] as $value => $label ) :
			if ( ! isset( $option[$i] ) ) $option[$i] = false; ?>
			<label><?php echo $label; ?>
				<input type="checkbox" name="<?php echo $this->field_name( $field ) . "[{$i}]"; ?>"
					   value="<?php echo $value; ?>" <?php checked( $option[$i], $value ); ?>>
			</label>
			<?php $i++; endforeach;

		$this->field_description( $field );

	}

	/**
	 * On Off
	 *
	 * Generates a single checkbox.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function on_off( $field ) {

		$option = $this->field_value( $field ); ?>
		<label>
			<input type="checkbox" name="<?php echo $this->field_name( $field ); ?>" value="1" <?php checked( $option, '1' ); ?>>
		</label>
		<?php $this->field_description( $field );

	}

	/**
	 * Upload
	 *
	 * Generates an upload field.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function upload( $field ) {

		$option = $this->field_value( $field ); ?>

		<div class="upload">
			<div class="upload-image <?php echo ( ! $option ) ? 'hide' : ''; ?>">
				<img src="<?php echo $option; ?>" alt="<?php echo $option; ?>">
				<i class="upload-remove dashicons dashicons-no-alt"></i>
			</div>

			<input type="hidden" name="<?php echo $this->field_name( $field ); ?>" value="<?php echo $option; ?>" class="upload-file">
			<button class="button upload-select" type="button"><?php _e( 'Select', 'kickoff' ); ?></button>

			<?php $this->field_description( $field ); ?>
		</div>

	<?php }

	/**
	 * Color
	 *
	 * Generates a color picker.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function color( $field ) { ?>

		<input type="text" name="<?php echo $this->field_name( $field ); ?>" value="<?php echo $this->field_value( $field ); ?>" class="color-picker">

		<?php $this->field_description( $field );

	}

	/**
	 * Block
	 *
	 * Generates a block section.
	 *
	 * @access private
	 * @param  array $field
	 * @return null
	 */
	private function block( $field ) { ?>

		<div class="block">
			<?php echo $field['content'] ?>
		</div>

	<?php }

	/**
	 * Field Description
	 *
	 * Displays the fields description.
	 *
	 * @access private
	 * @param array $field
	 * @return null
	 */
	private function field_description( $field ) {

		if ( isset( $field['description'] ) ) : ?>
			<p class="setting-description"><?php echo $field['description']; ?></p>
		<?php endif;

	}

	/**
	 * Field Name
	 *
	 * Generates a field name.
	 *
	 * @access private
	 * @param  string $field
	 * @return string $name
	 */
	private function field_name( $field ) {

		return $field['section'] . '[' . $field['id'] . ']';

	}

	/**
	 * Field Value
	 *
	 * Gets the current fields value.
	 *
	 * @access private
	 * @param array $field
	 * @return string $option
	 */
	private function field_value( $field ) {

		$options = get_option( $field['section'] );

		return ( isset( $options[$field['id']] ) ) ? $options[$field['id']] : '';

	}

	/**
	 * Current Tab
	 *
	 * Gets the current tab.
	 *
	 * @access private
	 * @return string $tab
	 */
	private function current_tab() {

		return ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : $this->settings[0]['id'];

	}

	/**
	 * Setting Name
	 *
	 * Generates a settings name.
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $id
	 * @return string $name
	 */
	public static function setting_name( $name, $id ) {

		return 'kickoff-' . $name . '-' . $id;

	}

	/**
	 * Get Settings
	 *
	 * Gets the all settings within a section.
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $id
	 * @return array $settings
	 */
	public static function get_settings( $name, $id ) {

		return get_option( self::setting_name( $name, $id ), [] );

	}

	/**
	 * Get Setting
	 *
	 * Gets a setting value.
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $id
	 * @param  string $field
	 * @return string $setting
	 */
	public static function get_setting( $name, $id, $field ) {

		$setting = get_option( self::setting_name( $name, $id ), '' );
		if ( $setting && isset( $setting[$field] ) ) {
			$setting = $setting[$field];
		}

		return $setting;

	}

}
