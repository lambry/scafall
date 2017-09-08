<?php
/**
 * Custom admin tweaks.
 *
 * @package Kickoff
 */

/**
 * Stop file editing via the admin.
 */
define('DISALLOW_FILE_EDIT', true);

 /**
  * Remove ACF admin menu.
  */
add_filter('acf/settings/show_admin', '__return_false');

/**
 * Allow SVG uploads.
 */
add_filter('upload_mimes', function($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});
