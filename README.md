# Kickoff

A simple example/starter plugin with helpers for adding post types, taxonomies, roles and api endpoints.

## Adding Post Types
```php
use Lambry\Kickoff\PostType;

/**
 * @param string post type id / slug
 * @param string post type name single
 * @param string post type name plural
 */
PostType::add('books', 'Book', 'Books')->set([
	'menu_icon' => 'dashicons-book-alt'
]);
```

## Adding Taxonomies
```php
use Lambry\Kickoff\Taxonomy;

/**
 * @param string taxomomy id / slug
 * @param string taxonomy name single
 * @param string taxonomy name plural
 * @param string|array post types
 */
Taxonomy::add('genre', 'Genre', 'Genres', 'books')->set([
	'hierarchical' => true,
	'public'       => true
]);
```

## Adding User Roles
```php
use Lambry\Kickoff\Role;

/**
 * @param string role id / slug
 * @param string role name
 */
Role::add('customer', 'Customer')->set([
	'publish_posts' => false,
	'read'          => true
]);
```

## Adding API endpoints
```php
use Lambry\Kickoff\Router;

$router = new Router('kickoff/v1');

/**
 * @param string endpoint path
 * @param string class name
 */
$router->get('settings' , 'Settings');
$router->get('books' , 'Books');
$router->get('genres' , 'Genres');
$router->post('signup' , 'Signup');
```
