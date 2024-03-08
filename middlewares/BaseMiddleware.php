<?php

namespace VMSMeruDairy\core\middlewares;

/**
 * Class BaseMiddleware
 * Base class for middlewares.
 * Extend this class to define custom middleware logic.
 *
 * @package VMSMeruDairy\core\middlewares
 */
abstract class BaseMiddleware
{
    /**
     * Executes the middleware logic.
     * This method should be implemented in the child classes.
     */
    abstract public function execute(): void;
}
