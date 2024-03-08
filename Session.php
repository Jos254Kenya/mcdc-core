<?php

namespace VMSMeruDairy\core;

/**
 * Class Session
 * Manages the session and handles flash messages.
 * Example usage - setFlash('Key','message'),,,, and accessed as getFlas('key')
 * the session is automatically destructed in the __destruct method, which the already accessed session freeing memory
 * This class is globally accessible in the whole project
 * See Documentation document for more details
 *
 * @package VMSMeruDairy\core
 */
class Session
{

    protected const FLASH_KEY = 'flash_messages';

    /**
     * Session constructor.
     * Initiates the session and processes flash messages.
     */
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    /**
     * Sets a flash message.
     *
     * @param string $key The key of the flash message.
     * @param mixed $message The message to be stored.
     */
    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    /**
     * Retrieves a flash message.
     *
     * @param string $key The key of the flash message.
     * @return mixed|false The flash message if found, false otherwise.
     */
    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    /**
     * Sets a session value.
     *
     * @param string $key The key of the session value.
     * @param mixed $value The value to be stored.
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves a session value.
     *
     * @param string $key The key of the session value.
     * @return mixed|false The session value if found, false otherwise.
     */
    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    /**
     * Removes a session value.
     *
     * @param string $key The key of the session value to be removed.
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destructor.
     * Removes flash messages from the session.
     */
    public function __destruct()
    {
        $this->removeFlashMessages();
    }

    /**
     * Removes flash messages marked for removal from the session.
     */
    private function removeFlashMessages()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}
