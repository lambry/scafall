<?php

/**
 * Handle router logic.
 *
 * @package Scafall
 */

namespace Lambry\Scafall;

use Closure;

defined('ABSPATH') || exit;

class Router
{
	protected array $routes = [];
	private bool $global = false;

	public function __construct(private string $prefix = '', private string $uri = '', private mixed $handle = null, private string $type = '')
	{ }

	/**
	 * Handle calls to magic methods.
	 */
	public function __call(string $name, array $arguments): self
	{
		$method = "_{$name}";

		$this->{$method}(...$arguments);

		return $this;
	}
	/**
	 * Handle calls to magic static methods.
	 */
	public static function __callStatic(string $name, array $arguments): self
	{
		$class = new static();

		$class->{$name}(...$arguments);

		return $class;
	}

	/**
	 * Setup prefix.
	 */
	public static function prefix(string $prefix): static
	{
		return new static(prefix: $prefix);
	}

	/**
	 * Create a grouped context.
	 */
	public function group(Closure $callback): self
	{
		$this->global = false;

		$callback($this);

		$this->global = true;

		return $this;
	}

	/**
	 * Register get route/endpoint.
	 */
	public function _get(string $uri, mixed $handle): self
	{
		$this->addRoute($uri, $handle, 'GET');

		return $this;
	}

	/**
	 * Register post route/endpoint.
	 */
	public function _post(string $uri, mixed $handle): self
	{
		$this->addRoute($uri, $handle, 'POST');

		return $this;
	}

	/**
	 * Register put route/endpoint.
	 */
	public function _put(string $uri, mixed $handle): self
	{
		$this->addRoute($uri, $handle, 'PUT');

		return $this;
	}

	/**
	 * Register patch route/endpoint.
	 */
	public function _patch(string $uri, mixed $handle): self
	{
		$this->addRoute($uri, $handle, 'PATCH');

		return $this;
	}

	/**
	 * Register delete route/endpoint.
	 */
	public function _delete(string $uri, mixed $handle): self
	{
		$this->addRoute($uri, $handle, 'DELETE');

		return $this;
	}

	/**
	 * Check the users capabilities.
	 */
	public function can(string $cap): self
	{
		$this->addProperty('cap', $cap);

		return $this;
	}

	/**
	 * Check the user's role.
	 */
	public function role(string $role): self
	{
		$this->addProperty('role', $role);

		return $this;
	}

	/**
	 * Add custom auth method.
	 */
	public function auth(mixed $auth): self
	{
		$this->addProperty('auth', $auth);

		return $this;
	}

	/**
	 * Register route to routes.
	 */
	public function addRoute(string $uri, mixed $handle, string $type): void
	{
		$prefix = $this->prefix;

		$this->routes[] = (object) compact('prefix', 'uri', 'handle', 'type');
	}

	/**
	 * Add a property to the route.
	 */
	public function addProperty(string $key, mixed $value): void
	{
		if ($this->global) {
			$this->routes = array_map(function ($route) use ($key, $value) {
				if (!isset($route->{$key})) {
					$route->{$key} = $value;
				}

				return $route;
			}, $this->routes);
		} else {
			$this->routes[array_key_last($this->routes)]->{$key} = $value;
		}
	}

	/**
	 * Run the routes callback i.e. handle.
	 */
	public function runCallback(object $route, $request = null): mixed
	{
		// Handle callbacks and named functions
		if (is_callable($route->handle)) {
			return call_user_func($route->handle, $request);
		}

		// Handle classes and default or overriden methods
		$class = is_array($route->handle) ? $route->handle[0] : $route->handle;
		$method = is_array($route->handle) ? $route->handle[1] ?? $route->type : $route->type;

		return (new $class)->{$method}($request);
	}

	/**
	 * Run any authentication methods i.e. check permissions.
	 */
	public function checkPermissions(object $route, $request = null): bool
	{
		$authed = [];

		if (isset($route->cap)) {
			$authed[] = current_user_can($route->cap);
		}

		if (isset($route->role)) {
			$user = wp_get_current_user();

			$authed[] = count(array_intersect((array) $route->role, (array) $user->roles)) > 0;
		}

		if (isset($route->auth)) {
			if (is_callable($route->auth)) {
				$authed[] = call_user_func($route->auth, $request);
			} elseif (is_array($route->handle)) {
				$authed[] = (new $route->handle[0])->{$route->auth}($request);
			} else {
				$authed[] = (new $route->handle)->{$route->auth}($request);
			}
		}

		return $authed ? count(array_filter($authed)) === count($authed) : true;
	}
}
