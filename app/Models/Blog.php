<?php

namespace App\Models;

use Core\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = ['user_id', 'title', 'slug', 'content', 'excerpt', 'featured_image', 'status'];

    /**
     * Get blog by slug
     */
    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }

    /**
     * Get published blogs with pagination
     */
    public function getPublished($page = 1, $perPage = 10)
    {
        return $this->paginate($page, $perPage, ['status' => 'published'], 'created_at', 'DESC');
    }

    /**
     * Get blog with author details
     */
    public function getBlogWithAuthor($blogId)
    {
        $sql = "SELECT b.*, u.username, u.avatar, u.bio
                FROM blogs b
                INNER JOIN users u ON b.user_id = u.id
                WHERE b.id = :blog_id";
        $result = $this->query($sql, ['blog_id' => $blogId]);
        return $result[0] ?? null;
    }

    /**
     * Get blog by slug with author
     */
    public function getBySlugWithAuthor($slug)
    {
        $sql = "SELECT b.*, u.username, u.avatar, u.bio
                FROM blogs b
                INNER JOIN users u ON b.user_id = u.id
                WHERE b.slug = :slug";
        $result = $this->query($sql, ['slug' => $slug]);
        return $result[0] ?? null;
    }

    /**
     * Get trending blogs (most views)
     */
    public function getTrending($limit = 5)
    {
        $sql = "SELECT b.*, u.username
                FROM blogs b
                INNER JOIN users u ON b.user_id = u.id
                WHERE b.status = 'published'
                ORDER BY b.views DESC
                LIMIT {$limit}";
        return $this->query($sql);
    }

    /**
     * Get popular blogs (most votes)
     */
    public function getPopular($limit = 10)
    {
        $sql = "SELECT b.*, u.username,
                COUNT(CASE WHEN v.vote_type = 'upvote' THEN 1 END) as upvotes,
                COUNT(CASE WHEN v.vote_type = 'downvote' THEN 1 END) as downvotes
                FROM blogs b
                INNER JOIN users u ON b.user_id = u.id
                LEFT JOIN votes v ON b.id = v.blog_id
                WHERE b.status = 'published'
                GROUP BY b.id
                ORDER BY (upvotes - downvotes) DESC
                LIMIT {$limit}";
        return $this->query($sql);
    }

    /**
     * Increment view count
     */
    public function incrementViews($blogId)
    {
        $sql = "UPDATE blogs SET views = views + 1 WHERE id = :blog_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['blog_id' => $blogId]);
    }

    /**
     * Get vote counts for a blog
     */
    public function getVoteCounts($blogId)
    {
        $sql = "SELECT 
                COUNT(CASE WHEN vote_type = 'upvote' THEN 1 END) as upvotes,
                COUNT(CASE WHEN vote_type = 'downvote' THEN 1 END) as downvotes
                FROM votes
                WHERE blog_id = :blog_id";
        $result = $this->query($sql, ['blog_id' => $blogId]);
        return $result[0] ?? ['upvotes' => 0, 'downvotes' => 0];
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
     * Get comment count for a blog
     */
    public function getCommentCount($blogId)
    {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE blog_id = :blog_id";
        $result = $this->query($sql, ['blog_id' => $blogId]);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Search blogs with pagination
     */
    public function search($keyword, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $keyword = "%{$keyword}%";
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total
                     FROM blogs b
                     INNER JOIN users u ON b.user_id = u.id
                     WHERE b.status = 'published'
                     AND (b.title LIKE :keyword_title OR b.content LIKE :keyword_content)";
        
        $params = [
            'keyword_title' => $keyword,
            'keyword_content' => $keyword
        ];
        
        $total = $this->query($countSql, $params)[0]['total'];
        
        // Get paginated results with all necessary fields
        $sql = "SELECT 
                    b.*,
                    u.id as author_id,
                    u.username,
                    u.avatar,
                    u.bio,
                    u.created_at as author_joined
                FROM blogs b
                INNER JOIN users u ON b.user_id = u.id
                WHERE b.status = 'published'
                AND (b.title LIKE :keyword_title OR b.content LIKE :keyword_content)
                ORDER BY b.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->query($sql, $params);
        
        // Calculate pagination data
        $totalPages = ceil($total / $perPage);
        
        // Ensure we have an array even if no results found
        $data = $data ?: [];
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'has_more' => $page < $totalPages
        ];
    }

    /**
     * Get blogs by category
     */
    public function getByCategory($categoryId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT b.*, u.username
                FROM blogs b
                INNER JOIN users u ON b.user_id = u.id
                INNER JOIN blog_categories bc ON b.id = bc.blog_id
                WHERE b.status = 'published' AND bc.category_id = :category_id
                ORDER BY b.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        return $this->query($sql, ['category_id' => $categoryId]);
    }

    /**
     * Get related blogs (same categories)
     */
    public function getRelated($blogId, $limit = 5)
    {
        $sql = "SELECT DISTINCT b.*, u.username
                FROM blogs b
                INNER JOIN users u ON b.user_id = u.id
                INNER JOIN blog_categories bc ON b.id = bc.blog_id
                WHERE bc.category_id IN (
                    SELECT category_id FROM blog_categories WHERE blog_id = :blog_id_1
                )
                AND b.id != :blog_id_2
                AND b.status = 'published'
                ORDER BY b.created_at DESC
                LIMIT {$limit}";
        
        return $this->query($sql, [
            'blog_id_1' => $blogId,
            'blog_id_2' => $blogId
        ]);
    }

    /**
     * Get blog categories
     */
    public function getCategories($blogId)
    {
        $sql = "SELECT c.*
                FROM categories c
                INNER JOIN blog_categories bc ON c.id = bc.category_id
                WHERE bc.blog_id = :blog_id";
        return $this->query($sql, ['blog_id' => $blogId]);
    }

    /**
     * Attach categories to blog
     */
    public function attachCategories($blogId, $categoryIds)
    {
        if (empty($categoryIds)) {
            return true;
        }

        $sql = "INSERT INTO blog_categories (blog_id, category_id) VALUES ";
        $values = [];
        $params = [];

        foreach ($categoryIds as $index => $categoryId) {
            $values[] = "(:blog_id_{$index}, :cat_{$index})";
            $params["blog_id_{$index}"] = $blogId;
            $params["cat_{$index}"] = $categoryId;
        }

        $sql .= implode(', ', $values);
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Detach all categories from blog
     */
    public function detachCategories($blogId)
    {
        $sql = "DELETE FROM blog_categories WHERE blog_id = :blog_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['blog_id' => $blogId]);
    }
}

