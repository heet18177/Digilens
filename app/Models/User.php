<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'email', 'password', 'avatar', 'bio', 'role', 'is_verified'];

    /**
     * Get user by email
     */
    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    /**
     * Get user by username
     */
    public function findByUsername($username)
    {
        return $this->findBy('username', $username);
    }

    /**
     * Get user's blogs
     */
    public function blogs($userId)
    {
        $sql = "SELECT * FROM blogs WHERE user_id = :user_id ORDER BY created_at DESC";
        return $this->query($sql, ['user_id' => $userId]);
    }

    /**
     * Get user's blog count
     */
    public function blogCount($userId)
    {
        $sql = "SELECT COUNT(*) as total FROM blogs WHERE user_id = :user_id AND status = 'published'";
        $result = $this->query($sql, ['user_id' => $userId]);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Get user's comment count
     */
    public function commentCount($userId)
    {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE user_id = :user_id";
        $result = $this->query($sql, ['user_id' => $userId]);
        return $result[0]['total'] ?? 0;
    }

    /**
     * Create new user
     */
    public function createUser($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->create($data);
    }

    /**
     * Update user profile
     */
    public function updateProfile($userId, $data)
    {
        return $this->update($userId, $data);
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'admin';
    }

    /**
     * Get user's bookmarked blogs
     */
    public function bookmarkedBlogs($userId)
    {
        $sql = "SELECT b.* FROM blogs b
                INNER JOIN bookmarks bm ON b.id = bm.blog_id
                WHERE bm.user_id = :user_id
                ORDER BY bm.created_at DESC";
        return $this->query($sql, ['user_id' => $userId]);
    }
}

