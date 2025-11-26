<?php

namespace App\Controllers;

use Core\Controller;
use Core\Session;
use App\Models\Blog;
use App\Models\User;
use App\Models\Vote;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;

class BlogController extends Controller
{
    private $blogModel;
    private $voteModel;
    private $likeModel;
    private $commentModel;
    private $categoryModel;

    public function __construct()
    {
        $this->blogModel = new Blog();
        $this->voteModel = new Vote();
        $this->likeModel = new Like();
        $this->commentModel = new Comment();
        $this->categoryModel = new Category();
    }

    /**
     * Show all blogs (homepage)
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $blogs = $this->blogModel->getPublished($page, 10);
        $trending = $this->blogModel->getTrending(5);
        $categories = $this->categoryModel->getPopular(10);

        foreach ($blogs['data'] as &$blog) {
            $blog['votes'] = $this->voteModel->getVoteCounts($blog['id']);
            $blog['likes'] = $this->likeModel->getLikeCount($blog['id']);
            $blog['comments'] = $this->commentModel->count(['blog_id' => $blog['id']]);

            if ($this->isAuthenticated()) {
                $blog['user_vote'] = $this->voteModel->getUserVote($this->user()['id'], $blog['id']);
                $blog['user_liked'] = $this->likeModel->hasLiked($this->user()['id'], $blog['id']);
            }
        }

        return $this->view('blog/index', [
            'title' => 'Blog - Home',
            'layout' => 'main',
            'blogs' => $blogs,
            'trending' => $trending,
            'categories' => $categories
        ]);
    }

    /**
     * Show single blog
     */
    public function show($slug)
    {
        $blog = $this->blogModel->getBySlugWithAuthor($slug);

        if (!$blog) {
            $this->setFlash('error', 'Blog not found');
            return $this->redirect('/');
        }

        $this->blogModel->incrementViews($blog['id']);

        $blog['votes'] = $this->voteModel->getVoteCounts($blog['id']);
        $blog['likes'] = $this->likeModel->getLikeCount($blog['id']);

        if ($this->isAuthenticated()) {
            $userId = $this->user()['id'];
            $blog['user_vote'] = $this->voteModel->getUserVote($userId, $blog['id']);
            $blog['user_liked'] = $this->likeModel->hasLiked($userId, $blog['id']);
        }

        $comments = $this->commentModel->getByBlog($blog['id']);

        foreach ($comments as &$comment) {
            $comment['replies'] = $this->commentModel->getReplies($comment['id']);
        }

        $related = $this->blogModel->getRelated($blog['id'], 5);
        $categories = $this->blogModel->getCategories($blog['id']);

        return $this->view('blog/show', [
            'title' => $blog['title'],
            'layout' => 'main',
            'blog' => $blog,
            'comments' => $comments,
            'related' => $related,
            'categories' => $categories
        ]);
    }

    /**
     * Show create blog form
     */
    public function create()
    {
        $this->requireAuth();

        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }

        $categories = $this->categoryModel->all();

