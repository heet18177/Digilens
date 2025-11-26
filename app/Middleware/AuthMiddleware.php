<?php

namespace App\Middleware;

use Core\Session;

class AuthMiddleware
{
    /**
     * Handle authentication check
     */
    public function handle()
    {
        if (!Session::has('user_id')) {
            Session::setFlash('error', 'Please login to continue');
            header('Location: /login');
            exit;
        }
    }
}

