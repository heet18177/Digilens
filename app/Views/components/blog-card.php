<div class="card hover:shadow-xl transition-shadow duration-300">
    <?php if ($blog['featured_image']): ?>
        <img src="<?= asset($blog['featured_image']) ?>" 
             alt="<?= e($blog['title']) ?>"
             class="w-full h-48 object-cover rounded-lg mb-4">
    <?php endif; ?>
    
    <div class="flex items-center space-x-2 mb-3">
        <?php 
            $username = $blog['username'] ?? 'Anonymous';
            $firstLetter = mb_substr($username, 0, 1, 'UTF-8');
        ?>
        <?php if (!empty($blog['avatar'])): ?>
            <img src="<?= asset($blog['avatar']) ?>" alt="<?= e($username) ?>" 
                 class="w-8 h-8 rounded-full object-cover">
        <?php else: ?>
            <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm">
                <?= strtoupper($firstLetter) ?>
            </div>
        <?php endif; ?>
        <div class="text-sm">
            <p class="font-medium text-gray-900 dark:text-white"><?= e($username) ?></p>
            <p class="text-gray-500 dark:text-gray-400"><?= timeAgo($blog['created_at']) ?></p>
        </div>
    </div>

    <a href="<?= url('/blog/' . $blog['slug']) ?>" class="block group">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 mb-2">
            <?= e($blog['title']) ?>
        </h2>
    </a>

    <?php if (isset($blog['excerpt']) && $blog['excerpt']): ?>
        <p class="text-gray-600 dark:text-gray-300 mb-4">
            <?= e(truncate($blog['excerpt'], 150)) ?>
        </p>
    <?php endif; ?>

    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
        <!-- Vote Buttons -->
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-1">
                <button id="upvote-btn-<?= $blog['id'] ?>" 
                        onclick="vote(<?= $blog['id'] ?>, 'upvote')"
                        class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 <?= isset($blog['user_vote']) && $blog['user_vote']['vote_type'] === 'upvote' ? 'text-primary-600 bg-primary-50' : '' ?>">
                    <i class="fas fa-arrow-up"></i>
                </button>
                <span id="upvote-count-<?= $blog['id'] ?>" class="font-medium">
                    <?= $blog['votes']['upvotes'] ?? 0 ?>
                </span>
            </div>

            <div class="flex items-center space-x-1">
                <button id="downvote-btn-<?= $blog['id'] ?>" 
                        onclick="vote(<?= $blog['id'] ?>, 'downvote')"
                        class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 <?= isset($blog['user_vote']) && $blog['user_vote']['vote_type'] === 'downvote' ? 'text-red-600 bg-red-50' : '' ?>">
                    <i class="fas fa-arrow-down"></i>
                </button>
                <span id="downvote-count-<?= $blog['id'] ?>" class="font-medium">
                    <?= $blog['votes']['downvotes'] ?? 0 ?>
                </span>
            </div>
        </div>

        <!-- Like & Comment -->
        <div class="flex items-center space-x-4 text-gray-600 dark:text-gray-400">
            <button id="like-btn-<?= $blog['id'] ?>" 
                    onclick="toggleLike(<?= $blog['id'] ?>)"
                    class="flex items-center space-x-1 hover:text-red-600 <?= isset($blog['user_liked']) && $blog['user_liked'] ? 'text-red-600' : '' ?>">
                <i class="<?= isset($blog['user_liked']) && $blog['user_liked'] ? 'fas' : 'far' ?> fa-heart"></i>
                <span id="like-count-<?= $blog['id'] ?>"><?= $blog['likes'] ?? 0 ?></span>
            </button>

            <a href="<?= url('/blog/' . $blog['slug']) ?>#comments" 
               class="flex items-center space-x-1 hover:text-primary-600">
                <i class="far fa-comment"></i>
                <span><?= $blog['comments'] ?? 0 ?></span>
            </a>

            <a href="<?= url('/blog/' . $blog['slug']) ?>" 
               class="flex items-center space-x-1 hover:text-primary-600">
                <i class="fas fa-eye"></i>
                <span><?= $blog['views'] ?? 0 ?></span>
            </a>
        </div>
    </div>
</div>

