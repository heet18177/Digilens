<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Profile Header -->
    <div class="card mb-8">
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <?php if ($user['avatar']): ?>
                    <img src="<?= asset($user['avatar']) ?>" 
                         alt="<?= e($user['username']) ?>"
                         class="w-32 h-32 rounded-full object-cover border-4 border-primary-500">
                <?php else: ?>
                    <div class="w-32 h-32 rounded-full bg-primary-600 text-white flex items-center justify-center text-5xl font-bold border-4 border-primary-500">
                        <?= strtoupper(substr($user['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- User Info -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    <?= e($user['username']) ?>
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    <?= e($user['email']) ?>
                </p>
                
                <?php if ($user['bio']): ?>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        <?= e($user['bio']) ?>
                    </p>
                <?php endif; ?>

                <!-- Stats -->
                <div class="flex justify-center md:justify-start space-x-6 mb-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $blogCount ?></p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Posts</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $commentCount ?></p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Comments</p>
                    </div>
                </div>

                <div class="flex justify-center md:justify-start space-x-2">
                    <a href="<?= url('/profile/edit') ?>" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </a>
                    <a href="<?= url('/blog/create') ?>" class="btn btn-secondary">
                        <i class="fas fa-plus mr-2"></i> New Post
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User's Posts -->
    <div class="card">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
            <i class="fas fa-newspaper mr-2"></i> My Posts
        </h2>

        <?php if (empty($blogs)): ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't created any posts yet.</p>
                <a href="<?= url('/blog/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Create Your First Post
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($blogs as $blog): ?>
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex-1">
                            <a href="<?= url('/blog/' . $blog['slug']) ?>" 
                               class="text-lg font-semibold text-gray-900 dark:text-white hover:text-primary-600">
                                <?= e($blog['title']) ?>
                            </a>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <span>
                                    <i class="fas fa-calendar mr-1"></i>
                                    <?= formatDate($blog['created_at']) ?>
                                </span>
                                <span>
                                    <i class="fas fa-eye mr-1"></i>
                                    <?= $blog['views'] ?> views
                                </span>
                                <span class="px-2 py-1 rounded text-xs <?= $blog['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= ucfirst($blog['status']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="<?= url('/blog/' . $blog['id'] . '/edit') ?>" 
                               class="btn btn-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteBlog(<?= $blog['id'] ?>)" 
                                    class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

