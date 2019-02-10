<?php
/**
 * Create a new user role.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

defined('ABSPATH') || exit;

class Role {

    private $slug;
    private $name;

    /**
     * Return an instance of the class.
     *
     * @access public
     * @param mixed add()
     * @return object $this
     */
    public function __construct($slug, $name) {

        $this->slug = $slug;
        $this->name = $name;

		return $this;

    }

    /**
	 * Set up new post type details.
	 *
	 * @access public
	 * @param string $slug
	 * @param string $name
	 * @return object $role
	 */
    public static function add(string $slug, string $name) : Role {

		return new Role($slug, $name);

    }

    /**
     * Set options and add role.
     *
     * @access public
     * @param array options
     * @return void
     */
    public function set(array $options = []) {

        $capabilities = wp_parse_args($options, $this->capabilities());

		add_role($this->slug, $this->name, $capabilities);

    }

    /**
     * Setup the default capabilities.
     *
     * @access private
     * @return array $capabilities
     */
    private function capabilities() : array {

        return [
            'read' => true,
            'upload_files' => true,
            'publish_posts' => false,
            'edit_posts' => false,
            'edit_published_posts' => false,
            'delete_posts' => false,
            'delete_published_posts' => false
		];

	}

}
