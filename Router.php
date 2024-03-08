<?php

namespace VMSMeruDairy\core;

use VMSMeruDairy\core\exception\NotFoundException;

/**
 * Class Router
 * Handles routing for incoming and outgoing URLs using the Request and Response classes.
 *
 * @package VMSMeruDairy\core
 */
class Router
{
    private Request $request;
    private Response $response;
    private array $routeMap = [];

    /**
     * Router constructor.
     *
     * @param Request $request The request object.
     * @param Response $response The response object.
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Registers a GET route.
     *
     * @param string $url The URL pattern.
     * @param mixed $callback The callback to be executed.
     */
    public function get(string $url, $callback)
    {
        $this->routeMap['get'][$url] = $callback;
    }

    /**
     * Registers a POST route.
     *
     * @param string $url The URL pattern.
     * @param mixed $callback The callback to be executed.
     */
    public function post(string $url, $callback)
    {
        $this->routeMap['post'][$url] = $callback;
    }

    /**
     * Retrieves the route map for the specified HTTP method.
     *
     * @param string $method The HTTP method.
     * @return array The route map.
     */
    public function getRouteMap($method): array
    {
        return $this->routeMap[$method] ?? [];
    }

    /**
     * Retrieves the callback for the current request.
     *
     * @return false|mixed The callback or false if not found.
     */
    public function getCallback()
    {
        // Method and URL of the current request
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $url = trim($url, '/');

        $routes = $this->getRouteMap($method);

        $routeParams = false;

        foreach ($routes as $route => $callback) {
            $route = trim($route, '/');
            $routeNames = [];

            if (!$route) {
                continue;
            }

            if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
                $routeNames = $matches[1];
            }

            $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route) . "$@";

            if (preg_match_all($routeRegex, $url, $valueMatches)) {
                $values = [];
                for ($i = 1; $i < count($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }
                $routeParams = array_combine($routeNames, $values);

                $this->request->setRouteParams($routeParams);
                return $callback;
            }
        }

        return false;
    }

    /**
     * Resolves the current request.
     *
     * @return mixed The result of the resolved request.
     * @throws NotFoundException If the requested resource is not found.
     */
    public function resolve()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $callback = $this->routeMap[$method][$url] ?? false;
        if (!$callback) {

            $callback = $this->getCallback();

            if ($callback === false) {
                throw new NotFoundException();
            }
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        if (is_array($callback)) {
            $controller = new $callback[0];
            $controller->action = $callback[1];
            Application::$app->controller = $controller;
            $middlewares = $controller->getMiddlewares();
            foreach ($middlewares as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }

}
