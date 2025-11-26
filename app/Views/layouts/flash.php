<?php
$success = flash('success');
$error = flash('error');
$info = flash('info');
$warning = flash('warning');
?>

<?php if ($success): ?>
<div class="flash-message fixed top-20 right-4 z-50 animate-slide-up">
    <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span><?= e($success) ?></span>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:text-green-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="flash-message fixed top-20 right-4 z-50 animate-slide-up">
    <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <span><?= e($error) ?></span>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:text-red-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if ($info): ?>
<div class="flash-message fixed top-20 right-4 z-50 animate-slide-up">
    <div class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
        <i class="fas fa-info-circle text-xl"></i>
        <span><?= e($info) ?></span>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:text-blue-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if ($warning): ?>
<div class="flash-message fixed top-20 right-4 z-50 animate-slide-up">
    <div class="bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
        <i class="fas fa-exclamation-triangle text-xl"></i>
        <span><?= e($warning) ?></span>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:text-yellow-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<?php
// Display validation errors
$errors = \Core\Session::get('errors');
if ($errors):
    \Core\Session::remove('errors');
?>
<div class="flash-message fixed top-20 right-4 z-50 animate-slide-up max-w-md">
    <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <div class="flex items-start justify-between">
            <div>
                <h4 class="font-bold mb-2">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Validation Errors
                </h4>
                <ul class="text-sm space-y-1">
                    <?php foreach ($errors as $field => $fieldErrors): ?>
                        <?php foreach ($fieldErrors as $error): ?>
                            <li>• <?= e($error) ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-4 hover:text-red-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

