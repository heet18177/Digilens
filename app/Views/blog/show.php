<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <article class="card">
        <!-- Featured Image -->
        <?php if ($blog['featured_image']): ?>
            <img src="<?= asset($blog['featured_image']) ?>" 
                 alt="<?= e($blog['title']) ?>"
                 class="w-full h-96 object-cover rounded-lg mb-6">
        <?php endif; ?>

        <!-- Title -->
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            <?= e($blog['title']) ?>
        </h1>

        <!-- Author and Meta -->
        <div class="flex items-center justify-between pb-6 border-b border-gray-200 dark:border-gray-700 mb-6">
            <div class="flex items-center space-x-3">
                <?php if ($blog['avatar']): ?>
                    <img src="<?= asset($blog['avatar']) ?>" alt="<?= e($blog['username']) ?>" 
                         class="w-12 h-12 rounded-full object-cover">
                <?php else: ?>
                    <div class="w-12 h-12 rounded-full bg-primary-600 text-white flex items-center justify-center text-lg">
                        <?= strtoupper(substr($blog['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white"><?= e($blog['username']) ?></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <?= formatDate($blog['created_at'], 'F d, Y') ?> • <?= timeAgo($blog['created_at']) ?>
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <?php if (auth() && currentUser()['id'] == $blog['user_id']): ?>
                <div class="flex space-x-2">
                    <a href="<?= url('/blog/' . $blog['id'] . '/edit') ?>" class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button onclick="deleteBlog(<?= $blog['id'] ?>)" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Categories -->
        <?php if (!empty($categories)): ?>
            <div class="flex flex-wrap gap-2 mb-6">
                <?php foreach ($categories as $category): ?>
                    <a href="<?= url('/category/' . $category['slug']) ?>" 
                       class="px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded-full text-sm hover:bg-primary-200 dark:hover:bg-primary-800">
                        <?= e($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="prose prose-lg dark:prose-invert max-w-none mb-8">
            <?= $blog['content'] ?>
        </div>

        <!-- Interaction Buttons -->
        <div class="flex items-center justify-between py-6 border-t border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-6">
                <!-- Vote Buttons -->
                <div class="flex items-center space-x-2">
                    <button id="upvote-btn-<?= $blog['id'] ?>" 
                            onclick="vote(<?= $blog['id'] ?>, 'upvote')"
                            class="btn <?= isset($blog['user_vote']) && $blog['user_vote']['vote_type'] === 'upvote' ? 'btn-primary' : 'btn-outline' ?>">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span id="upvote-count-<?= $blog['id'] ?>"><?= $blog['votes']['upvotes'] ?></span>
                    </button>
                    <button id="downvote-btn-<?= $blog['id'] ?>" 
                            onclick="vote(<?= $blog['id'] ?>, 'downvote')"
                            class="btn <?= isset($blog['user_vote']) && $blog['user_vote']['vote_type'] === 'downvote' ? 'btn-danger' : 'btn-outline' ?>">
                        <i class="fas fa-arrow-down mr-1"></i>
                        <span id="downvote-count-<?= $blog['id'] ?>"><?= $blog['votes']['downvotes'] ?></span>
                    </button>
                </div>

                <!-- Like Button -->
                <button id="like-btn-<?= $blog['id'] ?>" 
                        onclick="toggleLike(<?= $blog['id'] ?>)"
                        class="btn <?= isset($blog['user_liked']) && $blog['user_liked'] ? 'text-red-600' : 'btn-outline' ?>">
                    <i class="<?= isset($blog['user_liked']) && $blog['user_liked'] ? 'fas' : 'far' ?> fa-heart mr-1"></i>
                    <span id="like-count-<?= $blog['id'] ?>"><?= $blog['likes'] ?></span> Likes
                </button>

                <!-- Bookmark Button -->
                <?php if (auth()): ?>
                    <button id="bookmark-btn-<?= $blog['id'] ?>" 
                            onclick="toggleBookmark(<?= $blog['id'] ?>)"
                            class="btn btn-outline">
                        <i class="far fa-bookmark mr-1"></i> Save
                    </button>
                <?php endif; ?>
            </div>

            <!-- Share -->
            <div class="flex items-center space-x-2">
                <span class="text-gray-600 dark:text-gray-400">Share:</span>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(url('/blog/' . $blog['slug'])) ?>&text=<?= urlencode($blog['title']) ?>" 
                   target="_blank" class="text-blue-400 hover:text-blue-500">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(url('/blog/' . $blog['slug'])) ?>" 
                   target="_blank" class="text-blue-600 hover:text-blue-700">
                    <i class="fab fa-facebook text-xl"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?url=<?= urlencode(url('/blog/' . $blog['slug'])) ?>&title=<?= urlencode($blog['title']) ?>" 
                   target="_blank" class="text-blue-700 hover:text-blue-800">
                    <i class="fab fa-linkedin text-xl"></i>
                </a>
            </div>
        </div>
    </article>

    <!-- Related Posts -->
    <?php if (!empty($related)): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Related Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($related as $post): ?>
                    <a href="<?= url('/blog/' . $post['slug']) ?>" class="card hover:shadow-xl transition-shadow">
                        <?php if ($post['featured_image']): ?>
                            <img src="<?= asset($post['featured_image']) ?>" 
                                 alt="<?= e($post['title']) ?>"
                                 class="w-full h-32 object-cover rounded-lg mb-3">
                        <?php endif; ?>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            <?= e($post['title']) ?>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            By <?= e($post['username']) ?>
                        </p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Comments Section -->
    <div id="comments" class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
            Comments (<?= count($comments) ?>)
        </h2>

        <!-- Add Comment Form -->
        <?php if (auth()): ?>
            <div class="card mb-8">
                <textarea id="comment-content" rows="3" 
                          class="input"
                          placeholder="Write a comment..."></textarea>
                <button onclick="submitComment(<?= $blog['id'] ?>)" class="btn btn-primary mt-3">
                    <i class="fas fa-comment mr-2"></i> Post Comment
                </button>
            </div>
        <?php else: ?>
            <div class="card mb-8 text-center">
                <p class="text-gray-600 dark:text-gray-400 mb-4">Please login to comment</p>
                <a href="<?= url('/login') ?>" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
            </div>
        <?php endif; ?>

        <!-- Comments List -->
        <div class="space-y-6">
            <?php foreach ($comments as $comment): ?>
                <div class="card">
                    <div class="flex items-start space-x-3">
                        <?php if ($comment['avatar']): ?>
                            <img src="<?= asset($comment['avatar']) ?>" alt="<?= e($comment['username']) ?>" 
                                 class="w-10 h-10 rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-full bg-primary-600 text-white flex items-center justify-center">
                                <?= strtoupper(substr($comment['username'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white"><?= e($comment['username']) ?></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= timeAgo($comment['created_at']) ?></p>
                                </div>
                                
                                <?php if (auth() && currentUser()['id'] == $comment['user_id']): ?>
                                    <button onclick="deleteComment(<?= $comment['id'] ?>)" 
                                            class="text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                            <p class="text-gray-700 dark:text-gray-300 mt-2"><?= e($comment['content']) ?></p>
                            
                            <?php if (auth()): ?>
                                <button onclick="toggleReplyForm(<?= $comment['id'] ?>)" 
                                        class="text-primary-600 text-sm mt-2">
                                    <i class="fas fa-reply mr-1"></i> Reply
                                </button>
                            <?php endif; ?>
                            
                            <!-- Reply Form -->
                            <div id="reply-form-<?= $comment['id'] ?>" class="hidden mt-4">
                                <textarea id="reply-content-<?= $comment['id'] ?>" rows="2" 
                                          class="input"
                                          placeholder="Write a reply..."></textarea>
                                <button onclick="submitComment(<?= $blog['id'] ?>, <?= $comment['id'] ?>)" 
                                        class="btn btn-primary mt-2">
                                    Post Reply
                                </button>
                            </div>
                            
                            <!-- Replies -->
                            <?php if (!empty($comment['replies'])): ?>
                                <div class="mt-4 space-y-4 pl-8 border-l-2 border-gray-200 dark:border-gray-700">
                                    <?php foreach ($comment['replies'] as $reply): ?>
                                        <div class="flex items-start space-x-3">
                                            <?php if ($reply['avatar']): ?>
                                                <img src="<?= asset($reply['avatar']) ?>" alt="<?= e($reply['username']) ?>" 
                                                     class="w-8 h-8 rounded-full object-cover">
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm">
                                                    <?= strtoupper(substr($reply['username'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 dark:text-white text-sm"><?= e($reply['username']) ?></p>
                                                <p class="text-gray-700 dark:text-gray-300 text-sm mt-1"><?= e($reply['content']) ?></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= timeAgo($reply['created_at']) ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

