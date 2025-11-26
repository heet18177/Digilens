<?php

namespace Core;

class Application
{
    protected $router;

    public function __construct()
    {
        $this->loadEnvironment();
        $this->setTimezone();
        $this->setErrorReporting();
        $this->router = new Router();
        Session::start();
    }

    /**
     * Set default timezone
     */
    protected function setTimezone()
    {
        // Set timezone from environment or use UTC as default
        $timezone = $_ENV['APP_TIMEZONE'] ?? 'UTC';
        date_default_timezone_set($timezone);
    }

    /**
     * Load environment variables
     */
    protected function loadEnvironment()
    {
        if (file_exists(__DIR__ . '/../.env')) {
            $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes
                $value = trim($value, '"\'');
                
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }

    /**
     * Set error reporting based on environment
     */
    protected function setErrorReporting()
    {
        $config = require __DIR__ . '/../config/app.php';
        
        if ($config['debug']) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }
    }

    /**
     * Get router instance
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Run the application
     */
    public function run()
    {
        try {
            // Load routes
            $router = $this->router;
            require __DIR__ . '/../config/routes.php';
            
            // Dispatch router
            $this->router->dispatch();
        } catch (\Exception $e) {
            // Log the error
            error_log($e->getMessage() . "\n" . $e->getTraceAsString());
            
            if ($this->isDebugMode()) {
                // Show detailed error in debug mode
                echo "<h1>Application Error</h1>";
                echo "<pre>";
                echo "Message: " . htmlspecialchars($e->getMessage()) . "\n\n";
                echo "File: " . htmlspecialchars($e->getFile()) . "\n";
                echo "Line: " . htmlspecialchars($e->getLine()) . "\n\n";
                echo "Stack Trace:\n" . htmlspecialchars($e->getTraceAsString());
                echo "</pre>";
            } else {
                // Show generic error in production
                http_response_code(500);
                echo "<h1>Internal Server Error</h1>";
                echo "<p>An error occurred. Please try again later.</p>";
            }
        }
    }
    
    protected function isDebugMode()
    {
        $config = require __DIR__ . '/../config/app.php';
        return isset($config['debug']) && $config['debug'] === true;
    }
}

