<?php
/***
 * Examples on adding post types, taxonomies and user roles.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

use Lambry\Kickoff\{PostType, Taxonomy, Role, Router};

defined('ABSPATH') || exit;

/**
 * Register book post type.
 */
PostType::add('book', 'Book', 'Books')->set([
    'menu_icon' => 'dashicons-book-alt'
]);

/**
 * Register genre taxonomy.
 */
Taxonomy::add('genre', 'Genre', 'Genres', 'book')->set([
    'hierarchical' => true,
    'public'       => true
]);

/**
 * Register cutomer user role.
 */
Role::add('customer', 'Customer')->set([
    'publish_posts' => false,
    'read'          => true
]);

/**
 * Setup api endpoints.
 *
 * @access private
 * @return null
 */
add_action('rest_api_init', function() {
    $router = new Router('kickoff/v1');
    // Add individual routes
    $router->get('settings' , 'Settings');
    $router->get('books' , 'Books');
    $router->get('genres' , 'Genres');
});
