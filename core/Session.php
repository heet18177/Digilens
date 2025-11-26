<?php

namespace Core;

class Session
{
    /**
     * Start session
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require __DIR__ . '/../config/app.php';
            
            session_name($config['session']['name']);
            
            session_set_cookie_params([
                'lifetime' => $config['session']['lifetime'],
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            
            session_start();
        }
    }

    /**
     * Set session value
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     */
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     */
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session key
     */
    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy session
     */
    public static function destroy()
    {
        self::start();
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Regenerate session ID
     */
    public static function regenerate()
    {
        self::start();
        session_regenerate_id(true);
    }

    /**
     * Set flash message
     */
    public static function setFlash($type, $message)
    {
        self::start();
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Get flash message
     */
    public static function getFlash($type)
    {
        self::start();
        
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        
        return null;
    }

    /**
     * Check if flash message exists
     */
    public static function hasFlash($type)
    {
        self::start();
        return isset($_SESSION['flash'][$type]);
    }

    /**
     * Get all flash messages
     */
    public static function getAllFlash()
    {
        self::start();
        $flash = $_SESSION['flash'] ?? [];
        $_SESSION['flash'] = [];
        return $flash;
    }
}

