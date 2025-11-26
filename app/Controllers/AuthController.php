<?php

namespace App\Controllers;

use Core\Controller;
use Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        // Ensure CSRF token exists
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }

        return $this->view('auth/register', [
            'title' => 'Register',
            'layout' => 'main'
        ]);
    }

    /**
     * Handle registration
     */
    public function register()
    {
        $data = [
            'username' => $this->input('username'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'password_confirm' => $this->input('password_confirm')
        ];

        // Validation
        $validation = $this->validate($data, [
            'username' => 'required|min:3|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_confirm' => 'required|matches:password'
        ]);

        if (!$validation['valid']) {
            Session::set('errors', $validation['errors']);
            Session::set('old_input', $data);
            return $this->redirect('/register');
        }

        // Create user
        unset($data['password_confirm']);
        $userId = $this->userModel->createUser($data);

        if ($userId) {
            // Auto login after registration
            Session::set('user_id', $userId);
            Session::set('username', $data['username']);
            Session::regenerate();
            
            $this->setFlash('success', 'Registration successful! Welcome to our blog.');
            return $this->redirect('/');
        }

        $this->setFlash('error', 'Registration failed. Please try again.');
        return $this->redirect('/register');
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        // Ensure CSRF token exists
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }

        return $this->view('auth/login', [
            'title' => 'Login',
            'layout' => 'main'
        ]);
    }

    /**
     * Handle login
     */
    public function login()
    {
        $email = $this->input('email');
        $password = $this->input('password');
        $remember = $this->input('remember');

        // Validation
        $validation = $this->validate([
            'email' => $email,
            'password' => $password
        ], [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$validation['valid']) {
            Session::set('errors', $validation['errors']);
            return $this->redirect('/login');
        }

        // Find user
        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $this->setFlash('error', 'Invalid email or password');
            return $this->redirect('/login');
        }

        // Set session
        Session::set('user_id', $user['id']);
        Session::set('username', $user['username']);
        Session::set('user_role', $user['role']);
        Session::regenerate();

        $this->setFlash('success', 'Welcome back, ' . $user['username'] . '!');
        return $this->redirect('/');
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        Session::destroy();
        $this->setFlash('success', 'You have been logged out successfully.');
        return $this->redirect('/login');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $this->requireAuth();
        
        $user = $this->user();
        $blogCount = $this->userModel->blogCount($user['id']);
        $commentCount = $this->userModel->commentCount($user['id']);
        $blogs = $this->userModel->blogs($user['id']);

        return $this->view('user/profile', [
            'title' => 'My Profile',
            'layout' => 'main',
            'user' => $user,
            'blogCount' => $blogCount,
            'commentCount' => $commentCount,
            'blogs' => $blogs
        ]);
    }

    /**
     * Show edit profile form
     */
    public function showEditProfile()
    {
        $this->requireAuth();
        
        // Ensure CSRF token exists
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }

        return $this->view('user/edit', [
            'title' => 'Edit Profile',
            'layout' => 'main',
            'user' => $this->user()
        ]);
    }

    /**
     * Handle profile update
     */
    public function updateProfile()
    {
        $this->requireAuth();
        
        $user = $this->user();
        $data = [
            'username' => $this->input('username'),
            'email' => $this->input('email'),
            'bio' => $this->input('bio')
        ];

        // Validation
        $validation = $this->validate($data, [
            'username' => "required|min:3|max:50|unique:users,username,{$user['id']}",
            'email' => "required|email|unique:users,email,{$user['id']}",
        ]);

        if (!$validation['valid']) {
            Session::set('errors', $validation['errors']);
            return $this->redirect('/profile/edit');
        }

        // Debug: Log file upload data
        error_log("Avatar upload debug - FILES array: " . print_r($_FILES, true));
        
        // Handle avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
                $this->setFlash('error', 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.');
                return $this->redirect('/profile/edit');
            }
            
            // Validate file size (max 2MB for avatars)
            $maxSize = 2 * 1024 * 1024; // 2MB
            if ($_FILES['avatar']['size'] > $maxSize) {
                $this->setFlash('error', 'File size too large. Maximum size is 2MB.');
                return $this->redirect('/profile/edit');
            }
            
            $avatar = uploadFile($_FILES['avatar'], 'uploads/avatars');
            
            if ($avatar) {
                // Delete old avatar
                if ($user['avatar']) {
                    deleteFile($user['avatar']);
                }
                $data['avatar'] = $avatar;
            } else {
                $this->setFlash('error', 'Failed to upload avatar. Please try again.');
                return $this->redirect('/profile/edit');
            }
        }

        // Update user
        if ($this->userModel->updateProfile($user['id'], $data)) {
            Session::set('username', $data['username']);
            $this->setFlash('success', 'Profile updated successfully!');
        } else {
            $this->setFlash('error', 'Failed to update profile.');
        }

        return $this->redirect('/profile');
    }
}

