<?php
/**
 * Handle fetching and formating data for the genres endpoint.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff\Endpoints;

class Genres {

    /**
     * Get all genres.
     *
     * @access public
     * @return json $genres
     */
    public function get() {

        $genres = get_terms([
            'taxonomy' => 'genre',
            'hide_empty' => false
        ]);

        return rest_ensure_response(array_map([$this, 'format'], $genres));

    }

    /**
     * Format a single genres data.
     *
     * @access private
     * @param object $book
     * @return array $formatted
     */
    private function format($genre) {

        return [
            'id' => $genre->term_id,
            'name' => $genre->name,
            'slug' => $genre->slug,
            'description' => $genre->description,
            'path' => "{$genre->taxonomy}/{$book->slug}"
        ];

    }

}
