<?php

namespace VMSMeruDairy\core;

use VMSMeruDairy\core\db\DbModel;

/**
 * Class UserModel
 * Represents the base model class for user-related data.
 * Extend this class to define user-specific functionality and attributes.
 *
 * @package VMSMeruDairy\core
 */
abstract class UserModel extends DbModel
{
    /**
     * Retrieves the display name of the user.
     *
     * @return string The display name of the user.
     */
    abstract public function getDisplayName(): string;
}
