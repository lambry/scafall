# Kickoff

A starter plugin of sorts; simplifies setting up post types, taxonomies, user roles, settings and meta boxes.

### Adding Post Types
```php
use Lambry\Kickoff\Post_Types;

$types = [
    [
    	'name'    => 'Book',
    	'plural'  => 'Books',
    	'options' => [ 'menu_icon'   => 'dashicons-book-alt' ]
    ], [
    	'name'    => 'Contact',
    	'plural'  => 'Contacts',
    	'options' => [ 'menu_icon'   => 'dashicons-groups' ]
    ]
];
new Post_Types( $types );
```

### Adding Taxonomies
```php
use Lambry\Kickoff\Taxonomies;

$taxonomies = [
    [
    	'name'       => 'Genre',
    	'plural'     => 'Genres',
    	'post_types' => 'Book'
    ], [
    	'name'       => 'Region',
    	'plural'     => 'Regions',
    	'post_types' => 'Contact'
    ]
];
new Taxonomies( $taxonomies );
```

### Adding User Roles
```php
use Lambry\Kickoff\User_Roles;

$roles = [
    [
        'name' => 'Contractor',
        'capabilities' => [
            'read'                   => true,
            'edit_posts'             => false
        ]
    ]
];
new User_Roles( $roles );
```

### Adding Settings
```php
use Lambry\Kickoff\Settings;

$settings = [
	[
		'id'    => 'signup',
		'title' => __( 'Signup Options', 'kickoff' ),
		'description' => __( 'The basic signup settings.', 'kickoff' ),
		'fields' => [
			[
				'id'    => 'title',
				'label' => __( 'Signup Title', 'kickoff' ),
				'description' => __( 'The signup forms title.', 'kickoff' ),
				'type'  => 'text'
			], [
				'id'    => 'modal',
				'label' => __( 'Show signup in modal.', 'kickoff' ),
				'type'  => 'on_off'
			]
		]
	]
];

new Settings( 'option', $settings, __( 'Kickoff', 'kickoff' ) );
```

#### Using Settings
```php
use Lambry\Kickoff\Settings as Setting;

// Get field
Setting::get('signup', 'modal');
// Get and echo field
Setting::show('signup', 'title');
```

### Adding Meta Boxes
```php
use Lambry\Kickoff\Meta_Boxes;

$meta_boxes = [
	[
		'id'          => 'header_section',
		'title'       => __( 'Header Section', 'kickoff' ),
		'description' => __( 'An example of a classic header section.', 'kickoff' ),
		'fields'      => [
			[
				'id'    => 'header_color',
				'label' => __( 'header_color', 'kickoff' ),
				'description' => __( 'The main header color.', 'kickoff' ),
				'type'  => 'color'
			], [
				'id'    => 'header_text',
				'label' => __( 'Header Text', 'kickoff' ),
				'description' => __( 'The main header text.', 'kickoff' ),
				'type'  => 'textarea'
			]
		]
	]
];

new Meta_Boxes( $meta_boxes );
```

#### Using Meta Boxes
```php
use Lambry\Kickoff\Meta_Boxes\Fields as Meta;

// Get field
Meta::get('header_color');
// Get and echo field
Meta::show('header_copy');
```
