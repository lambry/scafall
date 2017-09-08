<?php
/**
 * Helper class to register routes.
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
