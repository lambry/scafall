<?php

/**
 * Create rest endpoints.
 *
 * @package Scafall
 */

namespace Lambry\Scafall;

use WP_REST_Request;

defined('ABSPATH') || exit;

class Rest extends Router
{
	/**
	 * Add actions.
	 */
	public function __construct(...$args)
	{
		parent::__construct(...$args);

		add_action('rest_api_init', [$this, 'register']);

		return $this;
	}

	/**
	 * Register rest endpoints.
	 */
	public function register(): void
	{
		foreach ($this->routes as $route) {
			register_rest_route($route->prefix, $this->formatUri($route->uri), [
				'methods' => $route->type,
				'callback' => [$this, 'callback'],
				'permission_callback' => [$this, 'permission']
			]);
		}
	}

	/**
	 * Handle the rest endpoints callback.
	 */
	public function callback(WP_REST_Request $request): mixed
	{
		$route = $this->matchRoute($request);

		return $this->runCallback($route, $request);
	}

	/**
	 * Handle the rest endpoints permission callback.
	 */
	public function permission(WP_REST_Request $request): bool
	{
		$route = $this->matchRoute($request);

		return $this->checkPermissions($route);
	}

	/**
	 * Parse the URI.
	 */
	public function formatUri(string $uri): string
	{
		return str_replace(['{', '}'], '', preg_replace('/(?<={)(.*?)(?=})/', '(?P<\0>[a-zA-Z0-9_]+)', $uri));
	}

	/**
	 * Find the route that matches the request.
	 */
	private function matchRoute(WP_REST_Request $request): ?object
	{
		$route = array_filter($this->routes, function ($route) use ($request) {
			$parsedRoute = "/{$route->prefix}{$route->uri}";

			if ($params = $request->get_params()) {
				foreach ($params as $key => $value) {
					$parsedRoute = str_replace("{{$key}}", "{$value}", $parsedRoute);
				}
			}

			return $request->get_route() === $parsedRoute && $request->get_method() === $route->type;
		});

		if (!$route) {
			throw new \Exception('Route not found.');
		}

		return array_values($route)[0];
	}
}
