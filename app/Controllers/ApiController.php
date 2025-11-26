<?php

namespace App\Controllers;

use Core\Controller;
use Core\Session;

class ApiController extends Controller
{
    /**
     * Handle image upload for rich text editor
     */
    public function uploadImage()
    {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Check if file was uploaded
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return $this->json(['success' => false, 'message' => 'No image uploaded'], 400);
        }

        $file = $_FILES['image'];

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return $this->json(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'], 400);
        }

        // Validate file size (max 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            return $this->json(['success' => false, 'message' => 'File size too large. Maximum size is 5MB.'], 400);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        
        // Create upload directory if it doesn't exist
        $uploadDir = 'uploads/blog-images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadPath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Return success with file URL
            return $this->json([
                'success' => true,
                'url' => asset($uploadPath),
                'filename' => $filename
            ]);
        } else {
            return $this->json(['success' => false, 'message' => 'Failed to upload image'], 500);
        }
    }

    /**
     * Handle file deletion
     */
    public function deleteImage()
    {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $imageUrl = $this->input('url');
        
        if (empty($imageUrl)) {
            return $this->json(['success' => false, 'message' => 'No image URL provided'], 400);
        }

        // Extract file path from URL
        $parsedUrl = parse_url($imageUrl);
        $filePath = ltrim($parsedUrl['path'], '/');
        
        // Security check - ensure file is in uploads directory
        if (!str_starts_with($filePath, 'uploads/')) {
            return $this->json(['success' => false, 'message' => 'Invalid file path'], 400);
        }

        // Delete file if it exists
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                return $this->json(['success' => true, 'message' => 'Image deleted successfully']);
            } else {
                return $this->json(['success' => false, 'message' => 'Failed to delete image'], 500);
            }
        } else {
            return $this->json(['success' => false, 'message' => 'Image not found'], 404);
        }
    }
}
