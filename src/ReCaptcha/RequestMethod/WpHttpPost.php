<?php
/**
 * This is a PHP library that handles calling reCAPTCHA.
 *
 * @copyright Copyright (c) 2015, Google Inc.
 * @link      http://www.google.com/recaptcha
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace ReCaptcha\RequestMethod;

use ReCaptcha\RequestMethod;
use ReCaptcha\RequestParameters;

/**
 * Sends WP_Http request to the reCAPTCHA service.
 * Note: this requires that Wordpress be loaded with the WP_Http class
 * @see https://developer.wordpress.org/reference/classes/wp_http/
 */
class WpHttpPost implements RequestMethod
{
    /**
     * URL to which requests are sent via WP_Http.
     * @const string
     */
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Instance of WP_Http object
     * @var WP_Http
     */
    private $wp_http;

    public function __construct(WP_Http $wp_http = null)
    {
        if ( !is_null( $wp_http ) ) {
            $this->wp_http = $wp_http;
        } else {
            $this->wp_http = _wp_http_get_object();
        }
    }

    /**
     * Submit the WP_HTTP request with the specified parameters.
     *
     * @param RequestParameters $params Request parameters
     * @return string Body of the reCAPTCHA response
     */
    public function submit(RequestParameters $params)
    {
		$args = array(
			'body' => $params->toArray(),
			'headers' => array(
				'Content-Type' => 'application/x-www-form-urlencoded'
			),
			'sslverify' => true
		);

		$response =
			$this->wp_http->post(
				self::SITE_VERIFY_URL,
				$args
			);

		$body = wp_remote_retrieve_body( $response );

		return $body;
    }
}
