<?php
/**
 * Examples
 *
 * Examples on adding settings, post types, taxonomies and user roles.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

defined( 'ABSPATH' ) || exit;

/*
 * Register Settings
 */
$settings = [
	[
		'id'    => 'basic',
		'title' => __( 'Basic', 'kickoff' ),
		'description' => __( 'The basic field options.', 'kickoff' ),
		'fields' => [
			[
				'id'    => 'text',
				'label' => __( 'Text', 'kickoff' ),
				'description' => __( 'A sample description.', 'kickoff' ),
				'type'  => 'text'
			], [
				'id'    => 'select',
				'label' => __( 'Select', 'kickoff' ),
				'type'  => 'select',
				'choices' => [
					'one' => 'One',
					'two' => 'Two',
					'three' => 'Three',
				]
			], [
				'id'    => 'radio',
				'label' => __( 'Radio', 'kickoff' ),
				'type'  => 'radio',
				'choices' => [
					'one' => 'One',
					'two' => 'Two',
					'three' => 'Three',
				]
			], [
				'id'    => 'checkbox',
				'label' => __( 'Checkbox', 'kickoff' ),
				'type'  => 'checkbox',
				'choices' => [
					'one' => 'One',
					'two' => 'Two',
					'three' => 'Three',
				]
			], [
				'id'    => 'textarea',
				'label' => __( 'Textarea', 'kickoff' ),
				'type'  => 'textarea'
			], [
				'id'    => 'editor',
				'label' => __( 'Editor', 'kickoff' ),
				'type'  => 'editor'
			]
		]
	], [
		'id'          => 'extra',
		'title'       => __( 'Extra', 'kickoff' ),
		'description' => __( 'Some more feild options.', 'kickoff' ),
		'fields'      => [
			[
				'id'    => 'on_off',
				'label' => __( 'On Off', 'kickoff' ),
				'type'  => 'on_off'
			], [
				'id'    => 'upload',
				'label' => __( 'Upload', 'kickoff' ),
				'type'  => 'upload'
			], [
				'id'    => 'color',
				'label' => __( 'Color', 'kickoff' ),
				'type'  => 'color'
			], [
				'id'    => 'block',
				'label' => __( 'Block', 'kickoff' ),
				'content' => __( 'The block field is for display text only.', 'kickoff' ),
				'type'  => 'block'
			]
		]
	]
];
new Settings( 'option', $settings, __( 'Kickoff', 'kickoff' ) );

/*
 * Register post types
 */
$types = [
    [
    	'name'    => 'Book',
    	'plural'  => 'Books',
    	'options' => [
            'public'      => true,
            'has_archive' => true,
            'menu_icon'   => 'dashicons-book-alt',
            'supports'    => [ 'title', 'editor', 'revisions', 'thumbnail', 'excerpt' ]
        ]
    ]
];
new Post_Types( $types );

/*
 * Register taxonomies
 */
$taxonomies = [
    [
    	'name'       => 'Genre',
    	'plural'     => 'Genres',
    	'post_types' => [ 'Book' ],
    	'options'    => [
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true
    	]
    ]
];
new Taxonomies( $taxonomies );

/*
 * Register user roles
 */
$roles = [
    [
        'name' => 'Acquaintance',
        'capabilities' => [
            'read'                   => true,
            'publish_posts'          => false,
            'edit_posts'             => false,
            'edit_published_posts'   => false,
            'delete_posts'           => false,
            'delete_published_posts' => false,
            'upload_files'           => false
        ]
    ]
];
new User_Roles( $roles );
