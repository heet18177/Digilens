<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Bookmark;

class BookmarkController extends Controller
{
    private $bookmarkModel;

    public function __construct()
    {
        $this->bookmarkModel = new Bookmark();
    }

    /**
     * Toggle bookmark
     */
    public function toggle()
    {
        if (!$this->isAuthenticated()) {
            return $this->json([
                'success' => false,
                'message' => 'Please login to bookmark'
            ], 401);
        }

        $blogId = $this->input('blog_id');
        $user = $this->user();

        try {
            $result = $this->bookmarkModel->toggle($user['id'], $blogId);
            $bookmarked = $this->bookmarkModel->hasBookmarked($user['id'], $blogId);

            return $this->json([
                'success' => true,
                'bookmarked' => $bookmarked,
                'message' => $bookmarked ? 'Post bookmarked' : 'Bookmark removed'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Failed to toggle bookmark'
            ], 500);
        }
    }

    /**
     * Show user's bookmarks
     */
    public function index()
    {
        $this->requireAuth();

        $user = $this->user();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        $bookmarks = $this->bookmarkModel->getUserBookmarks($user['id'], $page, 10);

        return $this->view('user/bookmarks', [
            'title' => 'My Bookmarks',
            'layout' => 'main',
            'bookmarks' => $bookmarks
        ]);
    }
}

