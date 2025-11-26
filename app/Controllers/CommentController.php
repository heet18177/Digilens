<?php

namespace App\Controllers;

use Core\Controller;
use Core\Session;
use App\Models\Comment;

class CommentController extends Controller
{
    private $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    /**
     * Store new comment
     */
    public function store()
    {
        $this->requireAuth();

        $user = $this->user();
        
        $data = [
            'blog_id' => $this->input('blog_id'),
            'user_id' => $user['id'],
            'parent_id' => $this->input('parent_id'),
            'content' => $this->input('content')
        ];

        // Validation
        $validation = $this->validate($data, [
            'blog_id' => 'required|numeric',
            'content' => 'required|min:3|max:1000'
        ]);

        if (!$validation['valid']) {
            return $this->json([
                'success' => false,
                'errors' => $validation['errors']
            ], 400);
        }

        // Create comment
        $commentId = $this->commentModel->create($data);

        if ($commentId) {
            $comment = $this->commentModel->getWithUser($commentId);
            
            return $this->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => $comment
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Failed to add comment'
        ], 500);
    }

    /**
     * Delete comment
     */
    public function delete($id)
    {
        $this->requireAuth();

        $comment = $this->commentModel->find($id);

        if (!$comment) {
            return $this->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        $user = $this->user();

        // Check if user owns the comment
        if ($comment['user_id'] != $user['id']) {
            return $this->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        if ($this->commentModel->deleteWithReplies($id)) {
            return $this->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Failed to delete comment'
        ], 500);
    }

    /**
     * Update comment
     */
    public function update($id)
    {
        $this->requireAuth();

        $comment = $this->commentModel->find($id);

        if (!$comment) {
            return $this->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        $user = $this->user();

        // Check if user owns the comment
        if ($comment['user_id'] != $user['id']) {
            return $this->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $content = $this->input('content');

        // Validation
        $validation = $this->validate(['content' => $content], [
            'content' => 'required|min:3|max:1000'
        ]);

        if (!$validation['valid']) {
            return $this->json([
                'success' => false,
                'errors' => $validation['errors']
            ], 400);
        }

        if ($this->commentModel->update($id, ['content' => $content])) {
            $updatedComment = $this->commentModel->getWithUser($id);
            
            return $this->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'comment' => $updatedComment
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Failed to update comment'
        ], 500);
    }
}

