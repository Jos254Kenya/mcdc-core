<?php

namespace VMSMeruDairy\core\exception;

/**
 * Class ForbiddenException
 * Custom exception class for representing forbidden access errors (HTTP 403).
 *
 * @package VMSMeruDairy\core\exception
 */
class ForbiddenException extends \Exception
{
    /**
     * The error message associated with the exception.
     *
     * @var string
     */
    protected $message = 'You don\'t have permission to access this page';

    /**
     * The HTTP status code associated with the exception.
     *
     * @var int
     */
    protected $code = 403;
}
