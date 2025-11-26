<?php

namespace App\Middleware;

use Core\Session;

class GuestMiddleware
{
    /**
     * Handle guest check (redirect if already authenticated)
     */
    public function handle()
    {
        if (Session::has('user_id')) {
            header('Location: /');
            exit;
        }
    }
}

