<?php

namespace Core;

class Router
{
    protected $routes = [];
    protected $middlewares = [];

    /**
     * Add a GET route
     */
    public function get($uri, $action, $middlewares = [])
    {
        $this->addRoute('GET', $uri, $action, $middlewares);
    }

    /**
     * Add a POST route
     */
    public function post($uri, $action, $middlewares = [])
    {
        $this->addRoute('POST', $uri, $action, $middlewares);
    }

    /**
     * Add a route
     */
    protected function addRoute($method, $uri, $action, $middlewares = [])
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Dispatch the router
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchUri($route['uri'], $requestUri, $params)) {
                // Execute middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareClass = "App\\Middleware\\{$middleware}";
                    if (class_exists($middlewareClass)) {
                        $middlewareInstance = new $middlewareClass();
                        $middlewareInstance->handle();
                    }
                }

                // Execute action
                return $this->executeAction($route['action'], $params);
            }
        }

        // 404 Not Found
        $this->notFound();
    }

    /**
     * Match URI pattern
     */
    protected function matchUri($routeUri, $requestUri, &$params)
    {
        $params = [];
        
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            // Extract parameter names
            preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $routeUri, $paramNames);
            
            // Map parameter values to names
            array_shift($matches); // Remove full match
            
            if (!empty($paramNames[1])) {
                $params = array_combine($paramNames[1], $matches);
            }
            
            return true;
        }

        return false;
    }

    /**
     * Execute controller action
     */
    protected function executeAction($action, $params = [])
    {
        if (is_callable($action)) {
            // Closure
            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            // Controller@method
            list($controller, $method) = explode('@', $action);
            $controllerClass = "App\\Controllers\\{$controller}";

            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                
                if (method_exists($controllerInstance, $method)) {
                    return call_user_func_array([$controllerInstance, $method], $params);
                }
            }

            die("Controller method not found: {$action}");
        }
    }

    /**
     * Handle 404 Not Found
     */
    protected function notFound()
    {
        http_response_code(404);
        echo "404 - Page Not Found";
        exit;
    }

    /**
     * Generate URL
     */
    public static function url($path = '')
    {
        $config = require __DIR__ . '/../config/app.php';
        $baseUrl = rtrim($config['url'], '/');
        $path = ltrim($path, '/');
        
        return $baseUrl . '/' . $path;
    }
}

