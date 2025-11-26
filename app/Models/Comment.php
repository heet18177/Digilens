<?php

namespace App\Models;

use Core\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = ['blog_id', 'user_id', 'parent_id', 'content'];

    /**
     * Get comments for a blog
     */
    public function getByBlog($blogId)
    {
        $sql = "SELECT c.*, u.username, u.avatar
                FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.blog_id = :blog_id AND c.parent_id IS NULL
                ORDER BY c.created_at DESC";
        return $this->query($sql, ['blog_id' => $blogId]);
    }

    /**
     * Get replies for a comment
     */
    public function getReplies($commentId)
    {
        $sql = "SELECT c.*, u.username, u.avatar
                FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.parent_id = :parent_id
                ORDER BY c.created_at ASC";
        return $this->query($sql, ['parent_id' => $commentId]);
    }

    /**
     * Get comment with user details
     */
    public function getWithUser($commentId)
    {
        $sql = "SELECT c.*, u.username, u.avatar
                FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.id = :comment_id";
        $result = $this->query($sql, ['comment_id' => $commentId]);
        return $result[0] ?? null;
    }

    /**
     * Get recent comments
     */
    public function getRecent($limit = 10)
    {
        $sql = "SELECT c.*, u.username, b.title as blog_title, b.slug as blog_slug
                FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                INNER JOIN blogs b ON c.blog_id = b.id
                WHERE b.status = 'published'
                ORDER BY c.created_at DESC
                LIMIT {$limit}";
        return $this->query($sql);
    }

    /**
     * Delete comment and its replies
     */
    public function deleteWithReplies($commentId)
    {
        // Delete replies first
        $sql = "DELETE FROM comments WHERE parent_id = :comment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['comment_id' => $commentId]);
        
        // Delete the comment
        return $this->delete($commentId);
    }
}

