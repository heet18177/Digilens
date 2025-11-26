<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Vote;

class VoteController extends Controller
{
    private $voteModel;

    public function __construct()
    {
        $this->voteModel = new Vote();
    }

    /**
     * Handle vote action
     */
    public function vote()
    {
        if (!$this->isAuthenticated()) {
            return $this->json([
                'success' => false,
                'message' => 'Please login to vote'
            ], 401);
        }

        $blogId = $this->input('blog_id');
        $voteType = $this->input('vote_type'); // 'upvote' or 'downvote'

        if (!in_array($voteType, ['upvote', 'downvote'])) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid vote type'
            ], 400);
        }

        $user = $this->user();
        
        try {
            $this->voteModel->castVote($user['id'], $blogId, $voteType);
            
            $voteCounts = $this->voteModel->getVoteCounts($blogId);
            $userVote = $this->voteModel->getUserVote($user['id'], $blogId);
            
            return $this->json([
                'success' => true,
                'upvotes' => $voteCounts['upvotes'],
                'downvotes' => $voteCounts['downvotes'],
                'userVote' => $userVote ? $userVote['vote_type'] : null
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Failed to cast vote'
            ], 500);
        }
    }
}

