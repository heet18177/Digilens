<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Like;

class LikeController extends Controller
{
    private $likeModel;

    public function __construct()
    {
        $this->likeModel = new Like();
    }

    /**
     * Toggle like
     */
    public function toggle()
    {
        if (!$this->isAuthenticated()) {
            return $this->json([
                'success' => false,
                'message' => 'Please login to like'
            ], 401);
        }

        $blogId = $this->input('blog_id');
        $user = $this->user();

        try {
            $result = $this->likeModel->toggle($user['id'], $blogId);
            $likeCount = $this->likeModel->getLikeCount($blogId);
            $userLiked = $this->likeModel->hasLiked($user['id'], $blogId);

            return $this->json([
                'success' => true,
                'liked' => $userLiked,
                'count' => $likeCount
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Failed to toggle like'
            ], 500);
        }
    }
}

