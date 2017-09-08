<?php
/**
 * Handles fetching and formating data for the books endpoint.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff\Endpoints;

class Books {

    /**
     * Get all published books.
     *
     * @access public
     * @return json $books
     */
    public function get() {

        $books = get_posts([
            'post_type' => 'book',
            'posts_per_book' => -1
        ]);

        return rest_ensure_response(array_map([$this, 'format'], $books));

    }

    /**
     * Format a single books data.
     *
     * @access private
     * @param object $book
     * @return array $formatted
     */
    private function format($book) {

        return [
            'id' => $book->ID,
            'slug' => $book->post_name,
            'title' => $book->post_title,
            'content' => $book->post_content,
            'exceprt' => $book->post_excerpt,
            'date' => $book->post_date,
            'path' => "{$book->post_type}/{$book->post_name}"
        ];

    }

}
