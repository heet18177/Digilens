<?php

namespace Core;

abstract class Controller
{
    /**
     * Render a view
     */
    protected function view($view, $data = [])
    {
        return View::render($view, $data);
    }

    /**
     * Redirect to a URL
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Get request input
     */
    protected function input($key = null, $default = null)
    {
        return Request::input($key, $default);
    }

    /**
     * Validate request data
     */
    protected function validate(array $data, array $rules)
    {
        return Validation::validate($data, $rules);
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated()
    {
        return Session::has('user_id');
    }

    /**
     * Get current authenticated user
     */
    protected function user()
    {
        if ($this->isAuthenticated()) {
            $userId = Session::get('user_id');
            $userModel = new \App\Models\User();
            return $userModel->find($userId);
        }
        
        return null;
    }

    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            Session::setFlash('error', 'Please login to continue');
            $this->redirect('/login');
        }
    }

    /**
     * Set flash message
     */
    protected function setFlash($type, $message)
    {
        Session::setFlash($type, $message);
    }
}

