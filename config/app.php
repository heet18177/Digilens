<?php

// Determine APP_URL based on environment
$defaultUrl = 'https://digilens.great-site.net'; // Production URL

// Auto-detect if we're on localhost or production
if (!isset($_ENV['APP_URL'])) {
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';

    // Check if running on localhost
    if (in_array($host, ['localhost', '127.0.0.1', 'localhost:8000', '127.0.0.1:8000'])) {
        $defaultUrl = 'http://localhost:8000';
    } else {
        $defaultUrl = 'https://digilens.great-site.net';
    }
}

return [
    'name' => $_ENV['APP_NAME'] ?? 'Blog Application',
    'url' => $_ENV['APP_URL'] ?? $defaultUrl,
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => true, // Temporarily enabled for debugging

    'session' => [
        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 7200,
        'name' => $_ENV['SESSION_NAME'] ?? 'blog_session',
    ],

    'upload' => [
        'max_size' => $_ENV['MAX_UPLOAD_SIZE'] ?? 5242880, // 5MB
        'allowed_image_types' => explode(',', $_ENV['ALLOWED_IMAGE_TYPES'] ?? 'jpg,jpeg,png,gif,webp'),
        'avatar_path' => 'uploads/avatars/',
        'blog_image_path' => 'uploads/blog-images/',
    ],

    'pagination' => [
        'posts_per_page' => $_ENV['POSTS_PER_PAGE'] ?? 10,
        'comments_per_page' => $_ENV['COMMENTS_PER_PAGE'] ?? 20,
    ],

    'security' => [
        'csrf_token_name' => $_ENV['CSRF_TOKEN_NAME'] ?? 'csrf_token',
        'hash_cost' => $_ENV['HASH_COST'] ?? 12,
    ],
];
