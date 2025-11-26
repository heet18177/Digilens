<?php

namespace App\Models;

use Core\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';
    protected $fillable = ['user_id', 'blog_id'];

    /**
     * Check if user has bookmarked a blog
     */
    public function hasBookmarked($userId, $blogId)
    {
        $sql = "SELECT COUNT(*) as total FROM bookmarks WHERE user_id = :user_id AND blog_id = :blog_id";
        $result = $this->query($sql, ['user_id' => $userId, 'blog_id' => $blogId]);
        return $result[0]['total'] > 0;
    }

    /**
     * Toggle bookmark
     */
    public function toggle($userId, $blogId)
    {
        if ($this->hasBookmarked($userId, $blogId)) {
            return $this->removeBookmark($userId, $blogId);
        }
        
        return $this->addBookmark($userId, $blogId);
    }

    /**
     * Add bookmark
     */
    public function addBookmark($userId, $blogId)
    {
        try {
            return $this->create([
                'user_id' => $userId,
                'blog_id' => $blogId
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Remove bookmark
     */
    public function removeBookmark($userId, $blogId)
    {
        $sql = "DELETE FROM bookmarks WHERE user_id = :user_id AND blog_id = :blog_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId, 'blog_id' => $blogId]);
    }

    /**
     * Get user's bookmarks
     */
    public function getUserBookmarks($userId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT b.*, u.username, bm.created_at as bookmarked_at
                FROM blogs b
                INNER JOIN users u ON b.user_id = u.id
                INNER JOIN bookmarks bm ON b.id = bm.blog_id
                WHERE bm.user_id = :user_id
                ORDER BY bm.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        return $this->query($sql, ['user_id' => $userId]);
    }
}

