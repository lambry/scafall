<?php
/**
 * Register router and routes.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

class Router {

    private $endpoints = __NAMESPACE__ . '\Endpoints';

    /**
     * Contruct a new router intance.
     *
     * @param string $api
     */
    public function __construct(string $api) {
        $this->api = $api;
    }

    /**
     * Register a get route.
     *
     * @param string $uri
     * @param string $class
     * @param string $options
     * @return void
     */
    public function get(string $uri, string $class, $options = []) {

        $this->register(__FUNCTION__, $uri, $class, $options);

    }

    /**
     * Register a post route.
     *
     * @param string $uri
     * @param string $class
     * @param string $options
     * @return void
     */
    public function post(string $uri, string $class, $options = []) {

        $this->register(__FUNCTION__, $uri, $class, $options);

    }

    /**
     * Register a put route.
     *
     * @param string $uri
     * @param string $class
     * @param string $options
     * @return void
     */
    public function put(string $uri, string $class, $options = []) {

        $this->register(__FUNCTION__, $uri, $class, $options);

    }

    /**
     * Register a patch route.
     *
     * @param string $uri
     * @param string $class
     * @param string $options
     * @return void
     */
    public function patch(string $uri, string $class, $options = []) {

        $this->register(__FUNCTION__, $uri, $class, $options);

    }

    /**
     * Register a delete route.
     *
     * @param string $uri
     * @param string $class
     * @param string $options
     * @return void
     */
    public function delete(string $uri, string $class, $options = []) {

        $this->register(__FUNCTION__, $uri, $class, $options);

    }

	/**
     * Shortcut to setup all route methods for endpoint.
     *
     * @param string $uri
     * @param string $class
     * @param array $methods
     * @return void
     */
    public function resource(string $uri, string $class, $methods = []) {

		$identifier = $methods['identifier'] ?? 'id';

		$this->get($uri, $class, $methods['get'] ?? 'get');
		$this->post($uri, $class, $methods['post'] ?? 'post');
		$this->put("{$uri}/:{$identifier}", $class, $methods['put'] ?? 'put');
		$this->patch("{$uri}/:{$identifier}", $class, $methods['patch'] ?? 'patch');
		$this->delete("{$uri}/:{$identifier}", $class, $methods['delete'] ?? 'delete');

    }

    /**
     * Registers route and said routes callback functions.
     *
     * @param string $method
     * @param string $uri
     * @param string $class
     * @param array  $options
     */
    private function register(string $method, string $uri, string $class, $options = []) {

		$endpoint = $this->endpoints . '\\' . $class;
		$rest_uri = str_replace(':', '', preg_replace('/(:[a-z]*)/i', '(?P<$0>\d+)', $uri));

		$options = wp_parse_args($options, ['method' => $method]);

		register_rest_route($this->api, "/{$rest_uri}", [
			'methods' => $method,
			'callback' => [new $endpoint, $options['method']],
			'permission_callback' => isset($options['auth']) ? [new $endpoint, $options['auth'] !== true ? $options['auth'] : 'auth'] : null
		]);

    }

}
