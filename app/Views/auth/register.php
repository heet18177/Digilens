<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Already have an account?
                <a href="<?= url('/login') ?>" class="font-medium text-primary-600 hover:text-primary-500">
                    Sign in
                </a>
            </p>
        </div>
        
        <div class="card">
            <form action="<?= url('/register') ?>" method="POST" class="space-y-6">
                <?= csrfField() ?>
                
                <div>
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" required 
                           class="input"
                           placeholder="Choose a username"
                           value="<?= old('username') ?>">
                </div>

                <div>
                    <label for="email" class="label">Email address</label>
                    <input id="email" name="email" type="email" required 
                           class="input"
                           placeholder="Enter your email"
                           value="<?= old('email') ?>">
                </div>

                <div>
                    <label for="password" class="label">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="input"
                           placeholder="Create a password (min. 6 characters)">
                </div>

                <div>
                    <label for="password_confirm" class="label">Confirm Password</label>
                    <input id="password_confirm" name="password_confirm" type="password" required 
                           class="input"
                           placeholder="Confirm your password">
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        I agree to the Terms and Conditions
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full btn btn-primary">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

