<?php

namespace VMSMeruDairy\core\exception;

/**
 * Class NotFoundException
 * Custom exception class for representing resource not found errors (HTTP 404).
 *
 * @package VMSMeruDairy\core\exception
 */
class NotFoundException extends \Exception
{
    /**
     * The error message associated with the exception.
     *
     * @var string
     */
    protected $message = 'That Page was not found!';

    /**
     * The HTTP status code associated with the exception.
     *
     * @var int
     */
    protected $code = 404;
}
