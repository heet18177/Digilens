<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="card">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            <i class="fas fa-pen mr-2"></i> Create New Post
        </h1>

        <form action="<?= url('/blog/create') ?>" method="POST" enctype="multipart/form-data">
            <?= csrfField() ?>

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="label">Post Title *</label>
                    <input type="text" id="title" name="title" required
                           class="input"
                           placeholder="Enter an engaging title"
                           value="<?= old('title') ?>">
                </div>

                <!-- Featured Image -->
                <div>
                    <label for="featured_image" class="label">Featured Image</label>
                    <input type="file" id="featured_image" name="featured_image" 
                           accept="image/*"
                           class="input"
                           onchange="previewImage(this, 'imagePreview')">
                    <img id="imagePreview" class="mt-4 max-h-64 rounded-lg hidden" alt="Preview">
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="label">Content *</label>
                    <div id="editor" class="min-h-[400px] border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800"></div>
                    <textarea id="content" name="content" style="display: none;" required><?= old('content') ?></textarea>
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="label">Excerpt (Optional)</label>
                    <textarea id="excerpt" name="excerpt" rows="3"
                              class="input"
                              placeholder="Short description of your post (auto-generated if left empty)"><?= old('excerpt') ?></textarea>
                </div>

                <!-- Categories -->
                <?php if (!empty($categories)): ?>
                    <div>
                        <label class="label">Categories</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <?php foreach ($categories as $category): ?>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>"
                                           class="rounded text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        <?= e($category['name']) ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Status -->
                <div>
                    <label class="label">Status</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="status" value="draft" checked
                                   class="text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                <i class="fas fa-file mr-1"></i> Save as Draft
                            </span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="status" value="published"
                                   class="text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                <i class="fas fa-check-circle mr-1"></i> Publish Now
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="<?= url('/') ?>" class="btn btn-outline">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Create Post
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Quill.js Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="<?= asset('js/quill-editor.js') ?>"></script>

