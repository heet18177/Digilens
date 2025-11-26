<?php

namespace App\Middleware;

use Core\Request;
use Core\Session;

class CsrfMiddleware
{
    /**
     * Handle CSRF token validation
     */
    public function handle()
    {
        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            
            if (!$token || !$this->verifyCsrf($token)) {
                http_response_code(403);
                die('CSRF token validation failed');
            }
        }
    }

    /**
     * Verify CSRF token
     */
    private function verifyCsrf($token)
    {
        $sessionToken = Session::get('csrf_token');
        return $sessionToken && hash_equals($sessionToken, $token);
    }
}

