<?php

namespace App\Models;

use Core\Model;

class Vote extends Model
{
    protected $table = 'votes';
    protected $fillable = ['user_id', 'blog_id', 'vote_type'];

    /**
     * Get user's vote for a blog
     */
    public function getUserVote($userId, $blogId)
    {
        $sql = "SELECT * FROM votes WHERE user_id = :user_id AND blog_id = :blog_id LIMIT 1";
        $result = $this->query($sql, ['user_id' => $userId, 'blog_id' => $blogId]);
        return $result[0] ?? null;
    }

    /**
     * Cast or update vote
     */
    public function castVote($userId, $blogId, $voteType)
    {
        $existingVote = $this->getUserVote($userId, $blogId);

        if ($existingVote) {
            // If same vote type, remove the vote
            if ($existingVote['vote_type'] === $voteType) {
                return $this->removeVote($userId, $blogId);
            }
            
            // Update to different vote type
            $sql = "UPDATE votes SET vote_type = :vote_type, updated_at = CURRENT_TIMESTAMP 
                    WHERE user_id = :user_id AND blog_id = :blog_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'vote_type' => $voteType,
                'user_id' => $userId,
                'blog_id' => $blogId
            ]);
        }

        // Create new vote
        return $this->create([
            'user_id' => $userId,
            'blog_id' => $blogId,
            'vote_type' => $voteType
        ]);
    }

    /**
     * Remove vote
     */
    public function removeVote($userId, $blogId)
    {
        $sql = "DELETE FROM votes WHERE user_id = :user_id AND blog_id = :blog_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId, 'blog_id' => $blogId]);
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
     * Get net votes (upvotes - downvotes)
     */
    public function getNetVotes($blogId)
    {
        $counts = $this->getVoteCounts($blogId);
        return $counts['upvotes'] - $counts['downvotes'];
    }
}

