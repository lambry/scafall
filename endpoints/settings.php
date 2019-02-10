<?php
/**
 * Handle fetching data for the settings endpoint.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff\Endpoints;

class Settings extends Base {

    /**
     * Get's all settings options.
     *
     * @access public
     * @return json $settings
     */
    public function get() {

        $settings = [
            'title' => get_bloginfo('name'),
            'tagline' => get_bloginfo('description')
        ];

        return rest_ensure_response($settings);

    }

}
