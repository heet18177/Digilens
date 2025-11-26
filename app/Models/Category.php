<?php

namespace App\Models;

use Core\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Get category by slug
     */
    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }

    /**
     * Get all categories with blog count
     */
    public function getAllWithBlogCount()
    {
        $sql = "SELECT c.*, COUNT(bc.blog_id) as blog_count
                FROM categories c
                LEFT JOIN blog_categories bc ON c.id = bc.category_id
                LEFT JOIN blogs b ON bc.blog_id = b.id AND b.status = 'published'
                GROUP BY c.id
                ORDER BY c.name ASC";
        return $this->query($sql);
    }

    /**
     * Get popular categories
     */
    public function getPopular($limit = 10)
    {
        $sql = "SELECT c.*, COUNT(bc.blog_id) as blog_count
                FROM categories c
                INNER JOIN blog_categories bc ON c.id = bc.category_id
                INNER JOIN blogs b ON bc.blog_id = b.id AND b.status = 'published'
                GROUP BY c.id
                ORDER BY blog_count DESC
                LIMIT {$limit}";
        return $this->query($sql);
    }
}

