<footer class="bg-gray-900 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-lg font-bold mb-4">BlogApp</h3>
                <p class="text-gray-400 text-sm">
                    A modern blogging platform built with PHP, MySQL, and Tailwind CSS.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="<?= url('/') ?>" class="text-gray-400 hover:text-white text-sm">Home</a></li>
                    <li><a href="<?= url('/') ?>" class="text-gray-400 hover:text-white text-sm">Categories</a></li>
                    <li><a href="<?= url('/') ?>" class="text-gray-400 hover:text-white text-sm">Popular Posts</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div>
                <h3 class="text-lg font-bold mb-4">Categories</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">Technology</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">Programming</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">Design</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">Lifestyle</a></li>
                </ul>
            </div>

            <!-- Social -->
            <div>
                <h3 class="text-lg font-bold mb-4">Follow Us</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-facebook text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-twitter text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-github text-2xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; <?= date('Y') ?> BlogApp. All rights reserved. Built with <i class="fas fa-heart text-red-500"></i> using PHP MVC</p>
        </div>
    </div>
</footer>

