<?php
/**
 * Meta Boxes
 *
 * Create new meta boxes fields.
 *
 * @package Bow
 */

namespace Lambry\Kickoff\Meta_Boxes;

class Fields {

	/* Variables */
    private static $prefix = '_';

    /**
     * Add Field
     *
     * Display the appropraite field.
     *
     * @access public
     * @param  array $field
     * @return null
     */
    public function add_field( $field  ) {

        switch ( $field['type']  ) {
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

            case 'repeater':
                $this->repeater( $field );
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

        <div class="meta-field meta-text">
            <label><?php echo $field['label']; ?></label>
            <input type="text" name="<?php echo $this->field_name( $field ); ?>" value="<?php echo $this->field_value( $field ); ?>">
            <?php $this->field_description( $field ); ?>
        </div>

    <?php }

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

        <div class="meta-field meta-textarea">
            <label><?php echo $field['label']; ?></label>
            <textarea name="<?php echo $this->field_name( $field ); ?>" cols="50" rows="10"><?php echo $this->field_value( $field ); ?></textarea>
            <?php $this->field_description( $field ); ?>
        </div>

    <?php }

    /**
     * Editor
     *
     * Generates a WordPress editor.
     *
     * @access private
     * @param  array $field
     * @return null
     */
    private function editor( $field ) { ?>

        <div class="meta-field meta-editor">
            <label><?php echo $field['label']; ?></label>
            <?php wp_editor( $this->field_value( $field ), $field['id'], [ 'textarea_name' => $this->field_name( $field ) ] ); ?>
            <?php $this->field_description( $field ); ?>
        </div>

    <?php }

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

        <div class="meta-field meta-select">
            <label><?php echo $field['label']; ?></label>

            <select name="<?php echo $this->field_name( $field ); ?>">
                <option value=""><?php echo __('-- select --', 'bow' ); ?></option>
                <?php foreach ( $field['choices'] as $value => $label ) : ?>
                    <option value="<?php echo $value; ?>" <?php selected( $this->field_value( $field ), $value ); ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php $this->field_description( $field ); ?>
        </div>

