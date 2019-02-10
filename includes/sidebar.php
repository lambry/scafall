<?php

/**
 * Sidebars
 *
 * Create new sidebars i.e. widgets areas.
 *
 * @package Package
 */

namespace Lambry\Kickoff;

class Sidebar {

    public $id;
    public $name;

    /**
     * Return an instance of the class.
     *
     * @access public
     * @param mixed add()
     * @param object $this
     */
    public function __construct(string $id, string $name) {

        $this->id = $id;
        $this->name = $name;

        return $this;

    }

    /**
     * Set up a new sidebar/widget area.
     *
     * @access public
     * @param string $slug
     * @param string $name
     * @param string $plural
     * @return object $post
     */
    public static function add(string $id, string $name) : Sidebar {

        return new Sidebar($id, $name);

    }

    /**
     * Set options and add sidebar.
     *
     * @access public
     * @param array options
     * @return void
     */
    public function set(array $options = []) {

        $options = wp_parse_args($options, $this->defaultOptions());

        register_sidebar([
            'id' => $this->id,
            'name' => esc_html($this->name),
            'description' => esc_html($options['description']),
            'before_widget' => '<aside id="%1$s" class="widget %2$s ' . $options['classes'] . '">',
            'after_widget' => '</aside>',
            'before_title' => '<' . $options['header'] . ' class="widget-title">',
            'after_title' => '</' . $options['header'] . '>'
        ]);

    }

    /**
     * Setup the default options.
     *
     * @access private
     * @return array $options
     */
    private function defaultOptions() : array {

        return [
            'description' => '',
            'header' => 'h3',
            'classes' => ''
        ];

    }

}
