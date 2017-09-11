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
 * Allow SVG uploads.
 */
add_filter('upload_mimes', function($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});