    <?php }

    /**
     * Radio
     *
     * Generates a list of radio buttons.
     *
     * @access private
     * @param  array $field
     * @return null
     */
    private function radio( $field ) { ?>

        <div class="meta-field meta-radio">
            <?php foreach ( $field['choices'] as $value => $label ) : ?>
                <label><?php echo $label; ?>
                    <input type="radio" name="<?php echo $this->field_name( $field ); ?>" value="<?php echo $value; ?>" <?php checked( $this->field_value( $field ), $value ); ?>>
                </label>
            <?php endforeach; ?>
            <?php $this->field_description( $field ); ?>
        </div>

    <?php }

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

        <div class="meta-field meta-checkbox">
            <?php $i = 0; foreach ( $field['choices'] as $value => $label ) :
                $checked = ( is_array( $option ) && in_array( $value, $option ) ) ? true : false; ?>
                <label><?php echo $label; ?>
                    <input type="checkbox" name="<?php echo $this->field_name( $field ) . "[{$i}]"; ?>" value="<?php echo $value; ?>" <?php checked( true, $checked ); ?>>
                </label>
            <?php $i++; endforeach; ?>
            <?php $this->field_description( $field ); ?>
        </div>

    <?php }

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

        <div class="meta-field meta-on-off">
            <label><?php echo $field['label']; ?>
                <input type="checkbox" name="<?php echo $this->field_name( $field ); ?>" value="1" <?php checked( $option, '1' ); ?>>
            </label>
            <?php $this->field_description( $field ); ?>
        </div>

    <?php }

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

        <div class="meta-field meta-upload">
            <label><?php echo $field['label']; ?></label>

            <div class="upload">
                <div class="upload-image <?php echo ( ! $option ) ? 'hide' : ''; ?>">
                    <img src="<?php echo $option; ?>" alt="<?php echo $option; ?>">
                    <i class="upload-remove remove dashicons dashicons-no-alt"></i>
                </div>

                <input type="hidden" name="<?php echo $this->field_name( $field ); ?>" value="<?php echo $option; ?>" class="upload-file">
                <button class="button upload-select block" type="button"><?php _e( 'Select', 'bow' ); ?></button>
                <?php $this->field_description( $field ); ?>
            </div>

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

        <div class="meta-field meta-text">
            <label><?php echo $field['label']; ?></label>
            <input type="text" name="<?php echo $this->field_name( $field ); ?>" value="<?php echo $this->field_value( $field ); ?>" class="color-picker">
            <?php $this->field_description( $field ); ?>
        </div>

    <?php }

    /**
     * Repeater
     *
     * Generates repeater fields.
     *
     * @access private
     * @param  array $fields
     * @return null
     */
    private function repeater( $fields ) {

        global $post;

        $meta = get_post_meta( $post->ID, $this->field_name( $fields ), false );
        $repeats = ( is_array( $meta) && ! empty( $meta[0] ) ) ? $meta[0] : [ 'default' ]; ?>

        <p><strong><?php echo $fields['label']; ?></strong></p>
        <div class="meta-repeater">
            <div class="meta-sortable">
                <?php $i = 0;
                    foreach ( $repeats as $repeat) : ?>
                    <div data-index="<?php echo $i; ?>" class="repeater <?php echo ( $repeat === 'default' ) ? 'hide' : ''; ?>">
                        <i class="repeater-remove remove dashicons dashicons-no-alt"></i>
                        <?php
                            foreach ( $fields['fields'] as $field ) {
                                $field['repeater'] = [
                                    'inc'   => $i,
                                    'id'    => $fields['id'],
                                    'value' => ( isset( $repeat[$field['id']] ) ) ? $repeat[$field['id']] : '',
                                ];
                                $this->add_field( $field );
                            }
                        ?>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
            <?php $this->field_description( $fields ); ?>
            <button class="button repeater-add block" type="button">
                <?php echo ( isset( $fields['button'] ) ) ? $fields['button'] : _e('Add New +', 'bow' ); ?>
            </button>
        </div>

    <?php }

    /**
    * Field Name
    *
    * Generates a field name.
    *
    * @access public
    * @param  string $field
    * @return string $name
    */
    public function field_name( $field ) {

        if (isset( $field['repeater'] ) ) {
            return self::$prefix . $field['repeater']['id'] . '[' . $field['repeater']['inc'] . ']' . '[' . $field['id'] . ']';
        } else {
            return self::$prefix . $field['id'];
        }

    }

    /**
     * Field Description
     *
     * Displays the fields description.
     *
     * @access private
     * @param  array $field
     * @return null
     */
    private function field_description( $field ) {

        if ( isset( $field['description'] ) ) : ?>
            <p class="description"><?php echo $field['description']; ?></p>
        <?php endif;

    }

    /**
     * Feild Value
     *
     * Gets the current fields value.
     *
     * @access private
     * @param  array $field
     * @return mixed $meta_value
     */
    private function field_value( $field ) {

        global $post;

        if ( isset( $field['repeater'] ) ) {
            return $field['repeater']['value'];
        } else {
            return get_post_meta( $post->ID, $this->field_name( $field ), true );
        }

    }

	/**
	 * Show
	 *
	 * Helper function to display a meta value.
	 *
	 * @access public
	 * @param string $key
	 * @param int $id
	 * @param bool $single
	 * @return void
	 */
	public static function show( $key, $id = 0, $single = true ) {

		echo self::get( $key, $id, $single );

	}

	/**
	 * Get
	 *
	 * Helper function to get a meta value.
	 *
	 * @access public
	 * @param string $key
	 * @param int $id
	 * @param bool $single
	 * @return mixed $value
	 */
	public static function get( $key, $id = 0, $single = true  ) {

		if ( ! $id  ) {
			global $post;
			$id = $post->ID;
		}

		return get_post_meta( $id, self::$prefix . $key, $single );

	}

}
