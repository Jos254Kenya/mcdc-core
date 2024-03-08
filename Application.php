<?php

namespace VMSMeruDairy\core;

use VMSMeruDairy\core\db\Database;

/**
 * Class Application
 * Core class that manages the application lifecycle, including routing, session management, and event handling.
 *
 * @package VMSMeruDairy\core
 */
class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';

    /**
     * @var array $eventListeners Holds event listeners.
     */
    protected array $eventListeners = [];
    /**
     * @var Application $app Static instance of the Application class.
     * this way of calling class was implemented since PHP 7.4 typed properties concept.
     */
    public static Application $app;

    /**
     * @var string $ROOT_DIR Root directory of the project.
     */
    public static string $ROOT_DIR;

    /**
     * @var string $userClass Class name for user management.
     */
    public string $userClass;

    /**
     * @var string $layout Default layout for rendering views.
     * this file must be available inside Views/layouts/ directory else, NotFoundException will be thrown or a critical error will be thrown
     * Ensure to maintain this file inside the said directory
     */
    public string $layout = 'main';

    /**
     * @var Router $router Manages routing functionality.
     */
    public Router $router;

    /**
     * @var Request $request Handles incoming HTTP requests.
     */
    public Request $request;

    /**
     * @var Response $response Handles outgoing HTTP responses.
     */
    public Response $response;

    /**
     * @var Controller|null $controller Current controller instance.
     * in case the controller is not provided, pass null to avoid PHP's critical error message
     */
    public ?Controller $controller = null;

    /**
     * @var Database $db Database instance.
     */
    public Database $db;

    /**
     * @var Session $session Session management.
     */
    public Session $session;

    /**
     * @var View $view View rendering.
     */
    public View $view;

    /**
     * @var UserModel|null $user Current user model instance.
     */
    public ?UserModel $user;

    /**
     * Application constructor.
     *
     * @param string $rootDir Root directory of the project.
     *  we define the project's root dir pointed in the main entry script, this way, we do not have to worry about
     * callback URLs
     * @param array $config Configuration options.
     */
    public function __construct(string $rootDir, array $config)
    {
        $this->user = null;
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        /**
         * @Router is accessed in the Application class this way, taking into account the Request and Response classes'
         *passed as variable in the Router __contsruct function/method
         */
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $this->session = new Session();
        $this->view = new View();

        $userId = Application::$app->session->get('user');
        if ($userId) {
            $key = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$key => $userId]);
        }
    }

    /**
     * Checks if the user is a guest. this is a sample usage and can be accessed globally in the application/project
     *
     * @return bool Returns true if the user is a guest, otherwise false.
     */
    public static function isGuest(): bool
    {
        return !self::$app->user;
    }

    /**
     * Logs in a user.
     *
     * @param UserModel $user The user model to log in.
     * @return bool Returns true on successful login.
     */
    public function login(UserModel $user): bool
    {
        $this->user = $user;
        $className = get_class($user);
        $primaryKey = $className::primaryKey();
        $value = $user->{$primaryKey};
        Application::$app->session->set('user', $value);

        return true;
    }

    /**
     * Logs out the current user.
     */
    public function logout(): void
    {
        $this->user = null;
        self::$app->session->remove('user');
    }

    /**
     * Starts the application and handles the request.
     */
    public function run(): void
    {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            echo $this->router->renderView('_error', [
                'exception' => $e,
            ]);
        }
    }

    /**
     * Triggers an event.
     *
     * @param string $eventName The name of the event.
     */
    public function triggerEvent(string $eventName): void
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    /**
     * Attaches an event listener to an event.
     *
     * @param string $eventName The name of the event.
     * @param mixed $callback The callback function to be executed.
     */
    public function on(string $eventName, $callback): void
    {
        $this->eventListeners[$eventName][] = $callback;
    }
}
