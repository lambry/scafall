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
Taxonomy::add('group', 'Group', 'Group')->to('team');

// Overriding the default options
Taxonomy::add('genre', 'Genre', 'Genres')->options(['hierarchical' => true])->to('book');
```

## User Roles

```php
use Lambry\Scafall\Role;

// Adding a role
Role::add('manager', 'Manager');

// Overriding the default capabilities
Role::add('proofreader', 'Proofreader')->capabilities(['edit_posts' => true]);
```

## Options

```php
use Lambry\Scafall\Option;

// Adding a new options page
Option::add('contact', 'Contact', 'Contact Options')->fields(function($field) {
    $field->text('contact_name', 'Contact name');
    $field->email('contact_email', 'Contact email');
    $field->upload('contact_image', 'Contact image');
    $field->toggle('display_contact', 'Show contact details');
});

// An options page with tabbed sections
Option::add('options', 'Options', 'Main Options')
	->section('notice', 'Notice', function($field) {
		$field->number('notice_delay', 'Notice delay');
		$field->date('notice_date', 'Show notice until');
		$field->colour('notice_colour', 'Notice colour');
		$field->textarea('notice_content', 'Notice content');
	})
	->section('extras', 'Extras', function($field) {
		$field->url('api_url', 'API URL');
		$field->password('api_key', 'API Key');
		$field->range('api_cache', 'Cache duration')->attributes(['min' => 30, 'max' => 120]);
		$field->radio('map_type', 'Map type')->options(['map' => 'Map', 'sat' => 'Satellite']);
	})
	->to('options');
```

## Meta Boxes

```php
use Lambry\Scafall\Meta;

// Adding a new meta box
Meta::add('author', 'Author Details')->fields(function($field) {
	$field->info('author_info', 'Information all about the author');
	$field->toggle('author_active', 'Display author details');
	$field->editor('author_bio', 'Bio');
	$field->date('author_dob', 'Date of birth');
	$field->email('author_email', 'Email address');
	$field->colour('author_colour', 'Background colour');
	$field->select('author_publisher', 'Publisher')->options(['penguin' => 'Penguin', 'harper' => 'Harper']);
	$field->checkbox('author_awards', 'Awards')->options(['pulitzer' => 'Pulitzer', 'nebula' => 'Nebula']);
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
	$rest->post('/books', BookController::class)->can('create_books');

	// Checking the authed users role
	$rest->patch('/books/{slug}', BookController::class)->role('editor');

	// Custom authentication via the auth method
	$rest->delete('/books/{slug}', BookController::class)->auth($callback);

	// Nested endpoints
	$rest->get('/books/{slug}/chapter/{id}', $callback);
});
```

When using a controller, the default method called will match the HTTP request method.
For example `$rest->get('/books', BookController::class)` would call a `get` method on the `BookController` class.

## Routes

```php
use Lambry\Scafall\Route;

// Adding stand-alone routes
Route::get('/dashboard', fn() => get_template_directory() . '/dashboard.php');

// Using a controller to manage routes
Route::get('/members', MembersController::class);
Route::post('/members', MembersController::class);
Route::put('/members/{id}', MembersController::class);
Route::patch('/members/{id}', MembersController::class);
Route::delete('/members/{id}', MembersController::class);

// Overriding the default callback method
Route::get('/members', [MembersController::class, 'index']);

// Checking the authed users capabilities
Route::post('/members/{id}', MembersController::class)->can('create_members');

// Checking the authed users role
Route::patch('/members/{id}', MembersController::class)->role('manager');

// Custom authentication via the auth method
Route::delete('/members/{id}', MembersController::class)->auth($callback);

// Nested routes
Route::get('/members/{id}/posts/{slug}', $callback);

// Adding a group of prefixed routes
Route::prefix('team')->group(function($route) {
	$route->get('/members', MembersController::class);
	$route->post('/members', MembersController::class);
	$route->put('/members/{id}', MembersController::class);
	$route->patch('/members/{id}', MembersController::class);
	$route->delete('/members/{id}', MembersController::class);
});
```

To use the `put`, `patch` and `delete` methods on frontend routes, you'll need to add a hidden field named `_method` with a value of `PUT`, `PATCH` or `DELETE`.

Notes: requires PHP 8.0+
