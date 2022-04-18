<?php

/**
 * Handle routes.
 *
 * @package Scafall
 */

namespace Lambry\Scafall;

defined('ABSPATH') || exit;

class Route extends Router
{
	private array $registered = [];

	/**
	 * Add actions.
	 */
	public function __construct(...$args)
	{
		parent::__construct(...$args);

		add_action('init', [$this, 'rules']);
		add_filter('query_vars', [$this, 'variables']);
		add_filter('template_include', [$this, 'template']);

		return $this;
	}

	/**
	 * Register rewrite rules.
	 */
	public function rules(): void
	{
		foreach ($this->routes as $route) {
			$name = $this->getName($route);

			if (in_array($name, $this->registered)) continue;

			$uri = $this->formatUri($route->uri);

			add_rewrite_rule("{$route->prefix}{$uri}[/]?$", 'index.php?pagename=' . $name . $this->formatParams($route->uri), 'top');

			$this->registered[] = $name;
		}
	}

	/**
	 * Register route variables.
	 */
	public function variables(array $vars): array
	{
		foreach ($this->routes as $route) {
			$vars = array_merge($vars, $this->getVariables($route->uri));
		}

		return $vars;
	}

	/**
	 * Handle the actual route.
	 */
	public function template(string $template): string
	{
		$route = $this->matchRoute();

		if ($route && $this->checkPermissions($route)) {
			$template = $this->runCallback($route);
		}

		return $template;
	}

	/**
	 * Helper to get/generate the routes name.
	 */
	private function getName(object $route): string
	{
		return preg_replace('/[^A-Za-z0-9]/', '', $route->prefix . $route->uri);
	}

	/**
	 * Helper to get the uri variables i.e. route parameters.
	 */
	private function getVariables(string $uri): array
	{
		preg_match_all('/(?<={).*?(?=})/', $uri, $matches);

		return $matches[0] ?: [];
	}

	/**
	 * Parse the URI.
	 */
	public function formatUri(string $uri): string
	{
		return str_replace(['{', '}'], '', preg_replace('/(?<={)(.*?)(?=})/', '([a-z0-9-]+)', $uri));
	}

	/**
	 * Parse the URI and created needed parameters string.
	 */
	public function formatParams(string $uri): string
	{
		$variables = $this->getVariables($uri);

		$parts = array_map(function ($variable, $i) {
			return "&{$variable}" . '=$matches[' . $i + 1 . ']';
		}, $variables, array_keys($variables));

		return $parts ? join('', $parts) : '';
	}

	/**
	 * Find the route that matches the request.
	 */
	private function matchRoute(): ?object
	{
		$page = get_query_var('pagename');
		$type = $_POST ? $_POST['_method'] ?? 'POST' : 'GET';

		return array_values(array_filter($this->routes, function ($route) use ($page, $type) {
			return $this->getName($route) === $page && $route->type === $type;
		}))[0] ?? null;
	}
}
