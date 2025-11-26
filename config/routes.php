<?php

use Core\Router;

/** @var Router $router */

// Home / Blog listing
$router->get('/', 'BlogController@index');

// Authentication routes
$router->get('/register', 'AuthController@showRegister', ['GuestMiddleware']);
$router->post('/register', 'AuthController@register', ['GuestMiddleware', 'CsrfMiddleware']);
$router->get('/login', 'AuthController@showLogin', ['GuestMiddleware']);
$router->post('/login', 'AuthController@login', ['GuestMiddleware', 'CsrfMiddleware']);
$router->post('/logout', 'AuthController@logout', ['AuthMiddleware']);

// User profile routes
$router->get('/profile', 'AuthController@profile', ['AuthMiddleware']);
$router->get('/profile/edit', 'AuthController@showEditProfile', ['AuthMiddleware']);
$router->post('/profile/edit', 'AuthController@updateProfile', ['AuthMiddleware', 'CsrfMiddleware']);

// Blog routes
$router->get('/blog/create', 'BlogController@create', ['AuthMiddleware']);
$router->post('/blog/create', 'BlogController@store', ['AuthMiddleware', 'CsrfMiddleware']);
$router->get('/blog/{id}/edit', 'BlogController@edit', ['AuthMiddleware']);
$router->post('/blog/{id}/edit', 'BlogController@update', ['AuthMiddleware', 'CsrfMiddleware']);
$router->post('/blog/{id}/delete', 'BlogController@delete', ['AuthMiddleware']);
$router->get('/blog/{slug}', 'BlogController@show');

// Search
$router->get('/search', 'BlogController@search');

// Category
$router->get('/category/{slug}', 'BlogController@category');

// Vote routes
$router->post('/vote', 'VoteController@vote', ['AuthMiddleware']);

// Like routes
$router->post('/like', 'LikeController@toggle', ['AuthMiddleware']);

// Comment routes
$router->post('/comment', 'CommentController@store', ['AuthMiddleware']);
$router->post('/comment/{id}/update', 'CommentController@update', ['AuthMiddleware']);
$router->post('/comment/{id}/delete', 'CommentController@delete', ['AuthMiddleware']);

// Bookmark routes
$router->post('/bookmark', 'BookmarkController@toggle', ['AuthMiddleware']);
$router->get('/bookmarks', 'BookmarkController@index', ['AuthMiddleware']);

// API routes
$router->post('/api/upload-image', 'ApiController@uploadImage', ['AuthMiddleware']);