        return $this->view('blog/create', [
            'title' => 'Create New Post',
            'layout' => 'main',
            'categories' => $categories
        ]);
    }

    /**
     * Store new blog
     */
    public function store()
    {
        $this->requireAuth();

        $user = $this->user();

        $data = [
            'title' => $this->input('title'),
            'content' => $this->input('content'),
            'excerpt' => $this->input('excerpt'),
            'status' => $this->input('status', 'draft'),
            'user_id' => $user['id']
        ];

        $validation = $this->validate($data, [
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:50',
        ]);

        if (!$validation['valid']) {
            Session::set('errors', $validation['errors']);
            Session::set('old_input', $data);
            return $this->redirect('/blog/create');
        }

        $data['slug'] = slug($data['title']);
        $existingBlog = $this->blogModel->findBySlug($data['slug']);
        if ($existingBlog) {
            $data['slug'] = $data['slug'] . '-' . time();
        }

        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $image = uploadFile($_FILES['featured_image'], 'uploads/blog-images');
            if ($image) {
                $data['featured_image'] = $image;
            }
        }

        if (empty($data['excerpt'])) {
            $data['excerpt'] = truncate(strip_tags($data['content']), 200);
        }

        $blogId = $this->blogModel->create($data);

        if ($blogId) {
            $categoryIds = $this->input('categories', []);
            if (!empty($categoryIds)) {
                $this->blogModel->attachCategories($blogId, $categoryIds);
            }

            $this->setFlash('success', 'Blog post created successfully!');

            $blog = $this->blogModel->find($blogId);
            return $this->redirect('/blog/' . $blog['slug']);
        }

        $this->setFlash('error', 'Failed to create blog post.');
        return $this->redirect('/blog/create');
    }

    /**
     * Show edit blog form
     */
    public function edit($id)
    {
        $this->requireAuth();

        $blog = $this->blogModel->find($id);

        if (!$blog) {
            $this->setFlash('error', 'Blog not found');
            return $this->redirect('/');
        }

        $user = $this->user();
        if ($blog['user_id'] != $user['id']) {
            $this->setFlash('error', 'Unauthorized access');
            return $this->redirect('/');
        }

        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }

        $categories = $this->categoryModel->all();
        $blogCategories = $this->blogModel->getCategories($blog['id']);
        $selectedCategories = array_column($blogCategories, 'id');

        return $this->view('blog/edit', [
            'title' => 'Edit Post',
            'layout' => 'main',
            'blog' => $blog,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories
        ]);
    }

    /**
     * Update blog
     */
    public function update($id)
    {
        $this->requireAuth();

        $blog = $this->blogModel->find($id);

        if (!$blog) {
            $this->setFlash('error', 'Blog not found');
            return $this->redirect('/');
        }

        $user = $this->user();
        if ($blog['user_id'] != $user['id']) {
            $this->setFlash('error', 'Unauthorized access');
            return $this->redirect('/');
        }

        $data = [
            'title' => $this->input('title'),
            'content' => $this->input('content'),
            'excerpt' => $this->input('excerpt'),
            'status' => $this->input('status', 'draft')
        ];

        $validation = $this->validate($data, [
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:50',
        ]);

        if (!$validation['valid']) {
            Session::set('errors', $validation['errors']);
            return $this->redirect('/blog/' . $id . '/edit');
        }

        if ($data['title'] != $blog['title']) {
            $newSlug = slug($data['title']);
            $existingBlog = $this->blogModel->findBySlug($newSlug);
            if ($existingBlog && $existingBlog['id'] != $id) {
                $newSlug = $newSlug . '-' . time();
            }
            $data['slug'] = $newSlug;
        }

        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $image = uploadFile($_FILES['featured_image'], 'uploads/blog-images');
            if ($image) {
                if ($blog['featured_image']) {
                    deleteFile($blog['featured_image']);
                }
                $data['featured_image'] = $image;
            }
        }

        if (empty($data['excerpt'])) {
            $data['excerpt'] = truncate(strip_tags($data['content']), 200);
        }

        if ($this->blogModel->update($id, $data)) {
            $categoryIds = $this->input('categories', []);
            $this->blogModel->detachCategories($id);

            if (!empty($categoryIds)) {
                $this->blogModel->attachCategories($id, $categoryIds);
            }

            $this->setFlash('success', 'Blog post updated successfully!');
            $updatedBlog = $this->blogModel->find($id);
            return $this->redirect('/blog/' . $updatedBlog['slug']);
        }

        $this->setFlash('error', 'Failed to update blog post.');
        return $this->redirect('/blog/' . $id . '/edit');
    }

    /**
     * Delete blog
     */
    public function delete($id)
    {
        $this->requireAuth();

        $blog = $this->blogModel->find($id);

        if (!$blog) {
            return $this->json(['success' => false, 'message' => 'Blog not found'], 404);
        }

        $user = $this->user();
        if ($blog['user_id'] != $user['id']) {
            return $this->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        if ($blog['featured_image']) {
            deleteFile($blog['featured_image']);
        }

        if ($this->blogModel->delete($id)) {
            $this->setFlash('success', 'Blog post deleted successfully!');
            return $this->json(['success' => true, 'redirect' => '/profile']);
        }

        return $this->json(['success' => false, 'message' => 'Failed to delete blog post']);
    }

    /**
     * 🔍 Search blogs (enhanced)
     */
    public function search()
    {
        $keyword = trim($this->input('q', ''));
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if (empty($keyword)) {
            $this->setFlash('error', 'Please enter a search term.');
            return $this->redirect('/');
        }

        $blogs = $this->blogModel->search($keyword, $page, 10);

        if (isset($blogs['data']) && is_array($blogs['data'])) {
            foreach ($blogs['data'] as &$blog) {
                // Get author details if not included
                if (!isset($blog['username'])) {
                    $author = (new User())->find($blog['user_id']);
                    $blog['username'] = $author['username'];
                    $blog['avatar'] = $author['avatar'];
                    $blog['bio'] = $author['bio'];
                }
                
                $blog['votes'] = $this->voteModel->getVoteCounts($blog['id']);
                $blog['likes'] = $this->likeModel->getLikeCount($blog['id']);
                $blog['comments'] = $this->commentModel->count(['blog_id' => $blog['id']]);

                if ($this->isAuthenticated()) {
                    $userId = $this->user()['id'];
                    $blog['user_vote'] = $this->voteModel->getUserVote($userId, $blog['id']);
                    $blog['user_liked'] = $this->likeModel->hasLiked($userId, $blog['id']);
                }
            }
            unset($blog); // Unset reference after foreach

        }

        // Add search form to mobile menu
        return $this->view('blog/search', [
            'title' => 'Search results for "' . e($keyword) . '"',
            'layout' => 'main',
            'blogs' => $blogs,
            'keyword' => $keyword
        ]);
    }

    /**
     * Show blogs by category
     */
    public function category($slug)
    {
        $category = $this->categoryModel->findBySlug($slug);

        if (!$category) {
            $this->setFlash('error', 'Category not found');
            return $this->redirect('/');
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $blogs = $this->blogModel->getByCategory($category['id'], $page, 10);

        return $this->view('blog/category', [
            'title' => $category['name'],
            'layout' => 'main',
            'category' => $category,
            'blogs' => $blogs
        ]);
    }
}
