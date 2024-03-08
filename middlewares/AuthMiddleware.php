<?php

namespace VMSMeruDairy\core\middlewares;

use VMSMeruDairy\core\Application;
use VMSMeruDairy\core\exception\ForbiddenException;

/**
 * Class AuthMiddleware
 * Middleware for user authentication.
 * Ensures that only authenticated users can access specified actions.
 *
 * @package VMSMeruDairy\core\middlewares
 */
class AuthMiddleware extends BaseMiddleware
{
    /**
     * @var array List of actions that require authentication.
     */
    protected array $actions = [];

    /**
     * AuthMiddleware constructor.
     *
     * @param array $actions List of actions that require authentication.
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * Executes the middleware logic.
     * Throws a ForbiddenException if the user is not authenticated and attempts to access a protected action.
     *
     * @throws ForbiddenException If the user is not authenticated and attempts to access a protected action.
     */
    public function execute(): void
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}
