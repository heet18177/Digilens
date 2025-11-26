<div class="max-w-5xl mx-auto px-4 py-10">

    <!-- Search form at the top -->
    <form action="<?= url('/search') ?>" method="GET" class="mb-8">
        <div class="relative max-w-2xl mx-auto">
            <input
                type="text"
                name="q"
                placeholder="Search blogs..."
                value="<?= e($keyword) ?>"
                autofocus
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm
                       focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                       dark:bg-gray-800 dark:text-white">
            <button
                type="submit"
                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-search text-lg"></i>
            </button>
        </div>
    </form>

    <!-- Results -->
    <h1 class="text-2xl font-bold mb-6">
        Search results for "<?= e($keyword) ?>"
    </h1>

    <?php if (empty($blogs['data'])): ?>
        <div class="text-center py-8">
            <p class="text-gray-500 dark:text-gray-400 text-lg">
                No results found for your search.
            </p>
            <p class="text-gray-400 dark:text-gray-500 mt-2">
                Try different keywords or check your spelling.
            </p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($blogs['data'] as $blog): ?>
                <?php require base_path('app/Views/components/blog-card.php'); ?>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($blogs['total_pages'] > 1): ?>
            <div class="mt-8 flex justify-center">
                <div class="flex space-x-2">
                    <?php if ($blogs['current_page'] > 1): ?>
                        <a href="<?= url('/search?q=' . urlencode($keyword) . '&page=' . ($blogs['current_page'] - 1)) ?>"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            Previous
                        </a>
                    <?php endif; ?>

                    <?php if ($blogs['current_page'] < $blogs['total_pages']): ?>
                        <a href="<?= url('/search?q=' . urlencode($keyword) . '&page=' . ($blogs['current_page'] + 1)) ?>"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>