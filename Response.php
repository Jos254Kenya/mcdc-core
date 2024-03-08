<?php

namespace VMSMeruDairy\core;

/**
 * Class Response
 * Manages HTTP responses such as setting status codes and performing redirects.
 *
 * @package VMSMeruDairy\core
 */
class Response
{
    /**
     * Sets the HTTP status code.
     *
     * @param int $code The HTTP status code to set.
     */
    public function statusCode(int $code)
    {
        http_response_code($code);
    }

    /**
     * Redirects to a specified URL.
     *
     * @param string $url The URL to redirect to.
     */
    public function redirect(string $url)
    {
        header("Location: $url");
    }
}
