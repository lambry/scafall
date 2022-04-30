<?php
/**
 * Plugin Name: Scafall
 * Plugin URI: https://github.com/lambry/scafall
 * Description: A little plugin that makes scaffolding post types, taxonomies, user roles, options, meta boxes, rest endpoints and frontend routes easier.
 * Version: 0.2.1
 * Author: Lambry
 * Author URI: http://lambry.com
 */

defined('ABSPATH') || exit;

define('SCAFALL_URL', plugin_dir_url(__FILE__));
define('SCAFALL_DIR', plugin_dir_path(__FILE__));
define('SCAFALL_VERSION', '0.2.1');
define('SCAFALL_PREFIX', 'scafall_');

$autoload = [
	'includes/post',
    'includes/taxonomy',
    'includes/role',
    'includes/field',
    'includes/option',
    'includes/meta',
	'includes/router',
    'includes/route',
    'includes/rest'
];

foreach ($autoload as $include) {
	require_once SCAFALL_DIR . "{$include}.php";
}
