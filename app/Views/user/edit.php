<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="card">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            <i class="fas fa-cog mr-2"></i> Edit Profile
        </h1>

        <form action="<?= url('/profile/edit') ?>" method="POST" enctype="multipart/form-data">
            <?= csrfField() ?>

            <div class="space-y-6">
                <!-- Current Avatar -->
                <div class="flex flex-col items-center">
                    <?php if ($user['avatar']): ?>
                        <img src="<?= asset($user['avatar']) ?>" 
                             alt="<?= e($user['username']) ?>"
                             class="w-32 h-32 rounded-full object-cover mb-4">
                    <?php else: ?>
                        <div class="w-32 h-32 rounded-full bg-primary-600 text-white flex items-center justify-center text-5xl font-bold mb-4">
                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Avatar Upload -->
                <div>
                    <label for="avatar" class="label">Change Avatar</label>
                    <div class="mt-2">
                        <input type="file" id="avatar" name="avatar" 
                               accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                               onchange="previewAvatar(this)">
                    </div>
                    <div class="mt-4 flex justify-center">
                        <img id="avatarPreview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700 hidden" alt="Preview">
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                        Supported formats: JPEG, PNG, GIF, WebP (max 2MB)
                    </p>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="label">Username *</label>
                    <input type="text" id="username" name="username" required
                           class="input"
                           value="<?= e($user['username']) ?>">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="label">Email *</label>
                    <input type="email" id="email" name="email" required
                           class="input"
                           value="<?= e($user['email']) ?>">
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="label">Bio</label>
                    <textarea id="bio" name="bio" rows="4"
                              class="input"
                              placeholder="Tell us about yourself..."><?= e($user['bio'] ?? '') ?></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="<?= url('/profile') ?>" class="btn btn-outline">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, GIF, or WebP).');
            input.value = '';
            return;
        }
        
        // Validate file size (2MB max)
        const maxSize = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSize) {
            alert('File size too large. Please select an image smaller than 2MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>

