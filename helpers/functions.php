<?php

use Core\Router;
use Core\Session;

/**
 * Escape HTML output
 */
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate URL
 */
function url($path = '')
{
    return Router::url($path);
}

/**
 * Asset URL
 */
function asset($path)
{
    return url($path);
}

/**
 * Redirect helper
 */
function redirect($url)
{
    header("Location: {$url}");
    exit;
}

/**
 * Old input helper (for form repopulation)
 */
function old($key, $default = '')
{
    return Session::get("old_{$key}", $default);
}

/**
 * Display error for field
 */
function error($field, $errors = [])
{
    if (isset($errors[$field])) {
        return '<span class="error">' . e($errors[$field][0]) . '</span>';
    }
    return '';
}

/**
 * Check if user is authenticated
 */
function auth()
{
    return Session::has('user_id');
}

/**
 * Get current user
 */
function currentUser()
{
    if (auth()) {
        $userId = Session::get('user_id');
        $userModel = new \App\Models\User();
        return $userModel->find($userId);
    }
    return null;
}

/**
 * Get flash message
 */
function flash($type = null)
{
    if ($type) {
        return Session::getFlash($type);
    }
    return Session::getAllFlash();
}

/**
 * CSRF token field
 */
function csrfField()
{
    $token = Session::get('csrf_token');
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

/**
 * CSRF token
 */
function csrfToken()
{
    if (!Session::has('csrf_token')) {
        Session::set('csrf_token', bin2hex(random_bytes(32)));
    }
    return Session::get('csrf_token');
}

/**
 * Verify CSRF token
 */
function verifyCsrf($token)
{
    return hash_equals(Session::get('csrf_token'), $token);
}

/**
 * Generate slug from string
 */
function slug($string)
{
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Format date
 */
function formatDate($date, $format = 'M d, Y')
{
    if (empty($date) || $date === null) {
        return '';
    }
    
    // Handle DateTime objects
    if ($date instanceof DateTime) {
        return $date->format($format);
    }
    
    // Try to create DateTime object for better parsing
    // MySQL TIMESTAMP format: 'YYYY-MM-DD HH:MM:SS'
    // Parse as UTC first, then convert to server timezone
    try {
        // Check if it's a MySQL datetime format
        if (preg_match('/^\d{4}-\d{2}-\d{2}[\sT]\d{2}:\d{2}:\d{2}/', $date)) {
            // Parse as UTC (since we set MySQL to UTC)
            // MySQL returns TIMESTAMP in UTC when time_zone is set to UTC
            $dateTime = new DateTime($date, new DateTimeZone('UTC'));
            // Convert to PHP's timezone for display
            $phpTimezone = date_default_timezone_get();
            if ($phpTimezone !== 'UTC') {
                $dateTime->setTimezone(new DateTimeZone($phpTimezone));
            }
        } else {
            // For other formats, use default parsing
            $dateTime = new DateTime($date);
        }
        return $dateTime->format($format);
    } catch (Exception $e) {
        // Fallback to strtotime if DateTime fails
        $timestamp = strtotime($date);
        
        // If strtotime also fails, return empty string
        if ($timestamp === false) {
            return '';
        }
        
        return date($format, $timestamp);
    }
}

/**
 * Time ago helper
 */
function timeAgo($datetime)
{
    if (empty($datetime) || $datetime === null) {
        return '';
    }
    
    // Handle DateTime objects
    if ($datetime instanceof DateTime) {
        $timestamp = $datetime->getTimestamp();
    } else {
        // Try to create DateTime object for better parsing
        try {
            // Check if it's a MySQL datetime format
            if (preg_match('/^\d{4}-\d{2}-\d{2}[\sT]\d{2}:\d{2}:\d{2}/', $datetime)) {
                // Parse as UTC (since we set MySQL to UTC)
                // MySQL returns TIMESTAMP in UTC when time_zone is set to UTC
                $dateTime = new DateTime($datetime, new DateTimeZone('UTC'));
                // Convert to PHP's timezone for comparison
                $phpTimezone = date_default_timezone_get();
                if ($phpTimezone !== 'UTC') {
                    $dateTime->setTimezone(new DateTimeZone($phpTimezone));
                }
            } else {
                // For other formats, use default parsing
                $dateTime = new DateTime($datetime);
            }
            $timestamp = $dateTime->getTimestamp();
        } catch (Exception $e) {
            // Fallback to strtotime if DateTime fails
            $timestamp = strtotime($datetime);
            
            // If strtotime also fails, return empty string
            if ($timestamp === false) {
                return '';
            }
        }
    }
    
    $currentTime = time();
    $difference = $currentTime - $timestamp;
    
    // Handle future dates (shouldn't happen with proper timezone handling)
    if ($difference < 0) {
        $difference = abs($difference);
        $isFuture = true;
    } else {
        $isFuture = false;
    }
    
    // If less than a minute, return "just now"
    if ($difference < 60) {
        return $isFuture ? 'in a moment' : 'just now';
    }
    
    $periods = [
        'year' => 31536000,
        'month' => 2592000,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60
    ];
    
    foreach ($periods as $key => $value) {
        if ($difference >= $value) {
            $time = floor($difference / $value);
            $plural = $time > 1 ? 's' : '';
            return $isFuture 
                ? "in {$time} {$key}{$plural}" 
                : "{$time} {$key}{$plural} ago";
        }
    }
    
    return 'just now';
}

/**
 * Truncate string
 */
function truncate($string, $length = 100, $append = '...')
{
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . $append;
    }
    return $string;
}

/**
 * Upload file
 */
function uploadFile($file, $destination)
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        error_log("Upload error: " . $file['error']);
        return false;
    }
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        error_log("Invalid file type: " . $file['type']);
        return false;
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $uploadPath = __DIR__ . '/../public/' . $destination . '/' . $filename;
    
    // Create directory if not exists
    $directory = dirname($uploadPath);
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0755, true)) {
            error_log("Failed to create directory: " . $directory);
            return false;
        }
    }
    
    // Check if directory is writable
    if (!is_writable($directory)) {
        error_log("Directory not writable: " . $directory);
        return false;
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $destination . '/' . $filename;
    }
    
    error_log("Failed to move uploaded file from " . $file['tmp_name'] . " to " . $uploadPath);
    return false;
}

/**
 * Delete file
 */
function deleteFile($path)
{
    $fullPath = __DIR__ . '/../public/' . $path;
    
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    
    return false;
}

/**
 * Paginate array
 */
function paginate($array, $perPage = 10)
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $perPage;
    
    return [
        'data' => array_slice($array, $offset, $perPage),
        'total' => count($array),
        'per_page' => $perPage,
        'current_page' => $page,
        'last_page' => ceil(count($array) / $perPage)
    ];
}

/**
 * Get config value
 */
function config($key, $default = null)
{
    $keys = explode('.', $key);
    $file = array_shift($keys);
    
    $config = require __DIR__ . '/../config/' . $file . '.php';
    
    foreach ($keys as $key) {
        if (!isset($config[$key])) {
            return $default;
        }
        $config = $config[$key];
    }
    
    return $config;
}

/**
 * Dump and die
 */
function dd(...$vars)
{
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Debug print
 */
function debug($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

/**
 * Get base path
 */
function base_path($path = '')
{
    return rtrim(__DIR__ . '/../' . ltrim($path, '/'), '/');
}

