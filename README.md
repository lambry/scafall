# Scafall

A little plugin that makes scaffolding post types, taxonomies, user roles, options, meta boxes, rest endpoints and frontend routes easier.

Install: `composer require lambry/scafall`

## Post Types

```php
use Lambry\Scafall\Post;

// Adding a post type
Post::add('team', 'Team', 'Team Members');

// Overriding the default supports
Post::add('faq', 'FAQ', 'FAQs')->supports(['title', 'editor']);

// Overriding the default options
Post::add('book', 'Book', 'Books')->options(['menu_icon' => 'dashicons-book']);
```

## Taxonomies

```php
use Lambry\Scafall\Taxonomy;

// Adding a taxonomy
Taxonomy::add('genre', 'Genre', 'Genres')->to('book');

// Overriding the default options
Taxonomy::add('genre', 'Genre', 'Genres')->options(['hierarchical' => true])->to('book');
```

## User Roles

```php
use Lambry\Scafall\Role;

// Adding a role
Role::add('proofreader', 'Proofreader');

// Overriding the default capabilities
Role::add('proofreader', 'Proofreader')->capabilities(['edit_posts' => true]);
```

## Options

```php
use Lambry\Scafall\Option;

// Adding a new options page
Option::add('Options', 'Main Options')
	->section('display', 'Display', function($field) {
		$field->text('notice_title', 'Notice title');
		$field->upload('notice_image', 'Notice image');
		$field->number('notice_delay', 'Notice delay');
		$field->date('notice_date', 'Show notice until');
		$field->colour('notice_colour', 'Notice colour');
		$field->textarea('notice_content', 'Notice content');
	})
	->section('Extras', 'Extras', function($field) {
		$field->url('api_url', 'API URL');
		$field->password('api_key', 'API Key');
		$field->email('contact_email', 'Contact email');
		$field->toggle('maintenance_mode', 'Unable maintenance mode');
		$field->range('api_cache', 'Cache duration')->attributes([
			'min' => 30,
			'max' => 120
		]);
		$field->radio('map_type', 'Default map type')->options([
			'map' => 'Map',
			'sat' => 'Satellite'
		]);
	})
	->to('menu');
```

## Meta Boxes

```php
use Lambry\Scafall\Meta;

// Adding a new meta box
Meta::add('author', 'Author Details')->fields(function($field) {
	$field->info('author_info', 'Information all about the author');
	$field->toggle('author_active', 'Display author details');
	$field->colour('author_colour', 'Background colour');
	$field->email('author_email', 'Email address');
	$field->date('author_dob', 'Date of birth');
	$field->editor('author_bio', 'Bio');
	$field->select('author_publisher', 'Publisher')->options([
		'penguin' => 'Penguin',
		'harpercollins' => 'HarperCollins'
	]);
	$field->checkbox('author_awards', 'Awards')->options([
		'pulitzer' => 'Pulitzer',
		'bestseller' => 'Bestseller'
	]);
})
->to('book');
```

The field types available for use within options pages and meta boxes are: `text`, `number`, `email`, `date`, `range`, `url`, `password`, `textarea`, `editor`, `select`, `radio`, `checkbox`, `toggle`, `upload`, `colour` and `info`.

## Rest endpoints

```php
use Lambry\Scafall\Rest;

// Adding a new namespaced group of endpoints
Rest::prefix('theme/v1')->group(function($rest) {
	// Adding endpoints
	$rest->get('/options', fn() => rest_ensure_response(['data' => 'value']));

	// Using a controller to manage endpoints
	$rest->get('/books', BookController::class);
	$rest->post('/books', BookController::class);
	$rest->put('/books/{slug}', BookController::class);
	$rest->patch('/books/{slug}', BookController::class);
	$rest->delete('/books/{slug}', BookController::class);

	// Overriding the default callback method
	$rest->get('/books', [BookController::class, 'index']);

	// Checking the authed users capabilities
	$rest->patch('/books/{slug}', BookController::class)->can('edit_book');

	// Checking the authed users role
	$rest->delete('/books/{slug}', BookController::class)->role('editor');

	// Custom authentication via the auth method
	$rest->delete('/books/{slug}', BookController::class)->auth($callback);

	// Nested endpoints
	$rest->get('/books/{slug}/chapter/{id}', $callback);
});
```

## Routes

```php
use Lambry\Scafall\Route;

// Adding a new group of routes
Route::prefix('team')->group(function($route) {
	// Adding routes
	$route->get('/dashboard', fn() => get_template_directory() . '/team/dashboard.php');

	// Using a controller to manage routes
	$route->get('/members', MembersController::class);
	$route->post('/members', MembersController::class);
	$route->put('/members/{id}', MembersController::class);
	$route->patch('/members/{id}', MembersController::class);
	$route->delete('/members/{id}', MembersController::class);

	// Overriding the default callback method
	$route->get('/members', [MembersController::class, 'index']);

	// Checking the authed users capabilities
	$route->patch('/members/{id}', MembersController::class)->can('edit_member');

	// Checking the authed users role
	$route->delete('/members/{id}', MembersController::class)->role('manager');

	// Custom authentication via the auth method
	$route->delete('/members/{id}', MembersController::class)->auth($callback);

	// Nested routes
	$rest->get('/members/{id}/posts/{slug}', $callback);
});
```

To use the put, patch and delete methods with frontend routes you'll need to add a hidden field named `_method` with a value of `PUT`, `PATCH` or `DELETE`.

Notes: requires PHP 8.0+
