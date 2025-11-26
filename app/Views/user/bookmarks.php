<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-bookmark mr-2 text-yellow-600"></i> My Bookmarks
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Posts you've saved for later</p>
    </div>

    <?php if (empty($bookmarks)): ?>
        <div class="card text-center py-12">
            <i class="fas fa-bookmark text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400 mb-4">No bookmarked posts yet</p>
            <a href="<?= url('/') ?>" class="btn btn-primary">
                <i class="fas fa-search mr-2"></i> Explore Posts
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($bookmarks as $blog): ?>
                <div class="card hover:shadow-xl transition-shadow">
                    <div class="flex items-start space-x-4">
                        <?php if ($blog['featured_image']): ?>
                            <img src="<?= asset($blog['featured_image']) ?>" 
                                 alt="<?= e($blog['title']) ?>"
                                 class="w-48 h-32 object-cover rounded-lg flex-shrink-0">
                        <?php endif; ?>
                        
                        <div class="flex-1">
                            <a href="<?= url('/blog/' . $blog['slug']) ?>" 
                               class="text-2xl font-bold text-gray-900 dark:text-white hover:text-primary-600 block mb-2">
                                <?= e($blog['title']) ?>
                            </a>
                            
                            <p class="text-gray-600 dark:text-gray-300 mb-3">
                                <?= e(truncate($blog['excerpt'] ?? strip_tags($blog['content']), 150)) ?>
                            </p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-4">
                                    <span>By <?= e($blog['username']) ?></span>
                                    <span><?= timeAgo($blog['bookmarked_at']) ?></span>
                                </div>
                                
                                <button onclick="toggleBookmark(<?= $blog['id'] ?>)" 
                                        id="bookmark-btn-<?= $blog['id'] ?>"
                                        class="text-yellow-600 hover:text-yellow-700">
                                    <i class="fas fa-bookmark"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

