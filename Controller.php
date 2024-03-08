<?php

namespace VMSMeruDairy\core;

use VMSMeruDairy\core\middlewares\BaseMiddleware;

/**
 * Class Controller
 * Base controller class responsible for managing views, layouts, and middlewares.
 *
 * @package VMSMeruDairy\core
 */
class Controller
{
    public string $layout = 'main';
    public string $action = '';

    /**
     * @var BaseMiddleware[]
     */
    protected array $middlewares = [];

    /**
     * Sets the layout for the controller.
     *
     * @param string $layout The layout to be set.
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Renders a view with optional parameters.
     *
     * @param string $view The view to be rendered.
     * @param array $params Optional parameters to be passed to the view.
     * @return string The rendered view.
     */
    public function render(string $view, array $params = []): string
    {
        return Application::$app->router->renderView($view, $params);
    }

    /**
     * Registers a middleware for the controller.
     *
     * @param BaseMiddleware $middleware The middleware to be registered.
     */
    public function registerMiddleware(BaseMiddleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Returns an array of middlewares registered for the controller.
     *
     * @return BaseMiddleware[] The array of registered middlewares.
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
