<?php

namespace Core;

class View
{
    /**
     * Render a view file
     */
    public static function render($view, $data = [])
    {
        // Set base path for includes
        define('VIEW_PATH', __DIR__ . '/../app/Views/');
        
        $viewPath = VIEW_PATH . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            die("View not found: {$view}");
        }
        
        // Extract data array to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        require $viewPath;
        
        // Get the buffered content
        $content = ob_get_clean();
        
        // Check if we need to use a layout
        if (isset($layout) && $layout) {
            $layoutPath = __DIR__ . '/../app/Views/layouts/' . $layout . '.php';
            
            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Escape HTML to prevent XSS
     */
    public static function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Include a partial view
     */
    public static function partial($partial, $data = [])
    {
        $partialPath = __DIR__ . '/../app/Views/' . str_replace('.', '/', $partial) . '.php';
        
        if (file_exists($partialPath)) {
            extract($data);
            require $partialPath;
        }
    }
}

