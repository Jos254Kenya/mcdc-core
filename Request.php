<?php

namespace VMSMeruDairy\core;

/**
 * Class Request
 * Manages HTTP requests including methods, URLs, and request bodies.
 *
 * @package VMSMeruDairy\core
 */
class Request
{
    private array $routeParams = [];

    /**
     * Retrieves the HTTP request method.
     *
     * @return string The HTTP request method.
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Retrieves the requested URL.
     *
     * @return string The requested URL.
     */
    public function getUrl(): string
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    /**
     * Checks if the request method is GET.
     *
     * @return bool True if the request method is GET, false otherwise.
     */
    public function isGet(): bool
    {
        return $this->getMethod() === 'get';
    }

    /**
     * Checks if the request method is POST.
     *
     * @return bool True if the request method is POST, false otherwise.
     */
    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }

    /**
     * Retrieves the request body parameters.
     *
     * @return array The request body parameters.
     */
    public function getBody(): array
    {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
//                each get input/param is filtered for any malicious code
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            //                each post input/param is filtered for any malicious code

            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $data;
    }

    /**
     * Sets route parameters.
     *
     * @param array $params The route parameters.
     * @return $this The current Request object.
     */
    public function setRouteParams(array $params): self
    {
        $this->routeParams = $params;
        return $this;
    }

    /**
     * Retrieves route parameters.
     *
     * @return array The route parameters.
     */
    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    /**
     * Retrieves a specific route parameter.
     *
     * @param string $param The route parameter name.
     * @param mixed $default The default value if the parameter is not found.
     * @return mixed The route parameter value.
     */
    public function getRouteParam(string $param, $default = null)
    {
        return $this->routeParams[$param] ?? $default;
    }
}
