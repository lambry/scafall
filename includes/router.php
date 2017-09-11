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
     * Shortcut to call register for get routes.
     *
     * @param string $uri
     * @param string $class
     * @param string $method
     * @return void
     */
    public function get(string $uri, string $class, $method = 'get') {

        $this->register($uri, $class, $method, 'GET');

    }

    /**
     * Shortcut to call register for post routes.
     *
     * @param string $uri
     * @param string $class
     * @param string $method
     * @return void
     */
    public function post(string $uri, string $class, $method = 'post') {

        $this->register($uri, $class, $method, 'POST');

    }

    /**
     * Shortcut to call register for put routes.
     *
     * @param string $uri
     * @param string $class
     * @param string $method
     * @return void
     */
    public function put(string $uri, string $class, $method = 'put') {

        $this->register($uri, $class, $method, 'PUT');

    }

    /**
     * Shortcut to call register for patch routes.
     *
     * @param string $uri
     * @param string $class
     * @param string $method
     * @return void
     */
    public function patch(string $uri, string $class, $method = 'patch') {

        $this->register($uri, $class, $method, 'PATCH');

    }

    /**
     * Shortcut to call register for delete routes.
     *
     * @param string $uri
     * @param string $class
     * @param string $method
     * @return void
     */
    public function delete(string $uri, string $class, $method = 'delete') {

        $this->register($uri, $class, $method, 'DELETE');

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

		$this->get($uri, $class, $methods['get'] ?? 'get');
		$this->post($uri, $class, $methods['post'] ?? 'post');
		$this->put("{$uri}/(?P<id>\d+)", $class, $methods['put'] ?? 'put');
		$this->patch("{$uri}/(?P<id>\d+)", $class, $methods['patch'] ?? 'patch');
		$this->delete("{$uri}/(?P<id>\d+)", $class, $methods['delete'] ?? 'delete');

    }

    /**
     * Registers route and said routes callback function.
     *
     * @param string $uri
     * @param string $class
     * @param string $method
     * @param string|array $methods
     */
    private function register($uri, $class, $method, $methods) {

        $endpoint = $this->endpoints . '\\' . $class;

        register_rest_route($this->api, "/{$uri}", [
            'methods' => $methods,
            'callback' => [ new $endpoint, $method ]
        ]);

    }

}
