<?php

namespace VMSMeruDairy\core;

/**
 * Class View
 * Manages rendering of views and layouts.
 *
 * @package VMSMeruDairy\core
 */
class View
{
    public string $title = 'VMSMeruDairy';

    /**
     * Renders a view with the specified parameters within the layout.
     *
     * @param string $view The view to be rendered.
     * @param array $params Optional parameters to be passed to the view.
     * @return string The rendered view within the layout.
     */
    public function renderView(string $view, array $params): string
    {
        // Determine the layout name
        $layoutName = Application::$app->layout;
        if (Application::$app->controller) {
            $layoutName = Application::$app->controller->layout;
        }

        // Render the view content
        $viewContent = $this->renderViewOnly($view, $params);

        // Start output buffering to capture layout content
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layoutName.php";
        $layoutContent = ob_get_clean();

        // Replace the placeholder in layout with view content and return
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     * Renders a view without layout.
     *
     * @param string $view The view to be rendered.
     * @param array $params Optional parameters to be passed to the view.
     * @return string The rendered view content.
     */
    public function renderViewOnly(string $view, array $params): string
    {
        // Extract parameters as variables for use in the view
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        $viewFile = Application::$ROOT_DIR . "/views/$view.php";
        if (!file_exists($viewFile)) {
            throw new \Exception("View file '$view.php' not found.");
        }
        // Start output buffering to capture view content
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}
