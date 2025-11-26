<?php

namespace App\Models;

use Core\Model;

class Like extends Model
{
    protected $table = 'likes';
    protected $fillable = ['user_id', 'blog_id'];

    /**
     * Check if user has liked a blog
     */
    public function hasLiked($userId, $blogId)
    {
        $sql = "SELECT COUNT(*) as total FROM likes WHERE user_id = :user_id AND blog_id = :blog_id";
        $result = $this->query($sql, ['user_id' => $userId, 'blog_id' => $blogId]);
        return $result[0]['total'] > 0;
    }

    /**
     * Toggle like
     */
    public function toggle($userId, $blogId)
    {
        if ($this->hasLiked($userId, $blogId)) {
            return $this->unlike($userId, $blogId);
        }
        
        return $this->like($userId, $blogId);
    }

    /**
     * Like a blog
     */
    public function like($userId, $blogId)
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
     * Unlike a blog
     */
    public function unlike($userId, $blogId)
    {
        $sql = "DELETE FROM likes WHERE user_id = :user_id AND blog_id = :blog_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId, 'blog_id' => $blogId]);
    }

    /**
     * Get like count for a blog
     */
    public function getLikeCount($blogId)
    {
        $sql = "SELECT COUNT(*) as total FROM likes WHERE blog_id = :blog_id";
        $result = $this->query($sql, ['blog_id' => $blogId]);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Get users who liked a blog
     */
    public function getLikers($blogId, $limit = 10)
    {
        $sql = "SELECT u.id, u.username, u.avatar
                FROM users u
                INNER JOIN likes l ON u.id = l.user_id
                WHERE l.blog_id = :blog_id
                ORDER BY l.created_at DESC
                LIMIT {$limit}";
        return $this->query($sql, ['blog_id' => $blogId]);
    }
}

