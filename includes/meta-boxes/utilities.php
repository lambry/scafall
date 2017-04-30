<?php
/**
 * Meta Boxes
 *
 * Meta box related utility functions.
 *
 * @package Bow
 */

namespace Lambry\Kickoff\Meta_Boxes;

class Utilities {

    /**
     * Sanitize Array
     *
     * Sanitizes a meta array, i.e repeaters.
     *
     * @access public
     * @param  array $array
     * @return array $array
     */
    public function sanitize_array( $array ) {

        if (! is_array( $array) ) {
            return wp_strip_all_tags( $array);
        }

        array_walk_recursive( $array, function( &$value, $key ) {
            if (! is_array( $value) ) {
                $value = wp_strip_all_tags( $value);
            }
        });

        return $array;

    }

    /**
     * Filter Array
     *
     * Filters out empty repeaters.
     *
     * @access public
     * @param  array $array
     * @return array $filtered_array
     */
    public function filter_array( $array ) {

        $filtered_array = [];

        foreach ( $array as $item ) {

            $filtered_items = array_filter( $item, function( $value, $key ) {
                return $value;
            }, ARRAY_FILTER_USE_BOTH);

            if ( $filtered_items ) {
                array_push( $filtered_array, $item);
            }

        }

        return $filtered_array;

    }

}
