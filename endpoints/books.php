<?php
/**
 * Handles fetching and formating data for the books endpoint.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff\Endpoints;

class Books extends Base {

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
     * Add a new book.
     *
     * @access public
     * @param  object $request
     * @return json $status
     */
    public function post($request) {

        // Actually add a book
        return rest_ensure_response([
            'status' => 'added'
        ]);

    }

    /**
     * Replace a book.
     *
     * @access public
     * @param  object $request
     * @return json $status
     */
    public function put($request) {

        // Actually replace a book
        return rest_ensure_response([
            'status' => 'replaced'
        ]);

    }

    /**
     * Update a book.
     *
     * @access public
     * @param  object $request
     * @return json $status
     */
    public function patch($request) {

        // Actually update a book
        return rest_ensure_response([
            'status' => 'patched'
        ]);

    }

    /**
     * Delete an existing book.
     *
     * @access public
     * @param  object $request
     * @return json $status
     */
    public function delete($request) {

        // Actually delete the book
        return rest_ensure_response([
            'id' => $request->get_param('id'),
            'status' => 'deleted'
        ]);

    }

    /**
     * Format a single books data.
     *
     * @access private
     * @param object $book
     * @return array $formatted
     */
    private function format($book) : array {

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
