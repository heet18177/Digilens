<?php

namespace Core;

class Request
{
    /**
     * Get request method
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get request URI
     */
    public static function uri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Check if request is POST
     */
    public static function isPost()
    {
        return self::method() === 'POST';
    }

    /**
     * Check if request is GET
     */
    public static function isGet()
    {
        return self::method() === 'GET';
    }

    /**
     * Get input data
     */
    public static function input($key = null, $default = null)
    {
        $data = array_merge($_GET, $_POST);
        
        if ($key === null) {
            return $data;
        }
        
        return $data[$key] ?? $default;
    }

    /**
     * Get POST data
     */
    public static function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     */
    public static function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? $default;
    }

    /**
     * Get uploaded file
     */
    public static function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Check if request has file
     */
    public static function hasFile($key)
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Get all files
     */
    public static function files()
    {
        return $_FILES;
    }

    /**
     * Get request headers
     */
    public static function headers()
    {
        return getallheaders();
    }

    /**
     * Get specific header
     */
    public static function header($key, $default = null)
    {
        $headers = self::headers();
        return $headers[$key] ?? $default;
    }

    /**
     * Check if request is AJAX
     */
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Get client IP address
     */
    public static function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Get user agent
     */
    public static function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
}

