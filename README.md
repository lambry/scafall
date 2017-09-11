# Kickoff

A simple example/starter plugin with helpers for adding post types, taxonomies, roles, settings, meta boxes and api endpoints.

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
 */
Taxonomy::add('genre', 'Genre', 'Genres')->to('book')->set([
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

## Adding Settings Screens (and using said settings)
`Field types include: text, textarea, editor, select, radio, checkbox, on_off, upload, color and block`.
```php
use Lambry\Kickoff\Setting;

/**
 * @param string menu type
 * @param string menu name
 * @param string page title
 */
Setting::add('menu', 'Options', 'Plugin Options')
    ->section('general', 'General', 'Generic plugin options', [
        [
            'id'    => 'display_authors',
            'label' => 'Display author card',
            'type'  => 'on_off'
        ], [
            'id'    => 'authors_intro',
            'label' => 'Authors intro copy',
            'description' => 'This section will be displayed above all authors.',
            'type'  => 'editor'
        ]
    ])
    ->section('display', 'Display', 'Display specific options', [
        [
            'id'    => 'book_color',
            'label' => 'Book icon color',
            'type'  => 'color'
        ], [
            'id'    => 'book_position',
            'label' => 'Book icon position',
            'type'  => 'radio',
            'choices' => [
                'left' => 'Left',
                'right' => 'Right'
            ]
        ]
])->set();

// Get all settings within a section
$section = Setting::get('general');

// Get a specific setting field
$intro = Setting::get('general', 'authors_intro');

// Echo specific setting field
Setting::show('display', 'book_color');
```

## Adding MetaBoxes (and using custom fields)
`Field types include: text, textarea, editor, select, radio, checkbox, on_off, upload, color and repeater`.
```php
use Lambry\Kickoff\MetaBox;

/**
 * @param string id
 * @param string title
 * @param string description
 */
 MetaBox::add('review', 'Review Section', 'Reviews to display under the books info.')
	->fields([
        [
            'id'    => 'reviews_display',
            'label' => 'Display review section',
            'description' => 'Check here to display all reviews on this page.',
            'type'  => 'on_off'
        ], [
            'id'    => 'reviews_title',
            'label' => 'Reviewer section title',
            'type'  => 'text'
        ]
    ])
    ->repeat('reviews', 'Reviews', 'Add as many reviews as neeeded below.', [
        [
            'id'    => 'reviewer_name',
            'label' => 'Reviewer name',
            'type'  => 'text'
        ], [
            'id'    => 'reviewer_rating',
            'label' => 'Reviewer rating',
			'type'  => 'radio',
			'choices' => [
                'one' => 'One Star',
                'two' => 'Two Stars',
                'three' => 'Three Stars'
            ]
        ]
])->to('book')->set();

// Get a custom fields data with the loop
MetaBox::get('reviews_display');

// Get a custom fields data outside the loop
MetaBox::get('reviews_display', $post_id);

// Echo a custom fields data
MetaBox::show('reviews_title');
```

## Adding API endpoints
```php
use Lambry\Kickoff\Router;

$router = new Router('kickoff');

/**
 * @param string endpoint path
 * @param string class name
 * @param string class method
 */
$router->get('books', 'Books');
$router->post('books', 'Books');
$router->put('books/(?P<id>\d+)', 'Books');
$router->patch('books/(?P<id>\d+)', 'Books');
$router->delete('books/(?P<id>\d+)', 'Books');

// The resource method defines routes for get, post, put, patch and delete.
// You could effectively replace the above with a single resource.
$router->resource('books', 'Books');
```
