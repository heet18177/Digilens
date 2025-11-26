<nav class="bg-white dark:bg-gray-800 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- 🌐 Logo -->
            <div class="flex items-center">
                <a href="<?= url('/') ?>" class="flex items-center space-x-2">
                    <i class="fas fa-blog text-2xl text-primary-600"></i>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">
                        The Digital Lens
                    </span>
                </a>
            </div>

            <!-- 💻 Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="<?= url('/') ?>" 
                   class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-home mr-1"></i> Home
                </a>

                <!-- 🔍 Search (hidden on search page) -->
                <?php if (empty($hideNavbarSearch)): ?>
                    <form action="<?= url('/search') ?>" method="GET" class="relative">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Search..." 
                            class="py-2 pr-10 pl-3 w-64 border border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            value="<?= e($_GET['q'] ?? '') ?>">
                        <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                <?php endif; ?>

                <?php if (auth()): ?>
                    <?php $user = currentUser(); ?>

                    <a href="<?= url('/blog/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> New Post
                    </a>

                    <a href="<?= url('/bookmarks') ?>" 
                       class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-3 py-2">
                        <i class="fas fa-bookmark"></i>
                    </a>

                    <!-- 👤 User Dropdown -->
                    <div class="relative group" id="userDropdown">
                        <button class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400" id="userDropdownBtn">
                            <?php if (!empty($user['avatar'])): ?>
                                <img src="<?= asset($user['avatar']) ?>" alt="<?= e($user['username']) ?>" 
                                     class="w-8 h-8 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center">
                                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <span><?= e($user['username']) ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <div class="hidden group-hover:block absolute right-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 border border-gray-200 dark:border-gray-700" id="userDropdownMenu">
                            <a href="<?= url('/profile') ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>
                            <a href="<?= url('/profile/edit') ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <hr class="my-2 border-gray-200 dark:border-gray-700">
                            <form action="<?= url('/logout') ?>" method="POST">
                                <?= csrfField() ?>
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>

                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 px-3 py-2">
                        Login
                    </a>
                    <a href="<?= url('/register') ?>" class="btn btn-primary">
                        Register
                    </a>
                <?php endif; ?>

                <!-- 🌗 Dark Mode Toggle -->
                <button id="darkModeToggle" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 px-3 py-2">
                    <i id="themeIcon" class="fas fa-moon"></i>
                </button>
            </div>

            <!-- 📱 Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobileMenuBtn" class="text-gray-700 dark:text-gray-300">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- 📱 Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="<?= url('/') ?>" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                <i class="fas fa-home mr-2"></i> Home
            </a>

            <?php if (auth()): ?>
                <a href="<?= url('/blog/create') ?>" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    <i class="fas fa-plus mr-2"></i> New Post
                </a>
                <a href="<?= url('/profile') ?>" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <a href="<?= url('/bookmarks') ?>" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    <i class="fas fa-bookmark mr-2"></i> Bookmarks
                </a>
                <form action="<?= url('/logout') ?>" method="POST">
                    <?= csrfField() ?>
                    <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            <?php else: ?>
                <a href="<?= url('/login') ?>" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Login
                </a>
                <a href="<?= url('/register') ?>" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const darkModeToggle = document.getElementById('darkModeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // 📱 Mobile menu toggle
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // 🌗 Dark mode toggle
    darkModeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        const isDark = document.documentElement.classList.contains('dark');
        themeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

    // 🌓 Load theme from localStorage
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
        themeIcon.className = 'fas fa-sun';
    }
});
</script>
