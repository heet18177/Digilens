<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-blue-600 rounded-lg p-8 mb-8 text-white">
        <h1 class="text-4xl font-bold mb-4">Welcome to The Digital Lens</h1>
        <p class="text-xl mb-6">Discover amazing stories and share your own</p>
        <?php if (!auth()): ?>
            <a href="<?= url('/register') ?>" class="btn bg-white text-primary-600 hover:bg-gray-100">
                Get Started
            </a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Latest Posts</h2>
            
            <div class="space-y-6">
                <?php if (empty($blogs['data'])): ?>
                    <div class="card text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">No blog posts yet. Be the first to create one!</p>
                        <?php if (auth()): ?>
                            <a href="<?= url('/blog/create') ?>" class="btn btn-primary mt-4">
                                <i class="fas fa-plus mr-2"></i> Create Post
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($blogs['data'] as $blog): ?>
                        <?php include __DIR__ . '/../components/blog-card.php'; ?>
                    <?php endforeach; ?>

                    <!-- Pagination -->
                    <?php if ($blogs['last_page'] > 1): ?>
                        <div class="flex justify-center space-x-2 mt-8">
                            <?php for ($i = 1; $i <= $blogs['last_page']; $i++): ?>
                                <a href="?page=<?= $i ?>" 
                                   class="px-4 py-2 rounded <?= $i == $blogs['current_page'] ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Trending Posts -->
            <?php if (!empty($trending)): ?>
                <div class="card">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
                        <i class="fas fa-fire text-orange-500 mr-2"></i>
                        Trending
                    </h3>
                    <div class="space-y-4">
                        <?php foreach ($trending as $post): ?>
                            <a href="<?= url('/blog/' . $post['slug']) ?>" class="block group">
                                <h4 class="font-medium text-gray-900 dark:text-white group-hover:text-primary-600 line-clamp-2">
                                    <?= e($post['title']) ?>
                                </h4>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    <i class="fas fa-eye mr-1"></i>
                                    <?= $post['views'] ?> views
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Categories -->
            <?php if (!empty($categories)): ?>
                <div class="card">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
                        <i class="fas fa-tags mr-2"></i>
                        Categories
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($categories as $category): ?>
                            <a href="<?= url('/category/' . $category['slug']) ?>" 
                               class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm hover:bg-primary-600 hover:text-white transition">
                                <?= e($category['name']) ?>
                                <?php if (isset($category['blog_count'])): ?>
                                    <span class="ml-1">(<?= $category['blog_count'] ?>)</span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

