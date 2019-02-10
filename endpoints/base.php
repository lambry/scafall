<?php
/**
 * Handles generic endpoint functionality.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff\Endpoints;

defined('ABSPATH') || exit;

class Base {

    /**
     * Check auth on requests.
	 *
	 * @param object $request
     * @return bool $authenticated
     */
    public function auth($request) : bool {

        $apiToken = 'MY_API_TOKEN';

        $apiKey = $request->get_param('apiKey') ?: $this->getBody($request, 'apiKey');

        if ($apiKey && $apiToken && $apiKey === $apiToken) {
            return true;
        }

		return false;

    }

    /**
     * Fallback to request body.
	 *
	 * @param object $request
	 * @param string $key
     * @return string $key
     */
    protected function getBody($request, string $key = '') : string {

        $body = json_decode($request->get_body());

		return $body->{$key} ?? '';

	}

}
