<?php

// Error reporting - will be overridden by Application class based on .env
// For production, these settings are safe (errors hidden from users)
error_reporting(E_ALL);
// During debugging show errors so we can see the real exception causing HTTP 500.
// Remember to revert this change in production.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1); // Log errors as well

// Autoload classes
require_once __DIR__ . '/../vendor/autoload.php';

// Import Application class
use Core\Application;

// Create and run application
$app = new Application();
$router = $app->getRouter();

// Load routes
require_once __DIR__ . '/../config/routes.php';

// Run the application
$app->run();

